@extends('layouts.admin.app')

@section('title', "Campagnes d'Inventaire")

@section('content')
<div class="space-y-5">

    <!-- En-tête de page, 2 Compteurs & Action rapide sur la même ligne -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div style="background-color: #155E3B;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-clipboard-check"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Campagnes d'Inventaires Physiques</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Pilotage des missions de comptage terrain, rapprochement et réconciliation</p>
                </div>
            </div>

            <!-- 2 Compteurs sur la même ligne -->
            <div class="flex items-center space-x-2.5 sm:border-l sm:border-slate-200 sm:pl-4">
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                    <i class="fa-solid fa-layer-group text-slate-400 text-xs"></i>
                    <div class="text-xs font-semibold text-slate-700">
                        <span class="text-slate-400 font-normal">Total :</span> <strong class="text-slate-900 font-bold">{{ $stats['total'] ?? $campagnes->total() }}</strong>
                    </div>
                </div>

                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-sky-50 border border-sky-200">
                    <span class="w-2 h-2 rounded-full bg-[#155E3B] animate-pulse"></span>
                    <div class="text-xs font-semibold text-[#155E3B]">
                        <span class="text-slate-500 font-normal">En cours :</span> <strong>{{ $stats['en_cours'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2.5 flex-shrink-0">
            <a href="{{ route('admin.inventaires.create') }}"
               style="background-color: #155E3B;"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Nouvelle campagne</span>
            </a>
        </div>
    </div>

    <!-- Tableau Moderne des Campagnes -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-left">Campagne</th>
                        <th class="py-3.5 px-4 text-center">Code</th>
                        <th class="py-3.5 px-4 text-center">Type</th>
                        <th class="py-3.5 px-4 text-center">Période</th>
                        <th class="py-3.5 px-4 text-center">Relevés</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($campagnes as $c)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Intitulé & Superviseur (Aligné à gauche) -->
                            <td class="py-3.5 px-4 text-left">
                                <div class="font-bold text-slate-900">{{ $c->intitule }}</div>
                                <div class="text-[11px] text-slate-500">
                                    Superviseur : {{ $c->superviseur?->name ?? 'Direction UCP-FM' }}
                                    @if($c->programme)
                                        • {{ $c->programme->code }}
                                    @endif
                                </div>
                            </td>

                            <!-- Code -->
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-[#155E3B]">
                                <span class="px-2.5 py-1 rounded-md bg-sky-50 border border-sky-100 text-xs">
                                    {{ $c->code_campagne }}
                                </span>
                            </td>

                            <!-- Type -->
                            <td class="py-3.5 px-4 text-center font-semibold text-slate-700">
                                {{ $c->type_libelle }}
                            </td>

                            <!-- Période -->
                            <td class="py-3.5 px-4 text-center font-mono text-slate-600">
                                {{ $c->date_debut ? \Carbon\Carbon::parse($c->date_debut)->format('d/m/Y') : '—' }} &rarr; {{ $c->date_fin ? \Carbon\Carbon::parse($c->date_fin)->format('d/m/Y') : '—' }}
                            </td>

                            <!-- Relevés -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                    <i class="fa-solid fa-qrcode text-[10px] mr-1.5 text-slate-400"></i>
                                    {{ $c->releves_count ?? 0 }}
                                </span>
                            </td>

                            <!-- Statut -->
                            <td class="py-3.5 px-4 text-center">
                                @if($c->statut === 'en_cours')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-sky-50 text-[#155E3B] border border-sky-200">
                                        En cours
                                    </span>
                                @elseif($c->statut === 'cloturee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Clôturée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ $c->statut }}
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.inventaires.show', $c->id) }}"
                                   title="Tableau de bord de la mission"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#155E3B] text-slate-600 hover:text-white transition-colors">
                                    <i class="fa-solid fa-chart-pie text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                                    <i class="fa-solid fa-clipboard-check"></i>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">Aucune campagne d'inventaire</div>
                                <p class="text-xs text-slate-400 mt-1">Créez une nouvelle campagne pour planifier les missions de contrôle.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($campagnes->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Affichage de <strong>{{ $campagnes->firstItem() }}</strong> à <strong>{{ $campagnes->lastItem() }}</strong> sur <strong>{{ $campagnes->total() }}</strong> campagnes
                </div>
                <div>
                    {{ $campagnes->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
