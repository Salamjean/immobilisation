@extends('layouts.admin.app')

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
            <p class="text-xs text-slate-500 mt-0.5">Référentiel des programmes de santé, rattachement aux bailleurs et suivi des sous-subventions</p>
        </div>

        <a href="{{ route('admin.programmes.create') }}"
           style="background-color: #155E3B;"
           class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i>
            <span>Nouveau Programme</span>
        </a>
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

    <!-- Tableau & Filtres -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <form action="{{ route('admin.programmes.index') }}" method="GET" class="flex flex-wrap items-center gap-2.5 w-full sm:w-auto">
                <div class="relative w-full sm:w-64">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher nom, code, coordonnateur..."
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>

                <select name="bailleur_id" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <option value="">Tous les Bailleurs</option>
                    @foreach($bailleurs as $b)
                        <option value="{{ $b->id }}" {{ request('bailleur_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->code }} - {{ Str::limit($b->nom, 25) }}
                        </option>
                    @endforeach
                </select>

                <select name="actif" class="px-3 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <option value="">Tous les Statuts</option>
                    <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs uniquement</option>
                    <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                </select>

                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    <i class="fa-solid fa-filter mr-1"></i> Filtrer
                </button>

                @if(request()->hasAny(['search', 'bailleur_id', 'actif']))
                    <a href="{{ route('admin.programmes.index') }}" class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-500 text-xs font-semibold transition-all">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center">Code</th>
                        <th class="py-3.5 px-4">Intitulé du Programme</th>
                        <th class="py-3.5 px-4">Bailleur Principal</th>
                        <th class="py-3.5 px-4">Coordonnateur</th>
                        <th class="py-3.5 px-4 text-center">Subventions</th>
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
                                <a href="{{ route('admin.subventions.index', ['programme_id' => $p->id]) }}" class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200/60 hover:bg-amber-100 transition-all">
                                    <i class="fa-solid fa-sack-dollar text-[10px]"></i>
                                    <span>{{ $p->subventions_count }}</span>
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.immobilisations.index', ['programme_id' => $p->id]) }}" class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/60 hover:bg-sky-100 transition-all">
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
                                <div class="inline-flex items-center space-x-1">
                                    <a href="{{ route('admin.programmes.edit', $p->id) }}"
                                       class="w-7 h-7 rounded-lg bg-slate-100 text-slate-600 hover:bg-[#155E3B] hover:text-white flex items-center justify-center transition-all"
                                       title="Modifier le programme">
                                        <i class="fa-solid fa-pen-to-square text-[11px]"></i>
                                    </a>

                                    <form action="{{ route('admin.programmes.toggle', $p->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit"
                                                class="w-7 h-7 rounded-lg {{ $p->actif ? 'bg-amber-50 text-amber-600 hover:bg-amber-600 hover:text-white' : 'bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white' }} flex items-center justify-center transition-all"
                                                title="{{ $p->actif ? 'Désactiver' : 'Activer' }}">
                                            <i class="fa-solid {{ $p->actif ? 'fa-ban' : 'fa-check' }} text-[11px]"></i>
                                        </button>
                                    </form>

                                    @if($p->subventions_count == 0 && $p->immobilisations_count == 0)
                                        <form action="{{ route('admin.programmes.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Confirmez-vous la suppression de ce programme ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-7 h-7 rounded-lg bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white flex items-center justify-center transition-all"
                                                    title="Supprimer">
                                                <i class="fa-solid fa-trash text-[11px]"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
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
