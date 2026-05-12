<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LaboratoireController;
use App\Http\Controllers\ProduitsDopantsController;
use App\Http\Controllers\PrelevementController;
use App\Http\Controllers\DownloadController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    // Profil utilisateur
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Gestion des laboratoires
    Route::get('/laboratoires', [LaboratoireController::class, 'index'])->name('laboratoires');
    Route::post('/laboratoires', [LaboratoireController::class, 'store'])->name('laboratoires.store');
    Route::put('/laboratoires/{id}', [LaboratoireController::class, 'update'])->name('laboratoires.update');
    Route::delete('/laboratoires/{id}', [LaboratoireController::class, 'destroy'])->name('laboratoires.destroy');

    // Gestion des produits dopants
    Route::get('/produitsdopants', [ProduitsDopantsController::class, 'index'])->name('produitsdopants');
    Route::post('/produitsdopants', [ProduitsDopantsController::class, 'store'])->name('produitsdopants.store');
    Route::put('/produitsdopants/{id}', [ProduitsDopantsController::class, 'update'])->name('produitsdopants.update');
    Route::delete('/produitsdopants/{id}', [ProduitsDopantsController::class, 'destroy'])->name('produitsdopants.destroy');

    // Gestion des prélèvements
    Route::get('/prelevements', [PrelevementController::class, 'index'])->name('prelevements');
    // CORRECTION ICI : La ligne "create" est maintenant AVANT la ligne "{id}" !
    Route::get('/prelevements/create', [PrelevementController::class, 'create'])->name('prelevements.create');
    Route::post('/prelevements', [PrelevementController::class, 'store'])->name('prelevements.store');
    Route::get('/prelevements/{id}', [PrelevementController::class, 'show'])->name('prelevements.show');
    Route::post('/prelevements/generer', [PrelevementController::class, 'generer'])->name('prelevements.generer');
    Route::delete('/prelevements/{id}', [PrelevementController::class, 'destroy'])->name('prelevements.destroy');

    // Portail d'échange JSON (Laboratoires)
    Route::get('/download', [DownloadController::class, 'index'])->name('download.index');
    Route::post('/download/export', [DownloadController::class, 'export'])->name('download.export');
    Route::post('/download/import', [DownloadController::class, 'import'])->name('download.import');
    Route::post('/download/test', [DownloadController::class, 'testMode'])->name('download.test');
});

require __DIR__.'/auth.php';