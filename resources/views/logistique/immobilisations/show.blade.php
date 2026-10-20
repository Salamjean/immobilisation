@extends('layouts.app')

@section('title', "Fiche {$actif->code_immobilisation} - Logistique")
@section('header_title', "Fiche Matérielle d'Immobilisation")

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold font-mono bg-amber-100 text-amber-900 border border-amber-300">
        {{ $actif->code_immobilisation }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête Fiche Actif -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-[#0048FA] to-emerald-500 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-emerald-600/20 flex-shrink-0">
                <i class="{{ $actif->categorie?->icone ?? 'fa-solid fa-cube' }}"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold uppercase {{ $actif->niveau_criticite_badge_class }}">
                        Criticité {{ $actif->niveau_criticite }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-bold {{ $actif->etat_badge_class }}">
                        {{ $actif->etat_libelle }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded text-[10px] font-semibold bg-emerald-50 text-[#0048FA] border border-emerald-200">
                        {{ $actif->structureBeneficiaire?->nom ?? 'Site non défini' }}
                    </span>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 mt-1">{{ $actif->designation }}</h1>
                <div class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-x-4 gap-y-1">
                    <span><strong class="text-slate-700">Marque / Modèle:</strong> {{ $actif->marque }} {{ $actif->modele }}</span>
                    <span><strong class="text-slate-700">N° Série:</strong> <span class="font-mono text-slate-800">{{ $actif->numero_serie ?? 'N/A' }}</span></span>
                    <span><strong class="text-slate-700">Tag / QR:</strong> <span class="font-mono text-[#0048FA] font-bold">{{ $actif->reference_etiquette ?? 'N/A' }}</span></span>
                </div>
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('logistique.mouvements.create', ['actif_id' => $actif->id]) }}" class="px-4 py-2 rounded-xl bg-amber-600 hover:bg-amber-700 text-white text-xs font-bold transition-all flex items-center space-x-1.5 shadow-sm">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                <span>Nouveau Mouvement</span>
            </a>
            <a href="{{ route('logistique.maintenances.create', ['actif_id' => $actif->id]) }}" class="px-4 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all flex items-center space-x-1.5 shadow-sm">
                <i class="fa-solid fa-wrench"></i>
                <span>Demande SAV</span>
            </a>
            <a href="{{ route('logistique.immobilisations.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                Retour
            </a>
        </div>
    </div>

    <!-- Grille Principale -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Colonne Gauche (2/3) : Traçabilité, Mouvements, Maintenances -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Données Techniques -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-circle-info text-[#0048FA]"></i>
                    <span>Informations Techniques & Financement</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Catégorie</span>
                        <div class="font-bold text-slate-800">{{ $actif->categorie?->nom ?? 'Non définie' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Programme Rattaché</span>
                        <div class="font-bold text-[#0048FA]">{{ $actif->programme?->nom ?? 'GC7 / CI' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Valeur d'Acquisition</span>
                        <div class="font-extrabold font-mono text-base text-slate-900">{{ $actif->valeur_formatee }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Date Mise en Service</span>
                        <div class="font-semibold text-slate-800">{{ $actif->date_mise_en_service?->format('d/m/Y') ?? 'Non définie' }}</div>
                    </div>
                </div>
            </div>

            <!-- Historique des Transferts -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="flex items-center space-x-2">
                        <i class="fa-solid fa-timeline text-[#0048FA]"></i>
                        <span>Historique des Mouvements & Transferts ({{ $actif->mouvements->count() }})</span>
                    </span>
                </h3>

                @if($actif->mouvements->isEmpty())
                    <div class="text-center py-6 text-slate-400 text-xs">
                        <i class="fa-solid fa-clock-rotate-left text-2xl mb-2 text-slate-300 block"></i>
                        Aucun mouvement enregistré pour cet actif.
                    </div>
                @else
                    <div class="relative border-l-2 border-slate-200 ml-4 space-y-5 text-xs">
                        @foreach($actif->mouvements->sortByDesc('date_mouvement') as $mvt)
                            <div class="relative pl-6">
                                <span class="absolute -left-2 top-0.5 w-4 h-4 rounded-full border-2 border-white {{ $mvt->statut_workflow === 'valide' ? 'bg-emerald-500' : 'bg-amber-500' }}"></span>
                                <div class="flex items-center justify-between">
                                    <span class="font-bold text-slate-900">{{ $mvt->type_mouvement_libelle }}</span>
                                    <span class="text-slate-400 font-mono">{{ $mvt->date_mouvement?->format('d/m/Y') }}</span>
                                </div>
                                <p class="text-slate-600 mt-0.5">
                                    De : <strong class="text-slate-800">{{ $mvt->structureOrigine?->nom ?? 'Origine' }}</strong> 
                                    &rarr; Vers : <strong class="text-[#0048FA]">{{ $mvt->structureDestination?->nom ?? 'Destination' }}</strong>
                                </p>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

        </div>

        <!-- Colonne Droite (1/3) : Affectation & Point GPS -->
        <div class="space-y-6">

            <!-- Affectation Actuelle -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-3 text-xs">
                <h3 class="font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-location-dot text-[#0048FA]"></i>
                    <span>Affectation Actuelle</span>
                </h3>
                <div>
                    <span class="text-slate-400 block text-[11px]">Site / Établissement</span>
                    <div class="font-bold text-[#0048FA]">{{ $actif->structureBeneficiaire?->nom ?? 'Non assigné' }}</div>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Emplacement / Bureau</span>
                    <div class="font-semibold text-slate-800">{{ $actif->emplacement_bureau ?? 'Emplacement standard' }}</div>
                </div>
                <div>
                    <span class="text-slate-400 block text-[11px]">Détenteur</span>
                    <div class="font-bold text-slate-900">{{ $actif->detenteur?->nom_complet ?? 'Non assigné' }}</div>
                </div>
            </div>

            <!-- Point GPS -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-3 text-xs">
                <h3 class="font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="flex items-center space-x-2">
                        <i class="fa-solid fa-map-location-dot text-[#0048FA]"></i>
                        <span>Point GPS / SIG</span>
                    </span>
                    @if($actif->latitude && $actif->longitude)
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-emerald-100 text-emerald-800">Géolocalisé</span>
                    @else
                        <span class="px-2 py-0.5 rounded text-[10px] font-bold bg-amber-100 text-amber-800">Non localisé</span>
                    @endif
                </h3>

                @if($actif->latitude && $actif->longitude)
                    <div class="font-mono text-[11px] text-slate-600 bg-slate-50 p-2 rounded-lg">
                        Lat: {{ $actif->latitude }} | Lng: {{ $actif->longitude }}
                    </div>
                    <div id="map-asset" class="w-full h-44 rounded-xl border border-slate-200 z-0"></div>
                @else
                    <div class="text-center py-6 text-slate-400 text-xs italic bg-slate-50 rounded-xl border border-dashed border-slate-200">
                        Aucune coordonnée GPS enregistrée.
                    </div>
                @endif
            </div>

        </div>

    </div>

</div>
@endsection

@if($actif->latitude && $actif->longitude)
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
