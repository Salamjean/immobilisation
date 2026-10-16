<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CategorieActif;
use App\Models\Immobilisation;
use App\Models\Programme;
use App\Models\Region;
use App\Models\StructureBeneficiaire;
use Illuminate\Http\Request;

class CartographieController extends Controller
{
    public function index(Request $request)
    {
        $categories = CategorieActif::orderBy('nom')->get();
        $programmes = Programme::where('actif', true)->orderBy('nom')->get();
        $regions = Region::orderBy('nom')->get();
        $structures = StructureBeneficiaire::where('actif', true)->orderBy('nom')->get();

        $query = Immobilisation::with(['categorie', 'programme', 'structureBeneficiaire.region', 'detenteur'])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        if ($request->filled('programme_id')) {
            $query->where('programme_id', $request->programme_id);
        }
        if ($request->filled('categorie_id')) {
            $query->where('categorie_id', $request->categorie_id);
        }
        if ($request->filled('region_id')) {
            $query->whereHas('structureBeneficiaire', function ($q) use ($request) {
                $q->where('region_id', $request->region_id);
            });
        }
        if ($request->filled('structure_id')) {
            $query->where('structure_beneficiaire_id', $request->structure_id);
        }
        if ($request->filled('niveau_criticite')) {
            $query->where('niveau_criticite', $request->niveau_criticite);
        }
        if ($request->filled('etat')) {
            $query->where('etat', $request->etat);
        }

        $actifs = $query->get();

        $mapFeatures = $actifs->map(function ($actif) {
            $structureLat = $actif->structureBeneficiaire?->latitude;
            $structureLng = $actif->structureBeneficiaire?->longitude;
            $ecartMetres = 0;
            $geofenceAlerte = false;

            if ($structureLat && $structureLng && $actif->latitude && $actif->longitude) {
                $ecartMetres = $this->calculerDistanceMetres((float)$actif->latitude, (float)$actif->longitude, (float)$structureLat, (float)$structureLng);
                if ($ecartMetres > ($actif->rayon_geofence_metres ?? 500)) {
                    $geofenceAlerte = true;
                }
            }

            return [
                'id' => $actif->id,
                'code' => $actif->code_immobilisation,
                'designation' => $actif->designation,
                'marque_modele' => trim("{$actif->marque} {$actif->modele}"),
                'categorie' => $actif->categorie?->nom ?? 'Général',
                'programme' => $actif->programme?->sigle ?? $actif->programme?->code ?? 'UCP-FM',
                'structure' => $actif->structureBeneficiaire?->nom ?? 'Site non spécifié',
                'structure_id' => $actif->structure_beneficiaire_id,
                'ville' => $actif->structureBeneficiaire?->ville ?? 'Côte d\'Ivoire',
                'detenteur' => $actif->detenteur?->nom_complet ?? 'Non assigné',
                'valeur' => $actif->valeur_formatee ?? (number_format((float)$actif->valeur_acquisition, 0, ',', ' ') . ' FCFA'),
                'valeur_raw' => (float)$actif->valeur_acquisition,
                'etat' => $actif->etat,
                'etat_libelle' => $actif->etat_libelle ?? ucfirst(str_replace('_', ' ', $actif->etat ?? 'bon_etat')),
                'niveau_criticite' => $actif->niveau_criticite ?? 'modere',
                'latitude' => (float) $actif->latitude,
                'longitude' => (float) $actif->longitude,
                'precision_gps' => $actif->precision_gps ? "±{$actif->precision_gps} m" : '± 5.0 m',
                'date_localisation' => $actif->date_derniere_localisation ? $actif->date_derniere_localisation->format('d/m/Y H:i') : null,
                'ecart_metres' => round($ecartMetres),
                'geofence_alerte' => $geofenceAlerte,
                'photo' => $actif->photo_principale ?? null,
                'url' => route('admin.immobilisations.show', $actif->id),
            ];
        });

        $structuresSites = StructureBeneficiaire::whereNotNull('latitude')->whereNotNull('longitude')
            ->withCount('immobilisations')
            ->get()
            ->map(function ($s) {
                return [
                    'id' => $s->id,
                    'nom' => $s->nom,
                    'code' => $s->code,
                    'type_structure' => $s->type_structure ?? 'Établissement',
                    'ville' => $s->ville ?? 'Côte d\'Ivoire',
                    'latitude' => (float) $s->latitude,
                    'longitude' => (float) $s->longitude,
                    'nb_actifs' => $s->immobilisations_count,
                    'responsable' => $s->responsable_nom,
                    'contact' => $s->contact,
                ];
            });

        return view('admin.cartographie.index', compact('categories', 'programmes', 'regions', 'structures', 'mapFeatures', 'structuresSites'));
    }

    private function calculerDistanceMetres($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371000;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        return $earthRadius * $c;
    }
}
