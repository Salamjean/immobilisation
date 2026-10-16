<?php

namespace App\Http\Controllers\Logistique;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Immobilisation;
use App\Models\Maintenance;
use Illuminate\Http\Request;

class LogistiqueMaintenanceController extends Controller
{
    public function index(Request $request)
    {
        $query = Maintenance::with(['immobilisation.categorie', 'immobilisation.structureBeneficiaire']);

        if ($request->filled('statut')) {
            $query->where('statut', $request->statut);
        }
        if ($request->filled('type_maintenance')) {
            $query->where('type_maintenance', $request->type_maintenance);
        }

        $maintenances = $query->latest('date_signalement')->paginate(15)->withQueryString();

        $totalEnCours = Maintenance::whereIn('statut', ['signalee', 'en_attente', 'en_cours'])->count();
        $coutTotal = Maintenance::sum('cout_intervention');

        return view('logistique.maintenances.index', compact('maintenances', 'totalEnCours', 'coutTotal'));
    }

    public function create(Request $request)
    {
        $actifId = $request->query('actif_id');
        $actifSelectionne = $actifId ? Immobilisation::find($actifId) : null;
        $actifs = Immobilisation::orderBy('code_immobilisation')->get();

        $dernierId = Maintenance::max('id') ?? 0;
        $numSuggere = 'MAINT-' . date('Y') . '-' . str_pad($dernierId + 1, 4, '0', STR_PAD_LEFT);

        return view('logistique.maintenances.create', compact('actifSelectionne', 'actifs', 'numSuggere'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'numero_fiche' => 'required|string|max:100|unique:maintenances,numero_fiche',
            'immobilisation_id' => 'required|exists:immobilisations,id',
            'type_maintenance' => 'required|string',
            'statut' => 'required|string',
            'date_signalement' => 'required|date',
            'date_intervention' => 'nullable|date',
            'date_fin_intervention' => 'nullable|date',
            'prestataire' => 'nullable|string|max:255',
            'technicien_intervenant' => 'nullable|string|max:255',
            'description_panne' => 'nullable|string',
            'diagnostic' => 'nullable|string',
            'travaux_effectues' => 'nullable|string',
            'pieces_remplacees' => 'nullable|string',
            'cout_intervention' => 'nullable|numeric|min:0',
            'observations' => 'nullable|string',
        ]);

        $maintenance = Maintenance::create($validated);

        if (in_array($request->statut, ['en_cours', 'en_attente', 'signalee'])) {
            Immobilisation::where('id', $request->immobilisation_id)->update(['etat' => 'en_maintenance']);
        } elseif ($request->statut === 'terminee') {
            Immobilisation::where('id', $request->immobilisation_id)->update(['etat' => 'bon_etat']);
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'CREATION_MAINTENANCE',
            'auditable_type' => Maintenance::class,
            'auditable_id' => $maintenance->id,
            'nouvelles_valeurs' => $validated,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return redirect()->route('logistique.maintenances.show', $maintenance->id)
            ->with('success', "L'intervention {$maintenance->numero_fiche} a été enregistrée avec succès.");
    }

    public function show($id)
    {
        $maintenance = Maintenance::with([
            'immobilisation.categorie',
            'immobilisation.structureBeneficiaire',
            'immobilisation.programme'
        ])->findOrFail($id);

        return view('logistique.maintenances.show', compact('maintenance'));
    }

    public function cloturer(Request $request, $id)
    {
        $maintenance = Maintenance::findOrFail($id);
        $maintenance->update([
            'statut' => 'terminee',
            'date_fin_intervention' => now(),
            'travaux_effectues' => $request->travaux_effectues ?? $maintenance->travaux_effectues,
            'cout_intervention' => $request->cout_intervention ?? $maintenance->cout_intervention,
        ]);

        $maintenance->immobilisation?->update(['etat' => 'bon_etat']);

        return redirect()->route('logistique.maintenances.show', $maintenance->id)
            ->with('success', 'Intervention de maintenance clôturée avec succès.');
    }
}
