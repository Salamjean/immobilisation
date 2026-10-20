@extends('layouts.app')

@section('title', "Mouvement {$mouvement->reference_mouvement}")
@section('header_title', "Détail & Workflow du Mouvement")

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-amber-100 text-amber-900 border border-amber-300">
        {{ $mouvement->reference_mouvement }}
    </span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- Fiche du Mouvement -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $mouvement->statut_badge_class }}">
                    Statut : {{ ucfirst($mouvement->statut_workflow) }}
                </span>
                <h1 class="text-lg font-extrabold text-slate-900 mt-1">{{ $mouvement->type_libelle }}</h1>
                <div class="text-xs text-slate-500">Date planifiée : {{ $mouvement->date_mouvement->format('d/m/Y') }}</div>
            </div>
            
            <div class="flex items-center space-x-2">
                <a href="{{ route('gestionnaire.mouvements.decharge', $mouvement->id) }}" target="_blank" class="px-3 py-1.5 rounded-lg bg-[#0048FA] hover:bg-[#003ad1] text-white text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                    <i class="fa-solid fa-print"></i>
                    <span>Bordereau / Décharge</span>
                </a>
                <a href="{{ route('immobilisations.show', $mouvement->immobilisation_id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-colors">
                    Voir l'Actif Concerné &rarr;
                </a>
            </div>
        </div>

        <!-- Immobilisation rattachée -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between text-xs">
            <div>
                <span class="font-mono font-bold text-brand-700">{{ $mouvement->immobilisation?->code_immobilisation }}</span>
                <div class="font-extrabold text-slate-900 text-sm mt-0.5">{{ $mouvement->immobilisation?->designation }}</div>
                <div class="text-slate-500">{{ $mouvement->immobilisation?->marque }} {{ $mouvement->immobilisation?->modele }} | S/N: {{ $mouvement->immobilisation?->numero_serie }}</div>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block text-[10px]">Valeur comptable</span>
                <span class="font-mono font-bold text-slate-900">{{ $mouvement->immobilisation?->valeur_formatee }}</span>
            </div>
        </div>

        <!-- Schéma Origine -> Destination -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs">
            <div class="p-4 rounded-xl border border-slate-200 bg-white">
                <div class="font-bold text-slate-400 uppercase text-[10px] mb-2 flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-up-from-bracket text-slate-500"></i>
                    <span>Origine (Emplacement Actuel)</span>
                </div>
                <div class="font-extrabold text-slate-900 text-sm">{{ $mouvement->structureOrigine?->nom ?? 'Stock Central' }}</div>
                <div class="text-slate-500 mt-1">Service: {{ $mouvement->serviceOrigine?->nom ?? 'Non spécifié' }}</div>
                <div class="text-slate-500">Détenteur: {{ $mouvement->detenteurOrigine?->nom_complet ?? 'N/A' }}</div>
            </div>

            <div class="p-4 rounded-xl border border-amber-200 bg-amber-50/40">
                <div class="font-bold text-amber-700 uppercase text-[10px] mb-2 flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-down-to-bracket text-amber-600"></i>
                    <span>Destination (Nouvelle Affectation)</span>
                </div>
                <div class="font-extrabold text-slate-900 text-sm">{{ $mouvement->structureDestination?->nom ?? 'Attribution' }}</div>
                <div class="text-slate-600 mt-1">Service: {{ $mouvement->serviceDestination?->nom ?? 'À définir' }}</div>
                <div class="text-slate-600">Détenteur: {{ $mouvement->detenteurDestination?->nom_complet ?? 'À désigner' }}</div>
            </div>
        </div>

        <div class="text-xs space-y-2">
            <div>
                <strong class="text-slate-700">Motif :</strong>
                <p class="text-slate-600 mt-0.5">{{ $mouvement->motif }}</p>
            </div>
            @if($mouvement->commentaires)
            <div>
                <strong class="text-slate-700">Instructions / Commentaires :</strong>
                <p class="text-slate-600 mt-0.5">{{ $mouvement->commentaires }}</p>
            </div>
            @endif
        </div>

        <!-- Workflow Actions -->
        <div class="p-5 rounded-xl bg-slate-900 text-white flex flex-col sm:flex-row items-center justify-between gap-4">
            <div>
                <div class="font-bold text-xs">Étape du Workflow : {{ ucfirst($mouvement->statut_workflow) }}</div>
                <div class="text-[11px] text-slate-400 mt-0.5">
                    @if($mouvement->statut_workflow === 'soumis')
                        En attente de validation par le Gestionnaire / Responsable Logistique.
                    @elseif($mouvement->statut_workflow === 'en_transit')
                        Bien validé et en cours d'acheminement. En attente de signature de réception par le site destinataire.
                    @elseif($mouvement->statut_workflow === 'receptionne')
                        Transfert finalisé. Localisation et détenteur mis à jour sur le registre.
                    @endif
                </div>
            </div>

            <div class="flex items-center space-x-2">
                @if($mouvement->statut_workflow === 'soumis')
                <form action="{{ route('mouvements.valider', $mouvement->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="action" value="valide">
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition-all shadow">
                        <i class="fa-solid fa-check mr-1"></i> Valider le Transfert
                    </button>
                </form>

                <form action="{{ route('mouvements.valider', $mouvement->id) }}" method="POST" class="inline">
                    @csrf
                    <input type="hidden" name="action" value="rejete">
                    <button type="submit" class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-500 text-white font-bold text-xs transition-all">
                        Rejeter
                    </button>
                </form>
                @elseif($mouvement->statut_workflow === 'en_transit')
                <form action="{{ route('mouvements.receptionner', $mouvement->id) }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 rounded-lg bg-emerald-600 hover:bg-emerald-500 text-white font-bold text-xs transition-all shadow">
                        <i class="fa-solid fa-clipboard-check mr-1"></i> Confirmer la Réception & Mise à Jour
                    </button>
                </form>
                @endif
            </div>
        </div>

    </div>

</div>
@endsection
