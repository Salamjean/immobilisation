<?php

use Illuminate\Support\Facades\Route;

// Contrôleurs Administrateur
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\StructureController as AdminStructureController;
use App\Http\Controllers\Admin\CategorieController as AdminCategorieController;
use App\Http\Controllers\Admin\BailleurController as AdminBailleurController;
use App\Http\Controllers\Admin\ProgrammeController as AdminProgrammeController;
use App\Http\Controllers\Admin\SubventionController as AdminSubventionController;
use App\Http\Controllers\Admin\AuditController as AdminAuditController;
use App\Http\Controllers\Admin\ImmobilisationController as AdminImmobilisationController;
use App\Http\Controllers\Admin\MouvementController as AdminMouvementController;
use App\Http\Controllers\Admin\CampagneInventaireController as AdminCampagneInventaireController;
use App\Http\Controllers\Admin\CartographieController as AdminCartographieController;
use App\Http\Controllers\Admin\ReportingController as AdminReportingController;

/*
|--------------------------------------------------------------------------
| Espace Administrateur Système (prefix: admin, name: admin.)
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // 1. Référentiel des Immobilisations
    Route::prefix('immobilisations')->name('immobilisations.')->group(function () {
        Route::get('/', [AdminImmobilisationController::class, 'index'])->name('index');
        Route::get('/creer', [AdminImmobilisationController::class, 'create'])->name('create');
        Route::post('/', [AdminImmobilisationController::class, 'store'])->name('store');
        Route::get('/export/csv', [AdminImmobilisationController::class, 'exportCsv'])->name('export.csv');
        Route::get('/{id}', [AdminImmobilisationController::class, 'show'])->name('show');
        Route::get('/{id}/modifier', [AdminImmobilisationController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminImmobilisationController::class, 'update'])->name('update');
        Route::get('/{id}/etiquette', [AdminImmobilisationController::class, 'etiquette'])->name('etiquette');
    });

    // 2. Gestion des Catégories & Familles
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminCategorieController::class, 'index'])->name('index');
        Route::get('/creer', [AdminCategorieController::class, 'create'])->name('create');
        Route::post('/', [AdminCategorieController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [AdminCategorieController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminCategorieController::class, 'update'])->name('update');
    });

    // 2b. Gestion des Bailleurs de Fonds
    Route::prefix('bailleurs')->name('bailleurs.')->group(function () {
        Route::get('/', [AdminBailleurController::class, 'index'])->name('index');
        Route::get('/creer', [AdminBailleurController::class, 'create'])->name('create');
        Route::post('/', [AdminBailleurController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [AdminBailleurController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminBailleurController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [AdminBailleurController::class, 'toggleStatus'])->name('toggle');
    });

    // 2c. Gestion des Programmes
    Route::prefix('programmes')->name('programmes.')->group(function () {
        Route::get('/', [AdminProgrammeController::class, 'index'])->name('index');
        Route::get('/creer', [AdminProgrammeController::class, 'create'])->name('create');
        Route::post('/', [AdminProgrammeController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [AdminProgrammeController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminProgrammeController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [AdminProgrammeController::class, 'toggleStatus'])->name('toggle');
        Route::delete('/{id}', [AdminProgrammeController::class, 'destroy'])->name('destroy');
    });

    // 2d. Gestion des Subventions
    Route::prefix('subventions')->name('subventions.')->group(function () {
        Route::get('/', [AdminSubventionController::class, 'index'])->name('index');
        Route::get('/creer', [AdminSubventionController::class, 'create'])->name('create');
        Route::post('/', [AdminSubventionController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [AdminSubventionController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminSubventionController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminSubventionController::class, 'destroy'])->name('destroy');
    });

    // 3. Mouvements, Affectations & Transferts
    Route::prefix('mouvements')->name('mouvements.')->group(function () {
        Route::get('/', [AdminMouvementController::class, 'index'])->name('index');
        Route::get('/creer', [AdminMouvementController::class, 'create'])->name('create');
        Route::post('/', [AdminMouvementController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminMouvementController::class, 'show'])->name('show');
        Route::post('/{id}/valider', [AdminMouvementController::class, 'valider'])->name('valider');
        Route::post('/{id}/receptionner', [AdminMouvementController::class, 'receptionner'])->name('receptionner');
    });

    // 4. Inventaires Physiques & Rapprochement
    Route::prefix('inventaires')->name('inventaires.')->group(function () {
        Route::get('/', [AdminCampagneInventaireController::class, 'index'])->name('index');
        Route::get('/creer', [AdminCampagneInventaireController::class, 'create'])->name('create');
        Route::post('/', [AdminCampagneInventaireController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminCampagneInventaireController::class, 'show'])->name('show');
        Route::post('/releve/{id}/valider', [AdminCampagneInventaireController::class, 'validerEcart'])->name('releve.valider');
        Route::post('/{id}/cloturer', [AdminCampagneInventaireController::class, 'cloturer'])->name('cloturer');
    });

    // 5. Reporting & Registre Officiel
    Route::prefix('reporting')->name('reporting.')->group(function () {
        Route::get('/', [AdminReportingController::class, 'index'])->name('index');
        Route::get('/registre-officiel', [AdminReportingController::class, 'registreOfficiel'])->name('registre');
    });

    // 6. Cartographie SIG
    Route::get('/cartographie', [AdminCartographieController::class, 'index'])->name('cartographie.index');

    // 8. Gestion des Structures Sanitaires & Bénéficiaires
    Route::prefix('structures')->name('structures.')->group(function () {
        Route::get('/', [AdminStructureController::class, 'index'])->name('index');
        Route::get('/creer', [AdminStructureController::class, 'create'])->name('create');
        Route::post('/', [AdminStructureController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [AdminStructureController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminStructureController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [AdminStructureController::class, 'toggleStatus'])->name('toggle');
    });

    // 9. Gestion des Utilisateurs & Droits
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/creer', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{id}/modifier', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::post('/{id}/toggle', [AdminUserController::class, 'toggleStatus'])->name('toggle');
    });

    // 10. Piste d'Audit & Journalisation
    Route::prefix('audit')->name('audit.')->group(function () {
        Route::get('/', [AdminAuditController::class, 'index'])->name('index');
        Route::get('/export', [AdminAuditController::class, 'export'])->name('export');
    });
});
