@extends('layouts.app')

@section('title', 'Tableau de Bord Gestionnaire')
@section('header_title', 'Tableau de Bord Opérationnel du Patrimoine')

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800 border border-emerald-200">
        Supervision Globale UCP-FM
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- 1 & 2. Grille 6 et 6 : Espace Gestionnaire & Campagne d'Inventaire Active -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5 items-stretch">
        
        <!-- Carte Gauche (6 / 12) : Accueil & Actions Rapides -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-xs flex flex-col justify-between space-y-4">
            <div class="space-y-2">
                <div class="flex items-center space-x-2 text-xs">
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg bg-emerald-50 text-[#0048FA] font-bold border border-emerald-200">
                        <i class="fa-solid fa-shield-halved mr-1.5 text-xs"></i> Espace Gestionnaire
                    </span>
                    <span class="text-slate-400">•</span>
                    <span class="text-slate-500 font-medium capitalize">{{ \Carbon\Carbon::now()->locale('fr')->isoFormat('dddd DD MMMM YYYY') }}</span>
                </div>
                <h1 class="text-lg sm:text-xl font-bold text-slate-900 tracking-tight">
                    Supervision & Gestion du Patrimoine
                </h1>
                <p class="text-xs text-slate-500 font-normal leading-relaxed">
                    Pilotez l'ensemble du parc d'immobilisations, suivez les transferts inter-sites et assurez la conformité patrimoniale UCP-FM.
                </p>
            </div>

            <!-- Raccourcis Rapides -->
            <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-100">
                <a href="{{ route('gestionnaire.immobilisations.create') }}"
                   style="background-color: #0048FA;"
                   class="px-3.5 py-2 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Nouvelle Immobilisation</span>
                </a>
                <a href="{{ route('gestionnaire.mouvements.create') }}"
                   class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-right-arrow-left text-xs text-slate-500"></i>
                    <span>Transférer</span>
                </a>
                <a href="{{ route('gestionnaire.maintenances.create') }}"
                   class="px-3 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-1.5">
                    <i class="fa-solid fa-wrench text-xs text-slate-500"></i>
                    <span>Déclarer Panne</span>
                </a>
            </div>
        </div>

        <!-- Carte Droite (6 / 12) : Campagne d'Inventaire Active ou Synthèse -->
        @if($campagneEnCours)
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-purple-200 shadow-xs flex flex-col justify-between space-y-4">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                        <span class="px-2.5 py-0.5 rounded-lg bg-purple-100 text-purple-800 font-bold text-[10px] uppercase tracking-wider">
                            Campagne Active
                        </span>
                        <span class="font-mono text-xs font-bold text-slate-800">{{ $campagneEnCours->code_campagne }}</span>
                    </div>
                    <div class="w-8 h-8 rounded-lg bg-purple-50 text-purple-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-satellite-dish"></i>
                    </div>
                </div>

                <h2 class="text-base font-bold text-slate-900">{{ $campagneEnCours->intitule }}</h2>

                <div class="grid grid-cols-2 gap-2 text-xs pt-1">
                    <div class="p-2 rounded-lg bg-slate-50 border border-slate-100">
                        <span class="text-[10px] text-slate-400 block uppercase">Période</span>
                        <span class="font-semibold text-slate-700 text-[11px]">
                            {{ $campagneEnCours->date_debut ? \Carbon\Carbon::parse($campagneEnCours->date_debut)->format('d/m/Y') : '-' }} au {{ $campagneEnCours->date_fin ? \Carbon\Carbon::parse($campagneEnCours->date_fin)->format('d/m/Y') : '-' }}
                        </span>
                    </div>
                    <div class="p-2 rounded-lg bg-purple-50/50 border border-purple-100">
                        <span class="text-[10px] text-purple-600 block uppercase">Mobilisation</span>
                        <span class="font-bold text-purple-900 text-[11px]">
                            {{ $campagneEnCours->equipes_count }} équipe(s) • {{ $campagneEnCours->releves_count }} relevé(s)
                        </span>
                    </div>
                </div>
            </div>

            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">Recensement terrain en cours</span>
                <a href="{{ route('gestionnaire.inventaires.show', $campagneEnCours->id) }}"
                   class="px-4 py-2 rounded-xl bg-purple-600 hover:bg-purple-700 text-white text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                    <span>Consulter l'avancement</span>
                    <i class="fa-solid fa-arrow-right text-[10px]"></i>
                </a>
            </div>
        </div>
        @else
        <!-- Si aucune campagne active, afficher une carte de veille patrimoniale -->
        <div class="bg-white rounded-2xl p-5 sm:p-6 border border-slate-200/90 shadow-xs flex flex-col justify-between space-y-4">
            <div class="space-y-2">
                <div class="flex items-center justify-between">
                    <span class="px-2.5 py-0.5 rounded-lg bg-slate-100 text-slate-700 font-bold text-[10px] uppercase tracking-wider">
                        Inventaire Physique
                    </span>
                    <div class="w-8 h-8 rounded-lg bg-slate-100 text-slate-500 flex items-center justify-center text-sm">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                </div>
                <h2 class="text-base font-bold text-slate-900">Aucune Campagne d'Inventaire en Cours</h2>
                <p class="text-xs text-slate-500 font-normal">
                    Toutes les campagnes précédentes ont été clôturées ou sont en préparation. Vous pouvez programmer une nouvelle campagne nationale ou régionale.
                </p>
            </div>
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between">
                <span class="text-[11px] text-slate-400">{{ $totalSites }} sites répertoriés</span>
                <a href="{{ route('gestionnaire.inventaires.create') }}"
                   class="px-4 py-2 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all shadow-xs flex items-center space-x-1.5">
                    <i class="fa-solid fa-plus text-xs"></i>
                    <span>Planifier une Campagne</span>
                </a>
            </div>
        </div>
        @endif

    </div>

    <!-- 3. KPI Cards Principaux (Nouveau Design Simple & Épuré) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        
        <!-- Carte 1 : Parc Immobilisations -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-[#0048FA]/40 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#0048FA]"></span>
                    <span class="text-xs font-semibold text-slate-600">Parc Immobilisations</span>
                </div>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#0048FA] flex items-center justify-center text-xs">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
            </div>
            <div class="my-3">
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ number_format($totalActifs, 0, ',', ' ') }}</div>
                <div class="text-xs font-mono font-bold text-[#0048FA] mt-0.5">{{ number_format($valeurTotale, 0, ',', ' ') }} <span class="text-[10px] font-normal text-slate-500">FCFA</span></div>
            </div>
            <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Couverture réseau</span>
                <span class="font-bold text-slate-800">{{ $totalSites }} sites bénéficiaires</span>
            </div>
        </div>

        <!-- Carte 2 : État Opérationnel -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-emerald-500/40 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-semibold text-slate-600">Disponibilité du Parc</span>
                </div>
                <div class="w-7 h-7 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-circle-check"></i>
                </div>
            </div>
            <div class="my-3">
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">
                    {{ $totalActifs > 0 ? round((($totalActifs - $actifsEnPanne) / $totalActifs) * 100, 1) : 0 }}%
                </div>
                <div class="text-xs font-semibold text-emerald-700 mt-0.5">
                    {{ $totalActifs - $actifsEnPanne }} actif(s) en service
                </div>
            </div>
            <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Hors service / SAV</span>
                <span class="font-bold text-rose-600">{{ $actifsEnPanne }} actif(s)</span>
            </div>
        </div>

        <!-- Carte 3 : Taux d'Étiquetage & SIG -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-sky-500/40 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-sky-500"></span>
                    <span class="text-xs font-semibold text-slate-600">Traçabilité & QR Code</span>
                </div>
                <div class="w-7 h-7 rounded-lg bg-sky-50 text-sky-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-qrcode"></i>
                </div>
            </div>
            <div class="my-3">
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $tauxEtiquetage }}%</div>
                <div class="text-xs font-semibold text-sky-700 mt-0.5">
                    Marquage physique actif
                </div>
            </div>
            <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Coordonnées GPS</span>
                <span class="font-bold text-slate-800">{{ $tauxGeolocalisation }}% localisés</span>
            </div>
        </div>

        <!-- Carte 4 : Actions en Attente -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200/90 shadow-xs hover:border-amber-500/40 transition-all flex flex-col justify-between">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-amber-500"></span>
                    <span class="text-xs font-semibold text-slate-600">Actions à Traiter</span>
                </div>
                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </div>
            </div>
            <div class="my-3">
                <div class="text-3xl font-extrabold text-slate-900 tracking-tight">{{ $mouvementsEnAttenteCount + $maintenancesEnCoursCount }}</div>
                <div class="text-xs font-semibold text-amber-700 mt-0.5">
                    En attente de validation
                </div>
            </div>
            <div class="pt-2.5 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-500">
                <span>Transferts : <strong>{{ $mouvementsEnAttenteCount }}</strong></span>
                <span>Pannes : <strong>{{ $maintenancesEnCoursCount }}</strong></span>
            </div>
        </div>

    </div>

    <!-- 4. Graphiques Analytiques & Répartition (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">
        
        <!-- Graphique 1 : Répartition par Programme -->
        <div class="lg:col-span-2 bg-white rounded-2xl p-5 border border-slate-200 shadow-xs">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#0048FA] flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-chart-column"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Valorisation par Programme Financier</h2>
                        <p class="text-[11px] text-slate-400">Montant d'acquisition cumulé par bailleur et programme</p>
                    </div>
                </div>
                <span class="text-xs font-bold text-slate-400 uppercase">FCFA</span>
            </div>
            <div class="h-64 mt-4">
                <canvas id="chartProgrammes"></canvas>
            </div>
        </div>

        <!-- Graphique 2 : Structure par Catégorie -->
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-xs flex flex-col justify-between">
            <div class="flex items-center justify-between pb-4 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-cyan-50 text-cyan-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-chart-pie"></i>
                    </div>
                    <div>
                        <h2 class="text-sm font-bold text-slate-900">Catégories d'Actifs</h2>
                        <p class="text-[11px] text-slate-400">Répartition quantitative</p>
                    </div>
                </div>
            </div>
            <div class="h-60 mt-2 flex items-center justify-center relative">
                <canvas id="chartCategories"></canvas>
            </div>
            <div class="mt-3 pt-3 border-t border-slate-100 text-center text-xs text-slate-400">
                Total : <strong class="text-slate-800">{{ $totalActifs }}</strong> actifs répartis en <strong class="text-slate-800">{{ $parCategorie->count() }}</strong> catégories
            </div>
        </div>

    </div>

    <!-- 5. Tableaux Opérationnels Récents (2 Colonnes) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">
        
        <!-- Dernières Immobilisations Enregistrées -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-emerald-50 text-[#0048FA] flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-cube"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Derniers Enregistrements</h3>
                        <p class="text-[11px] text-slate-400">Immobilisations récemment acquises ou intégrées</p>
                    </div>
                </div>
                <a href="{{ route('gestionnaire.immobilisations.index') }}" class="text-xs font-bold text-[#0048FA] hover:underline">
                    Tout voir &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($dernieresImmobilisations as $item)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                    <div class="flex items-center space-x-3 min-w-0">
                        <div style="background-color: #0048FA;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-xs font-bold shadow-xs flex-shrink-0">
                            <i class="fa-solid {{ $item->categorie?->icone ?? 'fa-cube' }}"></i>
                        </div>
                        <div class="min-w-0">
                            <a href="{{ route('gestionnaire.immobilisations.show', $item->id) }}" class="font-bold text-slate-900 hover:text-[#0048FA] truncate block">
                                {{ $item->designation }}
                            </a>
                            <div class="text-[11px] text-slate-400 truncate">
                                <span class="font-mono text-[#0048FA] font-semibold">{{ $item->code_immobilisation }}</span>
                                • {{ $item->structureBeneficiaire?->nom ?? 'Siège' }}
                            </div>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-3">
                        <div class="font-mono font-bold text-slate-900">{{ number_format($item->valeur_acquisition, 0, ',', ' ') }} {{ $item->devise }}</div>
                        <div class="text-[10px] text-slate-400">{{ $item->created_at ? $item->created_at->diffForHumans() : '-' }}</div>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400">
                    <i class="fa-solid fa-box-open text-2xl mb-1"></i>
                    <p>Aucune immobilisation enregistrée.</p>
                </div>
                @endforelse
            </div>
        </div>

        <!-- Derniers Mouvements & Demandes de Transfert -->
        <div class="bg-white rounded-2xl border border-slate-200 shadow-xs overflow-hidden">
            <div class="p-5 border-b border-slate-100 flex items-center justify-between">
                <div class="flex items-center space-x-2.5">
                    <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-700 flex items-center justify-center text-sm font-bold">
                        <i class="fa-solid fa-arrow-right-arrow-left"></i>
                    </div>
                    <div>
                        <h3 class="text-sm font-bold text-slate-900">Mouvements & Transferts</h3>
                        <p class="text-[11px] text-slate-400">Dernières opérations logistiques et affectations</p>
                    </div>
                </div>
                <a href="{{ route('gestionnaire.mouvements.index') }}" class="text-xs font-bold text-[#0048FA] hover:underline">
                    Registre complet &rarr;
                </a>
            </div>

            <div class="divide-y divide-slate-100 text-xs">
                @forelse($derniersMouvements as $mvt)
                <div class="p-4 flex items-center justify-between hover:bg-slate-50/70 transition-colors">
                    <div class="min-w-0">
                        <div class="flex items-center space-x-2">
                            <span class="font-mono font-bold text-slate-900">{{ $mvt->reference_mouvement }}</span>
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold {{ $mvt->statut_badge_class }}">
                                {{ ucfirst($mvt->statut_workflow) }}
                            </span>
                        </div>
                        <div class="text-[11px] text-slate-600 mt-1 truncate">
                            <strong>{{ $mvt->immobilisation?->designation }}</strong>
                        </div>
                        <div class="text-[10px] text-slate-400 mt-0.5 flex items-center space-x-1 truncate">
                            <span>{{ $mvt->structureOrigine?->nom ?? 'Origine' }}</span>
                            <i class="fa-solid fa-arrow-right text-[8px] text-slate-300"></i>
                            <span>{{ $mvt->structureDestination?->nom ?? 'Destination' }}</span>
                        </div>
                    </div>
                    <div class="text-right flex-shrink-0 ml-3">
                        <a href="{{ route('gestionnaire.mouvements.show', $mvt->id) }}" class="px-2.5 py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs inline-block">
                            Détail
                        </a>
                    </div>
                </div>
                @empty
                <div class="p-8 text-center text-slate-400">
                    <i class="fa-solid fa-route text-2xl mb-1"></i>
                    <p>Aucun mouvement récent enregistré.</p>
                </div>
                @endforelse
            </div>
        </div>

    </div>

</div>

<!-- Scripts pour Chart.js -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Chart Programmes (Bar Chart)
    const progCtx = document.getElementById('chartProgrammes');
    if (progCtx) {
        const progLabels = {!! json_encode($parProgramme->pluck('code')) !!};
        const progValues = {!! json_encode($parProgramme->pluck('immobilisations_sum_valeur_acquisition')) !!};

        new Chart(progCtx, {
            type: 'bar',
            data: {
                labels: progLabels,
                datasets: [{
                    label: 'Valeur (FCFA)',
                    data: progValues,
                    backgroundColor: '#0048FA',
                    borderRadius: 8,
                    maxBarThickness: 45
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return new Intl.NumberFormat('fr-FR').format(context.raw) + ' FCFA';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                if (value >= 1000000) {
                                    return (value / 1000000) + ' M';
                                }
                                return value;
                            }
                        },
                        grid: { color: '#f1f5f9' }
                    },
                    x: {
                        grid: { display: false }
                    }
                }
            }
        });
    }

    // 2. Chart Catégories (Doughnut)
    const catCtx = document.getElementById('chartCategories');
    if (catCtx) {
        const catLabels = {!! json_encode($parCategorie->pluck('nom')) !!};
        const catCounts = {!! json_encode($parCategorie->pluck('immobilisations_count')) !!};

        new Chart(catCtx, {
            type: 'doughnut',
            data: {
                labels: catLabels,
                datasets: [{
                    data: catCounts,
                    backgroundColor: [
                        '#0048FA', '#0ea5e9', '#8b5cf6', '#f59e0b', '#ec4899', '#10b981', '#64748b'
                    ],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            boxWidth: 10,
                            font: { size: 10 }
                        }
                    }
                },
                cutout: '70%'
            }
        });
    }
});
</script>
@endsection
