@extends('layouts.app')

@section('title', 'Registre Officiel des Immobilisations')
@section('header_title', 'Registre Général du Patrimoine')

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-emerald-100 text-emerald-900 border border-emerald-300">
        Valeur Totale : {{ number_format($valeurTotale, 0, ',', ' ') }} FCFA
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête du Registre Officiel -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Document Officiel de Synthèse Patrimoniale</div>
            <h1 class="text-xl font-black text-slate-900 mt-1">Registre des Immobilisations & Équipements Biomédicaux</h1>
            <div class="text-xs text-slate-500 mt-1">
                Fonds mondial de lutte contre le Sida, la Tuberculose et le Paludisme • UCP-FM Côte d'Ivoire
            </div>
        </div>
        <div class="flex items-center space-x-2">
            <button type="button" onclick="window.print()" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-md flex items-center space-x-2">
                <i class="fa-solid fa-print"></i>
                <span>Imprimer le Registre</span>
            </button>
            <a href="{{ route('immobilisations.export.csv') }}" class="px-4 py-2 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold transition-all shadow-md flex items-center space-x-2">
                <i class="fa-solid fa-file-excel"></i>
                <span>Télécharger Excel</span>
            </a>
        </div>
    </div>

    <!-- Tableau Exhaustif du Registre -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-[11px] text-slate-600">
                <thead class="bg-slate-900 text-white uppercase font-bold text-[10px] tracking-wider">
                    <tr>
                        <th class="py-3 px-3">N° Immo</th>
                        <th class="py-3 px-3">Désignation</th>
                        <th class="py-3 px-3">Marque & Modèle</th>
                        <th class="py-3 px-3">Numéro Série</th>
                        <th class="py-3 px-3">Programme</th>
                        <th class="py-3 px-3">Établissement / Site</th>
                        <th class="py-3 px-3">Détenteur</th>
                        <th class="py-3 px-3 text-right">Valeur (FCFA)</th>
                        <th class="py-3 px-3 text-center">État</th>
                        <th class="py-3 px-3 text-center">Criticité</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @foreach($actifs as $index => $a)
                    <tr class="hover:bg-slate-50 {{ $index % 2 === 0 ? 'bg-white' : 'bg-slate-50/40' }}">
                        <td class="py-2.5 px-3 font-mono font-bold text-brand-800">
                            {{ $a->code_immobilisation }}
                        </td>
                        <td class="py-2.5 px-3 font-bold text-slate-900">
                            {{ $a->designation }}
                        </td>
                        <td class="py-2.5 px-3">
                            {{ $a->marque }} {{ $a->modele }}
                        </td>
                        <td class="py-2.5 px-3 font-mono text-slate-700">
                            {{ $a->numero_serie ?? 'N/A' }}
                        </td>
                        <td class="py-2.5 px-3 font-bold">
                            {{ $a->programme?->code ?? 'N/A' }}
                        </td>
                        <td class="py-2.5 px-3">
                            <span class="font-semibold text-slate-800">{{ $a->structureBeneficiaire?->nom }}</span>
                            <span class="text-slate-400 block text-[10px]">{{ $a->structureBeneficiaire?->ville }}</span>
                        </td>
                        <td class="py-2.5 px-3">
                            {{ $a->detenteur?->nom_complet ?? 'Non assigné' }}
                        </td>
                        <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                            {{ number_format($a->valeur_acquisition, 0, ',', ' ') }}
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-block px-1.5 py-0.5 rounded text-[9px] font-bold {{ $a->etat_badge_class }}">
                                {{ $a->etat_libelle }}
                            </span>
                        </td>
                        <td class="py-2.5 px-3 text-center">
                            <span class="inline-block px-1.5 py-0.5 rounded text-[9px] uppercase font-bold {{ $a->niveau_criticite_badge_class }}">
                                {{ $a->niveau_criticite }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
                <tfoot class="bg-slate-100 font-bold border-t-2 border-slate-300 text-slate-900">
                    <tr>
                        <td colspan="7" class="py-3 px-3 uppercase text-right">TOTAL PATRIMOINE CONSOLIDÉ :</td>
                        <td class="py-3 px-3 text-right font-mono text-sm text-brand-900">{{ number_format($valeurTotale, 0, ',', ' ') }} FCFA</td>
                        <td colspan="2"></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

</div>
@endsection
