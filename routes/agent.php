<?php

use Illuminate\Support\Facades\Route;

// Contrôleurs Agent d'Inventaire
use App\Http\Controllers\AgentInventaire\InventaireMobileController;
use App\Http\Controllers\AgentInventaire\CartographieController as AgentCartographieController;
use App\Http\Controllers\AgentInventaire\CampagneController as AgentCampagneController;

/*
|--------------------------------------------------------------------------
| Espace Agent d'Inventaire Terrain Mobile & Web (Smartphones & Tablettes)
|--------------------------------------------------------------------------
*/

Route::prefix('agent')->name('agent.')->group(function () {
    // 1. Module Cartographie SIG Indépendant Agent
    Route::get('/cartographie', [AgentCartographieController::class, 'index'])->name('cartographie.index');

    // 2. Module Campagnes d'Inventaire Mobile & Tablette
    Route::prefix('campagnes')->name('campagnes.')->group(function () {
        Route::get('/', [AgentCampagneController::class, 'index'])->name('index');
        Route::get('/{id}', [AgentCampagneController::class, 'show'])->name('show');
    });

    // 3. Application Mobile PWA Scan & Relevé
    Route::prefix('mobile')->name('mobile.')->group(function () {
        Route::get('/', [InventaireMobileController::class, 'index'])->name('index');
        Route::get('/scan-chercher', [InventaireMobileController::class, 'scanChercher'])->name('scan.chercher');
        Route::post('/releve', [InventaireMobileController::class, 'storeReleve'])->name('releve.store');
        Route::get('/offline-data', [InventaireMobileController::class, 'offlineData'])->name('offline.data');
    });
});

Route::prefix('mobile')->name('mobile.')->group(function () {
    Route::get('/', [InventaireMobileController::class, 'index'])->name('index');
    Route::get('/scan-chercher', [InventaireMobileController::class, 'scanChercher'])->name('scan.chercher');
    Route::post('/releve', [InventaireMobileController::class, 'storeReleve'])->name('releve.store');
    Route::get('/offline-data', [InventaireMobileController::class, 'offlineData'])->name('offline.data');
});
