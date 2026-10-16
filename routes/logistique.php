<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Logistique\LogistiqueDashboardController;
use App\Http\Controllers\Logistique\LogistiqueMouvementController;
use App\Http\Controllers\Logistique\LogistiqueMaintenanceController;
use App\Http\Controllers\Logistique\LogistiqueImmobilisationController;
use App\Http\Controllers\Logistique\LogistiqueCartographieController;

/*
|--------------------------------------------------------------------------
| Espace Responsable Logistique (prefix: logistique, name: logistique.)
|--------------------------------------------------------------------------
*/

Route::prefix('logistique')->name('logistique.')->group(function () {
    // 1. Tableau de bord Logistique
    Route::get('/dashboard', [LogistiqueDashboardController::class, 'index'])->name('dashboard');

    // 2. Mouvements & Transferts
    Route::prefix('mouvements')->name('mouvements.')->group(function () {
        Route::get('/', [LogistiqueMouvementController::class, 'index'])->name('index');
        Route::get('/creer', [LogistiqueMouvementController::class, 'create'])->name('create');
        Route::post('/', [LogistiqueMouvementController::class, 'store'])->name('store');
        Route::get('/{id}', [LogistiqueMouvementController::class, 'show'])->name('show');
        Route::get('/{id}/decharge', [LogistiqueMouvementController::class, 'decharge'])->name('decharge');
        Route::post('/{id}/valider', [LogistiqueMouvementController::class, 'valider'])->name('valider');
        Route::post('/{id}/receptionner', [LogistiqueMouvementController::class, 'receptionner'])->name('receptionner');
    });

    // 3. Maintenance & SAV
    Route::prefix('maintenances')->name('maintenances.')->group(function () {
        Route::get('/', [LogistiqueMaintenanceController::class, 'index'])->name('index');
        Route::get('/creer', [LogistiqueMaintenanceController::class, 'create'])->name('create');
        Route::post('/', [LogistiqueMaintenanceController::class, 'store'])->name('store');
        Route::get('/{id}', [LogistiqueMaintenanceController::class, 'show'])->name('show');
        Route::post('/{id}/cloturer', [LogistiqueMaintenanceController::class, 'cloturer'])->name('cloturer');
    });

    // 4. Référentiel des Actifs
    Route::prefix('immobilisations')->name('immobilisations.')->group(function () {
        Route::get('/', [LogistiqueImmobilisationController::class, 'index'])->name('index');
        Route::get('/{id}', [LogistiqueImmobilisationController::class, 'show'])->name('show');
    });

    // 5. Cartographie SIG & GPS
    Route::get('/cartographie', [LogistiqueCartographieController::class, 'index'])->name('cartographie.index');
});
