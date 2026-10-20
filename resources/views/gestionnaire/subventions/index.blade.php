@extends('layouts.app')

@section('title', 'Gestion des Subventions & Financements')

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-sack-dollar text-[#0048FA]"></i>
                <span>Subventions & Lignes de Financement</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Consultation des accords de financement et affectation des actifs patrimoniaux</p>
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
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div style="background-color: #0048FA;" class="w-12 h-12 rounded-xl text-white flex items-center justify-center text-lg font-bold shadow-xs">
                <i class="fa-solid fa-sack-dollar"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Total Subventions</span>
                <span class="text-xl font-bold text-slate-900 font-mono">{{ $stats['total'] }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold border border-emerald-100">
                <i class="fa-solid fa-circle-play"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">En cours</span>
                <span class="text-xl font-bold text-emerald-700 font-mono">{{ $stats['en_cours'] }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center text-lg font-bold">
                <i class="fa-solid fa-flag-checkered"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Clôturées</span>
                <span class="text-xl font-bold text-slate-700 font-mono">{{ $stats['cloturees'] }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold border border-amber-100">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Montant Total Alloué</span>
                <span class="text-sm font-bold text-amber-800 font-mono">{{ number_format($stats['montant_total'], 0, ',', ' ') }} FCFA</span>
            </div>
        </div>
    </div>

    <!-- Filtres & Recherche Structurée -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('gestionnaire.subventions.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Recherche Mot-clé (4 cols) -->
            <div class="sm:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher intitulé, code subvention..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
            </div>

            <!-- Filtre Programme (3 cols) -->
            <div class="sm:col-span-3">
                <select name="programme_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                    <option value="">Tous les Programmes</option>
                    @foreach($programmes as $prg)
                        <option value="{{ $prg->id }}" {{ request('programme_id') == $prg->id ? 'selected' : '' }}>
                            {{ $prg->code }} - {{ Str::limit($prg->nom, 20) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Bailleur (2 cols) -->
            <div class="sm:col-span-2">
                <select name="bailleur_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                    <option value="">Tous Bailleurs</option>
                    @foreach($bailleurs as $b)
                        <option value="{{ $b->id }}" {{ request('bailleur_id') == $b->id ? 'selected' : '' }}>
                            {{ $b->code }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Statut (1 col) -->
            <div class="sm:col-span-1">
                <select name="statut" class="w-full px-2.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
                    <option value="">Statut</option>
                    @foreach($statuts as $stKey => $stVal)
                        <option value="{{ $stKey }}" {{ request('statut') === $stKey ? 'selected' : '' }}>{{ $stVal }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Boutons Filtrer / Reset (2 cols) -->
            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit"
                        style="background-color: #0048FA;"
                        class="flex-1 py-2.5 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filtrer</span>
                </button>

                @if(request()->hasAny(['search', 'programme_id', 'bailleur_id', 'statut']))
                    <a href="{{ route('gestionnaire.subventions.index') }}"
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
                        <th class="py-3.5 px-4 text-center">Code Subvention</th>
                        <th class="py-3.5 px-4">Intitulé & Projet</th>
                        <th class="py-3.5 px-4">Bailleur & Programme</th>
                        <th class="py-3.5 px-4">Période d'Exécution</th>
                        <th class="py-3.5 px-4 text-right">Budget Alloué</th>
                        <th class="py-3.5 px-4 text-center">Immobilisations</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($subventions as $sub)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-amber-50 text-amber-800 border border-amber-200/70">
                                    {{ $sub->code_subvention }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $sub->intitule }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center space-x-1.5 mb-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                        PRG: {{ $sub->programme?->code ?? '-' }}
                                    </span>
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-700">
                                        BL: {{ $sub->bailleur?->code ?? '-' }}
                                    </span>
                                </div>
                                <div class="text-[10px] text-slate-400 truncate max-w-[160px]">{{ $sub->programme?->nom }}</div>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="text-slate-700 font-mono text-[11px]">
                                    @if($sub->date_debut && $sub->date_fin)
                                        {{ $sub->date_debut->format('d/m/Y') }} <span class="text-slate-400">au</span> {{ $sub->date_fin->format('d/m/Y') }}
                                    @elseif($sub->date_debut)
                                        Depuis le {{ $sub->date_debut->format('d/m/Y') }}
                                    @else
                                        <span class="text-slate-400 italic">Non définie</span>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <div class="font-bold font-mono text-slate-900">
                                    {{ number_format($sub->montant_alloue, 0, ',', ' ') }}
                                </div>
                                <div class="text-[10px] text-slate-400 uppercase font-semibold">{{ $sub->devise }}</div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('gestionnaire.immobilisations.index', ['subvention_id' => $sub->id]) }}" class="inline-flex items-center space-x-1 px-2.5 py-0.5 rounded-full text-xs font-semibold bg-sky-50 text-sky-700 border border-sky-200/60 hover:bg-sky-100 transition-all">
                                    <i class="fa-solid fa-boxes-stacked text-[10px]"></i>
                                    <span>{{ $sub->immobilisations_count }}</span>
                                </a>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($sub->statut === 'en_cours')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                        En cours
                                    </span>
                                @elseif($sub->statut === 'cloturee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-slate-400 mr-1.5"></span>
                                        Clôturée
                                    </span>
                                @elseif($sub->statut === 'suspendue')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5"></span>
                                        Suspendue
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5"></span>
                                        En attente
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('gestionnaire.immobilisations.index', ['subvention_id' => $sub->id]) }}"
                                   class="inline-flex items-center px-2.5 py-1.5 rounded-lg border border-slate-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50 text-xs font-semibold transition-all"
                                   title="Voir les biens de cette subvention">
                                    <i class="fa-solid fa-boxes-stacked mr-1 text-[#0048FA]"></i>
                                    <span>Actifs liés</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-sack-dollar text-3xl mb-2 block opacity-40"></i>
                                <span>Aucune subvention trouvée selon vos critères.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($subventions->hasPages())
            <div class="p-4 border-t border-slate-100 bg-slate-50/50">
                {{ $subventions->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
