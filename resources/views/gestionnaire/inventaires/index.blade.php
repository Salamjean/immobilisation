@extends('layouts.app')

@section('title', "Campagnes d'Inventaire - Espace Gestionnaire")

@section('content')
<div class="space-y-6">

    <!-- En-tête de la page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-clipboard-check text-[#0048FA]"></i>
                <span>Campagnes d'Inventaire Physique & Rapprochement</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Pilotage du recensement des actifs, déploiement des équipes terrain et rapprochement comptable</p>
        </div>

        <a href="{{ route('gestionnaire.inventaires.create') }}"
           style="background-color: #0048FA;"
           class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Nouvelle Campagne</span>
        </a>
    </div>

    <!-- 4 Cartes Statistiques Métier -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- En cours -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold border border-emerald-100 shadow-xs">
                <i class="fa-solid fa-clipboard-user"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Campagnes Actives</span>
                <span class="text-xl font-bold text-slate-900 font-mono">
                    {{ $campagnes->where('statut', 'en_cours')->count() }}
                </span>
            </div>
        </div>

        <!-- Total Campagnes -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div style="background-color: #0048FA;" class="w-12 h-12 rounded-xl text-white flex items-center justify-center text-lg font-bold shadow-xs">
                <i class="fa-solid fa-layer-group"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Total Campagnes</span>
                <span class="text-xl font-bold text-slate-900 font-mono">{{ $campagnes->total() }}</span>
            </div>
        </div>

        <!-- Équipes Déployées -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center text-lg font-bold border border-purple-100 shadow-xs">
                <i class="fa-solid fa-users-gear"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Équipes Mobilisées</span>
                <span class="text-xl font-bold text-slate-900 font-mono">
                    {{ $campagnes->sum('equipes_count') }}
                </span>
            </div>
        </div>

        <!-- Relevés Terrain -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center text-lg font-bold border border-cyan-100 shadow-xs">
                <i class="fa-solid fa-barcode"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Relevés Synchronisés</span>
                <span class="text-xl font-bold text-slate-900 font-mono">
                    {{ $campagnes->sum('releves_count') }}
                </span>
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

    <!-- Tableau Moderne des Campagnes -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center">Code Campagne</th>
                        <th class="py-3.5 px-4">Intitulé & Périmètre</th>
                        <th class="py-3.5 px-4">Type d'Inventaire</th>
                        <th class="py-3.5 px-4">Période d'Exécution</th>
                        <th class="py-3.5 px-4 text-center">Équipes</th>
                        <th class="py-3.5 px-4 text-center">Relevés Reçus</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($campagnes as $c)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center font-mono font-bold">
                                <span class="px-2.5 py-1 rounded-lg text-xs bg-purple-50 text-purple-800 border border-purple-100">
                                    {{ $c->code_campagne }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $c->intitule }}</div>
                                <div class="text-[11px] text-slate-500 mt-0.5 flex items-center space-x-1.5">
                                    <span>Superviseur : <strong>{{ $c->superviseur?->name ?? 'Direction UCP-FM' }}</strong></span>
                                    @if($c->programme)
                                        <span>•</span>
                                        <span class="text-emerald-700 font-semibold">{{ $c->programme->code }}</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $c->type_libelle }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ $c->date_debut ? $c->date_debut->format('d/m/Y') : '—' }} &rarr; {{ $c->date_fin ? $c->date_fin->format('d/m/Y') : '—' }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-800">
                                <span class="px-2 py-0.5 rounded-md bg-slate-100 text-slate-700">
                                    {{ $c->equipes_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $c->releves_count }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($c->statut === 'cloturee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                        Clôturée
                                    </span>
                                @elseif($c->statut === 'en_cours')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                        En cours
                                    </span>
                                @elseif($c->statut === 'planifiee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        Planifiée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                                        {{ ucfirst($c->statut) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('gestionnaire.inventaires.show', $c->id) }}"
                                   style="background-color: #0048FA;"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg text-white text-xs font-semibold shadow-xs hover:opacity-95 transition-all">
                                    <i class="fa-solid fa-magnifying-glass-chart mr-1.5"></i>
                                    <span>Superviser</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-clipboard-check text-3xl mb-2 block opacity-40"></i>
                                <span>Aucune campagne d'inventaire enregistrée.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($campagnes->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $campagnes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
