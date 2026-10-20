@extends('layouts.admin.app')

@section('title', 'Registre Officiel des Immobilisations')

@section('content')
<div class="space-y-5">

    <!-- En-tête du Registre Officiel -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <div style="background-color: #0048FA;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                <i class="fa-solid fa-file-invoice"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Registre Général & Officiel du Patrimoine</h1>
                <p class="text-xs text-slate-500 mt-0.5">Synthèse officielle certifiée • UCP-FM Côte d'Ivoire</p>
            </div>
        </div>

        <div class="flex items-center space-x-2.5">
            <button type="button" onclick="window.print()" class="px-3.5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Imprimer le Registre</span>
            </button>
            <a href="{{ route('admin.immobilisations.export.csv') }}" class="px-3.5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-xs flex items-center space-x-2">
                <i class="fa-solid fa-file-csv"></i>
                <span>Exporter CSV</span>
            </a>
        </div>
    </div>

    <!-- Tableau du Registre -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-3 text-left">Code & Désignation</th>
                        <th class="py-3 px-3 text-center">Marque & Modèle</th>
                        <th class="py-3 px-3 text-center">Numéro Série</th>
                        <th class="py-3 px-3 text-center">Programme</th>
                        <th class="py-3 px-3 text-center">Site d'Implantation</th>
                        <th class="py-3 px-3 text-center">Valeur</th>
                        <th class="py-3 px-3 text-center">État</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($actifs as $a)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-3 text-left">
                                <div class="font-bold text-slate-900">{{ $a->designation }}</div>
                                <div class="font-mono text-[10px] text-[#0048FA] font-bold">{{ $a->code_immobilisation }}</div>
                            </td>
                            <td class="py-3 px-3 text-center text-slate-700">
                                {{ $a->marque ?? '—' }} {{ $a->modele ? '• '.$a->modele : '' }}
                            </td>
                            <td class="py-3 px-3 text-center font-mono text-slate-600">
                                {{ $a->numero_serie ?? 'N/A' }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                <span class="px-2 py-0.5 rounded bg-slate-100 font-semibold text-[11px] text-slate-700">
                                    {{ $a->programme?->code ?? 'Santé' }}
                                </span>
                            </td>
                            <td class="py-3 px-3 text-center">
                                <div class="font-semibold text-slate-800">{{ $a->structureBeneficiaire?->nom ?? 'Siège UCP-FM' }}</div>
                                <div class="text-[10px] text-slate-400">{{ $a->structureBeneficiaire?->region?->nom ?? 'Côte d\'Ivoire' }}</div>
                            </td>
                            <td class="py-3 px-3 text-center font-mono font-bold text-slate-900">
                                {{ number_format($a->valeur_acquisition, 0, ',', ' ') }} {{ $a->devise }}
                            </td>
                            <td class="py-3 px-3 text-center">
                                @if($a->etat === 'bon_etat')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Bon état
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ ucfirst(str_replace('_', ' ', $a->etat)) }}
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-10 text-center text-slate-400">Aucune immobilisation enregistrée dans le registre officiel.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($actifs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Affichage de <strong>{{ $actifs->firstItem() }}</strong> à <strong>{{ $actifs->lastItem() }}</strong> sur <strong>{{ $actifs->total() }}</strong> actifs
                </div>
                <div>
                    {{ $actifs->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
