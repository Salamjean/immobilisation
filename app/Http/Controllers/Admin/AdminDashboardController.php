<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\CampagneInventaire;
use App\Models\Immobilisation;
use App\Models\StructureBeneficiaire;
use App\Models\User;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $totalActifs = Immobilisation::count();
        $valeurTotale = Immobilisation::sum('valeur_acquisition');
        $totalStructures = StructureBeneficiaire::count();
        $totalLogs = AuditLog::count();
        $derniersLogs = AuditLog::with('user')->latest('created_at')->take(10)->get();
        $campagnes = CampagneInventaire::withCount(['releves', 'equipes'])->latest()->get();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalActifs',
            'valeurTotale',
            'totalStructures',
            'totalLogs',
            'derniersLogs',
            'campagnes'
        ));
    }
}
