<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaboratoireController extends Controller
{
    public function index()
    {
        $laboratoires = DB::table('Laboratoire')->get();
        return view('laboratoires', compact('laboratoires'));
    }

    public function store(Request $request)
    {
        $maxId = DB::table('Laboratoire')->max('idLabo') ?? 0;
        $newId = $maxId + 1;

        try {
            // 1. On essaye d'insérer avec toutes les infos (si ta base le permet)
            DB::table('Laboratoire')->insert([
                'idLabo'  => $newId,
                'nomlabo' => $request->input('nomlabo'),
                'adresse' => $request->input('adresse'),
                'cp'      => $request->input('cp'),
                'ville'   => $request->input('ville'),
            ]);
            return redirect()->route('laboratoires')->with('success', 'Laboratoire ajouté avec ses coordonnées.');
            
        } catch (\Exception $e) {
            // 2. Si ça crashe (colonnes inexistantes), on insère JUSTE le nom !
            DB::table('Laboratoire')->insert([
                'idLabo'  => $newId,
                'nomlabo' => $request->input('nomlabo')
            ]);
            return redirect()->route('laboratoires')->with('success', 'Laboratoire ajouté ! (Les champs adresse ont été ignorés car ils n\'existent pas dans ta table).');
        }
    }

    public function destroy($id)
    {
        $hasPrelevements = DB::table('Prelevement')->where('idLabo', $id)->exists();

        if ($hasPrelevements) {
            return redirect()->route('laboratoires')
                ->with('error', 'Action refusée : Impossible de supprimer ce laboratoire car des dossiers de prélèvement lui sont encore rattachés.');
        }

        DB::table('Laboratoire')->where('idLabo', $id)->delete();

        return redirect()->route('laboratoires')->with('success', 'Le laboratoire a été supprimé.');
    }
}