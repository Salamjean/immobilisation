@extends('layouts.app')

@section('title', 'Mouvements & Transferts - Espace Gestionnaire')

@section('content')
<div class="space-y-6">

    <!-- En-tête de la page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-arrow-right-arrow-left text-[#155E3B]"></i>
                <span>Mouvements, Transferts & Affectations</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Journal des transferts inter-établissements, bordereaux de décharge et circuit de validation</p>
        </div>

        <a href="{{ route('gestionnaire.mouvements.create') }}"
           style="background-color: #155E3B;"
           class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Nouveau Transfert / Mouvement</span>
        </a>
    </div>

    <!-- 4 Cartes Statistiques Métier -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- En attente -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold border border-amber-100 shadow-xs">
                <i class="fa-solid fa-hourglass-half"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">En Attente Validation</span>
                <span class="text-xl font-bold text-slate-900 font-mono">
                    {{ $mouvements->where('statut_workflow', 'soumis')->count() }}
                </span>
            </div>
        </div>

        <!-- En transit -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-sky-50 text-sky-600 flex items-center justify-center text-lg font-bold border border-sky-100 shadow-xs">
                <i class="fa-solid fa-truck-fast"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">En Transit / Acheminement</span>
                <span class="text-xl font-bold text-slate-900 font-mono">
                    {{ $mouvements->where('statut_workflow', 'en_transit')->count() }}
                </span>
            </div>
        </div>

        <!-- Réceptionnés -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold border border-emerald-100 shadow-xs">
                <i class="fa-solid fa-box-archive"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Réceptionnés / Clôturés</span>
                <span class="text-xl font-bold text-emerald-700 font-mono">
                    {{ $mouvements->where('statut_workflow', 'receptionne')->count() }}
                </span>
            </div>
        </div>

        <!-- Total Mouvements -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div style="background-color: #155E3B;" class="w-12 h-12 rounded-xl text-white flex items-center justify-center text-lg font-bold shadow-xs">
                <i class="fa-solid fa-arrows-split-up-and-left"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Total Historique</span>
                <span class="text-xl font-bold text-slate-900 font-mono">{{ $mouvements->total() }}</span>
            </div>
        </div>
    </div>

    <!-- Alertes Flash -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Filtres Multi-critères Structurés -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('gestionnaire.mouvements.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Recherche (4 cols) -->
            <div class="sm:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher par référence, désignation, code..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
            </div>

            <!-- Filtre Statut Workflow (3 cols) -->
            <div class="sm:col-span-3">
                <select name="statut_workflow" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous les Statuts Workflow</option>
                    <option value="soumis" {{ request('statut_workflow') === 'soumis' ? 'selected' : '' }}>En attente de validation</option>
                    <option value="en_transit" {{ request('statut_workflow') === 'en_transit' ? 'selected' : '' }}>En transit (Expédié)</option>
                    <option value="receptionne" {{ request('statut_workflow') === 'receptionne' ? 'selected' : '' }}>Réceptionné & Clôturé</option>
                    <option value="rejete" {{ request('statut_workflow') === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>

            <!-- Filtre Type (3 cols) -->
            <div class="sm:col-span-3">
                <select name="type_mouvement" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous les Types</option>
                    <option value="transfert" {{ request('type_mouvement') === 'transfert' ? 'selected' : '' }}>Transfert inter-établissements</option>
                    <option value="reaffectation" {{ request('type_mouvement') === 'reaffectation' ? 'selected' : '' }}>Réaffectation interne</option>
                    <option value="pret" {{ request('type_mouvement') === 'pret' ? 'selected' : '' }}>Prêt temporaire</option>
                    <option value="sortie_temporaire" {{ request('type_mouvement') === 'sortie_temporaire' ? 'selected' : '' }}>Sortie temporaire (SAV)</option>
                    <option value="reforme" {{ request('type_mouvement') === 'reforme' ? 'selected' : '' }}>Proposition de réforme</option>
                </select>
            </div>

            <!-- Boutons Filtrer / Reset (2 cols) -->
            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit"
                        style="background-color: #155E3B;"
                        class="flex-1 py-2.5 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filtrer</span>
                </button>

                @if(request()->hasAny(['search', 'statut_workflow', 'type_mouvement']))
                    <a href="{{ route('gestionnaire.mouvements.index') }}"
                       class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-all flex items-center justify-center"
                       title="Réinitialiser">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau Moderne des Mouvements -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center">Réf. Mouvement</th>
                        <th class="py-3.5 px-4">Immobilisation Transférée</th>
                        <th class="py-3.5 px-4">Type Opération</th>
                        <th class="py-3.5 px-4">Origine &rarr; Destination</th>
                        <th class="py-3.5 px-4">Date Prévue</th>
                        <th class="py-3.5 px-4 text-center">Statut Workflow</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mouvements as $m)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center font-mono font-bold">
                                <span class="px-2.5 py-1 rounded-lg text-xs bg-amber-50 text-amber-900 border border-amber-200">
                                    {{ $m->reference_mouvement }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $m->immobilisation?->designation ?? 'Bien non spécifié' }}</div>
                                <div class="text-[11px] font-mono text-slate-400 mt-0.5">
                                    {{ $m->immobilisation?->code_immobilisation }}
                                    @if($m->immobilisation?->categorie)
                                        • {{ $m->immobilisation->categorie->libelle }}
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $m->type_libelle }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-slate-500 flex items-center space-x-1">
                                    <span class="text-[10px] text-slate-400 uppercase font-semibold">De :</span>
                                    <span>{{ $m->structureOrigine?->nom ?? 'Entrepôt / Stock Central' }}</span>
                                </div>
                                <div class="font-bold text-slate-800 flex items-center space-x-1.5 mt-0.5">
                                    <i class="fa-solid fa-arrow-right text-[10px] text-[#155E3B]"></i>
                                    <span class="text-[#155E3B]">{{ $m->structureDestination?->nom ?? 'Attribution en cours' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ $m->date_mouvement ? $m->date_mouvement->format('d/m/Y') : '—' }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($m->statut_workflow === 'receptionne')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Réceptionné
                                    </span>
                                @elseif($m->statut_workflow === 'en_transit')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-sky-100 text-sky-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-sky-500 mr-1.5 animate-pulse"></span>
                                        En transit
                                    </span>
                                @elseif($m->statut_workflow === 'soumis')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        En attente
                                    </span>
                                @elseif($m->statut_workflow === 'rejete')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                        Rejeté
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                                        {{ ucfirst($m->statut_workflow) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('gestionnaire.mouvements.show', $m->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:text-slate-900 hover:bg-slate-50 text-xs font-semibold transition-all">
                                    <i class="fa-solid fa-file-invoice text-[#155E3B] mr-1.5"></i>
                                    <span>Gérer Décharge</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-arrow-right-arrow-left text-3xl mb-2 block opacity-40"></i>
                                <span>Aucun mouvement ou transfert enregistré.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mouvements->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $mouvements->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
