@extends('layouts.admin.app')

@section('title', "Mouvement {$mouvement->reference_mouvement}")

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
                    <span>Bordereau de Mouvement : {{ $mouvement->reference_mouvement }}</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">{{ $mouvement->type_libelle }} • Date : {{ $mouvement->date_mouvement ? \Carbon\Carbon::parse($mouvement->date_mouvement)->format('d/m/Y') : '—' }}</p>
            </div>
        </div>

        <div>
            @if($mouvement->statut_workflow === 'soumis')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-50 text-amber-700 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span> En attente de validation
                </span>
            @elseif($mouvement->statut_workflow === 'en_transit')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-sky-50 text-[#155E3B] border border-sky-200">
                    <i class="fa-solid fa-truck-fast mr-1.5"></i> En transit / Validé
                </span>
            @elseif($mouvement->statut_workflow === 'receptionne')
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fa-solid fa-check-double mr-1.5"></i> Réceptionné & Clôturé
                </span>
            @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-700 border border-slate-200">
                    {{ ucfirst($mouvement->statut_workflow) }}
                </span>
            @endif
        </div>
    </div>

    <!-- Détails Actif & Origine / Destination -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

        <!-- Colonne 1 : Bien Concerné -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <h2 class="text-sm font-bold text-slate-900">Immobilisation Rattachée</h2>
            </div>

            <div class="space-y-2.5 text-xs">
                <div>
                    <span class="text-slate-400 block text-[11px]">Désignation</span>
                    <div class="font-bold text-slate-900">{{ $mouvement->immobilisation?->designation }}</div>
                </div>

                <div>
                    <span class="text-slate-400 block text-[11px]">Code Unique</span>
                    <div class="font-mono font-bold text-[#155E3B]">{{ $mouvement->immobilisation?->code_immobilisation }}</div>
                </div>

                <div>
                    <span class="text-slate-400 block text-[11px]">Marque / Modèle</span>
                    <div class="font-medium text-slate-700">{{ $mouvement->immobilisation?->marque }} {{ $mouvement->immobilisation?->modele }}</div>
                </div>

                <div>
                    <span class="text-slate-400 block text-[11px]">Valeur comptable</span>
                    <div class="font-mono font-bold text-slate-900">{{ number_format($mouvement->immobilisation?->valeur_acquisition, 0, ',', ' ') }} {{ $mouvement->immobilisation?->devise }}</div>
                </div>
            </div>
        </div>

        <!-- Colonne 2 : Origine -> Destination -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                    <i class="fa-solid fa-map-pin"></i>
                </div>
                <h2 class="text-sm font-bold text-slate-900">Parcours & Localisation</h2>
            </div>

            <div class="space-y-3 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-200">
                    <span class="text-slate-400 text-[10px] font-bold uppercase block">Origine</span>
                    <div class="font-bold text-slate-900 mt-0.5">{{ $mouvement->structureOrigine?->nom ?? 'Siège UCP-FM' }}</div>
                    <div class="text-[11px] text-slate-500">Service : {{ $mouvement->serviceOrigine?->nom ?? 'Non spécifié' }}</div>
                </div>

                <div class="p-3 rounded-xl bg-sky-50/60 border border-sky-100">
                    <span class="text-[#155E3B] text-[10px] font-bold uppercase block">Destination</span>
                    <div class="font-bold text-slate-900 mt-0.5">{{ $mouvement->structureDestination?->nom ?? 'Attribution' }}</div>
                    <div class="text-[11px] text-slate-500">Service : {{ $mouvement->serviceDestination?->nom ?? 'À définir' }}</div>
                </div>
            </div>
        </div>

        <!-- Colonne 3 : Motif & Décision -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
            <div class="space-y-4">
                <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                    <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-file-signature"></i>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900">Motif & Décision</h2>
                </div>

                <div class="text-xs space-y-2">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Motif</span>
                        <p class="font-semibold text-slate-800">{{ $mouvement->motif }}</p>
                    </div>

                    @if($mouvement->commentaires)
                    <div>
                        <span class="text-slate-400 block text-[11px]">Commentaires</span>
                        <p class="text-slate-600 italic">{{ $mouvement->commentaires }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Boutons de validation Workflow -->
            <div class="pt-4 border-t border-slate-100 space-y-2">
                @if($mouvement->statut_workflow === 'soumis')
                    <div class="flex items-center space-x-2">
                        <form action="{{ route('admin.mouvements.valider', $mouvement->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="action" value="valide">
                            <button type="submit" style="background-color: #155E3B;" class="w-full py-2 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all">
                                <i class="fa-solid fa-check mr-1"></i> Valider
                            </button>
                        </form>
                        <form action="{{ route('admin.mouvements.valider', $mouvement->id) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="action" value="rejete">
                            <button type="submit" class="w-full py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-700 text-xs font-bold border border-rose-200 transition-all">
                                <i class="fa-solid fa-xmark mr-1"></i> Rejeter
                            </button>
                        </form>
                    </div>
                @elseif($mouvement->statut_workflow === 'en_transit')
                    <form action="{{ route('admin.mouvements.receptionner', $mouvement->id) }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold shadow-xs transition-all">
                            <i class="fa-solid fa-box-open mr-1"></i> Confirmer la Réception & Clôturer
                        </button>
                    </form>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
