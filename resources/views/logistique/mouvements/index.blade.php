@extends('layouts.app')

@section('title', 'Mouvements & Transferts Logistiques')
@section('header_title', 'Journal des Mouvements & Transferts')

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
        Logistique
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête & Filtres -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('logistique.mouvements.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 text-xs items-center">
            <div class="sm:col-span-4 relative">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-400"></i>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Réf. mouvement, code actif, désignation..."
                       class="w-full pl-9 pr-4 py-2.5 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] focus:ring-2 focus:ring-[#0048FA]/20 outline-none transition-all font-medium text-slate-800">
            </div>

            <div class="sm:col-span-3">
                <select name="type_mouvement" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium text-slate-700">
                    <option value="">Tous les types de mouvements</option>
                    <option value="transfert_site" {{ request('type_mouvement') === 'transfert_site' ? 'selected' : '' }}>Transfert de site</option>
                    <option value="affectation_interne" {{ request('type_mouvement') === 'affectation_interne' ? 'selected' : '' }}>Affectation interne</option>
                    <option value="reaffectation" {{ request('type_mouvement') === 'reaffectation' ? 'selected' : '' }}>Réaffectation</option>
                    <option value="pret_temporaire" {{ request('type_mouvement') === 'pret_temporaire' ? 'selected' : '' }}>Prêt temporaire</option>
                    <option value="retour_pret" {{ request('type_mouvement') === 'retour_pret' ? 'selected' : '' }}>Retour de prêt</option>
                    <option value="reforme" {{ request('type_mouvement') === 'reforme' ? 'selected' : '' }}>Réforme</option>
                    <option value="mise_au_rebut" {{ request('type_mouvement') === 'mise_au_rebut' ? 'selected' : '' }}>Mise au rebut</option>
                </select>
            </div>

            <div class="sm:col-span-3">
                <select name="statut_workflow" class="w-full py-2.5 px-3 bg-slate-50 border border-slate-200 rounded-xl focus:bg-white focus:border-[#0048FA] outline-none font-medium text-slate-700">
                    <option value="">Tous les statuts</option>
                    <option value="soumis" {{ request('statut_workflow') === 'soumis' ? 'selected' : '' }}>Soumis (En attente)</option>
                    <option value="valide" {{ request('statut_workflow') === 'valide' ? 'selected' : '' }}>Validé</option>
                    <option value="receptionne" {{ request('statut_workflow') === 'receptionne' ? 'selected' : '' }}>Réceptionné</option>
                    <option value="rejete" {{ request('statut_workflow') === 'rejete' ? 'selected' : '' }}>Rejeté</option>
                </select>
            </div>

            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit" class="flex-1 py-2.5 px-4 bg-[#0048FA] hover:bg-[#003ad1] text-white rounded-xl font-bold shadow-sm transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Filtrer</span>
                </button>
                <a href="{{ route('logistique.mouvements.create') }}" class="py-2.5 px-3 bg-amber-600 hover:bg-amber-700 text-white rounded-xl font-bold shadow-sm transition-all flex items-center justify-center" title="Nouveau mouvement">
                    <i class="fa-solid fa-plus"></i>
                </a>
            </div>
        </form>
    </div>

    <!-- Tableau des Mouvements -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 border-b border-slate-100 flex items-center justify-between">
            <div class="text-xs font-bold text-slate-800 flex items-center space-x-2">
                <i class="fa-solid fa-arrow-right-arrow-left text-[#0048FA]"></i>
                <span>Historique et Traçabilité des Mouvements</span>
            </div>
            <span class="text-xs font-bold px-2.5 py-0.5 rounded-full bg-slate-100 text-slate-600">
                {{ $mouvements->total() }} mouvement(s)
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold text-[11px] border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Réf. Mouvement</th>
                        <th class="py-3 px-4">Date</th>
                        <th class="py-3 px-4">Actif & Catégorie</th>
                        <th class="py-3 px-4">Type</th>
                        <th class="py-3 px-4">Origine &rarr; Destination</th>
                        <th class="py-3 px-4 text-center">Statut</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($mouvements as $m)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-3.5 px-4 font-mono font-bold text-[#0048FA]">
                            <a href="{{ route('logistique.mouvements.show', $m->id) }}" class="hover:underline">
                                {{ $m->reference_mouvement }}
                            </a>
                        </td>
                        <td class="py-3.5 px-4 text-slate-500 font-medium">
                            {{ $m->date_mouvement?->format('d/m/Y') }}
                        </td>
                        <td class="py-3.5 px-4">
                            <div class="font-bold text-slate-900">{{ $m->immobilisation?->designation ?? 'Actif inconnu' }}</div>
                            <div class="text-[11px] font-mono text-slate-400">{{ $m->immobilisation?->code_immobilisation }}</div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">
                                {{ $m->type_mouvement_libelle }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-xs">
                            <span class="text-slate-500">{{ $m->structureOrigine?->nom ?? 'Origine' }}</span>
                            <i class="fa-solid fa-arrow-right text-[10px] text-slate-400 mx-1"></i>
                            <strong class="text-slate-800">{{ $m->structureDestination?->nom ?? 'Destination' }}</strong>
                        </td>
                        <td class="py-3.5 px-4 text-center">
                            <span class="inline-block px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $m->statut_workflow_badge_class }}">
                                {{ $m->statut_workflow_libelle }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 text-right">
                            <a href="{{ route('logistique.mouvements.show', $m->id) }}" class="inline-flex items-center space-x-1 px-3 py-1 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold transition-all text-xs">
                                <span>Fiche</span>
                                <i class="fa-solid fa-chevron-right text-[10px]"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400 italic">
                            <i class="fa-solid fa-arrow-right-arrow-left text-3xl mb-2 text-slate-300 block"></i>
                            Aucun mouvement trouvé.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($mouvements->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50/50">
            {{ $mouvements->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
