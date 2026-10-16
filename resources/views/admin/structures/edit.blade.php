@extends('layouts.admin.app')

@section('title', 'Modifier une Structure')

@section('content')
<div class="space-y-5">

    <!-- En-tête avec Bouton Retour -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.structures.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Modifier la structure : {{ $structure->nom }}</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Mise à jour des informations, rattachement territorial et contact</p>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition en 3 Colonnes -->
    <form action="{{ route('admin.structures.update', $structure->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

            <!-- COLONNE 1 : Identité & Typologie -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
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
                                   name="nom"
                                   value="{{ old('nom', $structure->nom) }}"
                                   required
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Code Unique Structure -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700">
                                Code Unique Structure <span class="text-rose-500">*</span>
                            </label>
                            <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                                <i class="fa-solid fa-lock text-[9px]"></i>
                                <span>Verrouillé</span>
                            </span>
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-barcode"></i>
                            </div>
                            <input type="text"
                                   name="code"
                                   value="{{ old('code', $structure->code) }}"
                                   readonly
                                   required
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 uppercase font-mono cursor-not-allowed select-all">
                        </div>
                    </div>

                    <!-- Type de Structure -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Type de structure sanitaire <span class="text-rose-500">*</span>
                        </label>
                        <select name="type_structure"
                                required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            @foreach($typesStructure as $codeType => $libelleType)
                                <option value="{{ $codeType }}" {{ old('type_structure', $structure->type_structure) == $codeType ? 'selected' : '' }}>
                                    {{ $libelleType }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <!-- Statut Actif -->
                <div class="pt-3 border-t border-slate-100">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="actif" value="1" {{ old('actif', $structure->actif) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-[#155E3B] focus:ring-[#155E3B]">
                        <span class="text-xs font-semibold text-slate-700">Structure active et opérationnelle</span>
                    </label>
                </div>
            </div>

            <!-- COLONNE 2 : Rattachement Géographique -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
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
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <option value="">-- Aucune / Région non spécifiée --</option>
                            @foreach($regions as $reg)
                                <option value="{{ $reg->id }}" {{ old('region_id', $structure->region_id) == $reg->id ? 'selected' : '' }}>
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
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <option value="">-- Aucun / District non spécifié --</option>
                            @foreach($districts as $dist)
                                <option value="{{ $dist->id }}" {{ old('district_sanitaire_id', $structure->district_sanitaire_id) == $dist->id ? 'selected' : '' }}>
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
                                   value="{{ old('ville', $structure->ville) }}"
                                   placeholder="Ex: Aboisso"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>

                        <div>
                            <label class="block text-xs font-semibold text-slate-700 mb-1.5">Commune</label>
                            <input type="text"
                                   name="commune"
                                   value="{{ old('commune', $structure->commune) }}"
                                   placeholder="Ex: Centre-ville"
                                   class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>
                </div>

                <!-- Adresse physique -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Adresse physique</label>
                    <input type="text"
                           name="adresse"
                           value="{{ old('adresse', $structure->adresse) }}"
                           placeholder="Ex: Boulevard Principal, Face Préfecture"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                </div>
            </div>

            <!-- COLONNE 3 : Point Focal & Coordonnées -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
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
                                   value="{{ old('responsable_nom', $structure->responsable_nom) }}"
                                   placeholder="Ex: Dr. KOFFI Emmanuel"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
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
                                   value="{{ old('responsable_contact', $structure->responsable_contact) }}"
                                   placeholder="+225 07 00 00 00 00"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
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
                                   value="{{ old('responsable_email', $structure->responsable_email) }}"
                                   placeholder="direction@structure.ci"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Coordonnées GPS (Optionnelles) -->
                    <div class="grid grid-cols-2 gap-3 pt-1">
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Latitude</label>
                            <input type="number"
                                   step="any"
                                   name="latitude"
                                   value="{{ old('latitude', $structure->latitude) }}"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                        </div>
                        <div>
                            <label class="block text-[11px] font-semibold text-slate-600 mb-1">Longitude</label>
                            <input type="number"
                                   step="any"
                                   name="longitude"
                                   value="{{ old('longitude', $structure->longitude) }}"
                                   class="w-full px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                        </div>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 italic pt-2">
                    <i class="fa-solid fa-location-crosshairs text-[#155E3B] mr-1"></i> Utilisé pour la cartographie SIG des immobilisations
                </div>
            </div>

        </div>

        <!-- Barre d'Action & Soumission -->
        <div class="flex items-center justify-end space-x-3 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('admin.structures.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <button type="submit"
                    style="background-color: #155E3B;"
                    class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Enregistrer les modifications</span>
            </button>
        </div>
    </form>

</div>
@endsection
