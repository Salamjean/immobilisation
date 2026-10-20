@extends('layouts.gestionnaire.app')

@section('title', 'Ajouter une Subvention - Espace Gestionnaire')

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('gestionnaire.subventions.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <i class="fa-solid fa-sack-dollar text-[#0048FA]"></i>
                    <span>Ajouter une nouvelle Subvention</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Enregistrement d'un accord financier lié à un bailleur et un programme</p>
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

    <form action="{{ route('gestionnaire.subventions.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #0048FA;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-sack-dollar"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Identification de la Subvention</h2>
                    <p class="text-xs text-slate-400">Renseignez le code unique, l'intitulé et les partenaires associés</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
                <!-- Intitulé -->
                <div class="md:col-span-2">
                    <label for="intitule" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Intitulé de la Subvention <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="intitule"
                           name="intitule"
                           value="{{ old('intitule') }}"
                           required
                           autofocus
                           placeholder="Ex: Subvention Paludisme NFM3 - Phase Consolidation 2024-2026"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <!-- Code Subvention (Grisé / Auto-généré) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="code_subvention" class="block text-xs font-semibold text-slate-700">
                            Code Unique (Généré) <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            <span>Automatique</span>
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text"
                                id="code_subvention"
                                name="code_subvention"
                                value="{{ old('code_subvention') }}"
                                readonly
                                required
                                placeholder="Ex: SUB-FM-PNLP-2026-001"
                                class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold uppercase text-slate-600 cursor-not-allowed select-all pr-8">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                            <i class="fa-solid fa-barcode"></i>
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Généré selon l'intitulé et les partenaires</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <!-- Programme -->
                <div>
                    <label for="programme_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Programme Associé <span class="text-rose-500">*</span>
                    </label>
                    <select id="programme_id"
                            name="programme_id"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                        <option value="">-- Sélectionner un programme --</option>
                        @foreach($programmes as $p)
                            <option value="{{ $p->id }}" data-code="{{ $p->code }}" {{ old('programme_id') == $p->id ? 'selected' : '' }}>
                                [{{ $p->code }}] {{ $p->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Bailleur -->
                <div>
                    <label for="bailleur_id" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Bailleur de Fonds / Donateur <span class="text-rose-500">*</span>
                    </label>
                    <select id="bailleur_id"
                            name="bailleur_id"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                        <option value="">-- Sélectionner un bailleur --</option>
                        @foreach($bailleurs as $b)
                            <option value="{{ $b->id }}" data-code="{{ $b->code }}" {{ old('bailleur_id') == $b->id ? 'selected' : '' }}>
                                [{{ $b->code }}] {{ $b->nom }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Période & Financement -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-5 pt-2">
                <!-- Date Début -->
                <div>
                    <label for="date_debut" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Date de Début
                    </label>
                    <input type="date"
                           id="date_debut"
                           name="date_debut"
                           value="{{ old('date_debut') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <!-- Date Fin -->
                <div>
                    <label for="date_fin" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Date de Clôture Prévisionnelle
                    </label>
                    <input type="date"
                           id="date_fin"
                           name="date_fin"
                           value="{{ old('date_fin') }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <!-- Montant Alloué -->
                <div>
                    <label for="montant_alloue" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Montant Total Alloué
                    </label>
                    <input type="number"
                           step="0.01"
                           min="0"
                           id="montant_alloue"
                           name="montant_alloue"
                           value="{{ old('montant_alloue', 0) }}"
                           placeholder="0.00"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <!-- Devise -->
                <div>
                    <label for="devise" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Devise <span class="text-rose-500">*</span>
                    </label>
                    <select id="devise"
                            name="devise"
                            required
                            class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                        <option value="FCFA" {{ old('devise', 'FCFA') === 'FCFA' ? 'selected' : '' }}>FCFA (XOF)</option>
                        <option value="EUR" {{ old('devise') === 'EUR' ? 'selected' : '' }}>Euro (€ / EUR)</option>
                        <option value="USD" {{ old('devise') === 'USD' ? 'selected' : '' }}>Dollar US ($ / USD)</option>
                    </select>
                </div>
            </div>

            <!-- Statut -->
            <div class="pt-2">
                <label for="statut" class="block text-xs font-semibold text-slate-700 mb-1.5">
                    Statut de la Subvention <span class="text-rose-500">*</span>
                </label>
                <select id="statut"
                        name="statut"
                        required
                        class="w-full sm:w-64 px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                    @foreach($statuts as $stKey => $stVal)
                        <option value="{{ $stKey }}" {{ old('statut', 'en_cours') === $stKey ? 'selected' : '' }}>{{ $stVal }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Boutons d'Action -->
        <div class="flex items-center justify-end space-x-3">
            <a href="{{ route('gestionnaire.subventions.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-50 text-xs font-bold transition-all">
                Annuler
            </a>
            <button type="submit"
                    style="background-color: #0048FA;"
                    class="px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Enregistrer la subvention</span>
            </button>
        </div>
    </form>

</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const intituleInput = document.getElementById('intitule');
        const progSelect = document.getElementById('programme_id');
        const bailSelect = document.getElementById('bailleur_id');
        const codeInput = document.getElementById('code_subvention');

        function generateCodeSubvention() {
            const intitule = (intituleInput ? intituleInput.value : '').trim();
            const progOpt = progSelect.selectedOptions[0];
            const bailOpt = bailSelect.selectedOptions[0];

            const pCode = (progOpt && progOpt.getAttribute('data-code')) ? progOpt.getAttribute('data-code').replace('PRG-', '') : 'PRG';
            const bCode = (bailOpt && bailOpt.getAttribute('data-code')) ? bailOpt.getAttribute('data-code').replace('UCPFM-', '') : 'BL';
            const year = new Date().getFullYear();

            let suffix = '';
            if (intitule) {
                const clean = intitule.normalize("NFD").replace(/[\u0300-\u036f]/g, "").toUpperCase();
                const words = clean.match(/[A-Z0-9]+/g) || [];
                const stopWords = ['LE', 'LA', 'LES', 'L', 'DE', 'DU', 'DES', 'D', 'UN', 'UNE', 'ET', 'OU', 'POUR', 'EN', 'DANS', 'PAR', 'SUR', 'AU', 'AUX', 'AVEC', 'SANS', 'SOUS', 'CONTRE', 'SUBVENTION', 'PROJET', 'PROGRAMME'];
                const filtered = words.filter(w => !stopWords.includes(w));
                const target = filtered.length > 0 ? filtered : words;
                if (target.length === 1) {
                    suffix = target[0].length <= 6 ? target[0] : target[0].substring(0, 4);
                } else {
                    suffix = target.map(w => w[0]).join('').substring(0, 6);
                }
            }

            if (!suffix) {
                suffix = String(Math.floor(100 + Math.random() * 900));
            }

            return `SUB-${bCode}-${pCode}-${year}-${suffix}`.toUpperCase();
        }

        if (intituleInput) {
            intituleInput.addEventListener('input', function() {
                codeInput.value = generateCodeSubvention();
            });
        }

        progSelect.addEventListener('change', function() {
            codeInput.value = generateCodeSubvention();
        });

        bailSelect.addEventListener('change', function() {
            codeInput.value = generateCodeSubvention();
        });

        if (intituleInput && intituleInput.value && !codeInput.value) {
            codeInput.value = generateCodeSubvention();
        }
    });
</script>
@endsection
