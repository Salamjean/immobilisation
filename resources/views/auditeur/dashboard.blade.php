@extends('layouts.app')

@section('title', 'Espace Audit & Contrôle Interne')
@section('header_title', 'Espace Auditeur & Contrôle Interne')

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
        Supervision & Conformité
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- Indicateurs de Risque & Contrôle -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase">Actifs sous Surveillance</div>
            <div class="text-2xl font-extrabold text-rose-600 mt-1">{{ $actifsCritiques }}</div>
            <div class="text-xs text-rose-600 font-semibold mt-1">Criticité Élevée / Critique</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase">Écarts d'Inventaire Détectés</div>
            <div class="text-2xl font-extrabold text-amber-600 mt-1 font-mono">{{ $totalEcarts }}</div>
            <div class="text-xs text-amber-600 font-semibold mt-1">Anomalies de localisation / état</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase">Valeur Globale Auditée</div>
            <div class="text-xl font-extrabold text-slate-900 mt-1 font-mono">{{ number_format($valeurTotale, 0, ',', ' ') }} FCFA</div>
            <div class="text-xs text-slate-500 mt-1">{{ $totalActifs }} équipements répertoriés</div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase">Traces d'Audit Enregistrées</div>
            <div class="text-2xl font-extrabold text-blue-600 mt-1 font-mono">{{ $totalLogs }}</div>
            <div class="text-xs text-blue-600 font-semibold mt-1">Piste d'audit non altérable</div>
        </div>
    </div>

    <!-- Alertes & Piste d'Audit -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Alertes de contrôle -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                <span>Alertes de Contrôle Interne</span>
                <i class="fa-solid fa-bell text-rose-500"></i>
            </h3>

            <div class="space-y-3 text-xs">
                @forelse($alertesControle as $alt)
                <div class="p-3 rounded-xl border {{ $alt->niveau_severite === 'danger' ? 'bg-red-50/50 border-red-200' : 'bg-amber-50/50 border-amber-200' }}">
                    <div class="font-bold text-slate-900">{{ $alt->titre }}</div>
                    <div class="text-slate-600 text-[11px] mt-0.5">{{ $alt->message }}</div>
                    <div class="text-[10px] text-slate-400 mt-1 font-mono">{{ $alt->created_at->format('d/m/Y H:i') }}</div>
                </div>
                @empty
                <div class="text-slate-400 py-6 text-center italic">Aucune alerte active.</div>
                @endforelse
            </div>
        </div>

        <!-- Derniers Événements d'Audit -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                <span>Dernières Actions Journalisées</span>
                <a href="{{ route('auditeur.audit.index') }}" class="text-blue-600 text-[11px] font-bold hover:underline">Voir tout</a>
            </h3>

            <div class="space-y-2.5 text-xs font-mono">
                @foreach($derniersLogs as $log)
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-between">
                    <div>
                        <span class="px-1.5 py-0.5 rounded text-[9px] bg-blue-100 text-blue-800 font-bold font-sans">{{ $log->action }}</span>
                        <span class="font-sans text-slate-700 font-semibold ml-1.5 text-[11px]">{{ $log->user_name ?? 'Système' }}</span>
                    </div>
                    <span class="text-[10px] text-slate-400">{{ $log->created_at->format('d/m/Y H:i') }}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>

</div>
@endsection
