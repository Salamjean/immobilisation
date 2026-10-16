@extends('layouts.app')

@section('title', 'Ajouter une Immobilisation')

@section('content')
<div class="space-y-6">

    <!-- En-tête avec Bouton Retour -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('gestionnaire.immobilisations.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Enregistrer une nouvelle immobilisation</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Saisie guidée en 4 étapes conformes aux 7 volets du Cahier des Charges</p>
            </div>
        </div>

        <div class="flex items-center space-x-2 text-xs">
            <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 font-semibold border border-emerald-200">
                <i class="fa-solid fa-shield-halved mr-1.5"></i> Norme UCPFM
            </span>
            <span class="text-slate-400">Champs obligatoires <span class="text-rose-500 font-bold">*</span></span>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="flex items-center space-x-2 font-bold mb-1">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>Veuillez corriger les erreurs ci-dessous :</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Barre de Progression : 4 ÉTAPES (Step-by-Step) -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-3 relative">
            
            <!-- Step 1 -->
            <button type="button" onclick="goToStep(1)" id="step-indicator-1" class="step-btn text-left p-3 rounded-xl border-2 border-[#155E3B] bg-cyan-50/50 transition-all flex items-center space-x-3 cursor-pointer">
                <div id="step-circle-1" class="w-8 h-8 rounded-lg bg-[#155E3B] text-white flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                    1
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-[#155E3B]">Étape 1</span>
                    <span class="block text-xs font-bold text-slate-900">Identification</span>
                </div>
            </button>

            <!-- Step 2 -->
            <button type="button" onclick="goToStep(2)" id="step-indicator-2" class="step-btn text-left p-3 rounded-xl border-2 border-slate-200 bg-slate-50/50 transition-all flex items-center space-x-3 cursor-pointer">
                <div id="step-circle-2" class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                    2
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Étape 2</span>
                    <span class="block text-xs font-bold text-slate-700">Acquisition</span>
                </div>
            </button>

            <!-- Step 3 -->
            <button type="button" onclick="goToStep(3)" id="step-indicator-3" class="step-btn text-left p-3 rounded-xl border-2 border-slate-200 bg-slate-50/50 transition-all flex items-center space-x-3 cursor-pointer">
                <div id="step-circle-3" class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                    3
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Étape 3</span>
                    <span class="block text-xs font-bold text-slate-700">Affectation & GPS</span>
                </div>
            </button>

            <!-- Step 4 -->
            <button type="button" onclick="goToStep(4)" id="step-indicator-4" class="step-btn text-left p-3 rounded-xl border-2 border-slate-200 bg-slate-50/50 transition-all flex items-center space-x-3 cursor-pointer">
                <div id="step-circle-4" class="w-8 h-8 rounded-lg bg-slate-200 text-slate-600 flex items-center justify-center font-bold text-xs flex-shrink-0">
                    4
                </div>
                <div>
                    <span class="block text-[10px] font-bold uppercase tracking-wider text-slate-400">Étape 4</span>
                    <span class="block text-xs font-bold text-slate-700">État, Risque & Doc</span>
                </div>
            </button>

        </div>
    </div>

    <!-- Formulaire d'enregistrement avec Conteneur des 4 Étapes -->
    <form id="immobilisationForm" action="{{ route('gestionnaire.immobilisations.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <!-- ==================== ÉTAPE 1 : IDENTIFICATION & NOMENCLATURE ==================== -->
        <div id="step-content-1" class="step-content bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-6">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #155E3B;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Étape 1 : Identification & Nomenclature</h2>
                        <p class="text-xs text-slate-400">Renseignez les références uniques de l'équipement et sa classification</p>
                    </div>
                </div>
                <span class="text-xs font-bold font-mono px-3 py-1 rounded-full bg-cyan-50 text-[#155E3B] border border-cyan-100">Volet 1/7</span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Code Immobilisation (grisé) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Identifiant Unique <span class="text-rose-500">*</span>
                    </label>
                    <div class="relative">
                        <input type="text"
                               name="code_immobilisation"
                               value="{{ old('code_immobilisation', $codeSuggere) }}"
                               readonly
                               required
                               class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-600 cursor-not-allowed select-none focus:outline-none shadow-2xs">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-lock text-slate-400"></i>
                        </div>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Généré automatiquement par le système (Norme UCP-FM)</span>
                </div>

                <!-- Référence Tag QR (grisé) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Référence Étiquette / Tag QR</label>
                    <div class="relative">
                        <input type="text"
                               name="reference_etiquette"
                               value="{{ old('reference_etiquette', $tagSuggere) }}"
                               readonly
                               class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-600 cursor-not-allowed select-none focus:outline-none shadow-2xs">
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-lock text-slate-400"></i>
                        </div>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Tag QR sécurisé prêt pour l'impression d'étiquette</span>
                </div>
            </div>

            <!-- Ligne Bailleur / Code Bailleur (6) & Désignation (6) -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-5">
                <!-- Premier bloc de 6 : Divisé en 8 (Bailleur) et 4 (Code Bailleur) -->
                <div class="md:col-span-6 grid grid-cols-12 gap-3">
                    <div class="col-span-12 sm:col-span-8">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Bailleur de Fonds</label>
                        <select name="bailleur_id" id="bailleur_select_step1" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                            <option value="">Sélectionner</option>
                            @foreach($bailleurs as $b)
                                <option value="{{ $b->id }}" data-code="{{ $b->code }}" {{ old('bailleur_id') == $b->id ? 'selected' : '' }}>
                                    {{ $b->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-span-12 sm:col-span-4">
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Code Bailleur</label>
                        <div class="relative">
                            <input type="text"
                                   name="code_bailleur"
                                   id="input_code_bailleur"
                                   value="{{ old('code_bailleur') }}"
                                   readonly
                                   placeholder="Code auto"
                                   class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-600 cursor-not-allowed select-none focus:outline-none shadow-2xs">
                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Deuxième bloc de 6 : Désignation -->
                <div class="md:col-span-6">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Désignation de l'actif <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           name="designation"
                           id="input_designation"
                           value="{{ old('designation') }}"
                           required
                           placeholder="Ex: Analyseur de Biochimie Automatique 400 tests/h"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>

            <!-- Catégorie & Sous-Catégorie -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Catégorie <span class="text-rose-500">*</span>
                    </label>
                    <select name="categorie_id" id="categorie_select" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">Sélectionner une catégorie</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ old('categorie_id') == $cat->id ? 'selected' : '' }}>
                                {{ $cat->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Sous-Catégorie</label>
                    <select name="sous_categorie_id" id="sous_categorie_select" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">Sélectionner une sous-catégorie (optionnel)</option>
                        @foreach($categories as $cat)
                            @foreach($cat->sousCategories as $scat)
                                <option value="{{ $scat->id }}" data-category="{{ $cat->id }}" {{ old('sous_categorie_id') == $scat->id ? 'selected' : '' }}>
                                    {{ $cat->nom }} &gt; {{ $scat->nom }}
                                </option>
                            @endforeach
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Marque, Modèle & Numéro de Série -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Marque</label>
                    <input type="text" name="marque" value="{{ old('marque') }}" placeholder="Ex: Mindray, Siemens, HP" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Modèle</label>
                    <input type="text" name="modele" value="{{ old('modele') }}" placeholder="Ex: BS-240, Dimension EXL" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Numéro de Série Fabricant</label>
                    <input type="text" name="numero_serie" value="{{ old('numero_serie') }}" placeholder="Ex: SN-884920482" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>
        </div>

        <!-- ==================== ÉTAPE 2 : ACQUISITION & FINANCEMENT ==================== -->
        <div id="step-content-2" class="step-content bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-6 hidden">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #155E3B;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Étape 2 : Acquisition & Financement</h2>
                        <p class="text-xs text-slate-400">Détails financiers, sources de financement bailleurs et références d'achat</p>
                    </div>
                </div>
                <span class="text-xs font-bold font-mono px-3 py-1 rounded-full bg-cyan-50 text-[#155E3B] border border-cyan-100">Volet 2/7</span>
            </div>

            <!-- Valeur & Devise -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Valeur d'Acquisition <span class="text-rose-500">*</span>
                    </label>
                    <input type="number"
                           name="valeur_acquisition"
                           id="input_valeur_acquisition"
                           value="{{ old('valeur_acquisition') }}"
                           min="0"
                           step="1"
                           required
                           placeholder="Ex: 15000000"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Devise <span class="text-rose-500">*</span></label>
                    <select name="devise" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="FCFA" {{ old('devise', 'FCFA') == 'FCFA' ? 'selected' : '' }}>FCFA (XOF)</option>
                        <option value="EUR" {{ old('devise') == 'EUR' ? 'selected' : '' }}>EUR (€)</option>
                        <option value="USD" {{ old('devise') == 'USD' ? 'selected' : '' }}>USD ($)</option>
                    </select>
                </div>
            </div>

            <!-- Subvention & Programme -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Subvention</label>
                    <select name="subvention_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">Sélectionner une subvention</option>
                        @foreach($subventions as $sub)
                            <option value="{{ $sub->id }}" {{ old('subvention_id') == $sub->id ? 'selected' : '' }}>
                                {{ $sub->code_subvention }} - {{ $sub->intitule ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Programme de Rattachement</label>
                    <select name="programme_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">Sélectionner un programme</option>
                        @foreach($programmes as $p)
                            <option value="{{ $p->id }}" {{ old('programme_id') == $p->id ? 'selected' : '' }}>
                                {{ $p->code }} - {{ $p->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Fournisseur, Marché & Bon de Commande -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Fournisseur</label>
                    <input type="text" name="fournisseur" value="{{ old('fournisseur') }}" placeholder="Ex: MedEquip SARL" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Marché / Contrat</label>
                    <input type="text" name="reference_marche" value="{{ old('reference_marche') }}" placeholder="Ex: DAO-2024-082" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Bon de Commande (PO)</label>
                    <input type="text" name="bon_commande" value="{{ old('bon_commande') }}" placeholder="Ex: BC-2024-114" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>

            <!-- Dates Clés : Acquisition, Réception & Mise en service -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Date d'Acquisition</label>
                    <input type="date" name="date_acquisition" value="{{ old('date_acquisition') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Date de Réception</label>
                    <input type="date" name="date_reception" value="{{ old('date_reception') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Date de Mise en Service</label>
                    <input type="date" name="date_mise_en_service" value="{{ old('date_mise_en_service') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>
        </div>

        <!-- ==================== ÉTAPE 3 : AFFECTATION & GÉOGRAPHIE ==================== -->
        <div id="step-content-3" class="step-content bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-6 hidden">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #155E3B;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Étape 3 : Affectation & Localisation Géographique</h2>
                        <p class="text-xs text-slate-400">Structure sanitaire, service, responsable détenteur et coordonnées GPS précises</p>
                    </div>
                </div>
                <span class="text-xs font-bold font-mono px-3 py-1 rounded-full bg-cyan-50 text-[#155E3B] border border-cyan-100">Volets 3 & 4/7</span>
            </div>

            <!-- Structure Bénéficiaire -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Structure Bénéficiaire / Établissement <span class="text-rose-500">*</span>
                </label>
                <select name="structure_beneficiaire_id" id="structure_select" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <option value="">Sélectionner une structure</option>
                    @foreach($structures as $st)
                        <option value="{{ $st->id }}"
                                data-lat="{{ $st->latitude }}"
                                data-lng="{{ $st->longitude }}"
                                data-region="{{ $st->region?->nom }}"
                                data-district="{{ $st->districtSanitaire?->nom }}"
                                {{ old('structure_beneficiaire_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->nom }} ({{ $st->ville ?? 'Côte d\'Ivoire' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Service & Emplacement / Site -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Service</label>
                    <select name="service_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">Sélectionner un service</option>
                        @foreach($services as $srv)
                            <option value="{{ $srv->id }}" {{ old('service_id') == $srv->id ? 'selected' : '' }}>
                                {{ $srv->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Site / Emplacement Détaillé</label>
                    <input type="text" name="emplacement_detaille" value="{{ old('emplacement_detaille') }}" placeholder="Ex: Bâtiment A, Salle Radiologie 1" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>

            <!-- Utilisateur / Détenteur & Date d'Affectation -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Utilisateur / Détenteur</label>
                    <select name="detenteur_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">Sélectionner un détenteur</option>
                        @foreach($detenteurs as $det)
                            <option value="{{ $det->id }}" {{ old('detenteur_id') == $det->id ? 'selected' : '' }}>
                                {{ $det->nom_complet }} ({{ $det->fonction ?? 'Agent' }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Date d'Affectation</label>
                    <input type="date" name="date_affectation" value="{{ old('date_affectation') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>

            <!-- Recherche Google Maps & Coordonnées GPS -->
            <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200 space-y-3.5">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 pb-2 border-b border-slate-200/80">
                    <div class="flex items-center space-x-2">
                        <span class="w-6 h-6 rounded-lg bg-[#155E3B]/10 text-[#155E3B] flex items-center justify-center text-xs">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </span>
                        <div>
                            <span class="text-xs font-bold text-slate-800">Recherche & Géolocalisation Google Maps</span>
                            <p class="text-[10px] text-slate-400">Recherchez un lieu ou déplacez le repère sur la carte pour capturer le nom et les coordonnées GPS exactes</p>
                        </div>
                    </div>
                    <button type="button" onclick="captureCurrentGPS()" class="px-3 py-1.5 rounded-lg bg-white border border-slate-300 text-slate-700 hover:bg-slate-100 text-xs font-bold shadow-2xs transition-all flex items-center space-x-1.5 self-start sm:self-auto flex-shrink-0">
                        <i class="fa-solid fa-location-crosshairs text-[#155E3B]"></i>
                        <span>Ma position actuelle</span>
                    </button>
                </div>

                <!-- Barre de Recherche Google Places -->
                <div class="relative">
                    <div class="flex rounded-xl shadow-2xs">
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </span>
                            <input type="text"
                                   id="google_places_search"
                                   placeholder="Rechercher une adresse, un établissement sanitaire, une ville sur Google Maps..."
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-white border border-slate-200 rounded-l-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                        </div>
                        <button type="button" id="btn_search_gmap" class="px-4 py-2.5 bg-[#155E3B] text-white rounded-r-xl text-xs font-bold hover:opacity-95 transition-all flex items-center space-x-1.5">
                            <i class="fa-solid fa-location-dot"></i>
                            <span>Rechercher</span>
                        </button>
                    </div>
                </div>

                <!-- Carte Interactive Google Maps -->
                <div class="relative rounded-xl overflow-hidden border border-slate-200 shadow-inner">
                    <div id="google_map_picker" style="height: 280px; width: 100%;" class="bg-slate-100"></div>
                    <div class="absolute top-2 right-2 bg-white/95 backdrop-blur-xs px-2.5 py-1 rounded-lg border border-slate-200 text-[10px] font-semibold text-slate-600 shadow-xs flex items-center space-x-1 pointer-events-none">
                        <i class="fa-solid fa-hand-pointer text-[#155E3B]"></i>
                        <span>Cliquez sur la carte ou glissez le repère</span>
                    </div>
                </div>

                <!-- Info-bulle / Nom exact du lieu détecté -->
                <div id="gmap_detected_box" class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 p-2.5 rounded-xl bg-white border border-slate-200 text-xs">
                    <div class="flex items-start space-x-2 min-w-0">
                        <i class="fa-solid fa-location-dot text-[#155E3B] mt-0.5 flex-shrink-0"></i>
                        <div class="min-w-0">
                            <span class="text-[10px] text-slate-400 block uppercase font-bold">Lieu exact détecté sur Google Maps</span>
                            <span id="detected_location_name" class="font-semibold text-slate-800 truncate block">Aucun lieu sélectionné (par défaut : Côte d'Ivoire)</span>
                        </div>
                    </div>
                    <button type="button" id="btn_apply_location_name" class="px-3 py-1 bg-emerald-50 hover:bg-emerald-100 text-[#155E3B] border border-emerald-200 rounded-lg text-[11px] font-bold transition-all flex items-center space-x-1 self-start sm:self-auto flex-shrink-0" title="Copier ce nom dans l'emplacement détaillé">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Utiliser comme emplacement</span>
                    </button>
                </div>

                <!-- Champs Numériques Latitude / Longitude / Précision (Grisés) -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-1">
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Latitude</label>
                        <div class="relative">
                            <input type="number" step="0.00000001" id="latitude_input" name="latitude" value="{{ old('latitude') }}" readonly placeholder="Capturé via carte" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-600 cursor-not-allowed select-none focus:outline-none shadow-2xs">
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400 text-[10px]">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Longitude</label>
                        <div class="relative">
                            <input type="number" step="0.00000001" id="longitude_input" name="longitude" value="{{ old('longitude') }}" readonly placeholder="Capturé via carte" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-600 cursor-not-allowed select-none focus:outline-none shadow-2xs">
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400 text-[10px]">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[11px] font-semibold text-slate-600 mb-1">Précision GPS (mètres)</label>
                        <div class="relative">
                            <input type="number" step="0.1" id="precision_gps_input" name="precision_gps" value="{{ old('precision_gps', '5.0') }}" readonly placeholder="± 5m" class="w-full px-3 py-2 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-600 cursor-not-allowed select-none focus:outline-none shadow-2xs">
                            <div class="absolute inset-y-0 right-0 pr-2.5 flex items-center pointer-events-none text-slate-400 text-[10px]">
                                <i class="fa-solid fa-lock text-slate-400"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ==================== ÉTAPE 4 : ÉTAT, RISQUE & DOCUMENTS ==================== -->
        <div id="step-content-4" class="step-content bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-6 hidden">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #155E3B;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                        <i class="fa-solid fa-heart-pulse"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Étape 4 : État, Risque, Documents & Photos</h2>
                        <p class="text-xs text-slate-400">9 statuts normés, analyse de risque, photos et observations</p>
                    </div>
                </div>
                <span class="text-xs font-bold font-mono px-3 py-1 rounded-full bg-cyan-50 text-[#155E3B] border border-cyan-100">Volets 5, 6 & 7/7</span>
            </div>

            <!-- État & Statut Opérationnel -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        État Physique & Opérationnel <span class="text-rose-500">*</span>
                    </label>
                    <select name="etat" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="bon_etat" {{ old('etat', 'bon_etat') == 'bon_etat' ? 'selected' : '' }}>🟢 Bon état (En service)</option>
                        <option value="panne" {{ old('etat') == 'panne' ? 'selected' : '' }}>🟡 Panne (À réparer)</option>
                        <option value="hors_service" {{ old('etat') == 'hors_service' ? 'selected' : '' }}>🔴 Hors service (Arrêt complet)</option>
                        <option value="non_retrouve" {{ old('etat') == 'non_retrouve' ? 'selected' : '' }}>⚪ Non vu / Non retrouvé</option>
                        <option value="transfere" {{ old('etat') == 'transfere' ? 'selected' : '' }}>🔵 Transféré (En transit)</option>
                        <option value="vole" {{ old('etat') == 'vole' ? 'selected' : '' }}>⚫ Volé (Sinistre déclaré)</option>
                        <option value="en_maintenance" {{ old('etat') == 'en_maintenance' ? 'selected' : '' }}>🟠 En maintenance / Atelier</option>
                        <option value="a_reformer" {{ old('etat') == 'a_reformer' ? 'selected' : '' }}>🟤 À réformer (Obsolescence)</option>
                        <option value="mis_au_rebut" {{ old('etat') == 'mis_au_rebut' ? 'selected' : '' }}>🟣 Mis au rebut (Déclassé)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Statut Opérationnel</label>
                    <select name="statut_operationnel" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="en_service" {{ old('statut_operationnel', 'en_service') == 'en_service' ? 'selected' : '' }}>Actif / En exploitation</option>
                        <option value="en_stock" {{ old('statut_operationnel') == 'en_stock' ? 'selected' : '' }}>En réserve / Stock</option>
                        <option value="en_transit" {{ old('statut_operationnel') == 'en_transit' ? 'selected' : '' }}>En déplacement</option>
                        <option value="reforme" {{ old('statut_operationnel') == 'reforme' ? 'selected' : '' }}>Déclassé / Archivé</option>
                    </select>
                </div>
            </div>

            <!-- Criticité & Score de Risque -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Niveau de Criticité <span class="text-rose-500">*</span>
                    </label>
                    <select name="niveau_criticite" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="faible" {{ old('niveau_criticite') == 'faible' ? 'selected' : '' }}>🟢 Faible (Impact minime)</option>
                        <option value="modere" {{ old('niveau_criticite', 'modere') == 'modere' ? 'selected' : '' }}>🟡 Modéré (Impact moyen)</option>
                        <option value="eleve" {{ old('niveau_criticite') == 'eleve' ? 'selected' : '' }}>🟠 Élevé (Équipement important)</option>
                        <option value="critique" {{ old('niveau_criticite') == 'critique' ? 'selected' : '' }}>🔴 Critique (Vital / Stratégique)</option>
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Score de Risque (0 à 100)</label>
                    <input type="number" min="0" max="100" name="score_risque" value="{{ old('score_risque', '50') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>

            <!-- Justification du Risque -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Justification du Classement & Risque</label>
                <textarea name="justification_risque" rows="2" placeholder="Expliquez les motifs du niveau de risque et de criticité..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">{{ old('justification_risque') }}</textarea>
            </div>

            <!-- Garantie & Prestataire -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Date de Fin de Garantie</label>
                    <input type="date" name="date_fin_garantie" value="{{ old('date_fin_garantie') }}" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Prestataire / Fournisseur SAV Garantie</label>
                    <input type="text" name="fournisseur_garantie" value="{{ old('fournisseur_garantie') }}" placeholder="Ex: SAV Siemens CI" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>
            </div>

            <!-- Photo & Observations -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Photo de l'Actif</label>
                    <div class="flex justify-center px-4 pt-4 pb-4 border-2 border-slate-300 border-dashed rounded-xl hover:border-[#155E3B] transition-all bg-slate-50">
                        <div class="space-y-1 text-center">
                            <i class="fa-solid fa-camera text-xl text-slate-400"></i>
                            <div class="text-xs text-slate-600">
                                <label for="photo_input" class="cursor-pointer font-bold text-[#155E3B] hover:underline">
                                    <span>Sélectionner une photo</span>
                                    <input id="photo_input" name="photo_principale" type="file" accept="image/*" class="sr-only">
                                </label>
                            </div>
                            <p class="text-[10px] text-slate-400">PNG, JPG jusqu'à 5MB</p>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Observations Générales</label>
                    <textarea name="observations" rows="3" placeholder="Notes additionnelles ou accessoires..." class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#155E3B] transition-all">{{ old('observations') }}</textarea>
                </div>
            </div>
        </div>

        <!-- ==================== BARRE DE NAVIGATION (Précédent / Suivant / Enregistrer) ==================== -->
        <div class="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <button type="button"
                    id="btn-prev"
                    onclick="prevStep()"
                    class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all hidden">
                <i class="fa-solid fa-arrow-left mr-1.5"></i>
                <span>Étape précédente</span>
            </button>

            <a href="{{ route('gestionnaire.immobilisations.index') }}"
               id="btn-cancel"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <div class="flex items-center space-x-3">
                <button type="button"
                        id="btn-next"
                        onclick="nextStep()"
                        style="background-color: #155E3B;"
                        class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                    <span>Étape suivante</span>
                    <i class="fa-solid fa-arrow-right"></i>
                </button>

                <button type="submit"
                        id="btn-submit"
                        style="background-color: #155E3B;"
                        class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all hidden">
                    <i class="fa-solid fa-check"></i>
                    <span>Enregistrer l'immobilisation</span>
                </button>
            </div>
        </div>
    </form>

</div>

<script>
    let currentStep = 1;
    const totalSteps = 4;

    function updateStepUI() {
        // Masquer tous les contenus d'étapes
        for (let i = 1; i <= totalSteps; i++) {
            const content = document.getElementById('step-content-' + i);
            const indicator = document.getElementById('step-indicator-' + i);
            const circle = document.getElementById('step-circle-' + i);

            if (content) {
                if (i === currentStep) {
                    content.classList.remove('hidden');
                } else {
                    content.classList.add('hidden');
                }
            }

            if (indicator && circle) {
                if (i === currentStep) {
                    indicator.classList.remove('border-slate-200', 'bg-slate-50/50');
                    indicator.classList.add('border-[#155E3B]', 'bg-cyan-50/50');
                    circle.classList.remove('bg-slate-200', 'text-slate-600', 'bg-emerald-500');
                    circle.classList.add('bg-[#155E3B]', 'text-white');
                    circle.textContent = i;
                } else if (i < currentStep) {
                    indicator.classList.remove('border-[#155E3B]', 'bg-cyan-50/50');
                    indicator.classList.add('border-emerald-300', 'bg-emerald-50/30');
                    circle.classList.remove('bg-[#155E3B]', 'bg-slate-200', 'text-slate-600');
                    circle.classList.add('bg-emerald-500', 'text-white');
                    circle.textContent = '✓';
                } else {
                    indicator.classList.remove('border-[#155E3B]', 'bg-cyan-50/50', 'border-emerald-300', 'bg-emerald-50/30');
                    indicator.classList.add('border-slate-200', 'bg-slate-50/50');
                    circle.classList.remove('bg-[#155E3B]', 'bg-emerald-500', 'text-white');
                    circle.classList.add('bg-slate-200', 'text-slate-600');
                    circle.textContent = i;
                }
            }
        }

        // Boutons Précédent / Annuler
        const btnPrev = document.getElementById('btn-prev');
        const btnCancel = document.getElementById('btn-cancel');
        const btnNext = document.getElementById('btn-next');
        const btnSubmit = document.getElementById('btn-submit');

        if (currentStep > 1) {
            btnPrev.classList.remove('hidden');
            btnCancel.classList.add('hidden');
        } else {
            btnPrev.classList.add('hidden');
            btnCancel.classList.remove('hidden');
        }

        // Boutons Suivant / Soumettre
        if (currentStep === totalSteps) {
            btnNext.classList.add('hidden');
            btnSubmit.classList.remove('hidden');
        } else {
            btnNext.classList.remove('hidden');
            btnSubmit.classList.add('hidden');
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    }

    function validateCurrentStep() {
        const currentContainer = document.getElementById('step-content-' + currentStep);
        if (!currentContainer) return true;

        const requiredInputs = currentContainer.querySelectorAll('[required]');
        let isValid = true;
        let firstInvalid = null;

        requiredInputs.forEach(input => {
            if (!input.value || input.value.trim() === '') {
                isValid = false;
                input.classList.add('border-rose-500', 'ring-2', 'ring-rose-200', 'bg-rose-50/20');
                if (!firstInvalid) firstInvalid = input;

                input.addEventListener('input', function removeError() {
                    if (this.value && this.value.trim() !== '') {
                        this.classList.remove('border-rose-500', 'ring-2', 'ring-rose-200', 'bg-rose-50/20');
                        this.removeEventListener('input', removeError);
                    }
                });
                input.addEventListener('change', function removeErrorChange() {
                    if (this.value && this.value.trim() !== '') {
                        this.classList.remove('border-rose-500', 'ring-2', 'ring-rose-200', 'bg-rose-50/20');
                        this.removeEventListener('change', removeErrorChange);
                    }
                });
            } else {
                input.classList.remove('border-rose-500', 'ring-2', 'ring-rose-200', 'bg-rose-50/20');
            }
        });

        if (firstInvalid) {
            firstInvalid.focus();
        }

        return isValid;
    }

    function nextStep() {
        if (validateCurrentStep()) {
            if (currentStep < totalSteps) {
                currentStep++;
                updateStepUI();
            }
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            currentStep--;
            updateStepUI();
        }
    }

    function goToStep(step) {
        if (step < currentStep || validateCurrentStep()) {
            currentStep = step;
            updateStepUI();
        }
    }

    function refreshGoogleMap() {
        if (window.gMap && window.gMarker) {
            setTimeout(function() {
                google.maps.event.trigger(window.gMap, 'resize');
                const pos = window.gMarker.getPosition();
                if (pos) window.gMap.setCenter(pos);
            }, 100);
        }
    }

    function captureCurrentGPS() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const accuracy = position.coords.accuracy ? (Math.round(position.coords.accuracy * 10) / 10) : 3.0;

                    const latInput = document.getElementById('latitude_input');
                    const lngInput = document.getElementById('longitude_input');
                    const precisionInput = document.getElementById('precision_gps_input');

                    if (latInput) latInput.value = lat.toFixed(7);
                    if (lngInput) lngInput.value = lng.toFixed(7);
                    if (precisionInput) precisionInput.value = accuracy.toFixed(1);

                    if (window.gMap && window.gMarker) {
                        const pos = new google.maps.LatLng(lat, lng);
                        window.gMarker.setPosition(pos);
                        window.gMap.setCenter(pos);
                        window.gMap.setZoom(16);

                        if (window.gGeocoder) {
                            window.gGeocoder.geocode({ location: pos }, function(results, status) {
                                if (status === 'OK' && results[0]) {
                                    window.lastDetectedAddress = results[0].formatted_address;
                                    const detectedName = document.getElementById('detected_location_name');
                                    if (detectedName) detectedName.textContent = window.lastDetectedAddress;
                                    const emplacementInput = document.querySelector('input[name="emplacement_detaille"]');
                                    if (emplacementInput && !emplacementInput.value.trim()) {
                                        emplacementInput.value = window.lastDetectedAddress;
                                    }
                                }
                            });
                        }
                    }
                },
                function(error) {
                    alert("Impossible d'obtenir la position GPS : " + error.message);
                },
                { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
            );
        } else {
            alert("La géolocalisation n'est pas supportée par votre navigateur.");
        }
    }

    document.getElementById('bailleur_select_step1')?.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const code = selected ? selected.getAttribute('data-code') : '';
        const codeBailleurInput = document.getElementById('input_code_bailleur');
        if (codeBailleurInput) {
            codeBailleurInput.value = code || '';
        }
    });

    document.getElementById('structure_select')?.addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const lat = parseFloat(selected.getAttribute('data-lat'));
        const lng = parseFloat(selected.getAttribute('data-lng'));
        const latInput = document.getElementById('latitude_input');
        const lngInput = document.getElementById('longitude_input');
        const precisionInput = document.getElementById('precision_gps_input');
        const emplacementInput = document.querySelector('input[name="emplacement_detaille"]');
        
        if (!isNaN(lat) && !isNaN(lng) && lat !== 0 && lng !== 0) {
            if (latInput) latInput.value = lat.toFixed(7);
            if (lngInput) lngInput.value = lng.toFixed(7);
            if (precisionInput) precisionInput.value = '5.0';

            if (window.gMap && window.gMarker) {
                const pos = new google.maps.LatLng(lat, lng);
                window.gMarker.setPosition(pos);
                window.gMap.setCenter(pos);
                window.gMap.setZoom(15);
                
                if (window.gGeocoder) {
                    window.gGeocoder.geocode({ location: pos }, function(results, status) {
                        if (status === 'OK' && results[0]) {
                            window.lastDetectedAddress = results[0].formatted_address;
                            const detectedName = document.getElementById('detected_location_name');
                            if (detectedName) detectedName.textContent = window.lastDetectedAddress;
                        }
                    });
                }
            }
        }
        if (selected.text && emplacementInput && !emplacementInput.value.trim()) {
            emplacementInput.value = selected.text.trim();
        }
    });

    window.gMap = null;
    window.gMarker = null;
    window.gGeocoder = null;
    window.gAutocomplete = null;
    window.lastDetectedAddress = '';

    window.initGoogleMapPicker = function() {
        const latInput = document.getElementById('latitude_input');
        const lngInput = document.getElementById('longitude_input');
        const precisionInput = document.getElementById('precision_gps_input');
        const searchInput = document.getElementById('google_places_search');
        const detectedName = document.getElementById('detected_location_name');
        const emplacementInput = document.querySelector('input[name="emplacement_detaille"]');

        const defaultLat = parseFloat(latInput?.value) || 5.3599517;
        const defaultLng = parseFloat(lngInput?.value) || -4.0082563;
        const initialPos = { lat: defaultLat, lng: defaultLng };

        const mapElement = document.getElementById('google_map_picker');
        if (!mapElement) return;

        window.gMap = new google.maps.Map(mapElement, {
            center: initialPos,
            zoom: (latInput?.value && lngInput?.value) ? 15 : 12,
            mapTypeId: 'roadmap',
            mapTypeControl: true,
            streetViewControl: false,
            fullscreenControl: true,
        });

        window.gGeocoder = new google.maps.Geocoder();

        window.gMarker = new google.maps.Marker({
            position: initialPos,
            map: window.gMap,
            draggable: true,
            title: "Position de l'équipement",
            animation: google.maps.Animation.DROP
        });

        function getPrecisionFromZoom(zoom) {
            if (zoom >= 20) return 0.5;
            if (zoom >= 19) return 1.0;
            if (zoom >= 18) return 2.0;
            if (zoom >= 17) return 3.0;
            if (zoom >= 16) return 5.0;
            if (zoom >= 15) return 10.0;
            if (zoom >= 14) return 20.0;
            if (zoom >= 13) return 50.0;
            if (zoom >= 12) return 100.0;
            return 200.0;
        }

        function updatePosition(lat, lng, address = null, customPrecision = null) {
            if (latInput) latInput.value = lat.toFixed(7);
            if (lngInput) lngInput.value = lng.toFixed(7);

            if (precisionInput) {
                if (customPrecision !== null && !isNaN(customPrecision)) {
                    precisionInput.value = parseFloat(customPrecision).toFixed(1);
                } else if (window.gMap) {
                    const zoom = window.gMap.getZoom();
                    precisionInput.value = getPrecisionFromZoom(zoom).toFixed(1);
                }
            }

            if (address) {
                window.lastDetectedAddress = address;
                if (detectedName) detectedName.textContent = address;
                if (emplacementInput && !emplacementInput.value.trim()) {
                    emplacementInput.value = address;
                }
            } else if (window.gGeocoder) {
                window.gGeocoder.geocode({ location: { lat: lat, lng: lng } }, function(results, status) {
                    if (status === 'OK' && results[0]) {
                        window.lastDetectedAddress = results[0].formatted_address;
                        if (detectedName) detectedName.textContent = window.lastDetectedAddress;
                        if (emplacementInput && !emplacementInput.value.trim()) {
                            emplacementInput.value = window.lastDetectedAddress;
                        }
                    }
                });
            }
        }

        if (latInput?.value && lngInput?.value) {
            updatePosition(defaultLat, defaultLng, null, parseFloat(precisionInput?.value) || 5.0);
        }

        window.gMap.addListener('zoom_changed', function() {
            if (precisionInput && window.gMap) {
                const zoom = window.gMap.getZoom();
                precisionInput.value = getPrecisionFromZoom(zoom).toFixed(1);
            }
        });

        window.gMarker.addListener('dragend', function(event) {
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();
            const zoom = window.gMap.getZoom();
            updatePosition(lat, lng, null, getPrecisionFromZoom(zoom));
        });

        window.gMap.addListener('click', function(event) {
            const lat = event.latLng.lat();
            const lng = event.latLng.lng();
            window.gMarker.setPosition(event.latLng);
            const zoom = window.gMap.getZoom();
            updatePosition(lat, lng, null, getPrecisionFromZoom(zoom));
        });

        if (searchInput && google.maps.places) {
            window.gAutocomplete = new google.maps.places.Autocomplete(searchInput, {
                componentRestrictions: { country: 'ci' },
                fields: ['geometry', 'name', 'formatted_address']
            });

            window.gAutocomplete.addListener('place_changed', function() {
                const place = window.gAutocomplete.getPlace();
                if (!place.geometry || !place.geometry.location) {
                    return;
                }

                window.gMap.setCenter(place.geometry.location);
                window.gMap.setZoom(17);
                window.gMarker.setPosition(place.geometry.location);

                const exactName = place.name ? `${place.name} - ${place.formatted_address || ''}` : place.formatted_address;
                let placePrecision = 2.5;
                if (place.geometry.location_type === 'ROOFTOP') placePrecision = 1.0;
                else if (place.geometry.location_type === 'RANGE_INTERPOLATED') placePrecision = 3.5;
                else if (place.geometry.location_type === 'GEOMETRIC_CENTER') placePrecision = 8.0;
                else if (place.geometry.location_type === 'APPROXIMATE') placePrecision = 20.0;

                updatePosition(place.geometry.location.lat(), place.geometry.location.lng(), exactName, placePrecision);
            });
        }

        document.getElementById('btn_search_gmap')?.addEventListener('click', function() {
            const query = searchInput?.value;
            if (!query) return;

            window.gGeocoder.geocode({ address: query, componentRestrictions: { country: 'CI' } }, function(results, status) {
                if (status === 'OK' && results[0]) {
                    const loc = results[0].geometry.location;
                    window.gMap.setCenter(loc);
                    window.gMap.setZoom(16);
                    window.gMarker.setPosition(loc);
                    const locType = results[0].geometry.location_type;
                    let prec = locType === 'ROOFTOP' ? 1.0 : 4.0;
                    updatePosition(loc.lat(), loc.lng(), results[0].formatted_address, prec);
                } else {
                    window.gGeocoder.geocode({ address: query }, function(res2, stat2) {
                        if (stat2 === 'OK' && res2[0]) {
                            const loc2 = res2[0].geometry.location;
                            window.gMap.setCenter(loc2);
                            window.gMap.setZoom(16);
                            window.gMarker.setPosition(loc2);
                            updatePosition(loc2.lat(), loc2.lng(), res2[0].formatted_address, 5.0);
                        } else {
                            alert("Aucun résultat trouvé pour cette recherche sur Google Maps.");
                        }
                    });
                }
            });
        });

        document.getElementById('btn_apply_location_name')?.addEventListener('click', function() {
            if (window.lastDetectedAddress && emplacementInput) {
                emplacementInput.value = window.lastDetectedAddress;
                emplacementInput.classList.add('ring-2', 'ring-[#155E3B]');
                setTimeout(() => emplacementInput.classList.remove('ring-2', 'ring-[#155E3B]'), 500);
            }
        });

        window.gMap.addListener('zoom_changed', function() {
            if (precisionInput && window.gMarker) {
                const pos = window.gMarker.getPosition();
                if (pos) {
                    const zoom = window.gMap.getZoom();
                    let precision = 5.0;
                    if (zoom >= 18) precision = 1.5;
                    else if (zoom >= 16) precision = 3.0;
                    else if (zoom >= 14) precision = 8.0;
                    else if (zoom >= 12) precision = 20.0;
                    else precision = 50.0;
                    precisionInput.value = precision.toFixed(1);
                }
            }
        });
    };

    const baseUpdateStepUI = updateStepUI;
    updateStepUI = function() {
        baseUpdateStepUI();
        if (currentStep === 3) {
            refreshGoogleMap();
        }
    };

    document.addEventListener('DOMContentLoaded', function() {
        updateStepUI();
    });
</script>

<!-- Script Google Maps API -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key', 'AIzaSyCRXwpzz4SVK_wQpOeh4p3-sSOq1c7Qqg4') }}&libraries=places&callback=initGoogleMapPicker" async defer></script>
@endsection
