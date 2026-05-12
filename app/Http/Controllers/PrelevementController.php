<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PrelevementController extends Controller
{
    public function index(Request $request)
    {
        $labos = DB::table('Laboratoire')->get();
        $triathlons = DB::table('Triathlon')->get();
        $triathletes = DB::table('Triathlete')->get();

        $query = DB::table('Prelevement')
            ->join('Triathlete', 'Prelevement.numLicence', '=', 'Triathlete.numLicence')
            ->join('Inscription', 'Triathlete.numLicence', '=', 'Inscription.numLicence')
            ->join('Triathlon', 'Inscription.idT', '=', 'Triathlon.idT')
            ->select(
                'Triathlon.nomT as triathlon_nom',
                'Inscription.idT as idT',
                'Triathlete.numLicence',
                'Inscription.numDossard',
                'Triathlete.nom',
                'Triathlete.prenom',
                'Prelevement.idPrelevement',
                'Prelevement.datePrelevement',
                'Prelevement.idLabo'
            )
            ->orderBy('Prelevement.idPrelevement', 'desc');

        if ($request->filled('idLabo')) {
            $query->where('Prelevement.idLabo', $request->idLabo);
        }
        if ($request->filled('idT')) {
            $query->where('Inscription.idT', $request->idT);
        }
        if ($request->filled('numLicence')) {
            $query->where('Prelevement.numLicence', $request->numLicence);
        }

        $prelevements = $query->get()->unique('idPrelevement');
        
        foreach($prelevements as $p) {
            $p_mesures = DB::table('comprendre')
                ->join('ProduitDopant', 'comprendre.codeProduit', '=', 'ProduitDopant.codeProduit')
                ->where('idPrelevement', $p->idPrelevement)
                ->get();

            $etat = 'blanc';
            $allNotNull = true;
            $hasDepassement = false;
            $hasAtLeastOne = false;

            foreach($p_mesures as $m) {
                $hasAtLeastOne = true;
                if ($m->mesure === null) {
                    $allNotNull = false;
                } else {
                    if ($m->mesure > $m->tauxMaxi) {
                        $hasDepassement = true;
                    }
                }
            }

            if ($hasAtLeastOne && $allNotNull) {
                $etat = $hasDepassement ? 'rouge' : 'vert';
            } elseif ($hasDepassement) {
                $etat = 'rouge';
            }
            $p->couleurEtat = $etat;
        }

        return view('prelevements', compact('prelevements', 'labos', 'triathlons', 'triathletes'));
    }

    public function show($id)
    {
        $prelevement = DB::table('Prelevement')
            ->join('Triathlete', 'Prelevement.numLicence', '=', 'Triathlete.numLicence')
            ->join('Laboratoire', 'Prelevement.idLabo', '=', 'Laboratoire.idLabo')
            ->join('Inscription', 'Triathlete.numLicence', '=', 'Inscription.numLicence')
            ->join('Triathlon', 'Inscription.idT', '=', 'Triathlon.idT')
            ->where('Prelevement.idPrelevement', $id)
            ->select(
                'Prelevement.*', 
                'Triathlete.nom', 
                'Triathlete.prenom', 
                'Laboratoire.nomlabo', 
                'Triathlon.nomT', 
                'Inscription.idT as idT'
            )
            ->first();

        // Sécurité si on tape un mauvais ID dans l'URL
        if (!$prelevement) {
            return redirect()->route('prelevements')->with('error', 'Dossier introuvable.');
        }

        $produits = DB::table('comprendre')
            ->join('ProduitDopant', 'comprendre.codeProduit', '=', 'ProduitDopant.codeProduit')
            ->where('comprendre.idPrelevement', $id)
            ->select('ProduitDopant.codeProduit', 'ProduitDopant.libelleProduit', 'ProduitDopant.tauxMaxi', 'comprendre.mesure')
            ->get();

        $etat = 'EN COURS';
        $hasDepassement = false;
        $allNull = true;

        foreach($produits as $p) {
            if ($p->mesure !== null) {
                $allNull = false;
                if ($p->mesure > $p->tauxMaxi) {
                    $hasDepassement = true;
                }
            }
        }

        if (!$allNull) {
            $etat = $hasDepassement ? 'POSITIF' : 'NÉGATIF';
        }

        $labos = DB::table('Laboratoire')->get();
        $triathlons = DB::table('Triathlon')->get();
        $triathletes = DB::table('Triathlete')->get();

        return view('prelevement_show', compact('prelevement', 'produits', 'etat', 'labos', 'triathlons', 'triathletes'));
    }

    public function generer(Request $request)
    {
        $idT = $request->input('idT');
        $idLabo = $request->input('idLabo');
        $taux = $request->input('taux', 10);

        if (!$idT || !$idLabo) {
            return redirect()->route('prelevements')->with('error', 'Sélectionnez Labo + Triathlon.');
        }

        $inscriptions = DB::table('Inscription')->where('idT', $idT)->get();
        if ($inscriptions->count() == 0) return redirect()->route('prelevements')->with('info', 'Aucun inscrit.');

        $toSelect = ceil($inscriptions->count() * ($taux / 100));
        $selected = $inscriptions->random($toSelect);
        $produits = DB::table('ProduitDopant')->get();
        $maxId = DB::table('Prelevement')->max('idPrelevement') ?? 0;

        DB::beginTransaction();
        try {
            foreach ($selected as $insc) {
                $maxId++;
                DB::table('Prelevement')->insert([
                    'idPrelevement' => $maxId,
                    'datePrelevement' => now()->toDateString(),
                    'numLicence' => $insc->numLicence,
                    'idLabo' => $idLabo
                ]);

                foreach ($produits as $prod) {
                    DB::table('comprendre')->insert([
                        'codeProduit' => $prod->codeProduit,
                        'idPrelevement' => $maxId,
                        'mesure' => null
                    ]);
                }
            }
            DB::commit();
            return redirect()->route('prelevements')->with('success', 'Génération réussie !');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('prelevements')->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    // CORRECTION : On renvoie vers la vue prelevement_create (pas show !)
    public function create()
    {
        $labos = DB::table('Laboratoire')->get();
        $triathletes = DB::table('Triathlete')->get();
        
        return view('prelevement_create', compact('labos', 'triathletes'));
    }

    public function store(Request $request)
    {
        $maxId = (DB::table('Prelevement')->max('idPrelevement') ?? 0) + 1;
        
        DB::table('Prelevement')->insert([
            'idPrelevement' => $maxId,
            'datePrelevement' => now()->toDateString(),
            'numLicence' => $request->numLicence,
            'idLabo' => $request->idLabo
        ]);
        
        $produits = DB::table('ProduitDopant')->get();
        foreach ($produits as $prod) {
            DB::table('comprendre')->insert([
                'codeProduit' => $prod->codeProduit, 
                'idPrelevement' => $maxId, 
                'mesure' => null
            ]);
        }
        
        return redirect()->route('prelevements.show', $maxId)->with('success', 'Dossier créé avec succès !');
    }

    public function destroy($id)
    {
        DB::table('comprendre')->where('idPrelevement', $id)->delete();
        DB::table('Prelevement')->where('idPrelevement', $id)->delete();
        return redirect()->route('prelevements')->with('success', 'Supprimé !');
    }
}