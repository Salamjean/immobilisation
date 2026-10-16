@extends('layouts.app')

@section('title', "Piste d'Audit & Sécurité")
@section('header_title', "Journal d'Audit & Traçabilité Inviolable")

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-800 border border-blue-200">
        Conformité TDR Art. 21
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <p class="text-xs text-slate-500 max-w-2xl">
            Piste d'audit non modifiable enregistrant chaque création, modification, scan terrain, transfert et arbitrage avec horodatage, utilisateur, adresse IP et valeurs différentielles.
        </p>
    </div>

    <!-- Tableau des Logs d'Audit -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Date & Heure</th>
                        <th class="py-3 px-4">Utilisateur / Auteur</th>
                        <th class="py-3 px-4">Action Réalisée</th>
                        <th class="py-3 px-4">Module</th>
                        <th class="py-3 px-4">Référence</th>
                        <th class="py-3 px-4">Adresse IP</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 font-mono text-[11px]">
                    @forelse($logs as $l)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-800">
                            {{ $l->created_at->format('d/m/Y H:i:s') }}
                        </td>
                        <td class="py-3 px-4 text-slate-700 font-sans">
                            <i class="fa-solid fa-user-circle text-slate-400 mr-1"></i>
                            <strong>{{ $l->user_name ?? ($l->user?->name ?? 'Système Automatique') }}</strong>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-blue-50 text-blue-800 border border-blue-200">
                                {{ $l->action }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-sans font-semibold text-slate-800">
                            {{ $l->module }}
                        </td>
                        <td class="py-3 px-4 font-bold text-brand-700">
                            {{ $l->code_reference ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-slate-400">
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
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $logs->links() }}
        </div>
        @endif
    </div>

</div>
@endsection
