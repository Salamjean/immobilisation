@extends('layouts.app')

@section('title', "Mouvement {$mouvement->reference_mouvement}")
@section('header_title', "Fiche de Mouvement Logistique")

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold font-mono bg-amber-100 text-amber-900 border border-amber-300">
        {{ $mouvement->reference_mouvement }}
    </span>
@endsection

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <!-- En-tête Mouvement -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-amber-600 to-orange-500 flex items-center justify-center text-white text-2xl font-black shadow-md shadow-amber-500/20 flex-shrink-0">
                <i class="fa-solid fa-truck-ramp-box"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $mouvement->statut_workflow_badge_class }}">
                        {{ $mouvement->statut_workflow_libelle }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                        {{ $mouvement->type_mouvement_libelle }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">
                        Date: <strong class="text-slate-700">{{ $mouvement->date_mouvement?->format('d/m/Y') }}</strong>
                    </span>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 mt-1">{{ $mouvement->reference_mouvement }}</h1>
                <div class="text-xs text-slate-500 mt-0.5">
                    Actif rattaché: <strong class="text-slate-800">{{ $mouvement->immobilisation?->designation }}</strong> (<span class="font-mono text-[#155E3B] font-bold">{{ $mouvement->immobilisation?->code_immobilisation }}</span>)
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('logistique.mouvements.decharge', $mouvement->id) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-print"></i>
                <span>Bordereau / Décharge</span>
            </a>

            @if($mouvement->statut_workflow === 'soumis')
            <form method="POST" action="{{ route('logistique.mouvements.valider', $mouvement->id) }}">
                @csrf
                <button type="submit" onclick="return confirm('Confirmez-vous la validation et le transfert effectif de cet actif ?')" class="px-4 py-2 rounded-xl bg-[#155E3B] hover:bg-[#0f442a] text-white text-xs font-bold shadow-sm transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-check"></i>
                    <span>Valider le mouvement</span>
                </button>
            </form>
            @elseif($mouvement->statut_workflow === 'valide')
            <form method="POST" action="{{ route('logistique.mouvements.receptionner', $mouvement->id) }}">
                @csrf
                <button type="submit" onclick="return confirm('Confirmer la réception de cet actif sur le site de destination ?')" class="px-4 py-2 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold shadow-sm transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-box-open"></i>
                    <span>Confirmer Réception</span>
                </button>
            </form>
            @endif

            <a href="{{ route('logistique.mouvements.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                Retour
            </a>
        </div>
    </div>

    <!-- Détails Transfert & Trajet -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
        <!-- Origine -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5">
                <i class="fa-solid fa-location-dot text-rose-500"></i>
                <span>Site & Emplacement Origine</span>
            </div>
            <div class="text-base font-extrabold text-slate-900">{{ $mouvement->structureOrigine?->nom ?? 'Non spécifié' }}</div>
            <div class="text-xs text-slate-500">Code: <span class="font-mono font-semibold text-slate-700">{{ $mouvement->structureOrigine?->code_structure ?? 'N/A' }}</span></div>
            <div class="text-xs text-slate-500">Demandeur: <strong class="text-slate-700">{{ $mouvement->demandeur?->nom_complet ?? 'Système' }}</strong></div>
        </div>

        <!-- Destination -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3">
            <div class="text-xs font-bold text-slate-400 uppercase tracking-wider flex items-center space-x-1.5">
                <i class="fa-solid fa-flag-checkered text-emerald-500"></i>
                <span>Site & Emplacement Destination</span>
            </div>
            <div class="text-base font-extrabold text-[#155E3B]">{{ $mouvement->structureDestination?->nom ?? 'Même site / Réforme' }}</div>
            <div class="text-xs text-slate-500">Bureau/Service: <strong class="text-slate-700">{{ $mouvement->emplacement_destination ?? 'Non spécifié' }}</strong></div>
            <div class="text-xs text-slate-500">Nouveau détenteur: <strong class="text-slate-700">{{ $mouvement->detenteurDestination?->nom_complet ?? 'Non assigné' }}</strong></div>
        </div>
    </div>

    <!-- Motif & Observations -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
            Justification & Informations Complémentaires
        </h3>
        <div class="space-y-3 text-xs">
            <div>
                <span class="text-slate-400 font-bold block mb-1">Motif :</span>
                <div class="p-3 bg-slate-50 rounded-xl text-slate-800 font-medium">{{ $mouvement->motif }}</div>
            </div>
            @if($mouvement->observations)
            <div>
                <span class="text-slate-400 font-bold block mb-1">Observations :</span>
                <div class="p-3 bg-slate-50 rounded-xl text-slate-700 italic">{{ $mouvement->observations }}</div>
            </div>
            @endif
        </div>
    </div>

</div>
@endsection
