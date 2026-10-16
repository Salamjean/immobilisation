@extends('layouts.app')

@section('title', "Piste d'Audit & Sécurité")

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <div style="background-color: #155E3B;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                <i class="fa-solid fa-clock-rotate-left"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Journal d'Audit & Traçabilité Inviolable</h1>
                <p class="text-xs text-slate-500 mt-0.5">Piste d'audit non modifiable enregistrant chaque création, modification, transfert et arbitrage avec horodatage</p>
            </div>
        </div>

        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-800 border border-emerald-200 self-start sm:self-auto">
            <i class="fa-solid fa-shield-halved mr-1.5 text-emerald-600"></i> Conformité TDR Art. 21
        </span>
    </div>

    <!-- Tableau des Logs d'Audit -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50/80 text-[11px] font-bold text-slate-400 uppercase tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3.5 px-4">Date & Heure</th>
                        <th class="py-3.5 px-4">Utilisateur / Auteur</th>
                        <th class="py-3.5 px-4">Action Réalisée</th>
                        <th class="py-3.5 px-4">Module</th>
                        <th class="py-3.5 px-4">Référence</th>
                        <th class="py-3.5 px-4">Adresse IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                    @forelse($logs as $l)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3.5 px-4 font-bold text-slate-800">
                            {{ $l->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-700 font-sans">
                            <div class="flex items-center space-x-1.5">
                                <i class="fa-solid fa-user-circle text-slate-400 text-xs"></i>
                                <strong>{{ $l->user_name ?? ($l->user?->name ?? 'Système Automatique') }}</strong>
                            </div>
                        </td>
                        <td class="py-3.5 px-4">
                            <span class="px-2.5 py-1 rounded-lg text-[10px] font-bold bg-sky-50 text-[#155E3B] border border-sky-100 font-mono">
                                {{ $l->action }}
                            </span>
                        </td>
                        <td class="py-3.5 px-4 font-sans font-semibold text-slate-800">
                            {{ $l->module }}
                        </td>
                        <td class="py-3.5 px-4 font-bold text-emerald-800">
                            {{ $l->code_reference ?? '-' }}
                        </td>
                        <td class="py-3.5 px-4 text-slate-400">
                            {{ $l->ip_address }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-400 font-sans">
                            <i class="fa-solid fa-shield-halved text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs">Aucun événement d'audit journalisé.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
        <div class="p-4 border-t border-slate-100 bg-slate-50/50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
