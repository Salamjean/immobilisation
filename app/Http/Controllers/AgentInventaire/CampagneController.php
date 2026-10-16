<?php

namespace App\Http\Controllers\AgentInventaire;

use App\Http\Controllers\Controller;
use App\Models\CampagneInventaire;
use App\Models\Immobilisation;
use App\Models\InventaireReleve;
use Illuminate\Http\Request;

class CampagneController extends Controller
{
    public function index()
    {
        $campagnes = CampagneInventaire::with(['programme', 'region', 'superviseur', 'equipes'])
            ->withCount(['releves', 'equipes'])
            ->latest()
            ->paginate(15);

        $totalBiens = Immobilisation::count();
        $campagnesActivesCount = CampagneInventaire::where('statut', 'en_cours')->count();
        $mesRelevesCount = InventaireReleve::where('agent_user_id', auth()->id())->count();

        return view('agent_inventaire.campagnes.index', compact(
            'campagnes',
            'totalBiens',
            'campagnesActivesCount',
            'mesRelevesCount'
        ));
    }

    public function show($id)
    {
        $campagne = CampagneInventaire::with([
            'programme',
            'region',
            'superviseur',
            'equipes.user',
            'equipes.structure',
            'releves.immobilisation.categorie',
            'releves.structureConstatee',
            'releves.agent'
        ])->findOrFail($id);

        $totalBiensTheoriques = Immobilisation::count();
        $totalReleves = $campagne->releves()->count();
        $mesRelevesCampagne = $campagne->releves()->where('agent_user_id', auth()->id())->count();

        $tauxProgression = $totalBiensTheoriques > 0 ? round(($totalReleves / $totalBiensTheoriques) * 100, 1) : 0;

        return view('agent_inventaire.campagnes.show', compact(
            'campagne',
            'totalBiensTheoriques',
            'totalReleves',
            'mesRelevesCampagne',
            'tauxProgression'
        ));
    }
}
