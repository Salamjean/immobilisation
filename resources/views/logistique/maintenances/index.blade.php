@extends('layouts.app')

@section('title', 'Maintenance & SAV Logistique')
@section('header_title', 'Suivi de la Maintenance & SAV')

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
        Logistique
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête & Filtres -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('logistique.maintenances.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs items-center">
            <div class="sm:col-span-5 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Numéro de fiche, prestataire, panne..."
                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium text-slate-800">
            </div>

            <div class="sm:col-span-3">
                <select name="statut" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#155E3B] outline-none font-medium text-slate-700">
                    <option value="">Tous les statuts</option>
                    <option value="signalee" {{ request('statut') === 'signalee' ? 'selected' : '' }}>Signalée</option>
                    <option value="en_attente" {{ request('statut') === 'en_attente' ? 'selected' : '' }}>En attente</option>
                    <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours d'intervention</option>
                    <option value="terminee" {{ request('statut') === 'terminee' ? 'selected' : '' }}>Terminée / Réparée</option>
                    <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <div class="sm:col-span-4 flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-[#155E3B] hover:bg-[#0f442a] text-white rounded-xl font-bold shadow-sm transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filtrer</span>
                </button>
                <a href="{{ route('logistique.maintenances.create') }}" class="py-2.5 px-3.5 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold shadow-sm transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus"></i>
                    <span>Nouvelle Fiche</span>
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des Maintenances -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs font-bold text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-wrench text-rose-500"></i>
                <span>Fiches d'Intervention SAV & Dépannage</span>
            </div>
            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                {{ $maintenances->total() }} fiche(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">N° Fiche</th>
                        <th class="py-3 px-4">Date Signalement</th>
                        <th class="py-3 px-4">Actif Concerné</th>
                        <th class="py-3 px-4">Type & Panne</th>
                        <th class="py-3 px-4">Prestataire</th>
                        <th class="py-3 px-4 text-center">Statut</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($maintenances as $maint)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-[#155E3B]">
                            <a href="{{ route('logistique.maintenances.show', $maint->id) }}" class="hover:underline">
                                {{ $maint->numero_fiche }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 font-medium">
                            {{ $maint->date_signalement?->format('d/m/Y') }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-900">{{ $maint->immobilisation?->designation }}</div>
                            <div class="text-[11px] font-mono text-slate-400">{{ $maint->immobilisation?->code_immobilisation }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-semibold text-slate-800">{{ ucfirst($maint->type_maintenance) }}</div>
                            <div class="text-[11px] text-slate-500 truncate max-w-xs">{{ $maint->description_panne ?? 'N/A' }}</div>
                        </td>
                        <td class="py-3.5 px-4 text-slate-700 font-medium">
                            {{ $maint->prestataire ?? 'Interne UCP-FM' }}
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $maint->statut_badge_class }}">
                                {{ $maint->statut_libelle }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <a href="{{ route('logistique.maintenances.show', $maint->id) }}" class="inline-flex items-center space-x-1 px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-all text-xs">
                                <span>Fiche</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400 italic">
                            <i class="fa-solid fa-wrench text-3xl mb-2 text-slate-300 block"></i>
                            Aucune fiche d'intervention trouvée.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($maintenances->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50/50">
            {{ $maintenances->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
