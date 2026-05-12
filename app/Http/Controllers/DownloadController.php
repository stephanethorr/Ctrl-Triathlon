<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DownloadController extends Controller
{
    // 1. Affichage de la page du portail
    public function index()
    {
        $laboratoires = DB::table('Laboratoire')->get();
        return view('download', compact('laboratoires'));
    }

    // 2. Exportation (Téléchargement des lots)
    public function export()
    {
        $analyses = DB::table('comprendre')
            ->whereNull('mesure')
            ->get();
            
        return response()->json($analyses);
    }

    // 3. Importation (Télétransmission réelle)
    public function import(Request $request)
    {
        // Code d'importation à coder plus tard selon le format JSON
        return redirect()->back()->with('success', 'Fichier importé avec succès.');
    }

    // 4. LE BOUTON MAGIQUE : Génération de faux résultats (CORRIGÉ POUR SQL SERVER)
    public function testMode()
    {
        // On récupère toutes les analyses vides
        $analysesVides = DB::table('comprendre')->whereNull('mesure')->get();

        if ($analysesVides->isEmpty()) {
            return redirect()->back()->with('error', 'Aucun prélèvement en attente de résultat ! Génère d\'abord des prélèvements côté Infirmier.');
        }

        foreach ($analysesVides as $analyse) {
            
            $produit = DB::table('ProduitDopant')->where('codeProduit', $analyse->codeProduit)->first();
            
            // On s'assure que le taux max est bien traité comme un entier
            $tauxMax = $produit ? (int) $produit->tauxMaxi : 10;

            // 80% Négatif, 20% Positif
            $estDope = rand(1, 100) > 80;

            if ($estDope) {
                // Taux entier au-dessus du seuil légal
                $mesure = $tauxMax + rand(1, 10); 
            } else {
                // Taux entier en-dessous du seuil légal
                $mesure = $tauxMax - rand(1, 10);
                if ($mesure < 0) {
                    $mesure = 0; // Pas de chiffres négatifs
                }
            }

            // SAUVEGARDE EN BASE : On force l'envoi d'un vrai entier (int)
            DB::table('comprendre')
                ->where('idPrelevement', $analyse->idPrelevement)
                ->where('codeProduit', $analyse->codeProduit)
                ->update(['mesure' => (int) $mesure]);
        }

        return redirect()->back()->with('success', 'Simulation réussie : Les résultats ont été insérés dans la base ! Reconnecte-toi en Infirmier pour voir les couleurs.');
    }
}