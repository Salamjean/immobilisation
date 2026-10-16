@extends('layouts.app')

@section('title', 'Rapports & États Patrimoniaux')
@section('header_title', 'Rapports Analytiques & États Réglementaires')

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-teal-100 text-teal-800 border border-teal-200">
        États Bailleurs & UCP-FM
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête avec bouton Registre Officiel & Export -->
    <div class="bg-gradient-to-r from-slate-900 to-teal-950 rounded-2xl p-6 text-white flex flex-col md:flex-row items-start md:items-center justify-between gap-4 border border-teal-500/20 shadow-xl">
        <div>
            <div class="text-xs text-teal-400 font-bold uppercase tracking-wider">Restitution Réglementaire</div>
            <h1 class="text-xl font-extrabold text-white mt-1">Registre Officiel & Situations des Immobilisations</h1>
            <p class="text-xs text-slate-300 mt-1 max-w-xl">
                Génération des états consolidés par programme de santé, source de financement, structure sanitaire et niveau de criticité.
            </p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('reporting.registre') }}" class="px-4 py-2.5 rounded-xl bg-teal-500 hover:bg-teal-400 text-slate-950 text-xs font-extrabold transition-all shadow-lg flex items-center space-x-2">
                <i class="fa-solid fa-book-bookmark"></i>
                <span>Ouvrir Registre Officiel</span>
            </a>
            <a href="{{ route('immobilisations.export.csv') }}" class="px-4 py-2.5 rounded-xl bg-white/10 hover:bg-white/20 text-white text-xs font-bold transition-all flex items-center space-x-2">
                <i class="fa-solid fa-file-excel text-emerald-400"></i>
                <span>Export Brut CSV</span>
            </a>
        </div>
    </div>

    <!-- Synthèse Financière par Programme (Section 22) -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Par Programme -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center justify-between">
                <span>Situation par Programme de Santé</span>
                <i class="fa-solid fa-stethoscope text-brand-500"></i>
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3">Programme</th>
                            <th class="py-2.5 px-3 text-center">Nombre Biens</th>
                            <th class="py-2.5 px-3 text-right">Valeur Cumulée</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($statsProgrammes as $sp)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-3 font-bold text-slate-800">{{ $sp->code }} - {{ $sp->nom }}</td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold">{{ $sp->immobilisations_count }}</td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                                {{ number_format($sp->immobilisations_sum_valeur_acquisition ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Par Bailleur -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center justify-between">
                <span>Situation par Bailleur de Fonds</span>
                <i class="fa-solid fa-hand-holding-dollar text-emerald-500"></i>
            </h3>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs text-slate-600">
                    <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                        <tr>
                            <th class="py-2.5 px-3">Bailleur</th>
                            <th class="py-2.5 px-3 text-center">Nombre Biens</th>
                            <th class="py-2.5 px-3 text-right">Valeur Cumulée</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($statsBailleurs as $sb)
                        <tr class="hover:bg-slate-50">
                            <td class="py-2.5 px-3 font-bold text-slate-800">{{ $sb->nom }}</td>
                            <td class="py-2.5 px-3 text-center font-mono font-bold">{{ $sb->immobilisations_count }}</td>
                            <td class="py-2.5 px-3 text-right font-mono font-bold text-slate-900">
                                {{ number_format($sb->immobilisations_sum_valeur_acquisition ?? 0, 0, ',', ' ') }} FCFA
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- Rapport des Actifs Critiques sous Haute Surveillance (Section Q) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                    <i class="fa-solid fa-triangle-exclamation text-rose-600"></i>
                    <span>Liste de Surveillance des Actifs Critiques & Haut Risque</span>
                </h3>
                <div class="text-xs text-slate-500">Actifs stratégiques de forte valeur soumis aux exigences de vérification continue</div>
            </div>
            <span class="px-2.5 py-1 rounded-full text-xs font-bold bg-rose-100 text-rose-800">
                {{ $actifsCritiques->count() }} Actif(s) sous surveillance
            </span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Code / Tag</th>
                        <th class="py-3 px-4">Désignation</th>
                        <th class="py-3 px-4">Établissement & Ville</th>
                        <th class="py-3 px-4 text-right">Valeur Acquisition</th>
                        <th class="py-3 px-4 text-center">Score Risque</th>
                        <th class="py-3 px-4 text-center">Dernière Vérification</th>
                        <th class="py-3 px-4 text-right">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @foreach($actifsCritiques as $ac)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-mono font-bold text-brand-700">
                            <a href="{{ route('immobilisations.show', $ac->id) }}" class="hover:underline">{{ $ac->code_immobilisation }}</a>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900">{{ $ac->designation }}</div>
                            <div class="text-[11px] text-slate-500">{{ $ac->marque }} {{ $ac->modele }} | S/N: {{ $ac->numero_serie }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-semibold text-slate-800">{{ $ac->structureBeneficiaire?->nom }}</div>
                            <div class="text-[10px] text-slate-400">{{ $ac->structureBeneficiaire?->ville }}</div>
                        </td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-slate-900">
                            {{ $ac->valeur_formatee }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-block px-2 py-0.5 rounded text-[10px] uppercase font-extrabold {{ $ac->niveau_criticite_badge_class }}">
                                {{ $ac->niveau_criticite }} ({{ $ac->score_risque }}/100)
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-mono text-slate-500">
                            {{ $ac->date_dernier_inventaire ? $ac->date_dernier_inventaire->format('d/m/Y') : 'Non vérifié' }}
                        </td>
                        <td class="py-3 px-4 text-right">
                            <a href="{{ route('immobilisations.show', $ac->id) }}" class="px-2.5 py-1 rounded bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold">
                                Fiche &rarr;
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
