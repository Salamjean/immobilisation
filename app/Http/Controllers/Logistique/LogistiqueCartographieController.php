<?php

namespace App\Http\Controllers\Logistique;

use App\Http\Controllers\Controller;
use App\Models\CategorieActif;
use App\Models\Immobilisation;
use App\Models\Programme;
use App\Models\StructureBeneficiaire;
use Illuminate\Http\Request;

class LogistiqueCartographieController extends Controller
{
    public function index(Request $request)
    {
        $query = Immobilisation::with(['structureBeneficiaire', 'categorie', 'programme', 'detenteur'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($request->filled('structure_id')) {
            $query->where('structure_beneficiaire_id', $request->structure_id);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        $points = $query->get()->map(function ($actif) {
            return [
                'id' => $actif->id,
                'code' => $actif->code_immobilisation,
                'designation' => $actif->designation,
                'marque' => $actif->marque,
                'modele' => $actif->modele,
                'lat' => (float)$actif->latitude,
                'lng' => (float)$actif->longitude,
                'structure' => $actif->structureBeneficiaire?->nom ?? 'Non rattaché',
                'programme' => $actif->programme?->sigle ?? 'N/A',
                'categorie' => $actif->categorie?->nom ?? 'N/A',
                'etat' => $actif->etat_libelle,
                'etat_badge' => $actif->etat_badge_class,
                'criticite' => $actif->niveau_criticite,
                'valeur' => $actif->valeur_formatee,
                'url' => route('logistique.immobilisations.show', $actif->id),
            ];
        });

        $totalGeolocalises = $points->count();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();
        $categories = CategorieActif::orderBy('nom')->get();

        return view('logistique.cartographie.index', compact('points', 'totalGeolocalises', 'structures', 'categories'));
    }
}
