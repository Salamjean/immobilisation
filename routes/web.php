<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

/*
|--------------------------------------------------------------------------
| Routes Publiques & Authentification
|--------------------------------------------------------------------------
| Gère le login, logout, et les changements de session démo.
*/
require __DIR__ . '/auth.php';

/*
|--------------------------------------------------------------------------
| Point d'Entrée Racine & Redirection par Rôle
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    $user = Auth::user();
    return match ($user->role) {
        'admin' => redirect()->route('admin.dashboard'),
        'gestionnaire' => redirect()->route('gestionnaire.dashboard'),
        'responsable_logistique' => redirect()->route('logistique.dashboard'),
        'agent_inventaire' => redirect()->route('agent.mobile.index'),
        'auditeur' => redirect()->route('auditeur.dashboard'),
        'controleur' => redirect()->route('auditeur.dashboard'),
        'consultant' => redirect()->route('auditeur.dashboard'),
        'lecteur' => redirect()->route('gestionnaire.dashboard'),
        'utilisateur_programme' => redirect()->route('programme.dashboard'),
        default => redirect()->route('gestionnaire.dashboard'),
    };
})->name('home');

Route::get('/dashboard', function () {
    return redirect()->route('home');
})->name('dashboard');

Route::middleware(['auth'])->group(function () {
    // 1. Espace Administrateur Système
    require __DIR__ . '/admin.php';

    // 2. Espace Gestionnaire de Patrimoine
    require __DIR__ . '/gestionnaire.php';

    // 3. Espace Agent d'Inventaire Terrain Mobile
    require __DIR__ . '/agent.php';

    // 4. Espace Auditeur & Bailleurs de Fonds
    require __DIR__ . '/auditeur.php';

    // 5. Espace Responsable Programme
    require __DIR__ . '/programme.php';

    // 6. Espace Responsable Logistique
    require __DIR__ . '/logistique.php';
});
