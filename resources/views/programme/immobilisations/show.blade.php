@extends('layouts.app')

@section('title', "Détail {$actif->code_immobilisation} - Espace Programme")
@section('header_title', "Fiche Patrimoniale Programme")

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold font-mono bg-emerald-100 text-[#155E3B] border border-emerald-300">
        {{ $actif->code_immobilisation }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête Fiche Actif -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#155E3B] to-emerald-500 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-emerald-600/20 flex-shrink-0">
                <i class="{{ $actif->categorie?->icone ?? 'fa-solid fa-cube' }}"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $actif->niveau_criticite_badge_class }}">
                        Criticité {{ $actif->niveau_criticite }} (Score: {{ $actif->score_risque }}/100)
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold {{ $actif->etat_badge_class }}">
                        {{ $actif->etat_libelle }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-[#155E3B] border border-emerald-200">
                        {{ $actif->programme?->nom ?? $actif->programme?->code ?? 'Programme Santé' }}
                    </span>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 mt-1">{{ $actif->designation }}</h1>
                <div class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-x-4 gap-y-1">
                    <span><strong class="text-slate-700">Marque / Modèle:</strong> {{ $actif->marque }} {{ $actif->modele }}</span>
                    <span><strong class="text-slate-700">N° Série:</strong> <span class="font-mono text-slate-800">{{ $actif->numero_serie ?? 'N/A' }}</span></span>
                    <span><strong class="text-slate-700">Tag / QR:</strong> <span class="font-mono text-[#155E3B] font-bold">{{ $actif->reference_etiquette ?? 'N/A' }}</span></span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            <a href="{{ route('programme.immobilisations.index') }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-left"></i>
                <span>Retour aux actifs</span>
            </a>
        </div>
    </div>

    <!-- Grille Principale -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Colonne Gauche (2/3) : Détails techniques, Financement, Historique -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Données Techniques & Financement -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-file-contract text-[#155E3B]"></i>
                    <span>Financement & Attribution Programme</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Bailleur de Fonds</span>
                        <div class="font-bold text-slate-800">{{ $actif->bailleur?->nom ?? 'Fonds mondial' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Subvention Rattachée</span>
                        <div class="font-bold text-[#155E3B]">{{ $actif->subvention?->code_subvention ?? 'N/A' }} - {{ $actif->subvention?->titre ?? '' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Valeur d'Acquisition Initiale</span>
                        <div class="font-extrabold font-mono text-base text-slate-900">{{ $actif->valeur_formatee }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Date de Mise en Service</span>
                        <div class="font-semibold text-slate-800">{{ $actif->date_mise_en_service?->format('d/m/Y') ?? 'Non définie' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Fournisseur</span>
                        <div class="font-semibold text-slate-800">{{ $actif->fournisseur ?? 'Non renseigné' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Marché / Contrat Réf.</span>
                        <div class="font-mono text-slate-800">{{ $actif->reference_marche ?? 'N/A' }}</div>
                    </div>
                </div>
            </div>

            <!-- Historique des Mouvements et Réaffectations -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="flex items-center space-x-2">
                        <i class="fa-solid fa-timeline text-[#155E3B]"></i>
                        <span>Traçabilité & Historique des Transferts ({{ $actif->mouvements->count() }})</span>
                    </span>
                </h3>

                @if($actif->mouvements->isEmpty())
                    <div class="text-center py-6 text-slate-400 text-xs">
                        <i class="fa-solid fa-clock-rotate-left text-2xl mb-2 text-slate-300 block"></i>
                        Aucun mouvement enregistré pour cet actif.
                    </div>
                @else
                    <div class="relative border-l-2 border-slate-200 ml-4 space-y-6">
                        @foreach($actif->mouvements->sortByDesc('date_mouvement') as $mvt)
                            <div class="relative pl-6">
                                <span class="absolute -left-2 top-0.5 w-4 h-4 rounded-full border-2 border-white {{ $mvt->statut === 'valide' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                <div class="flex items-center justify-between text-xs">
                                    <span class="font-bold text-slate-900">{{ $mvt->type_mouvement_libelle }}</span>
                                    <span class="text-slate-400 font-mono">{{ $mvt->date_mouvement?->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-xs text-slate-600 mt-1">
                                    De : <strong class="text-slate-800">{{ $mvt->structureOrigine?->nom ?? 'Origine' }}</strong> 
                                    &rarr; Vers : <strong class="text-[#155E3B]">{{ $mvt->structureDestination?->nom ?? 'Destination' }}</strong>
                                </p>
                                @if($mvt->motif)
                                    <p class="text-[11px] text-slate-500 italic mt-0.5">Motif : {{ $mvt->motif }}</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Historique des Maintenances & Pannes -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="flex items-center space-x-2">
                        <i class="fa-solid fa-screwdriver-wrench text-[#155E3B]"></i>
                        <span>Interventions SAV & Maintenance ({{ $actif->maintenances->count() }})</span>
                    </span>
                </h3>

                @if($actif->maintenances->isEmpty())
                    <div class="text-center py-6 text-slate-400 text-xs">
                        <i class="fa-solid fa-shield-check text-2xl mb-2 text-slate-300 block"></i>
                        Aucune maintenance signalée pour cet actif.
                    </div>
                @else
                    <div class="space-y-3">
                        @foreach($actif->maintenances->sortByDesc('date_demande') as $maint)
                            <div class="p-3.5 rounded-xl border border-slate-100 bg-slate-50/50 flex items-start justify-between text-xs">
                                <div>
                                    <div class="font-bold text-slate-800">{{ $maint->titre }}</div>
                                    <p class="text-[11px] text-slate-500 mt-0.5">{{ $maint->description }}</p>
                                    <div class="text-[10px] text-slate-400 mt-1">
                                        Prestataire : <span class="font-medium text-slate-600">{{ $maint->prestataire ?? 'Interne' }}</span> | Coût : <span class="font-mono font-bold text-slate-700">{{ number_format($maint->cout_reel ?? $maint->cout_estime ?? 0, 0, ',', ' ') }} FCFA</span>
                                    </div>
                                </div>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $maint->statut_badge_class }}">
                                    {{ $maint->statut_libelle }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Colonne Droite (1/3) : Affectation, Détenteur, SIG Géolocalisation -->
        <div class="space-y-6">

            <!-- Affectation & Détenteur Actuel -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-location-dot text-[#155E3B]"></i>
                    <span>Affectation Actuelle</span>
                </h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block text-[11px]">Région Sanitaire</span>
                        <div class="font-bold text-slate-800">{{ $actif->region?->nom ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">District Sanitaire</span>
                        <div class="font-bold text-slate-800">{{ $actif->district?->nom ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Structure Bénéficiaire / Site</span>
                        <div class="font-bold text-[#155E3B]">{{ $actif->structure?->nom ?? 'N/A' }}</div>
                        <span class="text-[10px] text-slate-500">{{ $actif->structure?->type_structure }}</span>
                    </div>
                    <div>
                        <span class="text-slate-400 block text-[11px]">Service & Emplacement</span>
                        <div class="font-semibold text-slate-800">{{ $actif->service?->nom ?? 'Bureau / Salle non spécifié' }} ({{ $actif->emplacement_bureau ?? 'Emplacement standard' }})</div>
                    </div>
                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block text-[11px]">Détenteur / Agent Responsable</span>
                        <div class="font-bold text-slate-900">{{ $actif->detenteur?->nom_complet ?? 'Non assigné nominativement' }}</div>
                        @if($actif->detenteur?->telephone)
                            <div class="text-[11px] text-slate-500"><i class="fa-solid fa-phone text-slate-400 mr-1"></i> {{ $actif->detenteur->telephone }}</div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Géolocalisation GPS & Carte SIG -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-3 flex items-center justify-between">
                    <span class="flex items-center space-x-2">
                        <i class="fa-solid fa-map-location-dot text-[#155E3B]"></i>
                        <span>Point GPS / SIG</span>
                    </span>
                    @if($actif->gps_valide)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Coordonnées OK</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Non géolocalisé</span>
                    @endif
                </h3>

                @if($actif->gps_valide)
                    <div class="font-mono text-[11px] text-slate-600 bg-slate-50 p-2 rounded-lg mb-3">
                        Lat: {{ $actif->latitude }} | Lng: {{ $actif->longitude }}
                    </div>
                    <div id="map-asset" class="w-full h-44 rounded-xl border border-slate-200 z-0"></div>
                @else
                    <div class="text-center py-6 text-slate-400 text-xs bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        <i class="fa-solid fa-satellite-dish text-2xl mb-2 text-slate-300 block"></i>
                        L'actif sera géoréférencé lors du prochain passage de l'équipe d'inventaire terrain.
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

@if($actif->gps_valide)
@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lat = {{ $actif->latitude }};
        const lng = {{ $actif->longitude }};
        const map = L.map('map-asset').setView([lat, lng], 14);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        L.marker([lat, lng])
            .addTo(map)
            .bindPopup("<strong>{{ $actif->code_immobilisation }}</strong><br>{{ $actif->designation }}")
            .openPopup();
    });
</script>
@endpush
@endif
