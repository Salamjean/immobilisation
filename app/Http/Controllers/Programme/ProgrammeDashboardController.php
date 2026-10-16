<?php

namespace App\Http\Controllers\Programme;

use App\Http\Controllers\Controller;
use App\Models\Immobilisation;
use App\Models\Mouvement;
use App\Models\Programme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProgrammeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $programmeId = $user?->programme_id ?? Programme::where('code', 'PNLP')->first()?->id ?? Programme::first()?->id;
        $programme = Programme::with('bailleur')->find($programmeId);

        $totalActifs = Immobilisation::where('programme_id', $programmeId)->count();
        $valeurTotale = Immobilisation::where('programme_id', $programmeId)->sum('valeur_acquisition');
        $actifsCritiques = Immobilisation::where('programme_id', $programmeId)->whereIn('niveau_criticite', ['critique', 'eleve'])->count();
        $actifsEnPanne = Immobilisation::where('programme_id', $programmeId)->whereIn('etat', ['panne', 'hors_service', 'en_maintenance'])->count();

        $derniersMouvements = Mouvement::whereHas('immobilisation', function ($q) use ($programmeId) {
            $q->where('programme_id', $programmeId);
        })->latest('date_mouvement')->take(5)->get();

        $actifs = Immobilisation::with(['categorie', 'structureBeneficiaire', 'detenteur'])
            ->where('programme_id', $programmeId)
            ->latest()
            ->take(10)
            ->get();

        return view('programme.dashboard', compact(
            'programme',
            'totalActifs',
            'valeurTotale',
            'actifsCritiques',
            'actifsEnPanne',
            'derniersMouvements',
            'actifs'
        ));
    }
}
