@extends('layouts.admin.app')

@section('title', 'Modifier le Programme')

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
                    <span>Modifier le Programme [{{ $programme->code }}]</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Mise à jour des informations du programme de santé</p>
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

    <form action="{{ route('admin.programmes.update', $programme->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #155E3B;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-diagram-project"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Identification du Programme</h2>
                    <p class="text-xs text-slate-400">Modifiez le libellé, le code ou le bailleur de rattachement</p>
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
                           value="{{ old('nom', $programme->nom) }}"
                           required
                           autofocus
                           placeholder="Ex: Programme National de Lutte contre le Paludisme (PNLP)"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                </div>

                <!-- Code Programme -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="code_programme" class="block text-xs font-semibold text-slate-700">
                            Code Unique Programme <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            <span>Verrouillé</span>
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text"
                                id="code_programme"
                                name="code"
                                value="{{ old('code', $programme->code) }}"
                                readonly
                                required
                                placeholder="Ex: PRG-PNLP"
                                class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold uppercase text-slate-600 cursor-not-allowed select-all pr-8">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                            <i class="fa-solid fa-barcode"></i>
                        </span>
                    </div>
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
                            <option value="{{ $b->id }}" {{ old('bailleur_id', $programme->bailleur_id) == $b->id ? 'selected' : '' }}>
                                [{{ $b->code }}] {{ $b->nom }}
                            </option>
                        @endforeach
                    </select>
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
                               value="{{ old('coordonnateur', $programme->coordonnateur) }}"
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
                          class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">{{ old('description', $programme->description) }}</textarea>
            </div>

            <!-- Statut Actif -->
            <div class="pt-2">
                <label class="inline-flex items-center space-x-2.5 cursor-pointer">
                    <input type="checkbox"
                           name="actif"
                           value="1"
                           {{ old('actif', $programme->actif ? '1' : '0') === '1' ? 'checked' : '' }}
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
                <span>Enregistrer les modifications</span>
            </button>
        </div>
    </form>

</div>
@endsection
