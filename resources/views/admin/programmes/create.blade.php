@extends('layouts.admin.app')

@section('title', 'Ajouter un Programme')

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.programmes.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <i class="fa-solid fa-diagram-project text-[#155E3B]"></i>
                    <span>Ajouter un nouveau Programme / Projet</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Enregistrement d'un programme de santé et de ses rattachements institutionnels</p>
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

    <form action="{{ route('admin.programmes.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #155E3B;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-diagram-project"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Identification du Programme</h2>
                    <p class="text-xs text-slate-400">Code unique, libellé officiel et bailleur de référence</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
                <!-- Nom Complet -->
                <div class="md:col-span-2">
                    <label for="nom_programme" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nom / Intitulé du Programme <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nom_programme"
                           name="nom"
                           value="{{ old('nom') }}"
                           required
                           autofocus
                           placeholder="Ex: Programme National de Lutte contre le Paludisme (PNLP)"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <span class="text-[10px] text-slate-400 mt-1 block">Le nom complet permet d'auto-générer un code unique s'il n'est pas saisi manuellement</span>
                </div>

                <!-- Code Programme (Grisé / Auto-généré) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="code_programme" class="block text-xs font-semibold text-slate-700">
                            Code Unique (Généré) <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            <span>Automatique</span>
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text"
                                id="code_programme"
                                name="code"
                                value="{{ old('code') }}"
                                readonly
                                required
                                placeholder="Ex: PRG-PNLP-{{ date('Ymd') }}"
                                class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold uppercase text-slate-600 cursor-not-allowed select-all pr-8">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                            <i class="fa-solid fa-barcode"></i>
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Format : PRG-[SIGLE]-[DATE]</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Bailleur Principal -->
                <div>
                    <label for="bailleur_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Bailleur de Fonds Principal / Partenaire
                    </label>
                    <select id="bailleur_id"
                            name="bailleur_id"
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="">-- Aucun bailleur principal assigné --</option>
                        @foreach($bailleurs as $b)
                            <option value="{{ $b->id }}" {{ old('bailleur_id') == $b->id ? 'selected' : '' }}>
                                [{{ $b->code }}] {{ $b->nom }}
                            </option>
                        @endforeach
                    </select>
                    <span class="text-[10px] text-slate-400 mt-1 block">Le bailleur finançant principalement ce programme</span>
                </div>

                <!-- Coordonnateur -->
                <div>
                    <label for="coordonnateur" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Coordonnateur / Responsable du Programme
                    </label>
                    <div class="relative">
                        <input type="text"
                               id="coordonnateur"
                               name="coordonnateur"
                               value="{{ old('coordonnateur') }}"
                               placeholder="Ex: Dr. KOUASSI Jean-Luc"
                               class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all pl-9">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                            <i class="fa-solid fa-user-tie"></i>
                        </span>
                    </div>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label for="description" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Description & Objectifs Stratégiques
                </label>
                <textarea id="description"
                          name="description"
                          rows="3"
                          placeholder="Présentation des objectifs généraux, périmètre d'intervention..."
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">{{ old('description') }}</textarea>
            </div>

            <!-- Statut Actif -->
            <div class="pt-2">
                <label class="inline-flex items-center space-x-2.5 cursor-pointer">
                    <input type="checkbox"
                           name="actif"
                           value="1"
                           {{ old('actif', '1') === '1' ? 'checked' : '' }}
                           class="w-4 h-4 rounded text-[#155E3B] focus:ring-[#155E3B] border-slate-300">
                    <span class="text-xs font-bold text-slate-700">Programme actif dans le système</span>
                </label>
            </div>
        </div>

        <!-- Boutons d'Action -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('admin.programmes.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">
                Annuler
            </a>
            <button type="submit"
                    style="background-color: #155E3B;"
                    class="px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Enregistrer le programme</span>
            </button>
        </div>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nomInput = document.getElementById('nom_programme');
        const codeInput = document.getElementById('code_programme');
        const btnRegen = document.getElementById('btn_regenerate_code');

        function generateCode(nom) {
            if (!nom) return '';
            const today = new Date();
            const y = today.getFullYear();
            const m = String(today.getMonth() + 1).padStart(2, '0');
            const d = String(today.getDate()).padStart(2, '0');
            const dateStr = `${y}${m}${d}`;

            const clean = nom.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toUpperCase();
            const words = clean.match(/[A-Z0-9]+/g) || [];
            const stopWords = ['LE', 'LA', 'LES', 'L', 'DE', 'DU', 'DES', 'D', 'UN', 'UNE', 'ET', 'OU', 'POUR', 'EN', 'DANS', 'PAR', 'SUR', 'AU', 'AUX', 'AVEC', 'SANS', 'SOUS', 'CONTRE', 'PROGRAMME', 'PROJET'];

            if (words.length === 0) return `PRG-${dateStr}`;

            const filtered = words.filter(w => !stopWords.includes(w));
            const target = filtered.length > 0 ? filtered : words;

            let prefix = '';
            if (target.length === 1) {
                prefix = target[0].length <= 6 ? target[0] : target[0].substring(0, 4);
            } else {
                prefix = target.map(w => w[0]).join('').substring(0, 6);
            }

            return `PRG-${prefix}-${dateStr}`;
        }

        nomInput.addEventListener('input', function() {
            codeInput.value = generateCode(this.value);
        });

        if (nomInput.value && !codeInput.value) {
            codeInput.value = generateCode(nomInput.value);
        }
    });
</script>
@endsection
