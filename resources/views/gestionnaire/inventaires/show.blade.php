@extends('layouts.app')

@section('title', "Campagne {$campagne->code_campagne}")
@section('header_title', "Suivi & Rapprochement d'Inventaire")

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-purple-100 text-purple-900 border border-purple-300">
        {{ $campagne->code_campagne }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête Campagne & Actions -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $campagne->statut_badge_class }}">
                    {{ ucfirst($campagne->statut) }}
                </span>
                <span class="text-xs text-slate-500 font-medium">
                    {{ $campagne->type_libelle }}
                </span>
            </div>
            <h1 class="text-xl font-extrabold text-slate-900 mt-1">{{ $campagne->intitule }}</h1>
            <div class="text-xs text-slate-500 mt-1 flex flex-wrap items-center gap-x-4">
                <span><i class="fa-regular fa-calendar mr-1"></i> Du {{ $campagne->date_debut->format('d/m/Y') }} au {{ $campagne->date_fin->format('d/m/Y') }}</span>
                <span><i class="fa-solid fa-user-tie mr-1"></i> Superviseur: {{ $campagne->superviseur?->name ?? 'Direction' }}</span>
            </div>
        </div>

        <div class="flex items-center space-x-3">
            <a href="{{ route('mobile.index') }}" target="_blank" class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold shadow-md shadow-purple-500/20 transition-all flex items-center space-x-2">
                <i class="fa-solid fa-qrcode"></i>
                <span>Ouvrir Scanner Mobile</span>
            </a>
            @if($campagne->statut !== 'cloturee')
            <form action="{{ route('inventaires.cloturer', $campagne->id) }}" method="POST" onsubmit="return confirm('Confirmez-vous la clôture définitive de cette campagne d\'inventaire ? Les données seront verrouillées.')">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all">
                    <i class="fa-solid fa-lock mr-1.5"></i> Clôturer la Campagne
                </button>
            </form>
            @endif
        </div>
    </div>

    <!-- Indicateurs de Rapprochement -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase">Taux d'Avancement Physique</div>
            <div class="text-2xl font-extrabold text-slate-900 mt-1 font-mono">{{ $tauxProgression }}%</div>
            <div class="text-xs text-slate-500 mt-1">{{ $totalReleves }} relevé(s) / {{ $totalBiensTheoriques }} biens théoriques</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-purple-600 h-1.5 rounded-full" style="width: {{ $tauxProgression }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase">Taux de Conformité</div>
            <div class="text-2xl font-extrabold text-emerald-600 mt-1 font-mono">{{ $tauxConformite }}%</div>
            <div class="text-xs text-emerald-600 font-medium mt-1">{{ $totalConformes }} bien(s) strictement conformes</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-emerald-500 h-1.5 rounded-full" style="width: {{ $tauxConformite }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase">Écarts Constatés</div>
            <div class="text-2xl font-extrabold text-amber-600 mt-1 font-mono">{{ $totalEcarts }}</div>
            <div class="text-xs text-amber-600 font-medium mt-1">Localisation, état ou détenteur modifiés</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-amber-500 h-1.5 rounded-full" style="width: {{ $totalEcarts > 0 ? 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-500 uppercase">Biens Non Retrouvés</div>
            <div class="text-2xl font-extrabold text-rose-600 mt-1 font-mono">{{ $totalNonRetrouves }}</div>
            <div class="text-xs text-rose-600 font-medium mt-1">À investiguer / Recherche d'actifs</div>
            <div class="w-full bg-slate-100 rounded-full h-1.5 mt-3">
                <div class="bg-rose-500 h-1.5 rounded-full" style="width: {{ $totalNonRetrouves > 0 ? 100 : 0 }}%"></div>
            </div>
        </div>
    </div>

    <!-- Équipe Terrain & Agents Assignés (Tableau avec défilement interne) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white">
            <div class="flex items-center space-x-2.5">
                <div style="background-color: #155E3B;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
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
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold font-mono {{ $relevesAgentCount > 0 ? 'bg-emerald-50 text-[#155E3B] border border-emerald-200' : 'bg-slate-100 text-slate-500' }}">
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
                                Aucun agent n'est actuellement assigné à cette campagne.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Tableau de Rapprochement & Traitement des Écarts (Section 7, 21, F33, F34) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-5 border-b border-slate-200 flex items-center justify-between">
            <div>
                <h3 class="text-sm font-bold text-slate-900">Tableau de Rapprochement & Analyse des Écarts</h3>
                <div class="text-xs text-slate-500">Confrontation Base Théorique &harr; Inventaire Physique &harr; Localisation GPS</div>
            </div>
            <span class="text-xs font-mono text-slate-400 font-semibold">{{ $campagne->releves->count() }} relevé(s) au total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs text-slate-600">
                <thead class="bg-slate-50 text-slate-700 uppercase font-bold border-b border-slate-200">
                    <tr>
                        <th class="py-3 px-4">Code Scanné / Actif</th>
                        <th class="py-3 px-4">Désignation</th>
                        <th class="py-3 px-4">Données Théoriques</th>
                        <th class="py-3 px-4">Constatations Terrain (GPS / Agent)</th>
                        <th class="py-3 px-4 text-center">Diagnostic d'Écart</th>
                        <th class="py-3 px-4 text-center">Décision / Régularisation</th>
                        <th class="py-3 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($campagne->releves as $rel)
                    <tr class="hover:bg-slate-50/80 transition-colors">
                        <td class="py-3 px-4 font-mono">
                            <span class="font-bold text-brand-700">{{ $rel->code_scanne }}</span>
                            <div class="text-[10px] text-slate-400 mt-0.5">
                                {{ $rel->date_heure_scan ? $rel->date_heure_scan->format('d/m/Y H:i') : '' }}
                            </div>
                        </td>

                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 max-w-xs truncate">{{ $rel->immobilisation?->designation ?? 'Actif non répertorié initialement' }}</div>
                            <div class="text-[11px] text-slate-500">{{ $rel->immobilisation?->marque }} {{ $rel->immobilisation?->modele }}</div>
                        </td>

                        <td class="py-3 px-4 text-[11px]">
                            @if($rel->immobilisation)
                                <div><strong class="text-slate-700">Site:</strong> {{ $rel->immobilisation->structureBeneficiaire?->nom }}</div>
                                <div><strong class="text-slate-700">Détenteur:</strong> {{ $rel->immobilisation->detenteur?->nom_complet ?? 'N/A' }}</div>
                                <div><strong class="text-slate-700">État:</strong> {{ $rel->immobilisation->etat_libelle }}</div>
                            @else
                                <span class="text-slate-400 italic">Aucune donnée théorique</span>
                            @endif
                        </td>

                        <td class="py-3 px-4 text-[11px]">
                            <div><strong class="text-slate-700">État:</strong> <span class="font-semibold text-slate-900">{{ ucfirst(str_replace('_', ' ', $rel->etat_constate)) }}</span></div>
                            <div><strong class="text-slate-700">GPS:</strong> <span class="font-mono text-slate-600">{{ $rel->latitude ? number_format($rel->latitude, 4).', '.number_format($rel->longitude, 4) : 'Non capturé' }}</span></div>
                            <div class="text-slate-500">Agent: {{ $rel->agent?->name ?? 'Terrain' }}</div>
                        </td>

                        <td class="py-3 px-4 text-center">
                            <span class="inline-block px-2.5 py-1 rounded-full text-[10px] font-bold {{ $rel->statut_rapprochement_badge_class }}">
                                {{ $rel->statut_rapprochement_libelle }}
                            </span>
                            @if($rel->analyse_ecart)
                            <div class="text-[10px] text-slate-500 mt-1 max-w-xs truncate" title="{{ $rel->analyse_ecart }}">
                                {{ $rel->analyse_ecart }}
                            </div>
                            @endif
                        </td>

                        <td class="py-3 px-4 text-center">
                            @if($rel->decision_regularisation)
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">
                                    {{ ucfirst($rel->decision_regularisation) }}
                                </span>
                            @else
                                <span class="text-[10px] text-amber-600 font-semibold">En attente d'arbitrage</span>
                            @endif
                        </td>

                        <td class="py-3 px-4 text-right">
                            @if($campagne->statut !== 'cloturee')
                            <!-- Formulaire de validation rapide de régularisation -->
                            <form action="{{ route('inventaires.releve.valider', $rel->id) }}" method="POST" class="inline-block">
                                @csrf
                                <input type="hidden" name="decision_regularisation" value="regularise">
                                <button type="submit" title="Régulariser automatiquement la fiche patrimoniale" class="px-2.5 py-1 rounded-md bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-bold transition-colors">
                                    <i class="fa-solid fa-check mr-1"></i> Régulariser
                                </button>
                            </form>
                            @else
                            <span class="text-slate-400 text-[10px] italic">Verrouillé</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-400">
                            <i class="fa-solid fa-clipboard-check text-3xl mb-2 text-slate-300"></i>
                            <p class="text-xs">Aucun relevé physique n'a encore été transmis pour cette campagne.</p>
                            <p class="text-[11px] text-purple-600 mt-1">Utilisez l'application mobile pour scanner les biens sur le terrain.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
