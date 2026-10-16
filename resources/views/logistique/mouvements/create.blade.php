@extends('layouts.app')

@section('title', 'Nouveau Mouvement Logistique')
@section('header_title', 'Enregistrer un Mouvement d\'Actif')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="flex items-center justify-between">
        <div class="flex items-center space-x-3">
            <a href="{{ route('logistique.mouvements.index') }}" class="w-9 h-9 rounded-xl bg-white hover:bg-slate-50 border border-slate-200 text-slate-700 flex items-center justify-center transition-all shadow-2xs">
                <i class="fa-solid fa-arrow-left text-sm"></i>
            </a>
            <div>
                <h1 class="text-base font-bold text-slate-900">Nouveau Mouvement d'Immobilisation</h1>
                <p class="text-xs text-slate-500">Créer une demande de transfert, affectation ou mise au rebut</p>
            </div>
        </div>
        <span class="font-mono text-xs font-bold text-[#155E3B] bg-emerald-50 px-3 py-1.5 rounded-xl border border-emerald-200">
            {{ $refSuggeree }}
        </span>
    </div>

    <form method="POST" action="{{ route('logistique.mouvements.store') }}" class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
        @csrf

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 text-xs">
            <!-- Sélection de l'actif -->
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-800 mb-1">Actif Concerné *</label>
                <select name="immobilisation_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">
                    <option value="">-- Sélectionnez un équipement --</option>
                    @foreach($actifs as $actif)
                    <option value="{{ $actif->id }}" {{ (old('immobilisation_id', $actifSelectionne?->id) == $actif->id) ? 'selected' : '' }}>
                        {{ $actif->code_immobilisation }} - {{ $actif->designation }} ({{ $actif->structureBeneficiaire?->nom ?? 'Site non défini' }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Type de mouvement -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Type de Mouvement *</label>
                <select name="type_mouvement" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">
                    <option value="transfert_site" {{ old('type_mouvement') === 'transfert_site' ? 'selected' : '' }}>Transfert de site / établissement</option>
                    <option value="affectation_interne" {{ old('type_mouvement') === 'affectation_interne' ? 'selected' : '' }}>Affectation interne (bureau/service)</option>
                    <option value="reaffectation" {{ old('type_mouvement') === 'reaffectation' ? 'selected' : '' }}>Réaffectation nouvel agent</option>
                    <option value="pret_temporaire" {{ old('type_mouvement') === 'pret_temporaire' ? 'selected' : '' }}>Prêt temporaire</option>
                    <option value="retour_pret" {{ old('type_mouvement') === 'retour_pret' ? 'selected' : '' }}>Retour de prêt</option>
                    <option value="reforme" {{ old('type_mouvement') === 'reforme' ? 'selected' : '' }}>Réforme technique</option>
                    <option value="mise_au_rebut" {{ old('type_mouvement') === 'mise_au_rebut' ? 'selected' : '' }}>Mise au rebut</option>
                </select>
            </div>

            <!-- Date du mouvement -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Date d'Exécution / Transfert *</label>
                <input type="date" name="date_mouvement" value="{{ old('date_mouvement', date('Y-m-d')) }}" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">
            </div>

            <!-- Structure Origine -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Site / Établissement Origine *</label>
                <select name="structure_origine_id" required class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">
                    <option value="">-- Sélectionnez l'origine --</option>
                    @foreach($structures as $st)
                    <option value="{{ $st->id }}" {{ (old('structure_origine_id', $actifSelectionne?->structure_beneficiaire_id) == $st->id) ? 'selected' : '' }}>
                        {{ $st->nom }} ({{ $st->code_structure }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Structure Destination -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Site / Établissement Destination</label>
                <select name="structure_destination_id" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">
                    <option value="">-- Même établissement ou non applicable --</option>
                    @foreach($structures as $st)
                    <option value="{{ $st->id }}" {{ old('structure_destination_id') == $st->id ? 'selected' : '' }}>
                        {{ $st->nom }} ({{ $st->code_structure }})
                    </option>
                    @endforeach
                </select>
            </div>

            <!-- Emplacement Destination -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Nouvel Emplacement / Bureau</label>
                <input type="text" name="emplacement_destination" value="{{ old('emplacement_destination') }}" placeholder="Ex: Bloc B - Salle 104" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">
            </div>

            <!-- Réf. Décharge / Bordereau -->
            <div>
                <label class="block font-bold text-slate-800 mb-1">Référence Décharge / Bordereau</label>
                <input type="text" name="reference_decharge" value="{{ old('reference_decharge') }}" placeholder="Ex: BL-2026-0042" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">
            </div>

            <!-- Motif -->
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-800 mb-1">Motif / Justification du Mouvement *</label>
                <textarea name="motif" rows="3" required placeholder="Expliquez la raison du déplacement ou du transfert..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">{{ old('motif') }}</textarea>
            </div>

            <!-- Observations -->
            <div class="sm:col-span-2">
                <label class="block font-bold text-slate-800 mb-1">Observations Logistiques Complémentaires</label>
                <textarea name="observations" rows="2" placeholder="État du matériel au départ, transporteur, conditions..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium">{{ old('observations') }}</textarea>
            </div>
        </div>

        <div class="flex items-center justify-end space-x-3 pt-4 border-t border-slate-100">
            <a href="{{ route('logistique.mouvements.index') }}" class="px-4 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                Annuler
            </a>
            <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#155E3B] hover:bg-[#0f442a] text-white text-xs font-bold shadow-sm transition-all flex items-center space-x-2">
                <i class="fa-solid fa-check"></i>
                <span>Enregistrer le mouvement</span>
            </button>
        </div>
    </form>

</div>
@endsection
