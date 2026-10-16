@extends('layouts.admin.app')

@section('title', 'Rapports & Statistiques')

@section('content')
<div class="space-y-5">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <div style="background-color: #155E3B;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                <i class="fa-solid fa-chart-pie"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Rapports & Indicateurs Clés</h1>
                <p class="text-xs text-slate-500 mt-0.5">Analytique décisionnelle, répartition catégorielle et volumétrie financière</p>
            </div>
        </div>

        <div class="flex items-center space-x-2.5">
            <a href="{{ route('admin.reporting.registre') }}" style="background-color: #155E3B;" class="px-4 py-2.5 rounded-xl text-white text-xs font-bold transition-all shadow-md shadow-[#155E3B]/25 hover:opacity-95 flex items-center space-x-1.5">
                <i class="fa-solid fa-file-invoice"></i>
                <span>Accéder au Registre Officiel</span>
            </a>
        </div>
    </div>

    <!-- 4 Cartes Métriques -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Patrimoine</div>
            <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalActifs }}</div>
            <div class="text-xs text-slate-500 mt-1">Biens immatriculés</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Valorisation Globale</div>
            <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ number_format($valeurTotale, 0, ',', ' ') }} FCFA</div>
            <div class="text-xs text-emerald-600 font-semibold mt-1">Coût d'acquisition consolidé</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Taux de Disponibilité</div>
            <div class="text-2xl font-black text-emerald-600 mt-1 font-mono">
                {{ $totalActifs > 0 ? round(($enService / $totalActifs) * 100, 1) : 0 }}%
            </div>
            <div class="text-xs text-emerald-600 font-semibold mt-1">{{ $enService }} actifs opérationnels</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Actifs en Panne / Maintenance</div>
            <div class="text-2xl font-black text-amber-600 mt-1">{{ $enPanne }}</div>
            <div class="text-xs text-amber-600 font-semibold mt-1">Nécessitant maintenance</div>
        </div>
    </div>

    <!-- Répartition par Catégorie -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
            <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-layer-group text-[#155E3B]"></i>
                <span>Répartition par Catégorie d'Actif</span>
            </h3>

            <div class="space-y-3">
                @foreach($parCategorie as $cat)
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid {{ $cat->icone ?? 'fa-cube' }} text-[#155E3B]"></i>
                        <span class="font-bold text-slate-800">{{ $cat->nom }}</span>
                    </div>
                    <span class="font-bold font-mono text-slate-900 px-2 py-0.5 bg-white rounded-lg border border-slate-200">
                        {{ $cat->immobilisations_count }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6">
            <h3 class="text-sm font-bold text-slate-900 mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                <i class="fa-solid fa-map-location-dot text-[#155E3B]"></i>
                <span>Couverture des Régions Sanitaires</span>
            </h3>

            <div class="space-y-3">
                @foreach($parRegion as $reg)
                <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50 border border-slate-100 text-xs">
                    <div class="flex items-center space-x-2.5">
                        <i class="fa-solid fa-hospital text-indigo-500"></i>
                        <span class="font-bold text-slate-800">{{ $reg->nom }}</span>
                    </div>
                    <span class="font-semibold text-slate-700">
                        {{ $reg->structures_count }} structure(s)
                    </span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
