@extends('layouts.app')

@section('title', 'Ajouter une Structure')

@section('content')
<div class="space-y-5">

    <!-- En-tête avec Bouton Retour -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('gestionnaire.structures.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Créer une nouvelle structure bénéficiaire</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Enregistrement d'un hôpital, centre de santé ou direction sanitaire</p>
            </div>
        </div>

        <div class="text-xs text-slate-400">
            Champs obligatoires <span class="text-rose-500 font-bold">*</span>
        </div>
    </div>

    <!-- Formulaire de création en 3 Colonnes -->
    <form action="{{ route('gestionnaire.structures.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

            <!-- COLONNE 1 : Identité & Typologie -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-hospital"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">1. Identité & Typologie</h2>
                    </div>

                    <!-- Nom de la Structure -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nom de la structure <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-heading"></i>
                            </div>
                            <input type="text"
                                   id="nom_structure"
                                   name="nom"
                                   value="{{ old('nom') }}"
                                   required
                                   placeholder="Ex: Hôpital Général d'Aboisso"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                        </div>
                    </div>

                    <!-- Code Unique Structure (Grisé / Auto-généré) -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700">
                                Code Unique (Généré) <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                                <i class="fa-solid fa-lock text-[9px]"></i>
                                <span>Automatique</span>
                            </span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-barcode"></i>
                            </div>
                            <input type="text"
                                   id="code_structure"
                                   name="code"
                                   value="{{ old('code') }}"
                                   readonly
                                   required
                                   placeholder="Ex: HG-ABOISSO"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 uppercase font-mono cursor-not-allowed select-all">
                        </div>
                    </div>

                    <!-- Type de Structure -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Type de structure sanitaire <span class="text-rose-500">*</span>
                        </label>
                        <select id="type_structure_select"
                                name="type_structure"
                                required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                            <option value="" disabled selected>-- Sélectionnez un type --</option>
                            @foreach($typesStructure as $codeType => $libelleType)
                                <option value="{{ $codeType }}" {{ old('type_structure') == $codeType ? 'selected' : '' }}>
                                    {{ $libelleType }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Activation Immédiate -->
                <div class="pt-3 border-t border-slate-100">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="actif" value="1" {{ old('actif', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-[#0048FA] focus:ring-[#0048FA]">
                        <span class="text-xs font-semibold text-slate-700">Structure active et opérationnelle</span>
                    </label>
                </div>
            </div>

            <!-- COLONNE 2 : Rattachement Géographique -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-map-location-dot"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">2. Rattachement Géographique</h2>
                    </div>

                    <!-- Région Sanitaire -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Région Sanitaire
                        </label>
                        <select name="region_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                            <option value="">-- Aucune / Région non spécifiée --</option>
                            @foreach($regions as $reg)
                                <option value="{{ $reg->id }}" {{ old('region_id') == $reg->id ? 'selected' : '' }}>
                                    {{ $reg->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- District Sanitaire -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            District Sanitaire
                        </label>
                        <select name="district_sanitaire_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                            <option value="">-- Aucun / District non spécifié --</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->id }}" {{ old('district_sanitaire_id') == $dist->id ? 'selected' : '' }}>
                                    {{ $dist->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Ville & Commune -->
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Ville</label>
                            <input type="text"
                                   name="ville"
                                   value="{{ old('ville') }}"
                                   placeholder="Ex: Aboisso"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Commune</label>
                            <input type="text"
                                   name="commune"
                                   value="{{ old('commune') }}"
                                   placeholder="Ex: Centre-ville"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                        </div>
                    </div>
                </div>

                <!-- Adresse physique -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Adresse physique</label>
                    <input type="text"
                           name="adresse"
                           value="{{ old('adresse') }}"
                           placeholder="Ex: Boulevard Principal, Face Préfecture"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                </div>
            </div>

            <!-- COLONNE 3 : Point Focal & Coordonnées -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">3. Responsable & Coordonnées</h2>
                    </div>

                    <!-- Nom du Responsable / Directeur -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nom du Point Focal / Directeur
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-user-doctor"></i>
                            </div>
                            <input type="text"
                                   name="responsable_nom"
                                   value="{{ old('responsable_nom') }}"
                                   placeholder="Ex: Dr. KOFFI Emmanuel"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                        </div>
                    </div>

                    <!-- Téléphone du Responsable -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Téléphone de contact
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <input type="text"
                                   name="responsable_contact"
                                   value="{{ old('responsable_contact') }}"
                                   placeholder="+225 07 00 00 00 00"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                        </div>
                    </div>

                    <!-- Email du Responsable -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Email professionnel
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input type="email"
                                   name="responsable_email"
                                   value="{{ old('responsable_email') }}"
                                   placeholder="direction@structure.ci"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                        </div>
                    </div>

                    <!-- Coordonnées GPS (Optionnelles) -->
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Latitude</label>
                            <input type="number"
                                   step="any"
                                   name="latitude"
                                   value="{{ old('latitude') }}"
                                   placeholder="Ex: 5.4667"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Longitude</label>
                            <input type="number"
                                   step="any"
                                   name="longitude"
                                   value="{{ old('longitude') }}"
                                   placeholder="Ex: -3.2000"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                        </div>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 italic pt-2">
                    <i class="fa-solid fa-location-crosshairs text-[#0048FA] mr-1"></i> Utilisé pour la cartographie SIG des immobilisations
                </div>
            </div>

        </div>

        <!-- Barre d'Action & Soumission -->
        <div class="flex items-center justify-end space-x-3 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('gestionnaire.structures.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <button type="submit"
                    style="background-color: #0048FA;"
                    class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-check"></i>
                <span>Enregistrer la structure</span>
            </button>
        </div>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nomInput = document.getElementById('nom_structure');
        const typeSelect = document.getElementById('type_structure_select');
        const codeInput = document.getElementById('code_structure');

        function generateStructureCode() {
            const nom = nomInput.value.trim();
            const type = typeSelect.value || 'STR';
            if (!nom) return '';

            const clean = nom.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toUpperCase();
            const words = clean.match(/[A-Z0-9]+/g) || [];
            const stopWords = ['LE', 'LA', 'LES', 'L', 'DE', 'DU', 'DES', 'D', 'UN', 'UNE', 'ET', 'OU', 'POUR', 'EN', 'DANS', 'PAR', 'SUR', 'AU', 'AUX', 'CENTRE', 'HOPITAL', 'GENERAL', 'REGIONAL', 'URBAIN', 'RURAL'];

            if (words.length === 0) return `${type}-${new Date().getFullYear()}`;

            const filtered = words.filter(w => !stopWords.includes(w));
            const target = filtered.length > 0 ? filtered : words;

            let suffix = '';
            if (target.length === 1) {
                suffix = target[0].length <= 8 ? target[0] : target[0].substring(0, 8);
            } else {
                suffix = target.map(w => w.substring(0, 3)).join('').substring(0, 8);
            }

            return `${type}-${suffix}`.toUpperCase();
        }

        nomInput.addEventListener('input', function() {
            codeInput.value = generateStructureCode();
        });

        typeSelect.addEventListener('change', function() {
            codeInput.value = generateStructureCode();
        });

        if (nomInput.value && !codeInput.value) {
            codeInput.value = generateStructureCode();
        }
    });
</script>
@endsection
