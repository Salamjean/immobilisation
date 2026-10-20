@extends('layouts.app')

@section('title', 'Nouvelle Demande de Maintenance')
@section('header_title', 'Créer une Fiche d\'Intervention SAV')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('logistique.maintenances.index') }}" class="w-9 h-9 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 flex items-center justify-center transition-all shadow-2xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-base font-bold text-slate-900">Nouvelle Fiche de Maintenance & Dépannage</h1>
                <p class="text-xs text-slate-500">Signaler une anomalie, programmer une révision ou un SAV</p>
            </div>
        </div>
        <span class="font-mono text-xs font-bold text-[#0048FA] bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
            {{ $numSuggere }}
        </span>
    </div>

    <form method="POST" action="{{ route('logistique.maintenances.store') }}" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
        @csrf

        <input type="hidden" name="numero_fiche" value="{{ $numSuggere }}">

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
            <!-- Actif concerné -->
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-800 mb-1">Équipement / Véhicule à Intervenir *</label>
                <select name="immobilisation_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">
                    <option value="">-- Sélectionnez l'actif --</option>
                    @foreach($actifs as $actif)
                    <option value="{{ $actif->id }}" {{ (old('immobilisation_id', $actifSelectionne?->id) == $actif->id) ? 'selected' : '' }}>
                        {{ $actif->code_immobilisation }} - {{ $actif->designation }} ({{ $actif->structureBeneficiaire?->nom ?? 'Site non défini' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Type maintenance -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Type d'Intervention *</label>
                <select name="type_maintenance" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">
                    <option value="corrective" {{ old('type_maintenance') === 'corrective' ? 'selected' : '' }}>Maintenance Corrective (Panne)</option>
                    <option value="preventive" {{ old('type_maintenance') === 'preventive' ? 'selected' : '' }}>Maintenance Préventive (Entretien périodique)</option>
                    <option value="curative" {{ old('type_maintenance') === 'curative' ? 'selected' : '' }}>Maintenance Curative (Remise en état)</option>
                    <option value="revision" {{ old('type_maintenance') === 'revision' ? 'selected' : '' }}>Révision générale</option>
                </select>
            </div>

            <!-- Statut initial -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Statut Initial *</label>
                <select name="statut" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">
                    <option value="signalee" {{ old('statut') === 'signalee' ? 'selected' : '' }}>Signalée (À traiter)</option>
                    <option value="en_attente" {{ old('statut') === 'en_attente' ? 'selected' : '' }}>En attente de devis / pièces</option>
                    <option value="en_cours" {{ old('statut') === 'en_cours' ? 'selected' : '' }}>En cours de réparation</option>
                </select>
            </div>

            <!-- Date signalement -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Date de Signalement *</label>
                <input type="date" name="date_signalement" value="{{ old('date_signalement', date('Y-m-d')) }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">
            </div>

            <!-- Date prévue d'intervention -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Date Prévue d'Intervention</label>
                <input type="date" name="date_intervention" value="{{ old('date_intervention') }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">
            </div>

            <!-- Prestataire -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Prestataire / Garage / Technicien</label>
                <input type="text" name="prestataire" value="{{ old('prestataire') }}" placeholder="Ex: CFAO Motors, Atelier Interne..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">
            </div>

            <!-- Technicien intervenant -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Nom du Technicien</label>
                <input type="text" name="technicien_intervenant" value="{{ old('technicien_intervenant') }}" placeholder="Nom et prénom..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">
            </div>

            <!-- Coût estimé -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Coût Estimé / Réel (FCFA)</label>
                <input type="number" step="0.01" name="cout_intervention" value="{{ old('cout_intervention') }}" placeholder="0" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-mono font-medium">
            </div>

            <!-- Description panne -->
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-800 mb-1">Description des Symptômes / Panne *</label>
                <textarea name="description_panne" rows="3" required placeholder="Décrivez le problème constaté, les voyants allumés, le comportement..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">{{ old('description_panne') }}</textarea>
            </div>

            <!-- Diagnostic préliminaire -->
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-800 mb-1">Diagnostic Préliminaire / Travaux Recommandés</label>
                <textarea name="diagnostic" rows="2" placeholder="Diagnostic établi par l'équipe logistique ou le réparateur..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium">{{ old('diagnostic') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('logistique.maintenances.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                Annuler
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0048FA] hover:bg-[#003ad1] text-white text-xs font-bold shadow-sm transition-all flex items-center space-x-2">
                <i class="fa-solid fa-check"></i>
                <span>Enregistrer la fiche</span>
            </button>
        </div>
    </form>

</div>
@endsection
