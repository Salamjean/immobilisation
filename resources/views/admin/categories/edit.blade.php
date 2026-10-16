@extends('layouts.admin.app')

@section('title', 'Modifier une Catégorie')

@section('content')
<div class="space-y-5">

    <!-- En-tête avec Bouton Retour -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.categories.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Modifier la catégorie : {{ $categorie->nom }}</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Mise à jour des paramètres comptables et durée d'amortissement</p>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition en 3 Colonnes -->
    <form action="{{ route('admin.categories.update', $categorie->id) }}" method="POST" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

            <!-- COLONNE 1 : Identité & Code -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-layer-group"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">1. Identité & Code</h2>
                    </div>

                    <!-- Nom de la Catégorie -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Libellé de la catégorie <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-heading"></i>
                            </div>
                            <input type="text"
                                   name="nom"
                                   value="{{ old('nom', $categorie->nom) }}"
                                   required
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Code Catégorie -->
                    <div>
                        <div class="flex items-center justify-between mb-1.5">
                            <label class="block text-xs font-semibold text-slate-700">
                                Code Unique <span class="text-rose-500">*</span>
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
                                   value="{{ old('code', $categorie->code) }}"
                                   readonly
                                   required
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-bold text-slate-600 uppercase font-mono cursor-not-allowed select-all">
                        </div>
                    </div>

                    <!-- Icône FontAwesome -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Icône d'affichage</label>
                        <select name="icone" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                            <option value="fa-hospital" {{ old('icone', $categorie->icone) == 'fa-hospital' ? 'selected' : '' }}>Hôpital / Médical (fa-hospital)</option>
                            <option value="fa-laptop" {{ old('icone', $categorie->icone) == 'fa-laptop' ? 'selected' : '' }}>Informatique & IT (fa-laptop)</option>
                            <option value="fa-truck-medical" {{ old('icone', $categorie->icone) == 'fa-truck-medical' ? 'selected' : '' }}>Véhicules & Ambulances (fa-truck-medical)</option>
                            <option value="fa-couch" {{ old('icone', $categorie->icone) == 'fa-couch' ? 'selected' : '' }}>Mobilier & Bureau (fa-couch)</option>
                            <option value="fa-microscope" {{ old('icone', $categorie->icone) == 'fa-microscope' ? 'selected' : '' }}>Laboratoire & Recherche (fa-microscope)</option>
                            <option value="fa-building" {{ old('icone', $categorie->icone) == 'fa-building' ? 'selected' : '' }}>Bâtiments & Locaux (fa-building)</option>
                            <option value="fa-screwdriver-wrench" {{ old('icone', $categorie->icone) == 'fa-screwdriver-wrench' ? 'selected' : '' }}>Équipements Techniques (fa-screwdriver-wrench)</option>
                            <option value="fa-cube" {{ old('icone', $categorie->icone) == 'fa-cube' ? 'selected' : '' }}>Générique (fa-cube)</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- COLONNE 2 : Amortissement & Description -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-clock-rotate-left"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">2. Paramètres Comptables</h2>
                    </div>

                    <!-- Durée de vie standard (en mois) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Durée d'amortissement standard (Mois) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-calendar-days"></i>
                            </div>
                            <input type="number"
                                   name="duree_vie_standard_mois"
                                   value="{{ old('duree_vie_standard_mois', $categorie->duree_vie_standard_mois) }}"
                                   min="1"
                                   max="600"
                                   required
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Description -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Description & Précisions</label>
                        <textarea name="description"
                                  rows="4"
                                  class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">{{ old('description', $categorie->description) }}</textarea>
                    </div>
                </div>
            </div>

            <!-- COLONNE 3 : Sous-Catégories Dynamiques avec Bouton + -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                        <div class="flex items-center space-x-2.5">
                            <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                                <i class="fa-solid fa-tags"></i>
                            </div>
                            <h2 class="text-sm font-bold text-slate-900">3. Sous-Catégories</h2>
                        </div>
                        <!-- Bouton + -->
                        <button type="button"
                                id="btn_add_sous_cat"
                                style="background-color: #155E3B;"
                                class="inline-flex items-center space-x-1 px-2.5 py-1 rounded-lg text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all">
                            <i class="fa-solid fa-plus text-[10px]"></i>
                            <span>Ajouter</span>
                        </button>
                    </div>

                    <p class="text-xs text-slate-500">Gérez et ajoutez des sous-catégories selon vos besoins :</p>

                    <!-- Conteneur dynamique des sous-catégories -->
                    <div id="sous_categories_container" class="space-y-2.5 max-h-64 overflow-y-auto pr-1">
                        @if(old('sous_categories'))
                            @foreach(old('sous_categories') as $scIndex => $scVal)
                                <div class="sous-cat-row flex items-center space-x-2">
                                    <div class="relative flex-1">
                                        <input type="text"
                                               name="sous_categories[]"
                                               value="{{ $scVal }}"
                                               placeholder="Sous-catégorie..."
                                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                                    </div>
                                    <button type="button" onclick="removeSousCat(this)" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center text-xs transition-all flex-shrink-0" title="Supprimer">
                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                    </button>
                                </div>
                            @endforeach
                        @elseif($categorie->sousCategories->count() > 0)
                            @foreach($categorie->sousCategories as $sc)
                                <div class="sous-cat-row flex items-center space-x-2">
                                    <div class="relative flex-1">
                                        <input type="text"
                                               name="sous_categories[]"
                                               value="{{ $sc->nom }}"
                                               placeholder="Sous-catégorie..."
                                               class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                                    </div>
                                    <button type="button" onclick="removeSousCat(this)" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center text-xs transition-all flex-shrink-0" title="Supprimer">
                                        <i class="fa-solid fa-trash text-[11px]"></i>
                                    </button>
                                </div>
                            @endforeach
                        @else
                            <div class="sous-cat-row flex items-center space-x-2">
                                <div class="relative flex-1">
                                    <input type="text"
                                           name="sous_categories[]"
                                           placeholder="Sous-catégorie 1..."
                                           class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                                </div>
                                <button type="button" onclick="removeSousCat(this)" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center text-xs transition-all flex-shrink-0" title="Supprimer">
                                    <i class="fa-solid fa-trash text-[11px]"></i>
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2 flex items-center justify-between">
                    <span><i class="fa-solid fa-circle-check text-emerald-500 mr-1"></i> Structure prête pour les immobilisations</span>
                    <span id="sous_cat_counter" class="font-bold text-slate-600 font-mono text-[10px]">{{ $categorie->sousCategories->count() }} entrée(s)</span>
                </div>
            </div>

        </div>

        <!-- Barre d'Action & Soumission -->
        <div class="flex items-center justify-end space-x-3 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('admin.categories.index') }}"
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

    <!-- Template pour nouvelle ligne de sous-catégorie -->
    <template id="sous_cat_template">
        <div class="sous-cat-row flex items-center space-x-2">
            <div class="relative flex-1">
                <input type="text"
                       name="sous_categories[]"
                       placeholder="Sous-catégorie..."
                       class="w-full px-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
            </div>
            <button type="button" onclick="removeSousCat(this)" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center text-xs transition-all flex-shrink-0" title="Supprimer">
                <i class="fa-solid fa-trash text-[11px]"></i>
            </button>
        </div>
    </template>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const container = document.getElementById('sous_categories_container');
        const btnAdd = document.getElementById('btn_add_sous_cat');
        const counter = document.getElementById('sous_cat_counter');

        function updateCounter() {
            const count = container.querySelectorAll('.sous-cat-row').length;
            if (counter) counter.textContent = `${count} entrée(s)`;
        }

        if (btnAdd) {
            btnAdd.addEventListener('click', function() {
                const currentCount = container.querySelectorAll('.sous-cat-row').length + 1;
                const template = document.getElementById('sous_cat_template');
                const clone = template.content.cloneNode(true);
                const input = clone.querySelector('input');
                input.placeholder = `Sous-catégorie ${currentCount}...`;
                container.appendChild(clone);
                const addedInput = container.querySelector('.sous-cat-row:last-child input');
                if (addedInput) addedInput.focus();
                updateCounter();
            });
        }

        window.removeSousCat = function(btn) {
            const rows = container.querySelectorAll('.sous-cat-row');
            if (rows.length > 1) {
                btn.closest('.sous-cat-row').remove();
            } else {
                btn.closest('.sous-cat-row').querySelector('input').value = '';
            }
            updateCounter();
        };

        updateCounter();
    });
</script>
@endsection
