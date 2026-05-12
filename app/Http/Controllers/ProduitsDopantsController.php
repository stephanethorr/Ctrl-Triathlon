<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProduitsDopantsController extends Controller
{
    public function index()
    {
        // Récupère tous les produits dopants
        $produits = DB::table('ProduitDopant')->get();
        return view('produitsdopants', compact('produits'));
    }

    public function store(Request $request)
    {
        try {
            DB::table('ProduitDopant')->insert([
                'codeProduit'    => $request->input('codeProduit'),
                'libelleProduit' => $request->input('libelleProduit'),
                'tauxMaxi'       => $request->input('tauxMaxi')
            ]);
            
            return redirect()->route('produitsdopants')->with('success', 'Produit dopant ajouté avec succès.');
        } catch (\Exception $e) {
            return redirect()->route('produitsdopants')->with('error', 'Erreur : Ce code produit existe probablement déjà dans la base.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            DB::table('ProduitDopant')
                ->where('codeProduit', $id)
                ->update([
                    'libelleProduit' => $request->input('libelleProduit'),
                    'tauxMaxi'       => $request->input('tauxMaxi')
                ]);
                
            return redirect()->route('produitsdopants')->with('success', 'Produit mis à jour.');
        } catch (\Exception $e) {
            return redirect()->route('produitsdopants')->with('error', 'Erreur lors de la mise à jour.');
        }
    }

    public function destroy($id)
    {
        // SÉCURITÉ : On vérifie si ce produit a déjà été testé dans un prélèvement
        $estUtilise = DB::table('comprendre')->where('codeProduit', $id)->exists();

        if ($estUtilise) {
            return redirect()->route('produitsdopants')
                ->with('error', 'Action refusée : Impossible de supprimer ce produit car il apparaît dans des analyses existantes.');
        }

        // Suppression
        DB::table('ProduitDopant')->where('codeProduit', $id)->delete();

        return redirect()->route('produitsdopants')->with('success', 'Le produit a été supprimé.');
    }
}