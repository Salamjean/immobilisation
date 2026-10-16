@extends('layouts.app')

@section('title', "Lancer une Campagne d'Inventaire")

@section('content')
<div class="space-y-5">

    <!-- En-tête avec Bouton Retour -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('gestionnaire.inventaires.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Lancer une nouvelle campagne d'inventaire</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Planification, affectation des agents contrôleurs et objectifs de réconciliation</p>
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

    <!-- Formulaire d'enregistrement en 3 Colonnes -->
    <form id="campagneFormGestionnaire" action="{{ route('gestionnaire.inventaires.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

            <!-- COLONNE 1 : Périmètre & Code -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-barcode"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">1. Définition & Code</h2>
                    </div>

                    <!-- Code Campagne -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Code Campagne <span class="text-rose-500">*</span></label>
                        <input type="text" name="code_campagne" value="{{ old('code_campagne', $codeSuggere) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                    </div>

                    <!-- Intitulé -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Intitulé de la Campagne <span class="text-rose-500">*</span></label>
                        <input type="text" name="intitule" value="{{ old('intitule') }}" required placeholder="Ex: Inventaire Général Annuel National 2026" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                    </div>

                    <!-- Type de campagne -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Type d'Inventaire <span class="text-rose-500">*</span></label>
                        <select name="type_campagne" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                            <option value="annuel" {{ old('type_campagne') === 'annuel' ? 'selected' : '' }}>Inventaire Général Annuel</option>
                            <option value="tournant" {{ old('type_campagne') === 'tournant' ? 'selected' : '' }}>Inventaire Tournant Périodique</option>
                            <option value="cible_programme" {{ old('type_campagne') === 'cible_programme' ? 'selected' : '' }}>Inventaire Ciblé par Programme</option>
                            <option value="cible_region" {{ old('type_campagne') === 'cible_region' ? 'selected' : '' }}>Inventaire Régional</option>
                            <option value="actifs_critiques" {{ old('type_campagne') === 'actifs_critiques' ? 'selected' : '' }}>Contrôle Actifs Critiques</option>
                        </select>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2">
                    <i class="fa-solid fa-layer-group text-[#155E3B] mr-1"></i> Campagne d'audit physique certifiée
                </div>
            </div>

            <!-- COLONNE 2 : Calendrier & Territoire -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-calendar-days"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">2. Calendrier & Région</h2>
                    </div>

                    <!-- Dates -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Date Début <span class="text-rose-500">*</span></label>
                            <input type="date" name="date_debut" value="{{ old('date_debut', date('Y-m-d')) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        </div>
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Date Fin <span class="text-rose-500">*</span></label>
                            <input type="date" name="date_fin" value="{{ old('date_fin', date('Y-m-d', strtotime('+60 days'))) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Région & Programme -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Région Sanitaire Ciblée</label>
                        <select name="region_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                            <option value="">Toutes les régions (National)</option>
                            @foreach($regions as $r)
                                <option value="{{ $r->id }}" {{ old('region_id') == $r->id ? 'selected' : '' }}>{{ $r->nom }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Programme de Santé Ciblé</label>
                        <select name="programme_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                            <option value="">Tous les programmes</option>
                            @foreach($programmes as $p)
                                <option value="{{ $p->id }}" {{ old('programme_id') == $p->id ? 'selected' : '' }}>{{ $p->code }} - {{ $p->nom }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2">
                    <i class="fa-solid fa-map-location-dot text-emerald-500 mr-1"></i> Périmètre géographique et temporel
                </div>
            </div>

            <!-- COLONNE 3 : Superviseur & Objectifs -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-user-shield"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">3. Supervision & Objectifs</h2>
                    </div>

                    <!-- Superviseur (Rôles : Auditeur, Consultant, Lecteur) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="superviseur_id" class="block text-xs font-semibold text-slate-700">
                                Superviseur de la Mission
                            </label>
                            <span class="text-[10px] font-medium text-purple-700 bg-purple-50 px-1.5 py-0.5 rounded border border-purple-200">
                                Auditeur / Consultant / Lecteur
                            </span>
                        </div>
                        <select name="superviseur_id" id="superviseur_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                            <option value="">Sélectionner un superviseur</option>
                            @foreach($superviseurs as $sup)
                                <option value="{{ $sup->id }}" {{ old('superviseur_id') == $sup->id ? 'selected' : '' }}>
                                    {{ $sup->nom_complet ?: $sup->name }} ({{ $sup->role_libelle }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Liste déroulante des Agents (Rôle Agent d'inventaire uniquement) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label for="agents_select" class="block text-xs font-semibold text-slate-700">
                                Agents d'Inventaire Attribués <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                                Rôle Agent uniquement
                            </span>
                        </div>
                        <select name="agents[]"
                                id="agents_select"
                                multiple
                                required
                                class="no-search w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all"
                                placeholder="Sélectionner les agents d'inventaire...">
                            @foreach($agents as $agent)
                                <option value="{{ $agent->id }}" {{ (is_array(old('agents')) && in_array($agent->id, old('agents'))) ? 'selected' : '' }}>
                                    {{ $agent->nom_complet ?: $agent->name }} @if($agent->telephone) — {{ $agent->telephone }}@endif
                                </option>
                            @endforeach
                        </select>
                        @error('agents')
                            <p class="text-[11px] text-rose-600 font-semibold mt-1">
                                <i class="fa-solid fa-triangle-exclamation mr-1"></i>{{ $message }}
                            </p>
                        @enderror
                        <p class="text-[10px] text-slate-400 mt-1">
                            Sélectionnez un ou plusieurs agents recenseurs pour cette campagne.
                        </p>
                    </div>

                    <!-- Objectifs -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Objectifs & Instructions</label>
                        <textarea name="objectifs" rows="3" placeholder="Précisions sur les protocoles de scan et rapprochement..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">{{ old('objectifs') }}</textarea>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2">
                    <i class="fa-solid fa-shield-halved text-[#155E3B] mr-1"></i> Rapprochement théorique vs physique
                </div>
            </div>

        </div>

        <!-- Barre d'Action & Soumission -->
        <div class="flex items-center justify-end space-x-3 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('gestionnaire.inventaires.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <button type="submit" style="background-color: #155E3B;" class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-check"></i>
                <span>Créer la campagne</span>
            </button>
        </div>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialisation de TomSelect pour la liste déroulante multiple des agents
            if (typeof TomSelect !== 'undefined') {
                const agentsEl = document.getElementById('agents_select');
                if (agentsEl) {
                    new TomSelect(agentsEl, {
                        plugins: ['remove_button'],
                        placeholder: 'Rechercher et sélectionner les agents...',
                        maxOptions: 100,
                        hideSelected: true,
                        closeAfterSelect: false,
                    });
                }
            }

            // Contrôle avant soumission
            const form = document.getElementById('campagneFormGestionnaire');
            if (form) {
                form.addEventListener('submit', function(e) {
                    const select = document.getElementById('agents_select');
                    if (select && select.selectedOptions.length === 0) {
                        e.preventDefault();
                        alert('Veuillez sélectionner au moins un agent d\'inventaire dans la liste déroulante de la carte Supervision & Objectifs.');
                        select.focus();
                    }
                });
            }
        });
    </script>

</div>
@endsection
