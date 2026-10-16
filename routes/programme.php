<?php

use Illuminate\Support\Facades\Route;

// Contrôleurs Utilisateur Programme
use App\Http\Controllers\Programme\ProgrammeDashboardController;
use App\Http\Controllers\Programme\ProgrammeImmobilisationController;

/*
|--------------------------------------------------------------------------
| Espace Responsable Programme (prefix: programme, name: programme.)
|--------------------------------------------------------------------------
*/

Route::prefix('programme')->name('programme.')->group(function () {
    Route::get('/dashboard', [ProgrammeDashboardController::class, 'index'])->name('dashboard');

    // Consultation des Actifs du Programme
    Route::prefix('immobilisations')->name('immobilisations.')->group(function () {
        Route::get('/', [ProgrammeImmobilisationController::class, 'index'])->name('index');
        Route::get('/{id}', [ProgrammeImmobilisationController::class, 'show'])->name('show');
    });
});
