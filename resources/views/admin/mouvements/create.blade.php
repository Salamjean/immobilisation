@extends('layouts.admin.app')

@section('title', 'Nouveau Mouvement de Bien')

@section('content')
<div class="space-y-5">

    <!-- En-tête avec Bouton Retour -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.mouvements.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Initier un mouvement d'immobilisation</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Demande de transfert, réaffectation de site, sortie ou proposition de réforme</p>
            </div>
        </div>

        <div class="text-xs text-slate-400">
            Champs obligatoires <span class="text-rose-500 font-bold">*</span>
        </div>
    </div>

    <!-- Formulaire d'enregistrement en 3 Colonnes -->
    <form action="{{ route('admin.mouvements.store') }}" method="POST" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

            <!-- COLONNE 1 : Référence & Type -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-arrow-right-arrow-left"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">1. Nature de l'Opération</h2>
                    </div>

                    <!-- Référence -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Référence Mouvement <span class="text-rose-500">*</span></label>
                        <input type="text" name="reference_mouvement" value="{{ old('reference_mouvement', $refSuggeree) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-900 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                    </div>

                    <!-- Type de mouvement -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Type de Mouvement <span class="text-rose-500">*</span></label>
                        <select name="type_mouvement" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                            <option value="transfert">Transfert inter-établissements</option>
                            <option value="reaffectation">Réaffectation de service / détenteur</option>
                            <option value="pret">Prêt temporaire</option>
                            <option value="sortie_temporaire">Sortie pour réparation / SAV</option>
                            <option value="reforme">Proposition de réforme / Rebut</option>
                            <option value="perte_vol">Déclaration de perte ou vol</option>
                        </select>
                    </div>

                    <!-- Date Prévue -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Date Prévue d'Exécution <span class="text-rose-500">*</span></label>
                        <input type="date" name="date_mouvement" value="{{ old('date_mouvement', date('Y-m-d')) }}" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2">
                    <i class="fa-solid fa-clock-rotate-left text-[#0048FA] mr-1"></i> Traçabilité horodatée
                </div>
            </div>

            <!-- COLONNE 2 : Immobilisation & Destination -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-boxes-stacked"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">2. Actif & Destination</h2>
                    </div>

                    <!-- Immobilisation -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Immobilisation Concernée <span class="text-rose-500">*</span></label>
                        <select name="immobilisation_id" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                            <option value="">Sélectionner une immobilisation</option>
                            @foreach($actifs as $a)
                                <option value="{{ $a->id }}" {{ (old('immobilisation_id') == $a->id || ($actifSelectionne && $actifSelectionne->id == $a->id)) ? 'selected' : '' }}>
                                    {{ $a->designation }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Structure de Destination -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Structure de Destination</label>
                        <select name="structure_destination_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                            <option value="">Conserver la structure d'origine</option>
                            @foreach($structures as $st)
                                <option value="{{ $st->id }}" {{ old('structure_destination_id') == $st->id ? 'selected' : '' }}>
                                    {{ $st->nom }} ({{ $st->ville ?? 'CI' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2">
                    <i class="fa-solid fa-hospital text-emerald-500 mr-1"></i> Réaffectation automatique lors de la réception
                </div>
            </div>

            <!-- COLONNE 3 : Motif & Justification -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">3. Justification & Notes</h2>
                    </div>

                    <!-- Motif -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Motif Administratif <span class="text-rose-500">*</span></label>
                        <input type="text" name="motif" value="{{ old('motif') }}" required placeholder="Ex: Réaffectation de service selon note de service n°..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                    </div>

                    <!-- Commentaires -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Commentaires & Instructions Particulières</label>
                        <textarea name="commentaires" rows="3" placeholder="Précisions sur le transport, l'état ou le procès-verbal..." class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">{{ old('commentaires') }}</textarea>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2">
                    <i class="fa-solid fa-shield-check text-[#0048FA] mr-1"></i> Enregistrement direct dans la piste d'audit
                </div>
            </div>

        </div>

        <!-- Barre d'Action & Soumission -->
        <div class="flex items-center justify-end space-x-3 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('admin.mouvements.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <button type="submit" style="background-color: #0048FA;" class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-check"></i>
                <span>Enregistrer le mouvement</span>
            </button>
        </div>
    </form>

</div>
@endsection
