@extends('layouts.admin.app')

@section('title', 'Administration Générale')

@section('content')
<div class="space-y-5">

    <!-- En-tête de bienvenue Admin -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3.5">
            <div style="background-color: #0048FA;" class="w-12 h-12 rounded-2xl text-white flex items-center justify-center text-xl shadow-md shadow-[#0048FA]/25 flex-shrink-0">
                <i class="fa-solid fa-gauge-high"></i>
            </div>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Panneau d'Administration & Pilotage</h1>
                <p class="text-xs text-slate-500 mt-0.5">Supervision globale, gestion des référentiels, habilitations et sécurité</p>
            </div>
        </div>

        <div class="flex items-center space-x-2.5">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl text-xs font-bold text-white shadow-xs" style="background-color: #0048FA;">
                <i class="fa-solid fa-shield-check mr-1.5"></i> Administrateur Système
            </span>
        </div>
    </div>

    <!-- KPIs Administration -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- KPI 1 : Utilisateurs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-[#0048FA]/50 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Utilisateurs Actifs</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalUsers }}</div>
                    <div class="text-xs font-semibold mt-1" style="color: #0048FA;">Comptes habilités</div>
                </div>
                <div style="background-color: #0048FA;" class="w-11 h-11 rounded-xl text-white flex items-center justify-center text-lg font-bold shadow-xs">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
            </div>
        </div>

        <!-- KPI 2 : Total Actifs -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-emerald-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Total Immobilisations</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalActifs }}</div>
                    <div class="text-xs text-emerald-600 font-semibold mt-1 font-mono">{{ number_format($valeurTotale, 0, ',', ' ') }} FCFA</div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-emerald-500 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
        </div>

        <!-- KPI 3 : Structures Sanitaires -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-indigo-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Structures Sanitaires</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalStructures }}</div>
                    <div class="text-xs text-indigo-600 font-semibold mt-1">Sites déployés en CI</div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-indigo-500 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                    <i class="fa-solid fa-hospital"></i>
                </div>
            </div>
        </div>

        <!-- KPI 4 : Piste d'Audit -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-amber-300 transition-all">
            <div class="flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Événements Journalisés</div>
                    <div class="text-2xl font-black text-slate-900 mt-1">{{ $totalLogs }}</div>
                    <div class="text-xs text-amber-600 font-semibold mt-1">Piste d'audit inviolable</div>
                </div>
                <div class="w-11 h-11 rounded-xl bg-amber-500 text-white flex items-center justify-center text-lg font-bold shadow-xs">
                    <i class="fa-solid fa-shield-halved"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Actions Rapides & Raccourcis Principaux -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-5">
        <!-- Carte 1 : Utilisateurs -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <div style="background-color: #0048FA;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base font-bold mb-3 shadow-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Utilisateurs & Droits d'Accès</h3>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Création de comptes, affectation des rôles et contrôle d'accès multi-profils.</p>
            </div>
            <div class="pt-5 flex items-center space-x-2">
                <a href="{{ route('admin.users.index') }}" class="flex-1 py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold text-center transition-all">
                    Liste
                </a>
                <a href="{{ route('admin.users.create') }}" style="background-color: #0048FA;" class="flex-1 py-2 px-3 rounded-xl text-white text-xs font-bold text-center shadow-xs hover:opacity-95 transition-all">
                    + Ajouter
                </a>
            </div>
        </div>

        <!-- Carte 2 : Structures -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <div class="w-10 h-10 rounded-xl bg-indigo-500 text-white flex items-center justify-center text-base font-bold mb-3 shadow-xs">
                    <i class="fa-solid fa-hospital"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Structures Sanitaires & Bénéficiaires</h3>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Gestion des établissements, hôpitaux, centres régionaux et directions.</p>
            </div>
            <div class="pt-5 flex items-center space-x-2">
                <a href="{{ route('admin.structures.index') }}" class="flex-1 py-2 px-3 rounded-xl border border-slate-200 hover:bg-slate-100 text-slate-700 text-xs font-bold text-center transition-all">
                    Liste
                </a>
                <a href="{{ route('admin.structures.create') }}" style="background-color: #0048FA;" class="flex-1 py-2 px-3 rounded-xl text-white text-xs font-bold text-center shadow-xs hover:opacity-95 transition-all">
                    + Ajouter
                </a>
            </div>
        </div>

        <!-- Carte 3 : Piste d'audit -->
        <div class="p-6 rounded-2xl bg-white border border-slate-200/90 shadow-xs flex flex-col justify-between hover:shadow-md transition-all">
            <div>
                <div class="w-10 h-10 rounded-xl bg-amber-500 text-white flex items-center justify-center text-base font-bold mb-3 shadow-xs">
                    <i class="fa-solid fa-fingerprint"></i>
                </div>
                <h3 class="text-sm font-bold text-slate-900">Piste d'Audit & Journalisation</h3>
                <p class="text-xs text-slate-500 mt-1.5 leading-relaxed">Traçabilité complète des opérations, modifications et scans terrain en temps réel.</p>
            </div>
            <div class="pt-5">
                <a href="{{ route('admin.audit.index') }}" class="w-full block py-2 px-3 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold text-center transition-all">
                    Consulter le Journal d'Audit &rarr;
                </a>
            </div>
        </div>
    </div>

    <!-- Derniers Événements d'Audit -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900">Dernières Activités Système & Sécurité</h3>
            <a href="{{ route('admin.audit.index') }}" style="color: #0048FA;" class="text-xs font-bold hover:underline">Voir tout le journal &rarr;</a>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4 text-center font-mono">Date</th>
                        <th class="py-3 px-4 text-left font-sans">Auteur</th>
                        <th class="py-3 px-4 text-center">Action</th>
                        <th class="py-3 px-4 text-center font-sans">Module</th>
                        <th class="py-3 px-4 text-center font-mono">Référence</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($derniersLogs as $l)
                    <tr class="hover:bg-slate-50/70 transition-colors">
                        <td class="py-3 px-4 font-mono font-bold text-slate-700 text-center">{{ $l->created_at->format('d/m/Y H:i:s') }}</td>
                        <td class="py-3 px-4 text-left">
                            <div class="font-bold text-slate-900">{{ $l->user_name ?? 'Système' }}</div>
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[11px] font-bold bg-sky-50 text-[#0048FA] border border-sky-100">
                                {{ $l->action }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-semibold text-slate-700">{{ $l->module }}</td>
                        <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
                            <span class="px-2 py-0.5 rounded bg-slate-100 border border-slate-200 text-xs">
                                {{ $l->code_reference ?? '-' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-8 text-center text-slate-400">Aucun journal d'audit récent.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
