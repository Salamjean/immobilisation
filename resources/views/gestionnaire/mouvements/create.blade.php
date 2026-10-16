@extends('layouts.app')

@section('title', 'Nouveau Mouvement / Transfert - Espace Gestionnaire')

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('gestionnaire.mouvements.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <i class="fa-solid fa-arrow-right-arrow-left text-[#155E3B]"></i>
                    <span>Demande de Transfert / Mouvement d'Actif</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Transfert inter-sites, réaffectation interne, prêt ou proposition de réforme</p>
            </div>
        </div>

        <div class="text-xs text-slate-400">
            Champs obligatoires <span class="text-rose-500 font-bold">*</span>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="flex items-center space-x-2 font-bold mb-1">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>Veuillez corriger les erreurs suivantes :</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('gestionnaire.mouvements.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Section 1 : Opération & Référence -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #155E3B;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-file-signature"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">1. Nature de l'Opération</h2>
                    <p class="text-xs text-slate-400">Définissez la référence administrative et le type de mouvement</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Référence Mouvement -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="reference_mouvement" class="block text-xs font-semibold text-slate-700">
                            Référence Mouvement <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            <span>Auto</span>
                        </span>
                    </div>
                    <input type="text"
                           id="reference_mouvement"
                           name="reference_mouvement"
                           value="{{ old('reference_mouvement', $refSuggeree) }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-amber-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>

                <!-- Type Mouvement -->
                <div>
                    <label for="type_mouvement" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Type d'Opération <span class="text-rose-500">*</span>
                    </label>
                    <select id="type_mouvement"
                            name="type_mouvement"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="transfert" {{ old('type_mouvement') === 'transfert' ? 'selected' : '' }}>Transfert inter-établissements sanitaires</option>
                        <option value="reaffectation" {{ old('type_mouvement') === 'reaffectation' ? 'selected' : '' }}>Réaffectation interne de service / bureau</option>
                        <option value="pret" {{ old('type_mouvement') === 'pret' ? 'selected' : '' }}>Prêt temporaire d'équipement</option>
                        <option value="sortie_temporaire" {{ old('type_mouvement') === 'sortie_temporaire' ? 'selected' : '' }}>Sortie temporaire (Maintenance / SAV)</option>
                        <option value="reforme" {{ old('type_mouvement') === 'reforme' ? 'selected' : '' }}>Proposition de réforme / mise au rebut</option>
                    </select>
                </div>

                <!-- Date Exécution -->
                <div>
                    <label for="date_mouvement" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Date d'Exécution Prévue <span class="text-rose-500">*</span>
                    </label>
                    <input type="date"
                           id="date_mouvement"
                           name="date_mouvement"
                           value="{{ old('date_mouvement', date('Y-m-d')) }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>
        </div>

        <!-- Section 2 : Actif & Destination -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm shadow-xs border border-amber-100">
                    <i class="fa-solid fa-hospital-user"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">2. Équipement & Destination</h2>
                    <p class="text-xs text-slate-400">Sélectionnez le bien à déplacer et le site sanitaire récepteur</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Immobilisation -->
                <div>
                    <label for="immobilisation_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Immobilisation à Transférer <span class="text-rose-500">*</span>
                    </label>
                    <select id="immobilisation_id"
                            name="immobilisation_id"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">-- Choisir un équipement actif --</option>
                        @foreach($actifs as $a)
                            <option value="{{ $a->id }}" {{ (old('immobilisation_id') == $a->id || ($actifSelectionne && $actifSelectionne->id == $a->id)) ? 'selected' : '' }}>
                                [{{ $a->code_immobilisation }}] {{ $a->designation }} — {{ $a->structureBeneficiaire?->nom ?? 'Site actuel non défini' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Destination -->
                <div>
                    <label for="structure_destination_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Structure Sanitaire de Destination <span class="text-rose-500">*</span>
                    </label>
                    <select id="structure_destination_id"
                            name="structure_destination_id"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">-- Sélectionner le site récepteur --</option>
                        @foreach($structures as $st)
                            <option value="{{ $st->id }}" {{ old('structure_destination_id') == $st->id ? 'selected' : '' }}>
                                {{ $st->nom }} ({{ $st->ville ?? 'Région' }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 3 : Justification & Instructions Logistiques -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm shadow-xs border border-slate-200">
                    <i class="fa-solid fa-align-left"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">3. Justification & Instructions</h2>
                    <p class="text-xs text-slate-400">Motivation administrative et consignes particulières pour le transport</p>
                </div>
            </div>

            <div>
                <label for="motif" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Motif Administratif & Justification <span class="text-rose-500">*</span>
                </label>
                <textarea id="motif"
                          name="motif"
                          rows="3"
                          required
                          placeholder="Préciser les raisons du mouvement (redéploiement d'équipements, renforcement de plateau technique, panne locale, décision de direction...)"
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">{{ old('motif') }}</textarea>
            </div>

            <div>
                <label for="commentaires" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Commentaires & Modalités Logistiques / Transport
                </label>
                <textarea id="commentaires"
                          name="commentaires"
                          rows="3"
                          placeholder="Transporteur mandaté, précautions particulières de manutention pour matériel médical fragile..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">{{ old('commentaires') }}</textarea>
            </div>
        </div>

        <!-- Boutons d'Action -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('gestionnaire.mouvements.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">
                Annuler
            </a>
            <button type="submit"
                    style="background-color: #155E3B;"
                    class="px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-paper-plane"></i>
                <span>Soumettre la demande de transfert</span>
            </button>
        </div>
    </form>

</div>
@endsection
