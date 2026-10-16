<?php

use Illuminate\Support\Facades\Route;

// Contrôleurs Auditeur & Bailleurs
use App\Http\Controllers\Auditeur\AuditeurDashboardController;
use App\Http\Controllers\Auditeur\AuditLogController as AuditeurAuditLogController;
use App\Http\Controllers\Auditeur\RapportAuditController as AuditeurRapportAuditController;

/*
|--------------------------------------------------------------------------
| Espace Auditeur & Bailleurs (prefix: auditeur, name: auditeur.)
|--------------------------------------------------------------------------
*/

Route::prefix('auditeur')->name('auditeur.')->group(function () {
    Route::get('/dashboard', [AuditeurDashboardController::class, 'index'])->name('dashboard');

    // Consultation Journal & Piste d'Audit
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', [AuditeurAuditLogController::class, 'index'])->name('index');
        Route::get('/export', [AuditeurAuditLogController::class, 'export'])->name('export');
    });

    // Rapports d'Audit & Registres
    Route::prefix('reporting')->name('reporting.')->group(function () {
        Route::get('/', [AuditeurRapportAuditController::class, 'index'])->name('index');
        Route::get('/registre-officiel', [AuditeurRapportAuditController::class, 'registreOfficiel'])->name('registre');
    });
});
