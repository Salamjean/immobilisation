@extends('layouts.app')

@section('title', 'Gestion des Bailleurs de Fonds')

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-hand-holding-dollar text-[#155E3B]"></i>
                <span>Bailleurs de Fonds & Partenaires Financiers</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Consultation des bailleurs et partenaires financiers pour le rattachement des équipements</p>
        </div>
        <div class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-slate-500 text-xs font-medium">
            <i class="fa-solid fa-eye text-slate-400"></i>
            <span>Consultation Référentiel</span>
        </div>
    </div>

    <!-- Statistiques rapides -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div style="background-color: #155E3B;" class="w-12 h-12 rounded-xl text-white flex items-center justify-center text-lg font-bold shadow-xs">
                <i class="fa-solid fa-hand-holding-dollar"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Total Bailleurs</span>
                <span class="text-xl font-bold text-slate-900 font-mono">{{ $stats['total'] }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold border border-emerald-100">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Bailleurs Actifs</span>
                <span class="text-xl font-bold text-emerald-700 font-mono">{{ $stats['actifs'] }}</span>
            </div>
        </div>

        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-slate-100 text-slate-500 flex items-center justify-center text-lg font-bold">
                <i class="fa-solid fa-ban"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Bailleurs Inactifs</span>
                <span class="text-xl font-bold text-slate-700 font-mono">{{ $stats['inactifs'] }}</span>
            </div>
        </div>
    </div>

    <!-- Tableau & Filtres -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
            <form action="{{ route('gestionnaire.bailleurs.index') }}" method="GET" class="flex items-center space-x-2 w-full sm:w-auto">
                <div class="relative w-full sm:w-72">
                    <input type="text"
                           name="search"
                           value="{{ request('search') }}"
                           placeholder="Rechercher nom, code, email..."
                           class="w-full pl-9 pr-3.5 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 text-xs">
                        <i class="fa-solid fa-magnifying-glass"></i>
                    </div>
                </div>
                <button type="submit" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                    Filtrer
                </button>
            </form>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center">Code</th>
                        <th class="py-3.5 px-4">Nom du Bailleur</th>
                        <th class="py-3.5 px-4">Contact Référent</th>
                        <th class="py-3.5 px-4 text-center">Programmes</th>
                        <th class="py-3.5 px-4 text-center">Immobilisations</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($bailleurs as $b)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-1 rounded-lg text-xs font-mono font-bold bg-cyan-50 text-[#155E3B] border border-cyan-100">
                                    {{ $b->code }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $b->nom }}</div>
                                @if($b->description)
                                    <div class="text-[11px] text-slate-400 truncate max-w-xs">{{ $b->description }}</div>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                @if($b->contact_nom || $b->contact_email)
                                    <div class="font-medium text-slate-800">{{ $b->contact_nom ?? '—' }}</div>
                                    <div class="text-[11px] text-slate-400">{{ $b->contact_email ?? '—' }}</div>
                                @else
                                    <span class="text-slate-400 italic">Non renseigné</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-700">
                                {{ $b->programmes_count }}
                            </td>
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-700">
                                {{ $b->immobilisations_count }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $b->actif ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                    {{ $b->actif ? 'Actif' : 'Inactif' }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <span class="inline-flex items-center text-xs text-slate-400 font-medium">
                                    <i class="fa-solid fa-check-circle text-emerald-500 mr-1 text-[11px]"></i>
                                    Disponible
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-8 text-center text-slate-400">
                                <i class="fa-solid fa-hand-holding-dollar text-3xl mb-2 text-slate-300 block"></i>
                                <span>Aucun bailleur de fonds trouvé.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bailleurs->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $bailleurs->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
