@extends('layouts.app')

@section('title', "Fiche SAV {$maintenance->numero_fiche}")
@section('header_title', "Fiche d'Intervention & Maintenance")

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-rose-100 text-rose-900 border border-rose-300">
        {{ $maintenance->numero_fiche }}
    </span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-6 text-xs">
        
        <div class="flex items-center justify-between pb-4 border-b border-slate-100">
            <div>
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $maintenance->statut_badge_class }}">
                    Statut : {{ ucfirst($maintenance->statut) }}
                </span>
                <h1 class="text-lg font-extrabold text-slate-900 mt-1">{{ $maintenance->type_libelle }}</h1>
                <div class="text-slate-500">Signalé le : {{ $maintenance->date_signalement->format('d/m/Y') }}</div>
            </div>
            
            <a href="{{ route('immobilisations.show', $maintenance->immobilisation_id) }}" class="px-3 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-colors">
                Fiche de l'Équipement &rarr;
            </a>
        </div>

        <!-- Équipement rattaché -->
        <div class="p-4 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
            <div>
                <span class="font-mono font-bold text-brand-700">{{ $maintenance->immobilisation?->code_immobilisation }}</span>
                <div class="font-extrabold text-slate-900 text-sm mt-0.5">{{ $maintenance->immobilisation?->designation }}</div>
                <div class="text-slate-500">
                    {{ $maintenance->immobilisation?->marque }} {{ $maintenance->immobilisation?->modele }} 
                    • Site: {{ $maintenance->immobilisation?->structureBeneficiaire?->nom }}
                </div>
            </div>
            <div class="text-right">
                <span class="text-slate-400 block text-[10px]">Coût Intervention</span>
                <span class="font-mono font-bold text-base text-slate-900">{{ number_format($maintenance->cout_intervention, 0, ',', ' ') }} FCFA</span>
            </div>
        </div>

        <!-- Diagnostic & Détails -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-2">
                <div class="font-bold text-slate-400 uppercase text-[10px]">Prestataire & Intervenant</div>
                <div class="font-bold text-slate-900">{{ $maintenance->prestataire ?? 'Équipe Technique UCP-FM' }}</div>
                <div class="text-slate-600">Technicien: {{ $maintenance->technicien_intervenant ?? 'Non assigné' }}</div>
            </div>

            <div class="p-4 rounded-xl border border-slate-200 bg-white space-y-2">
                <div class="font-bold text-slate-400 uppercase text-[10px]">Calendrier & Prochaine Échéance</div>
                <div><strong class="text-slate-700">Intervention :</strong> {{ $maintenance->date_intervention ? $maintenance->date_intervention->format('d/m/Y') : 'En attente' }}</div>
                <div><strong class="text-slate-700">Prochaine maintenance :</strong> {{ $maintenance->date_prochaine_maintenance ? $maintenance->date_prochaine_maintenance->format('d/m/Y') : 'Non planifiée' }}</div>
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <strong class="text-slate-700 block mb-1">Description de la Panne :</strong>
                <p class="text-slate-600 p-3 bg-slate-50 rounded-xl border border-slate-200">{{ $maintenance->description_panne ?? 'Non renseignée' }}</p>
            </div>

            <div>
                <strong class="text-slate-700 block mb-1">Diagnostic Technique :</strong>
                <p class="text-slate-600 p-3 bg-slate-50 rounded-xl border border-slate-200">{{ $maintenance->diagnostic ?? 'Diagnostic en cours' }}</p>
            </div>

            @if($maintenance->pieces_remplacees)
            <div>
                <strong class="text-slate-700 block mb-1">Pièces Remplacées / Recommandées :</strong>
                <p class="text-slate-600 p-3 bg-slate-50 rounded-xl border border-slate-200">{{ $maintenance->pieces_remplacees }}</p>
            </div>
            @endif
        </div>

        @if($maintenance->statut !== 'terminee')
        <!-- Section Clôture de l'Intervention -->
        <div class="mt-6 p-5 rounded-2xl bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-200">
            <h3 class="text-xs font-bold uppercase text-[#0048FA] tracking-wider mb-3 flex items-center">
                <i class="fa-solid fa-circle-check mr-2"></i> Clôturer l'Intervention & Remettre en Service
            </h3>
            <form action="{{ route('maintenances.cloturer', $maintenance->id) }}" method="POST" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Date Fin d'Intervention</label>
                        <input type="date" name="date_fin_intervention" value="{{ date('Y-m-d') }}" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Coût Total Réel (FCFA)</label>
                        <input type="number" name="cout_intervention" value="{{ $maintenance->cout_intervention ?? 0 }}" min="0" class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs font-mono focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div class="sm:col-span-2">
                        <label class="block font-bold text-slate-700 mb-1">Travaux Effectués & Rapport de Clôture</label>
                        <textarea name="travaux_effectues" rows="2" placeholder="Détail des réparations, réglages effectués et tests de conformité..." class="w-full px-3 py-2 bg-white border border-slate-300 rounded-xl text-xs focus:ring-2 focus:ring-emerald-500"></textarea>
                    </div>
                </div>
                <div class="flex items-center justify-end">
                    <button type="submit" class="px-5 py-2.5 rounded-xl bg-[#0048FA] hover:bg-[#003ad1] text-white font-bold text-xs shadow-md shadow-emerald-900/20 transition-all flex items-center space-x-1.5">
                        <i class="fa-solid fa-check-double"></i>
                        <span>Valider la Réparation & Remettre en Service</span>
                    </button>
                </div>
            </form>
        </div>
        @endif

    </div>

</div>
@endsection
