@extends('layouts.app')

@section('title', 'Tableau de bord Logistique')
@section('header_title', 'Supervision Logistique & Gestion de Flotte')

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
        <i class="fa-solid fa-truck-moving mr-1.5 text-amber-600"></i>
        Logistique & Mouvements
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Bannière Logistique -->
    <div class="bg-gradient-to-r from-[#155E3B] to-emerald-800 rounded-2xl p-6 text-white shadow-sm border border-emerald-700/50 flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2 text-emerald-200 text-xs font-bold uppercase tracking-wider">
                <i class="fa-solid fa-truck-ramp-box"></i>
                <span>Supervision Opérationnelle & Flotte</span>
            </div>
            <h2 class="text-xl font-black mt-1">Espace Logistique & Transferts</h2>
            <p class="text-emerald-100 text-xs mt-1">Pilotage des flux d'actifs, affectations de sites, décharges et maintenance SAV.</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('logistique.mouvements.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-white text-[#155E3B] hover:bg-emerald-50 text-xs font-bold shadow-sm transition-all">
                <i class="fa-solid fa-plus"></i>
                <span>Nouveau mouvement</span>
            </a>
            <a href="{{ route('logistique.maintenances.create') }}" class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-emerald-700/60 hover:bg-emerald-700 text-white text-xs font-bold border border-emerald-600 transition-all">
                <i class="fa-solid fa-wrench"></i>
                <span>Demande SAV</span>
            </a>
        </div>
    </div>

    <!-- KPIs Logistique -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Total Mouvements -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Mouvements Enregistrés</div>
                <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $totalMouvements }}</div>
                <div class="text-xs text-[#155E3B] font-semibold mt-0.5">{{ $mouvementsValides }} validés / exécutés</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-emerald-50 text-[#155E3B] flex items-center justify-center text-xl font-bold shadow-xs">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
            </div>
        </div>

        <!-- Mouvements En Attente -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">En Attente Validation</div>
                <div class="text-2xl font-black text-amber-600 mt-1 font-mono">{{ $mouvementsEnAttente }}</div>
                <div class="text-xs text-amber-600 font-semibold mt-0.5">Nécessite signature</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-amber-50 text-amber-600 flex items-center justify-center text-xl font-bold shadow-xs">
                <i class="fa-solid fa-clock"></i>
            </div>
        </div>

        <!-- Maintenances en cours -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Interventions SAV & Panne</div>
                <div class="text-2xl font-black text-rose-600 mt-1 font-mono">{{ $maintenancesEnCours }}</div>
                <div class="text-xs text-rose-600 font-semibold mt-0.5">Sur {{ $totalMaintenances }} au total</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-rose-50 text-rose-600 flex items-center justify-center text-xl font-bold shadow-xs">
                <i class="fa-solid fa-wrench"></i>
            </div>
        </div>

        <!-- Référentiel Sites & Actifs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex items-center justify-between">
            <div>
                <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Parc Géré</div>
                <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $totalActifs }}</div>
                <div class="text-xs text-slate-500 font-medium mt-0.5">Sur {{ $totalStructures }} structures & sites</div>
            </div>
            <div class="w-12 h-12 rounded-2xl bg-blue-50 text-blue-700 flex items-center justify-center text-xl font-bold shadow-xs">
                <i class="fa-solid fa-box-archive"></i>
            </div>
        </div>
    </div>

    <!-- Tableaux Mouvements Récents & Maintenances -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Colonne Gauche (2/3) : Journal des Mouvements Récents -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                        <i class="fa-solid fa-arrow-right-arrow-left text-[#155E3B]"></i>
                        <span>Derniers Mouvements de Matériel & Véhicules</span>
                    </h3>
                    <div class="text-xs text-slate-500 mt-0.5">Transferts inter-sites, affectations et décharges</div>
                </div>
                <a href="{{ route('logistique.mouvements.index') }}" class="px-3.5 py-1.5 bg-emerald-50 hover:bg-emerald-100 text-[#155E3B] rounded-xl text-xs font-bold transition-all flex items-center space-x-1.5">
                    <span>Tout voir</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                        <tr>
                            <th class="py-3 px-4">Réf. Mvt</th>
                            <th class="py-3 px-4">Actif Concerné</th>
                            <th class="py-3 px-4">Origine &rarr; Destination</th>
                            <th class="py-3 px-4 text-center">Statut</th>
                            <th class="py-3 px-4 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse($derniersMouvements as $m)
                        <tr class="hover:bg-slate-50 transition-colors">
                            <td class="py-3.5 px-4 font-mono font-bold text-[#155E3B]">
                                <div>{{ $m->reference_mouvement }}</div>
                                <div class="text-[10px] text-slate-400 font-sans font-normal">{{ $m->date_mouvement?->format('d/m/Y') }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $m->immobilisation?->designation ?? 'Actif' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">{{ $m->immobilisation?->code_immobilisation }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-xs">
                                <span class="text-slate-500">{{ $m->structureOrigine?->nom ?? 'Origine' }}</span>
                                <i class="fa-solid fa-arrow-right text-[10px] text-slate-400 mx-1"></i>
                                <strong class="text-slate-800">{{ $m->structureDestination?->nom ?? 'Destination' }}</strong>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $m->statut_workflow_badge_class }}">
                                    {{ $m->statut_workflow_libelle }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('logistique.mouvements.show', $m->id) }}" class="inline-flex items-center space-x-1 px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-all text-xs">
                                    <span>Fiche</span>
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-slate-400 italic">Aucun mouvement enregistré.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Colonne Droite (1/3) : Maintenances & Interventions en Cours -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-5 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-wrench text-rose-500"></i>
                    <span>Derniers Suivis SAV</span>
                </h3>
                <a href="{{ route('logistique.maintenances.index') }}" class="text-xs font-bold text-[#155E3B] hover:underline">Voir tout</a>
            </div>

            <div class="space-y-3 text-xs">
                @forelse($dernieresMaintenances as $maint)
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100 space-y-1">
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-slate-800 text-[11px]">{{ $maint->numero_fiche }}</span>
                        <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">{{ ucfirst($maint->statut) }}</span>
                    </div>
                    <div class="font-semibold text-slate-900 text-xs">{{ $maint->designation }}</div>
                    <div class="text-slate-500 text-[11px] truncate">{{ $maint->description_panne ?? 'Intervention programmée' }}</div>
                </div>
                @empty
                <div class="text-center py-6 text-slate-400 text-xs italic">Aucune intervention active.</div>
                @endforelse
            </div>
        </div>

    </div>

</div>
@endsection
