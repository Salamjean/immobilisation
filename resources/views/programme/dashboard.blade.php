@extends('layouts.app')

@section('title', "Espace Programme " . ($programme?->code ?? ''))
@section('header_title', "Espace Programme : " . ($programme?->code ?? 'Santé'))

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-300">
        {{ $programme?->nom ?? 'Programme Santé GC7' }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Bannière Programme & KPIs -->
    <div class="bg-gradient-to-r from-[#155E3B] to-emerald-800 rounded-2xl p-6 text-white shadow-sm border border-emerald-700/50 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-emerald-200 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-notes-medical"></i>
                <span>Programme National de Santé</span>
            </div>
            <h2 class="text-xl font-black mt-1">{{ $programme?->nom ?? 'Programme Non Défini' }} ({{ $programme?->code ?? 'N/A' }})</h2>
            <p class="text-emerald-100 text-xs mt-1">Supervision, suivi et traçabilité des équipements et dotations sanitaires.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('programme.immobilisations.index') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-white text-[#155E3B] hover:bg-emerald-50 text-xs font-bold shadow-sm transition-all">
                <i class="fa-solid fa-boxes-packing"></i>
                <span>Explorer les actifs</span>
            </a>
        </div>
    </div>

    <!-- KPIs du Programme -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Actifs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Actifs Rattachés</div>
                <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $totalActifs }}</div>
                <div class="text-xs text-[#155E3B] font-semibold mt-0.5">{{ $programme?->sigle ?? 'GC7' }} Côte d'Ivoire</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#155E3B] flex items-center justify-center text-xl font-bold shadow-xs">
                <i class="fa-solid fa-boxes-stacked"></i>
            </div>
        </div>

        <!-- Valeur Allouée -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Valeur Financière</div>
                <div class="text-xl font-black text-slate-900 mt-1 font-mono">{{ number_format($valeurTotale, 0, ',', ' ') }} <span class="text-xs font-bold text-slate-500">FCFA</span></div>
                <div class="text-xs text-slate-500 font-medium mt-0.5">Bailleur: <strong class="text-slate-700">{{ $programme?->bailleur?->nom ?? 'Fonds mondial' }}</strong></div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-xl font-bold shadow-xs">
                <i class="fa-solid fa-coins"></i>
            </div>
        </div>

        <!-- Actifs Stratégiques / Risque -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Équipements Stratégiques</div>
                <div class="text-2xl font-black text-rose-600 mt-1 font-mono">{{ $actifsCritiques }}</div>
                <div class="text-xs text-rose-600 font-semibold mt-0.5">Criticité Élevée / Critique</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold shadow-xs">
                <i class="fa-solid fa-triangle-exclamation"></i>
            </div>
        </div>

        <!-- En Panne / Arrêt -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">En Panne / À Réparer</div>
                <div class="text-2xl font-black text-amber-600 mt-1 font-mono">{{ $actifsEnPanne }}</div>
                <div class="text-xs text-amber-600 font-semibold mt-0.5">Intervention requise</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold shadow-xs">
                <i class="fa-solid fa-wrench"></i>
            </div>
        </div>
    </div>

    <!-- Tableau des Derniers Actifs Rattachés au Programme -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-list-check text-[#155E3B]"></i>
                    <span>Équipements & Immobilisations Récentes : {{ $programme?->code }}</span>
                </h3>
                <div class="text-xs text-slate-500 mt-0.5">Parc matériel déployé dans les structures sanitaires partenaires</div>
            </div>
            <a href="{{ route('programme.immobilisations.index') }}" class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-[#155E3B] rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5">
                <span>Voir tout le parc</span>
                <i class="fa-solid fa-arrow-right text-[10px]"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Code Actif</th>
                        <th class="py-3 px-4">Désignation</th>
                        <th class="py-3 px-4">Établissement / Site</th>
                        <th class="py-3 px-4">Détenteur</th>
                        <th class="py-3 px-4 text-right">Valeur Acquisition</th>
                        <th class="py-3 px-4 text-center">État</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($actifs as $a)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-[#155E3B]">{{ $a->code_immobilisation }}</td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-900">{{ $a->designation }}</div>
                            <div class="text-[11px] text-slate-400">{{ $a->marque }} {{ $a->modele }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">{{ $a->structureBeneficiaire?->nom ?? 'Non assigné' }}</td>
                        <td class="py-3.5 px-4 text-slate-600">{{ $a->detenteur?->nom_complet ?? 'Non assigné' }}</td>
                        <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">{{ $a->valeur_formatee }}</td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $a->etat_badge_class }}">
                                {{ $a->etat_libelle }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <a href="{{ route('programme.immobilisations.show', $a->id) }}" class="inline-flex items-center space-x-1 px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-all text-xs">
                                <span>Fiche</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-8 text-center text-slate-400 italic">Aucune immobilisation rattachée à ce programme pour le moment.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
