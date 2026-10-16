@extends('layouts.app')

@section('title', 'Maintenance & SAV - Espace Gestionnaire')

@section('content')
<div class="space-y-6">

    <!-- En-tête de la page -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div>
            <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                <i class="fa-solid fa-wrench text-[#155E3B]"></i>
                <span>Maintenance & Service Après-Vente (SAV)</span>
            </h1>
            <p class="text-xs text-slate-500 mt-0.5">Suivi curatif, préventif, réparations biomédicales et gestion des coûts de remise en état</p>
        </div>

        <a href="{{ route('gestionnaire.maintenances.create') }}"
           style="background-color: #155E3B;"
           class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all self-start sm:self-auto">
            <i class="fa-solid fa-plus text-xs"></i>
            <span>Déclarer une Panne / SAV</span>
        </a>
    </div>

    <!-- 4 Cartes Statistiques Métier -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- En cours -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center text-lg font-bold border border-amber-100 shadow-xs">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">En Cours / Signalées</span>
                <span class="text-xl font-bold text-slate-900 font-mono">{{ $totalEnCours }}</span>
            </div>
        </div>

        <!-- Coût Total Réparations -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div style="background-color: #155E3B;" class="w-12 h-12 rounded-xl text-white flex items-center justify-center text-lg font-bold shadow-xs">
                <i class="fa-solid fa-coins"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Dépenses Cumulées</span>
                <span class="text-lg font-bold text-slate-900 font-mono">{{ number_format($coutTotal ?? 0, 0, ',', ' ') }} <span class="text-xs font-sans text-slate-400 font-normal">FCFA</span></span>
            </div>
        </div>

        <!-- Total Fiches -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-slate-50 text-slate-600 flex items-center justify-center text-lg font-bold border border-slate-200 shadow-xs">
                <i class="fa-solid fa-clipboard-check"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Total Interventions</span>
                <span class="text-xl font-bold text-slate-900 font-mono">{{ $maintenances->total() }}</span>
            </div>
        </div>

        <!-- Taux de Résolution -->
        <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs flex items-center space-x-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center text-lg font-bold border border-emerald-100 shadow-xs">
                <i class="fa-solid fa-shield-heart"></i>
            </div>
            <div>
                <span class="text-xs font-semibold text-slate-400 block">Statut Parc</span>
                <span class="text-xs font-bold text-emerald-700 bg-emerald-50 px-2 py-0.5 rounded-md inline-block mt-0.5 border border-emerald-200">
                    Maintenance active
                </span>
            </div>
        </div>
    </div>

    <!-- Alertes Flash -->
    @if(session('success'))
        <div class="p-4 rounded-2xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-xs flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <i class="fa-solid fa-circle-check text-emerald-600 text-base"></i>
                <span class="font-semibold">{{ session('success') }}</span>
            </div>
            <button type="button" onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
    @endif

    <!-- Filtres & Recherche Structurée -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('gestionnaire.maintenances.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3 items-center">
            <!-- Recherche Mot-clé (5 cols) -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher par n° fiche, équipement, prestataire..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
            </div>

            <!-- Filtre Statut (3 cols) -->
            <div class="sm:col-span-3">
                <select name="statut" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous les Statuts</option>
                    <option value="signalee" {{ request('statut') === 'signalee' ? 'selected' : '' }}>Signalée</option>
                    <option value="en_cours" {{ request('statut') === 'en_cours' ? 'selected' : '' }}>En cours de diagnostic</option>
                    <option value="terminee" {{ request('statut') === 'terminee' ? 'selected' : '' }}>Terminée / Réparée</option>
                    <option value="annulee" {{ request('statut') === 'annulee' ? 'selected' : '' }}>Annulée</option>
                </select>
            </div>

            <!-- Filtre Type (2 cols) -->
            <div class="sm:col-span-2">
                <select name="type_maintenance" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous Types</option>
                    <option value="corrective" {{ request('type_maintenance') === 'corrective' ? 'selected' : '' }}>Corrective</option>
                    <option value="preventive" {{ request('type_maintenance') === 'preventive' ? 'selected' : '' }}>Préventive</option>
                    <option value="calibration" {{ request('type_maintenance') === 'calibration' ? 'selected' : '' }}>Étalonnage</option>
                    <option value="controle_technique" {{ request('type_maintenance') === 'controle_technique' ? 'selected' : '' }}>Contrôle</option>
                </select>
            </div>

            <!-- Boutons d'Action (2 cols) -->
            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit"
                        style="background-color: #155E3B;"
                        class="flex-1 py-2.5 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all flex items-center justify-center space-x-1.5">
                    <i class="fa-solid fa-filter text-[11px]"></i>
                    <span>Filtrer</span>
                </button>

                @if(request()->hasAny(['search', 'statut', 'type_maintenance']))
                    <a href="{{ route('gestionnaire.maintenances.index') }}"
                       class="px-3 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-600 text-xs font-semibold transition-all flex items-center justify-center"
                       title="Réinitialiser">
                        <i class="fa-solid fa-rotate-left"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau Moderne des Maintenances -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-center">N° Fiche</th>
                        <th class="py-3.5 px-4">Immobilisation & Localisation</th>
                        <th class="py-3.5 px-4">Type Intervention</th>
                        <th class="py-3.5 px-4">Prestataire & Diagnostic</th>
                        <th class="py-3.5 px-4">Date Signalée</th>
                        <th class="py-3.5 px-4 text-right">Coût (FCFA)</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($maintenances as $m)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3.5 px-4 text-center font-mono font-bold">
                                <span class="px-2.5 py-1 rounded-lg text-xs bg-rose-50 text-rose-800 border border-rose-100">
                                    {{ $m->numero_fiche }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-900">{{ $m->immobilisation?->designation ?? 'Équipement non spécifié' }}</div>
                                <div class="text-[11px] text-slate-500 flex items-center space-x-1.5 mt-0.5">
                                    <span class="font-mono text-slate-400">{{ $m->immobilisation?->code_immobilisation }}</span>
                                    <span>•</span>
                                    <span class="text-slate-600">{{ $m->immobilisation?->structureBeneficiaire?->nom ?? 'Site non défini' }}</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4">
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-semibold bg-slate-100 text-slate-700">
                                    {{ $m->type_libelle }}
                                </span>
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="font-bold text-slate-800">{{ $m->prestataire ?? 'Équipe Technique Interne' }}</div>
                                <div class="text-[11px] text-slate-400 truncate max-w-xs" title="{{ $m->diagnostic ?? $m->description_panne }}">
                                    {{ $m->diagnostic ?? $m->description_panne ?? 'Aucun détail technique renseigné' }}
                                </div>
                            </td>
                            <td class="py-3.5 px-4 font-mono text-slate-600">
                                {{ $m->date_signalement ? $m->date_signalement->format('d/m/Y') : '—' }}
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-slate-900">
                                {{ number_format($m->cout_intervention, 0, ',', ' ') }}
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($m->statut === 'terminee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-emerald-100 text-emerald-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5"></span>
                                        Terminée
                                    </span>
                                @elseif($m->statut === 'en_cours')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-amber-100 text-amber-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 mr-1.5 animate-pulse"></span>
                                        En cours
                                    </span>
                                @elseif($m->statut === 'signalee')
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-rose-100 text-rose-800">
                                        <span class="w-1.5 h-1.5 rounded-full bg-rose-500 mr-1.5 animate-ping"></span>
                                        Signalée
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[11px] font-semibold bg-slate-100 text-slate-600">
                                        {{ ucfirst($m->statut) }}
                                    </span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right">
                                <a href="{{ route('gestionnaire.maintenances.show', $m->id) }}"
                                   class="inline-flex items-center px-3 py-1.5 rounded-lg border border-slate-200 text-slate-700 hover:text-slate-900 hover:bg-slate-50 text-xs font-semibold transition-all">
                                    <i class="fa-solid fa-eye text-[#155E3B] mr-1.5"></i>
                                    <span>Consulter</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="py-12 text-center text-slate-400">
                                <i class="fa-solid fa-screwdriver-wrench text-3xl mb-2 block opacity-40"></i>
                                <span>Aucune intervention ou fiche de maintenance enregistrée.</span>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($maintenances->hasPages())
            <div class="p-4 border-t border-slate-100">
                {{ $maintenances->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
