@extends('layouts.app')

@section('title', 'Gestion des Programmes & Projets')

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-diagram-project text-[#155E3B]"></i>
                <span>Programmes Nationaux & Projets Stratégiques</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Consultation des programmes de santé et suivi des immobilisations associées</p>
        </div>
        <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-xs font-medium">
            <i class="fa-solid fa-eye text-slate-400"></i>
            <span>Consultation Référentiel</span>
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

    @if(session('error'))
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-exclamation text-rose-600 text-base"></i>
                <span class="font-semibold">{{ session('error') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-rose-500 hover:text-rose-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div style="background-color: #155E3B;" class="w-12 h-12 rounded-xl text-white flex items-center justify-center text-lg font-bold shadow-xs">
                <i class="fa-solid fa-diagram-project"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Total Programmes</span>
                <span class="text-xl font-bold text-slate-900 font-mono">{{ $stats['total'] }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold border border-emerald-100">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Programmes Actifs</span>
                <span class="text-xl font-bold text-emerald-700 font-mono">{{ $stats['actifs'] }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-lg font-bold">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Programmes Inactifs</span>
                <span class="text-xl font-bold text-slate-700 font-mono">{{ $stats['inactifs'] }}</span>
            </div>
        </div>
    </div>

    <!-- Filtres & Recherche Structurée -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('gestionnaire.programmes.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Recherche Mot-clé (5 cols) -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher nom, code, coordonnateur..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
            </div>

            <!-- Filtre Bailleur (3 cols) -->
            <div class="sm:col-span-3">
                <select name="bailleur_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous les Bailleurs</option>
                    @foreach($bailleurs as $b)
                        <option value="{{ $b->id }}" {{ request('bailleur_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->code }} - {{ Str::limit($b->nom, 22) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Statut (2 cols) -->
            <div class="sm:col-span-2">
                <select name="actif" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous les Statuts</option>
                    <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                    <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>

            <!-- Boutons d'Action (2 cols) -->
            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit"
                        style="background-color: #155E3B;"
                        class="flex-1 py-2.5 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filtrer</span>
                </button>

                @if(request()->hasAny(['search', 'bailleur_id', 'actif']))
                    <a href="{{ route('gestionnaire.programmes.index') }}"
                       class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-all flex items-center justify-center"
                       title="Réinitialiser">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center">Code</th>
                        <th class="py-3.5 px-4">Intitulé du Programme</th>
                        <th class="py-3.5 px-4">Bailleur Principal</th>
                        <th class="py-3.5 px-4">Coordonnateur</th>
                        <th class="py-3.5 px-4 text-center">Immobilisations</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($programmes as $p)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-emerald-50 text-[#155E3B] border border-emerald-100">
                                    {{ $p->code }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $p->nom }}</div>
                                @if($p->description)
                                    <div class="text-[11px] text-slate-400 truncate max-w-xs mt-0.5">{{ $p->description }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($p->bailleur)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        {{ $p->bailleur->code }}
                                    </span>
                                    <div class="text-[10px] text-slate-500 truncate max-w-[140px]">{{ $p->bailleur->nom }}</div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Non spécifié</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($p->coordonnateur)
                                    <div class="font-medium text-slate-800 flex items-center space-x-1.5">
                                        <i class="fa-solid fa-user-tie text-[10px] text-slate-400"></i>
                                        <span>{{ $p->coordonnateur }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">-</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('gestionnaire.immobilisations.index', ['programme_id' => $p->id]) }}" class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/60 hover:bg-sky-100 transition-all">
                                    <i class="fa-solid fa-boxes-stacked text-[10px]"></i>
                                    <span>{{ $p->immobilisations_count }}</span>
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($p->actif)
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                        Inactif
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('gestionnaire.immobilisations.index', ['programme_id' => $p->id]) }}"
                                   class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-semibold transition-all"
                                   title="Voir les biens de ce programme">
                                    <i class="fa-solid fa-boxes-stacked mr-1 text-[#155E3B]"></i>
                                    <span>Actifs liés</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-diagram-project text-3xl mb-2 block opacity-40"></i>
                                <span>Aucun programme trouvé selon ces critères.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($programmes->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $programmes->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
