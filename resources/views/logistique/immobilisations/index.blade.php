@extends('layouts.app')

@section('title', 'Référentiel des Actifs - Logistique')
@section('header_title', 'Référentiel & Parc des Immobilisations')

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
        Logistique
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête & Filtres -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('logistique.immobilisations.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs items-center">
            <div class="sm:col-span-4 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Code, désignation, n° série, tag..."
                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium text-slate-800">
            </div>

            <div class="sm:col-span-3">
                <select name="structure_id" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium text-slate-700">
                    <option value="">Toutes les structures</option>
                    @foreach($structures as $st)
                    <option value="{{ $st->id }}" {{ request('structure_id') == $st->id ? 'selected' : '' }}>{{ $st->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="etat" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium text-slate-700">
                    <option value="">Tous les états</option>
                    <option value="bon_etat" {{ request('etat') === 'bon_etat' ? 'selected' : '' }}>Bon état</option>
                    <option value="en_maintenance" {{ request('etat') === 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                    <option value="panne" {{ request('etat') === 'panne' ? 'selected' : '' }}>En panne</option>
                    <option value="hors_service" {{ request('etat') === 'hors_service' ? 'selected' : '' }}>Hors service</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit" class="w-full py-2.5 px-4 bg-[#155E3B] hover:bg-[#0f442a] text-white rounded-xl font-bold shadow-sm transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filtrer</span>
                </button>
            </div>
        </form>
    </div>

    <!-- Tableau des Actifs -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs font-bold text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-boxes-stacked text-[#155E3B]"></i>
                <span>Parc et État du Matériel</span>
            </div>
            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                {{ $immobilisations->total() }} actif(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Code Actif</th>
                        <th class="py-3 px-4">Désignation</th>
                        <th class="py-3 px-4">Site / Établissement</th>
                        <th class="py-3 px-4">Programme</th>
                        <th class="py-3 px-4 text-right">Valeur Initiale</th>
                        <th class="py-3 px-4 text-center">État</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($immobilisations as $a)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-[#155E3B]">
                            {{ $a->code_immobilisation }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-900">{{ $a->designation }}</div>
                            <div class="text-[11px] text-slate-400">{{ $a->marque }} {{ $a->modele }}</div>
                        </td>
                        <td class="py-3.5 px-4 font-semibold text-slate-800">
                            {{ $a->structureBeneficiaire?->nom ?? 'Non assigné' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-600">
                            {{ $a->programme?->sigle ?? 'N/A' }}
                        </td>
                        <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">
                            {{ $a->valeur_formatee }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $a->etat_badge_class }}">
                                {{ $a->etat_libelle }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right space-x-1">
                            <a href="{{ route('logistique.mouvements.create', ['actif_id' => $a->id]) }}" title="Créer un mouvement" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-amber-50 hover:bg-amber-100 text-amber-800 font-bold transition-all text-xs">
                                <i class="fa-solid fa-arrow-right-arrow-left mr-1"></i>
                                <span>Transférer</span>
                            </a>
                            <a href="{{ route('logistique.immobilisations.show', $a->id) }}" class="inline-flex items-center px-2.5 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-all text-xs">
                                <span>Fiche</span>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400 italic">Aucun actif trouvé.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($immobilisations->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50/50">
            {{ $immobilisations->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
