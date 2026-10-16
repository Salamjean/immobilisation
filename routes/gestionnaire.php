<?php

use Illuminate\Support\Facades\Route;

// Contrôleurs Gestionnaire
use App\Http\Controllers\Gestionnaire\GestionnaireDashboardController;
use App\Http\Controllers\Gestionnaire\ImmobilisationController as GestionnaireImmobilisationController;
use App\Http\Controllers\Gestionnaire\CartographieController as GestionnaireCartographieController;
use App\Http\Controllers\Gestionnaire\CampagneInventaireController as GestionnaireCampagneInventaireController;
use App\Http\Controllers\Gestionnaire\MouvementController as GestionnaireMouvementController;
use App\Http\Controllers\Gestionnaire\MaintenanceController as GestionnaireMaintenanceController;
use App\Http\Controllers\Gestionnaire\ReportingController as GestionnaireReportingController;
use App\Http\Controllers\Gestionnaire\CategorieController as GestionnaireCategorieController;
use App\Http\Controllers\Gestionnaire\StructureController as GestionnaireStructureController;
use App\Http\Controllers\Gestionnaire\ProgrammeController as GestionnaireProgrammeController;
use App\Http\Controllers\Gestionnaire\BailleurController as GestionnaireBailleurController;
use App\Http\Controllers\Gestionnaire\SubventionController as GestionnaireSubventionController;
use App\Http\Controllers\Gestionnaire\AuditController as GestionnaireAuditController;

/*
|--------------------------------------------------------------------------
| Espace Gestionnaire de Patrimoine (prefix: gestionnaire, name: gestionnaire.)
|--------------------------------------------------------------------------
*/

Route::prefix('gestionnaire')->name('gestionnaire.')->group(function () {
    Route::get('/dashboard', [GestionnaireDashboardController::class, 'index'])->name('dashboard');

    // Référentiel des Immobilisations
    Route::prefix('immobilisations')->name('immobilisations.')->group(function () {
        Route::get('/', [GestionnaireImmobilisationController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireImmobilisationController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireImmobilisationController::class, 'store'])->name('store');
        Route::get('/export/csv', [GestionnaireImmobilisationController::class, 'exportCsv'])->name('export.csv');
        Route::get('/{id}', [GestionnaireImmobilisationController::class, 'show'])->name('show');
        Route::get('/{id}/modifier', [GestionnaireImmobilisationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GestionnaireImmobilisationController::class, 'update'])->name('update');
        Route::post('/{id}/etat', [GestionnaireImmobilisationController::class, 'changerEtat'])->name('etat');
        Route::get('/{id}/etiquette', [GestionnaireImmobilisationController::class, 'etiquette'])->name('etiquette');
    });

    // Cartographie SIG & Géolocalisation
    Route::get('/cartographie', [GestionnaireCartographieController::class, 'index'])->name('cartographie.index');

    // Campagnes d'Inventaire Physique & Rapprochement
    Route::prefix('inventaires')->name('inventaires.')->group(function () {
        Route::get('/', [GestionnaireCampagneInventaireController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireCampagneInventaireController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireCampagneInventaireController::class, 'store'])->name('store');
        Route::get('/{id}', [GestionnaireCampagneInventaireController::class, 'show'])->name('show');
        Route::post('/releve/{id}/valider', [GestionnaireCampagneInventaireController::class, 'validerEcart'])->name('releve.valider');
        Route::post('/{id}/cloturer', [GestionnaireCampagneInventaireController::class, 'cloturer'])->name('cloturer');
    });

    // Mouvements & Transferts
    Route::prefix('mouvements')->name('mouvements.')->group(function () {
        Route::get('/', [GestionnaireMouvementController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireMouvementController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireMouvementController::class, 'store'])->name('store');
        Route::get('/{id}', [GestionnaireMouvementController::class, 'show'])->name('show');
        Route::get('/{id}/decharge', [GestionnaireMouvementController::class, 'decharge'])->name('decharge');
        Route::post('/{id}/valider', [GestionnaireMouvementController::class, 'valider'])->name('valider');
        Route::post('/{id}/receptionner', [GestionnaireMouvementController::class, 'receptionner'])->name('receptionner');
    });

    // Maintenances & SAV
    Route::prefix('maintenances')->name('maintenances.')->group(function () {
        Route::get('/', [GestionnaireMaintenanceController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireMaintenanceController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireMaintenanceController::class, 'store'])->name('store');
        Route::get('/{id}', [GestionnaireMaintenanceController::class, 'show'])->name('show');
        Route::post('/{id}/cloturer', [GestionnaireMaintenanceController::class, 'cloturer'])->name('cloturer');
    });

    // Reporting & Registre officiel
    Route::prefix('reporting')->name('reporting.')->group(function () {
        Route::get('/', [GestionnaireReportingController::class, 'index'])->name('index');
        Route::get('/registre-officiel', [GestionnaireReportingController::class, 'registreOfficiel'])->name('registre');
    });

    // Référentiels Métier du Patrimoine
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [GestionnaireCategorieController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireCategorieController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireCategorieController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [GestionnaireCategorieController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GestionnaireCategorieController::class, 'update'])->name('update');
    });

    Route::prefix('structures')->name('structures.')->group(function () {
        Route::get('/', [GestionnaireStructureController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireStructureController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireStructureController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [GestionnaireStructureController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GestionnaireStructureController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [GestionnaireStructureController::class, 'toggleStatus'])->name('toggle');
    });

    Route::prefix('programmes')->name('programmes.')->group(function () {
        Route::get('/', [GestionnaireProgrammeController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireProgrammeController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireProgrammeController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [GestionnaireProgrammeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GestionnaireProgrammeController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [GestionnaireProgrammeController::class, 'toggleStatus'])->name('toggle');
        Route::delete('/{id}', [GestionnaireProgrammeController::class, 'destroy'])->name('destroy');
    });

    Route::prefix('bailleurs')->name('bailleurs.')->group(function () {
        Route::get('/', [GestionnaireBailleurController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireBailleurController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireBailleurController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [GestionnaireBailleurController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GestionnaireBailleurController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [GestionnaireBailleurController::class, 'toggleStatus'])->name('toggle');
    });

    Route::prefix('subventions')->name('subventions.')->group(function () {
        Route::get('/', [GestionnaireSubventionController::class, 'index'])->name('index');
        Route::get('/creer', [GestionnaireSubventionController::class, 'create'])->name('create');
        Route::post('/', [GestionnaireSubventionController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [GestionnaireSubventionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [GestionnaireSubventionController::class, 'update'])->name('update');
        Route::delete('/{id}', [GestionnaireSubventionController::class, 'destroy'])->name('destroy');
    });

    // Audit Log & Traçabilité Gestionnaire
    Route::get('/audit', [GestionnaireAuditController::class, 'index'])->name('audit.index');
});

/*
|--------------------------------------------------------------------------
| Alias Globaux (compatibilité directe)
|--------------------------------------------------------------------------
*/
Route::prefix('immobilisations')->name('immobilisations.')->group(function () {
    Route::get('/', [GestionnaireImmobilisationController::class, 'index'])->name('index');
    Route::get('/creer', [GestionnaireImmobilisationController::class, 'create'])->name('create');
    Route::post('/', [GestionnaireImmobilisationController::class, 'store'])->name('store');
    Route::get('/export/csv', [GestionnaireImmobilisationController::class, 'exportCsv'])->name('export.csv');
    Route::get('/{id}', [GestionnaireImmobilisationController::class, 'show'])->name('show');
    Route::get('/{id}/modifier', [GestionnaireImmobilisationController::class, 'edit'])->name('edit');
    Route::put('/{id}', [GestionnaireImmobilisationController::class, 'update'])->name('update');
    Route::post('/{id}/etat', [GestionnaireImmobilisationController::class, 'changerEtat'])->name('etat');
    Route::get('/{id}/etiquette', [GestionnaireImmobilisationController::class, 'etiquette'])->name('etiquette');
});

Route::get('/cartographie', [GestionnaireCartographieController::class, 'index'])->name('cartographie.index');

Route::prefix('inventaires')->name('inventaires.')->group(function () {
    Route::get('/', [GestionnaireCampagneInventaireController::class, 'index'])->name('index');
    Route::get('/creer', [GestionnaireCampagneInventaireController::class, 'create'])->name('create');
    Route::post('/', [GestionnaireCampagneInventaireController::class, 'store'])->name('store');
    Route::get('/{id}', [GestionnaireCampagneInventaireController::class, 'show'])->name('show');
    Route::post('/releve/{id}/valider', [GestionnaireCampagneInventaireController::class, 'validerEcart'])->name('releve.valider');
    Route::post('/{id}/cloturer', [GestionnaireCampagneInventaireController::class, 'cloturer'])->name('cloturer');
});

Route::prefix('mouvements')->name('mouvements.')->group(function () {
    Route::get('/', [GestionnaireMouvementController::class, 'index'])->name('index');
    Route::get('/creer', [GestionnaireMouvementController::class, 'create'])->name('create');
    Route::post('/', [GestionnaireMouvementController::class, 'store'])->name('store');
    Route::get('/{id}', [GestionnaireMouvementController::class, 'show'])->name('show');
    Route::get('/{id}/decharge', [GestionnaireMouvementController::class, 'decharge'])->name('decharge');
    Route::post('/{id}/valider', [GestionnaireMouvementController::class, 'valider'])->name('valider');
    Route::post('/{id}/receptionner', [GestionnaireMouvementController::class, 'receptionner'])->name('receptionner');
});

Route::prefix('maintenances')->name('maintenances.')->group(function () {
    Route::get('/', [GestionnaireMaintenanceController::class, 'index'])->name('index');
    Route::get('/creer', [GestionnaireMaintenanceController::class, 'create'])->name('create');
    Route::post('/', [GestionnaireMaintenanceController::class, 'store'])->name('store');
    Route::get('/{id}', [GestionnaireMaintenanceController::class, 'show'])->name('show');
    Route::post('/{id}/cloturer', [GestionnaireMaintenanceController::class, 'cloturer'])->name('cloturer');
});

Route::prefix('reporting')->name('reporting.')->group(function () {
    Route::get('/', [GestionnaireReportingController::class, 'index'])->name('index');
    Route::get('/registre-officiel', [GestionnaireReportingController::class, 'registreOfficiel'])->name('registre');
});

Route::get('/audit', [GestionnaireAuditController::class, 'index'])->name('audit.index');

