@extends('layouts.app')

@section('title', "Parc Immobilisations - " . ($programme?->code ?? 'Programme'))
@section('header_title', "Parc des Immobilisations : " . ($programme?->code ?? 'Programme'))

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-900 border border-emerald-300">
        {{ $programme?->nom ?? 'Programme Santé' }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête & Filtres Recherche -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('programme.immobilisations.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs items-center">
            <div class="sm:col-span-6 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher par code, désignation, numéro de série..."
                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] focus:ring-2 focus:ring-[#155E3B]/20 outline-none transition-all font-medium text-slate-800">
            </div>

            <div class="sm:col-span-3">
                <select name="etat" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium text-slate-700">
                    <option value="">Tous les états de fonctionnement</option>
                    <option value="bon_etat" {{ request('etat') === 'bon_etat' ? 'selected' : '' }}>Bon état</option>
                    <option value="panne" {{ request('etat') === 'panne' ? 'selected' : '' }}>En panne</option>
                    <option value="hors_service" {{ request('etat') === 'hors_service' ? 'selected' : '' }}>Hors service</option>
                </select>
            </div>

            <div class="sm:col-span-3 flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-[#155E3B] hover:bg-[#0f442a] text-white rounded-xl font-bold shadow-sm transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filtrer</span>
                </button>
                @if(request()->hasAny(['search', 'etat']))
                    <a href="{{ route('programme.immobilisations.index') }}" title="Réinitialiser" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl font-bold transition-all">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau des Actifs -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs font-bold text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-boxes-stacked text-[#155E3B]"></i>
                <span>Inventaire des Actifs Affectés au Programme</span>
            </div>
            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                {{ $immobilisations->total() }} équipement(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Code Actif</th>
                        <th class="py-3 px-4">Désignation</th>
                        <th class="py-3 px-4">Site / Établissement</th>
                        <th class="py-3 px-4">Détenteur</th>
                        <th class="py-3 px-4 text-right">Valeur d'Acquisition</th>
                        <th class="py-3 px-4 text-center">État</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($immobilisations as $a)
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
                                <span>Détails</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400 italic">
                            <i class="fa-solid fa-folder-open text-3xl mb-2 text-slate-300 block"></i>
                            Aucune immobilisation trouvée pour les critères spécifiés.
                        </td>
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
