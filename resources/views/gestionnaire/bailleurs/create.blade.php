@extends('layouts.app')

@section('title', 'Ajouter un Bailleur de Fonds')

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('gestionnaire.bailleurs.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Ajouter un nouveau Bailleur de Fonds</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Enregistrement d'un partenaire financier et de son code d'identification</p>
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

    <form action="{{ route('gestionnaire.bailleurs.store') }}" method="POST" class="space-y-6">
        @csrf

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #0048FA;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-hand-holding-dollar"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Informations du Bailleur</h2>
                    <p class="text-xs text-slate-400">Renseignez le code unique et les coordonnées du bailleur</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
                <!-- Nom Complet (placé avant le code) -->
                <div class="md:col-span-2">
                    <label for="nom_bailleur" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nom Complet du Bailleur <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nom_bailleur"
                           name="nom"
                           value="{{ old('nom') }}"
                           required
                           autofocus
                           placeholder="Ex: Le Fonds Mondial de lutte contre le Sida, la Tuberculose et le Paludisme"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                    <span class="text-[10px] text-slate-400 mt-1 block">Saisissez le nom complet pour générer automatiquement le code unique ci-contre</span>
                </div>

                <!-- Code Bailleur (généré selon le nom et la date) -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="code_bailleur" class="block text-xs font-semibold text-slate-700">
                            Code Unique (Généré) <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            <span>Automatique</span>
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text"
                                id="code_bailleur"
                                name="code"
                                value="{{ old('code') }}"
                                readonly
                                required
                                placeholder="Ex: UCPFM-FM-{{ date('Ymd') }}"
                                class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold uppercase text-slate-600 cursor-not-allowed select-all pr-8">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                            <i class="fa-solid fa-barcode"></i>
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Format : UCPFM-[INITIALES]-[DATE COMPLÈTE]</span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Description & Objet du Financement</label>
                <textarea name="description" rows="3" placeholder="Informations complémentaires, programmes couverts..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">{{ old('description') }}</textarea>
            </div>

            <!-- Contacts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nom du Contact / Point Focal</label>
                    <input type="text"
                           name="contact_nom"
                           value="{{ old('contact_nom') }}"
                           placeholder="Ex: Dr. Jean Dupont"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email du Contact</label>
                    <input type="email"
                           name="contact_email"
                           value="{{ old('contact_email') }}"
                           placeholder="Ex: pointfocal@bailleur.org"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="flex items-center space-x-3 pt-2">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 rounded text-[#0048FA] focus:ring-[#0048FA] border-slate-300">
                <label for="actif" class="text-xs font-semibold text-slate-700">Actif (disponible immédiatement pour les affectations et saisies)</label>
            </div>
        </div>

        <!-- Boutons d'Action -->
        <div class="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('gestionnaire.bailleurs.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <button type="submit"
                    style="background-color: #0048FA;"
                    class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-check"></i>
                <span>Enregistrer le Bailleur</span>
            </button>
        </div>
    </form>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const nomInput = document.getElementById('nom_bailleur');
    const codeInput = document.getElementById('code_bailleur');

    const stopWords = new Set([
        'le', 'la', 'les', 'l', 'de', 'du', 'des', 'd', 'un', 'une',
        'et', 'ou', 'pour', 'en', 'dans', 'par', 'sur', 'au', 'aux', 'avec',
        'sans', 'sous', 'contre', 'the', 'of', 'and', 'for', 'in', 'to', 'at'
    ]);

    function removeAccents(str) {
        return str.normalize('NFD').replace(/[\u0300-\u036f]/g, '');
    }

    function generateBailleurCode(nom) {
        if (!nom || !nom.trim()) return '';
        
        const cleanNom = removeAccents(nom.trim());
        const words = cleanNom.split(/[\s,.'’_\-\/]+/).filter(w => w.length > 0);
        
        if (words.length === 0) return '';

        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const dateComplete = `${year}${month}${day}`;

        if (words.length === 1) {
            const single = words[0].toUpperCase();
            const prefix = single.length <= 6 ? single : single.substring(0, 4);
            return `UCPFM-${prefix}-${dateComplete}`;
        }

        const significantWords = words.filter(w => !stopWords.has(w.toLowerCase()));
        const targetWords = significantWords.length > 0 ? significantWords : words;

        let acronym = '';
        if (targetWords.length === 1) {
            const single = targetWords[0].toUpperCase();
            acronym = single.length <= 4 ? single : single.substring(0, 4);
        } else {
            acronym = targetWords.map(w => w[0].toUpperCase()).join('');
            if (acronym.length > 6) {
                acronym = acronym.substring(0, 6);
            }
        }

        return `UCPFM-${acronym}-${dateComplete}`;
    }

    nomInput.addEventListener('input', function () {
        const generated = generateBailleurCode(this.value);
        codeInput.value = generated || '';
    });

    if (nomInput.value && !codeInput.value) {
        codeInput.value = generateBailleurCode(nomInput.value);
    }
});
</script>
@endpush
