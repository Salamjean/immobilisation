@extends('layouts.admin.app')

@section('title', "Campagne {$campagne->code_campagne}")

@section('content')
<div class="space-y-5">

    <!-- En-tête Campagne & Actions -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase font-mono bg-sky-50 text-[#0048FA] border border-sky-200">
                    {{ $campagne->code_campagne }}
                </span>
                <span class="text-xs text-slate-500 font-semibold">
                    {{ $campagne->type_libelle }}
                </span>
            </div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight mt-1">{{ $campagne->intitule }}</h1>
            <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-x-4">
                <span><i class="fa-regular fa-calendar mr-1"></i> Du {{ $campagne->date_debut ? \Carbon\Carbon::parse($campagne->date_debut)->format('d/m/Y') : '—' }} au {{ $campagne->date_fin ? \Carbon\Carbon::parse($campagne->date_fin)->format('d/m/Y') : '—' }}</span>
                <span><i class="fa-solid fa-user-tie mr-1"></i> Superviseur : {{ $campagne->superviseur?->name ?? 'Direction UCP-FM' }}</span>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.inventaires.index') }}" class="px-3.5 py-2 rounded-xl border border-slate-200 hover:bg-slate-50 text-slate-600 text-xs font-bold transition-all">
                &larr; Retour à la liste
            </a>

            @if($campagne->statut !== 'cloturee')
            <form action="{{ route('admin.inventaires.cloturer', $campagne->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous la clôture définitive de cette campagne d\'inventaire ? Les données seront verrouillées.')">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs">
                    <i class="fa-solid fa-lock mr-1.5"></i> Clôturer la Mission
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- 4 Indicateurs de Rapprochement -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Avancement -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Avancement Physique</div>
            <div class="text-2xl font-black text-slate-900 mt-1 font-mono">{{ $tauxProgression }}%</div>
            <div class="text-xs text-slate-500 mt-1">{{ $totalReleves }} relevé(s) / {{ $totalBiensTheoriques }} biens</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-[#0048FA] h-1.5 rounded-full" style="width: {{ min($tauxProgression, 100) }}%"></div>
            </div>
        </div>

        <!-- Conformité -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Taux de Conformité</div>
            <div class="text-2xl font-black text-emerald-600 mt-1 font-mono">{{ $tauxConformite }}%</div>
            <div class="text-xs text-emerald-600 font-semibold mt-1">{{ $totalConformes }} bien(s) conformes</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ min($tauxConformite, 100) }}%"></div>
            </div>
        </div>

        <!-- Écarts -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Écarts Détectés</div>
            <div class="text-2xl font-black text-amber-600 mt-1 font-mono">{{ $totalEcarts }}</div>
            <div class="text-xs text-amber-600 font-semibold mt-1">Déplacés, abîmés ou modifiés</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $totalEcarts > 0 ? 100 : 0 }}%"></div>
            </div>
        </div>

        <!-- Non Retrouvés -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs">
            <div class="text-[11px] font-bold text-slate-400 uppercase tracking-wider">Biens Non Retrouvés</div>
            <div class="text-2xl font-black text-rose-600 mt-1 font-mono">{{ $totalNonRetrouves }}</div>
            <div class="text-xs text-rose-600 font-semibold mt-1">À investiguer / rechercher</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $totalNonRetrouves > 0 ? 100 : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Équipe Terrain & Agents Assignés (Tableau avec défilement interne) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white">
            <div class="flex items-center space-x-2.5">
                <div style="background-color: #0048FA;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-users"></i>
                </div>
                <div>
                    <h3 class="text-sm font-bold text-slate-900">Équipe Terrain & Agents Assignés</h3>
                    <p class="text-xs text-slate-400">Agents recenseurs habilités et affectés à cette mission d'inventaire</p>
                </div>
            </div>
            <div class="flex items-center space-x-2">
                <span class="inline-flex items-center px-2.5 py-1 rounded-xl text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <i class="fa-solid fa-user-check text-[10px] mr-1.5"></i>
                    {{ $campagne->equipes->count() }} agent(s) assigné(s)
                </span>
            </div>
        </div>

        <div class="overflow-x-auto max-h-72 overflow-y-auto divide-y divide-slate-100">
            <table class="w-full text-left border-collapse text-xs">
                <thead class="sticky top-0 z-10 bg-slate-50 border-b border-slate-200 text-[11px] font-bold text-slate-500 uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Agent Recenseur</th>
                        <th class="py-3 px-4">Contact</th>
                        <th class="py-3 px-4">Rôle Système</th>
                        <th class="py-3 px-4">Structure d'Origine</th>
                        <th class="py-3 px-4 text-center">Relevés Effectués</th>
                        <th class="py-3 px-4 text-center">Statut Mission</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 bg-white">
                    @forelse($campagne->equipes as $equipe)
                        @php
                            $relevesAgentCount = $campagne->releves->where('agent_user_id', $equipe->user_id)->count();
                            $user = $equipe->user;
                        @endphp
                        <tr class="hover:bg-slate-50/80 transition-colors">
                            <td class="py-3 px-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-8 h-8 rounded-xl bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-xs flex-shrink-0">
                                        {{ strtoupper(substr($user?->name ?? 'A', 0, 2)) }}
                                    </div>
                                    <div>
                                        <div class="font-bold text-slate-900 text-xs">
                                            {{ $user?->nom_complet ?: $user?->name ?? 'Agent non spécifié' }}
                                        </div>
                                        <div class="text-[11px] text-slate-400">
                                            {{ $user?->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 px-4 text-slate-600">
                                @if($user?->telephone)
                                    <span class="inline-flex items-center space-x-1.5 font-mono text-[11px]">
                                        <i class="fa-solid fa-phone text-[9px] text-slate-400"></i>
                                        <span>{{ $user->telephone }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 text-[11px]">—</span>
                                @endif
                            </td>
                            <td class="py-3 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-50 text-purple-700 border border-purple-200">
                                    {{ $user?->role_libelle ?? 'Agent inventaire' }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600 text-xs">
                                @if($user?->structure)
                                    <span class="inline-flex items-center space-x-1 text-slate-700">
                                        <i class="fa-solid fa-hospital text-[10px] text-emerald-600"></i>
                                        <span>{{ $user->structure->nom }}</span>
                                    </span>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Non rattaché</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold font-mono {{ $relevesAgentCount > 0 ? 'bg-emerald-50 text-[#0048FA] border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
                                    <i class="fa-solid fa-barcode text-[10px] mr-1.5"></i>
                                    {{ $relevesAgentCount }} relevé(s)
                                </span>
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1.5"></span>
                                    Actif sur le terrain
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-xs text-slate-400">
                                <i class="fa-solid fa-user-xmark mr-1.5 text-base text-slate-300 block mb-1"></i>
                                Aucun agent d'inventaire n'est encore assigné à cette campagne.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tableau des Relevés Terrain -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-list-check text-[#0048FA]"></i>
                <span>Relevés Terrain & Rapprochement Physique</span>
            </h3>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4 text-left">Immobilisation</th>
                        <th class="py-3 px-4 text-center">Code</th>
                        <th class="py-3 px-4 text-center">Site Théorique &rarr; Constaté</th>
                        <th class="py-3 px-4 text-center">Agent Terrain</th>
                        <th class="py-3 px-4 text-center">Rapprochement</th>
                        <th class="py-3 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($campagne->releves as $rel)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 text-left">
                                <div class="font-bold text-slate-900">{{ $rel->immobilisation?->designation }}</div>
                            </td>
                            <td class="py-3 px-4 text-center font-mono font-bold text-slate-800">
                                {{ $rel->immobilisation?->code_immobilisation }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                <span class="text-slate-500">{{ $rel->immobilisation?->structureBeneficiaire?->nom ?? 'Siège' }}</span>
                                <i class="fa-solid fa-arrow-right text-[10px] text-slate-400 mx-1"></i>
                                <span class="font-bold text-slate-800">{{ $rel->structureConstatee?->nom ?? 'Même site' }}</span>
                            </td>
                            <td class="py-3 px-4 text-center text-slate-700">
                                {{ $rel->agent?->nom_complet ?? $rel->agent?->name ?? 'Agent Terrain' }}
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($rel->statut_rapprochement === 'conforme')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Conforme
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        {{ ucfirst(str_replace('_', ' ', $rel->statut_rapprochement)) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-center">
                                @if($rel->statut_rapprochement !== 'conforme' && $rel->statut_rapprochement !== 'regularise')
                                    <form action="{{ route('admin.inventaires.releve.valider', $rel->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="decision" value="ajuster">
                                        <button type="submit" class="px-2.5 py-1 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-600 hover:text-white text-[11px] font-bold border border-emerald-200 transition-all">
                                            Régulariser
                                        </button>
                                    </form>
                                @else
                                    <span class="text-slate-400 text-[11px]">Validé</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-8 text-center text-slate-400">Aucun relevé physique n'a encore été transmis pour cette campagne.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
