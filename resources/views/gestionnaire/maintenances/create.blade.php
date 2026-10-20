@extends('layouts.app')

@section('title', 'Déclarer une Panne / SAV - Espace Gestionnaire')

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('gestionnaire.maintenances.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <i class="fa-solid fa-wrench text-[#0048FA]"></i>
                    <span>Déclarer une Panne / Fiche de Maintenance</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Enregistrement d'un incident matériel, demande de SAV ou révision périodique</p>
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

    <form action="{{ route('gestionnaire.maintenances.store') }}" method="POST" class="space-y-6">
        @csrf

        <!-- Section 1 : Identification du Matériel & Référence -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #0048FA;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">1. Matériel & Référence de Fiche</h2>
                    <p class="text-xs text-slate-400">Sélectionnez l'équipement concerné et la nature de la maintenance</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Numéro Fiche -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="numero_fiche" class="block text-xs font-semibold text-slate-700">
                            N° Fiche Maintenance <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            <span>Auto</span>
                        </span>
                    </div>
                    <input type="text"
                           id="numero_fiche"
                           name="numero_fiche"
                           value="{{ old('numero_fiche', $numSuggere) }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-rose-900 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <!-- Type Maintenance -->
                <div>
                    <label for="type_maintenance" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Type de Maintenance <span class="text-rose-500">*</span>
                    </label>
                    <select id="type_maintenance"
                            name="type_maintenance"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                        <option value="corrective" {{ old('type_maintenance') === 'corrective' ? 'selected' : '' }}>Maintenance Corrective (Dépannage / Panne)</option>
                        <option value="preventive" {{ old('type_maintenance') === 'preventive' ? 'selected' : '' }}>Maintenance Préventive Planifiée</option>
                        <option value="calibration" {{ old('type_maintenance') === 'calibration' ? 'selected' : '' }}>Étalonnage / Calibration Biomédicale</option>
                        <option value="controle_technique" {{ old('type_maintenance') === 'controle_technique' ? 'selected' : '' }}>Contrôle Technique & Sécurité</option>
                    </select>
                </div>

                <!-- Date Signalement -->
                <div>
                    <label for="date_signalement" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Date de Signalement <span class="text-rose-500">*</span>
                    </label>
                    <input type="date"
                           id="date_signalement"
                           name="date_signalement"
                           value="{{ old('date_signalement', date('Y-m-d')) }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>
            </div>

            <!-- Immobilisation Concernée -->
            <div>
                <label for="immobilisation_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Immobilisation Concernée <span class="text-rose-500">*</span>
                </label>
                <select id="immobilisation_id"
                        name="immobilisation_id"
                        required
                        class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                    <option value="">-- Sélectionner l'actif à dépanner ou réviser --</option>
                    @foreach($actifs as $a)
                        <option value="{{ $a->id }}" {{ (old('immobilisation_id') == $a->id || ($actifSelectionne && $actifSelectionne->id == $a->id)) ? 'selected' : '' }}>
                            [{{ $a->code_immobilisation }}] {{ $a->designation }} — {{ $a->structureBeneficiaire?->nom ?? 'Site non défini' }} (État actuel: {{ $a->statut_operationnel ?? 'en_service' }})
                        </option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Section 2 : Diagnostic & Prestataire SAV -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-700 flex items-center justify-center text-sm shadow-xs border border-amber-100">
                    <i class="fa-solid fa-stethoscope"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">2. Symptômes & Diagnostic Technique</h2>
                    <p class="text-xs text-slate-400">Décrivez la panne constatée et le prestataire en charge</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Prestataire SAV -->
                <div>
                    <label for="prestataire" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Prestataire / Société SAV
                    </label>
                    <input type="text"
                           id="prestataire"
                           name="prestataire"
                           value="{{ old('prestataire') }}"
                           placeholder="Ex: Copharmed CI, Siemens Healthcare, Équipe Biomédicale Interne..."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <!-- Technicien -->
                <div>
                    <label for="technicien_intervenant" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Technicien / Ingénieur Référent
                    </label>
                    <input type="text"
                           id="technicien_intervenant"
                           name="technicien_intervenant"
                           value="{{ old('technicien_intervenant') }}"
                           placeholder="Ex: Ing. Kouassi Jean..."
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>
            </div>

            <!-- Description Panne -->
            <div>
                <label for="description_panne" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Description des Symptômes / Anomalie Constatée
                </label>
                <textarea id="description_panne"
                          name="description_panne"
                          rows="3"
                          placeholder="Décrivez les pannes constatées (bruit anormal, erreur logicielle, coupure d'alimentation, pièce cassée...)"
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">{{ old('description_panne') }}</textarea>
            </div>

            <!-- Diagnostic Technique -->
            <div>
                <label for="diagnostic" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Diagnostic Technique & Travaux Recommandés
                </label>
                <textarea id="diagnostic"
                          name="diagnostic"
                          rows="3"
                          placeholder="Résultat de l'investigation technique, pièces à commander ou actions préconisées..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">{{ old('diagnostic') }}</textarea>
            </div>
        </div>

        <!-- Section 3 : Coûts, Statut & Calendrier -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center text-sm shadow-xs border border-slate-200">
                    <i class="fa-solid fa-coins"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">3. Suivi Financier & Statut</h2>
                    <p class="text-xs text-slate-400">Estimation financière et échéances de maintenance</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <!-- Coût -->
                <div>
                    <label for="cout_intervention" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Estimation du Coût (FCFA)
                    </label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           id="cout_intervention"
                           name="cout_intervention"
                           value="{{ old('cout_intervention', 0) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <!-- Statut Initial -->
                <div>
                    <label for="statut" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Statut Initial <span class="text-rose-500">*</span>
                    </label>
                    <select id="statut"
                            name="statut"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                        <option value="signalee" {{ old('statut', 'signalee') === 'signalee' ? 'selected' : '' }}>Signalée (En attente d'intervention)</option>
                        <option value="en_cours" {{ old('statut') === 'en_cours' ? 'selected' : '' }}>En cours de diagnostic / réparation</option>
                        <option value="terminee" {{ old('statut') === 'terminee' ? 'selected' : '' }}>Terminée / Réparée</option>
                    </select>
                </div>

                <!-- Date Prochaine Maintenance -->
                <div>
                    <label for="date_prochaine_maintenance" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Prochaine Maintenance Prévue
                    </label>
                    <input type="date"
                           id="date_prochaine_maintenance"
                           name="date_prochaine_maintenance"
                           value="{{ old('date_prochaine_maintenance') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>
            </div>
        </div>

        <!-- Boutons d'Action -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('gestionnaire.maintenances.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">
                Annuler
            </a>
            <button type="submit"
                    style="background-color: #0048FA;"
                    class="px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Enregistrer la fiche SAV</span>
            </button>
        </div>
    </form>

</div>
@endsection
