<?php

namespace App\Http\Controllers\Auditeur;

use App\Http\Controllers\Controller;
use App\Models\Alerte;
use App\Models\AuditLog;
use App\Models\CampagneInventaire;
use App\Models\Immobilisation;
use App\Models\InventaireReleve;
use Illuminate\Http\Request;

class AuditeurDashboardController extends Controller
{
    public function index()
    {
        $totalActifs = Immobilisation::count();
        $valeurTotale = Immobilisation::sum('valeur_acquisition');
        $actifsCritiques = Immobilisation::whereIn('niveau_criticite', ['critique', 'eleve'])->count();
        $totalEcarts = InventaireReleve::where('statut_rapprochement', '!=', 'conforme')->count();
        $totalLogs = AuditLog::count();
        
        $derniersLogs = AuditLog::with('user')->latest('created_at')->take(8)->get();
        $alertesControle = Alerte::with('immobilisation')->where('lue', false)->latest()->take(6)->get();
        $campagnes = CampagneInventaire::withCount('releves')->latest()->get();

        return view('auditeur.dashboard', compact(
            'totalActifs',
            'valeurTotale',
            'actifsCritiques',
            'totalEcarts',
            'totalLogs',
            'derniersLogs',
            'alertesControle',
            'campagnes'
        ));
    }
}
