@extends('layouts.admin.app')

@section('title', 'Mouvements & Transferts')

@section('content')
<div class="space-y-5">

    <!-- En-tête de page, 2 Compteurs & Action rapide sur la même ligne -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div style="background-color: #0048FA;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Journal des Mouvements & Transferts</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Affectations, transferts inter-structures, réformes et réintégrations</p>
                </div>
            </div>

            <!-- 2 Compteurs sur la même ligne -->
            <div class="flex items-center space-x-2.5 sm:border-l sm:border-slate-200 sm:pl-4">
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                    <i class="fa-solid fa-list-check text-slate-400 text-xs"></i>
                    <div class="text-xs font-semibold text-slate-700">
                        <span class="text-slate-400 font-normal">Total :</span> <strong class="text-slate-900 font-bold">{{ $stats['total'] ?? $mouvements->total() }}</strong>
                    </div>
                </div>

                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-amber-50 border border-amber-200">
                    <span class="w-2 h-2 rounded-full bg-amber-500 animate-pulse"></span>
                    <div class="text-xs font-semibold text-amber-800">
                        <span class="text-amber-600 font-normal">En attente :</span> <strong>{{ $stats['en_attente'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2.5 flex-shrink-0">
            <a href="{{ route('admin.mouvements.create') }}"
               style="background-color: #0048FA;"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Nouveau mouvement</span>
            </a>
        </div>
    </div>

    <!-- Filtres Multi-critères -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('admin.mouvements.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <!-- Recherche par Nom ou Référence -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Nom du bien, code, référence..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
            </div>

            <!-- Filtre par Statut -->
            <div>
                <select name="statut_workflow" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                    <option value="">Tous les statuts</option>
                    <option value="soumis" {{ request('statut_workflow') === 'soumis' ? 'selected' : '' }}>En attente</option>
                    <option value="en_transit" {{ request('statut_workflow') === 'en_transit' ? 'selected' : '' }}>En transit</option>
                    <option value="receptionne" {{ request('statut_workflow') === 'receptionne' ? 'selected' : '' }}>Clôturé / Réceptionné</option>
                    <option value="rejete" {{ request('statut_workflow') === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>

            <!-- Date Début -->
            <div class="relative">
                <input type="date"
                       name="date_debut"
                       value="{{ request('date_debut') }}"
                       title="Date début (Du)"
                       placeholder="Date début"
                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
            </div>

            <!-- Date Fin -->
            <div class="relative">
                <input type="date"
                       name="date_fin"
                       value="{{ request('date_fin') }}"
                       title="Date fin (Au)"
                       placeholder="Date fin"
                       class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
            </div>

            <!-- Boutons Filtrer & Réinitialiser -->
            <div class="flex items-center space-x-2">
                <button type="submit"
                        style="background-color: #0048FA;"
                        class="flex-1 py-2.5 px-3 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all text-center flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filtrer</span>
                </button>
                @if(request()->hasAny(['search', 'statut_workflow', 'type_mouvement', 'date_mouvement', 'date_debut', 'date_fin']))
                    <a href="{{ route('admin.mouvements.index') }}"
                       title="Réinitialiser tous les filtres"
                       class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 hover:text-slate-700 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau Moderne des Mouvements -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-left">Immobilisation</th>
                        <th class="py-3.5 px-4 text-center">Référence</th>
                        <th class="py-3.5 px-4 text-center">Type</th>
                        <th class="py-3.5 px-4 text-center">Origine &rarr; Destination</th>
                        <th class="py-3.5 px-4 text-center">Date</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($mouvements as $m)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Immobilisation (Aligné à gauche) -->
                            <td class="py-3.5 px-4 text-left">
                                <div class="font-bold text-slate-900">{{ $m->immobilisation?->designation ?? 'Actif non trouvé' }}</div>
                                <div class="text-[11px] font-mono text-slate-400">{{ $m->immobilisation?->code_immobilisation }}</div>
                            </td>

                            <!-- Référence -->
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-[#0048FA]">
                                {{ $m->reference_mouvement }}
                            </td>

                            <!-- Type -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 text-[#0048FA] border border-sky-100">
                                    {{ $m->type_libelle }}
                                </span>
                            </td>

                            <!-- Origine -> Destination -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center space-x-1.5 font-semibold text-slate-800">
                                    <span>{{ $m->structureOrigine?->nom ?? 'Siège' }}</span>
                                    <i class="fa-solid fa-arrow-right text-[10px] text-amber-500"></i>
                                    <span>{{ $m->structureDestination?->nom ?? 'Affectation' }}</span>
                                </div>
                            </td>

                            <!-- Date -->
                            <td class="py-3.5 px-4 text-center font-mono text-slate-700">
                                {{ $m->date_mouvement ? \Carbon\Carbon::parse($m->date_mouvement)->format('d/m/Y') : '—' }}
                            </td>

                            <!-- Statut -->
                            <td class="py-3.5 px-4 text-center">
                                @if($m->statut_workflow === 'soumis')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        En attente
                                    </span>
                                @elseif($m->statut_workflow === 'en_transit' || $m->statut_workflow === 'valide')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-[#0048FA] border border-sky-200">
                                        En transit
                                    </span>
                                @elseif($m->statut_workflow === 'receptionne' || $m->statut_workflow === 'cloture')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Clôturé
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $m->statut_workflow }}
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.mouvements.show', $m->id) }}"
                                   title="Consulter le bordereau"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#0048FA] text-slate-600 hover:text-white transition-colors">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                                    <i class="fa-solid fa-arrow-right-arrow-left"></i>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">Aucun mouvement enregistré</div>
                                <p class="text-xs text-slate-400 mt-1">Créez une demande de transfert ou d'affectation pour débuter le suivi.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mouvements->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Affichage de <strong>{{ $mouvements->firstItem() }}</strong> à <strong>{{ $mouvements->lastItem() }}</strong> sur <strong>{{ $mouvements->total() }}</strong> mouvements
                </div>
                <div>
                    {{ $mouvements->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
