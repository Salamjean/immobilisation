@extends('layouts.app')

@section('title', "Fiche {$actif->code_immobilisation} - {$actif->designation}")
@section('header_title', "Fiche Patrimoniale de l'Immobilisation")

@section('header_badge')
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-brand-100 text-brand-900 border border-brand-300">
        {{ $actif->code_immobilisation }}
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête Fiche Actif -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-4">
            <div class="w-16 h-16 rounded-2xl bg-gradient-to-tr from-brand-600 to-sky-400 flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-brand-500/20 flex-shrink-0">
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
                    <span class="px-2 py-0.5 rounded text-[10px] font-semibold bg-slate-100 text-slate-700">
                        {{ $actif->programme?->code ?? 'Non rattaché' }}
                    </span>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 mt-1">{{ $actif->designation }}</h1>
                <div class="text-xs text-slate-500 mt-0.5 flex flex-wrap items-center gap-x-4 gap-y-1">
                    <span><strong class="text-slate-700">Marque / Modèle:</strong> {{ $actif->marque }} {{ $actif->modele }}</span>
                    <span><strong class="text-slate-700">N° Série:</strong> <span class="font-mono text-slate-800">{{ $actif->numero_serie ?? 'N/A' }}</span></span>
                    <span><strong class="text-slate-700">Tag / QR:</strong> <span class="font-mono text-brand-700 font-bold">{{ $actif->reference_etiquette ?? 'N/A' }}</span></span>
                </div>
            </div>
        </div>

        <!-- Boutons d'action rapides -->
        <div class="flex flex-wrap items-center gap-2 w-full md:w-auto justify-end">
            <a href="{{ route('immobilisations.etiquette', $actif->id) }}" target="_blank" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-qrcode"></i>
                <span>Imprimer Tag</span>
            </a>
            <a href="{{ route('mouvements.create', ['actif_id' => $actif->id]) }}" class="px-3.5 py-2 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-all shadow-sm flex items-center space-x-1.5">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                <span>Transférer</span>
            </a>
            <a href="{{ route('maintenances.create', ['actif_id' => $actif->id]) }}" class="px-3.5 py-2 rounded-xl bg-rose-600 hover:bg-rose-700 text-white text-xs font-bold transition-all shadow-sm flex items-center space-x-1.5">
                <i class="fa-solid fa-wrench"></i>
                <span>Signaler Panne</span>
            </a>
            <a href="{{ route('immobilisations.edit', $actif->id) }}" class="px-3.5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold transition-all shadow-sm flex items-center space-x-1.5">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Modifier</span>
            </a>
        </div>
    </div>

    <!-- Grille Principale 360° -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- Colonne Gauche (2/3) : Détails techniques, Acquisition, Historique -->
        <div class="lg:col-span-2 space-y-6">

            <!-- Données Techniques & Acquisition -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-file-contract text-brand-600"></i>
                    <span>Informations d'Acquisition & Financement</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Bailleur de Fonds</span>
                        <div class="font-bold text-slate-800">{{ $actif->bailleur?->nom ?? 'Fonds mondial' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Subvention / Financement</span>
                        <div class="font-bold text-slate-800">{{ $actif->subvention?->code_subvention ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Valeur d'Acquisition</span>
                        <div class="font-extrabold font-mono text-base text-slate-900">{{ $actif->valeur_formatee }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Fournisseur / Distributeur</span>
                        <div class="font-semibold text-slate-800">{{ $actif->fournisseur ?? 'Non renseigné' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Marché / Contrat Réf.</span>
                        <div class="font-mono text-slate-800">{{ $actif->reference_marche ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Bon de Commande</span>
                        <div class="font-mono text-slate-800">{{ $actif->bon_commande ?? 'N/A' }}</div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Date d'Acquisition & Réception</span>
                        <div class="font-semibold text-slate-800">
                            {{ $actif->date_acquisition ? $actif->date_acquisition->format('d/m/Y') : 'N/A' }}
                            @if($actif->date_reception)
                            (Reçu le {{ $actif->date_reception->format('d/m/Y') }})
                            @endif
                        </div>
                    </div>
                    <div>
                        <span class="text-slate-400 block mb-0.5">Garantie Constructeur</span>
                        <div class="font-semibold text-slate-800">
                            @if($actif->date_fin_garantie)
                                Jusqu'au {{ $actif->date_fin_garantie->format('d/m/Y') }}
                                @if($actif->date_fin_garantie->isPast())
                                    <span class="text-red-500 font-bold">(Expirée)</span>
                                @else
                                    <span class="text-emerald-600 font-bold">(Active)</span>
                                @endif
                            @else
                                Non spécifiée
                            @endif
                        </div>
                    </div>
                </div>

                @if($actif->justification_risque)
                <div class="mt-4 p-3.5 rounded-xl bg-rose-50/60 border border-rose-200/80 text-xs">
                    <span class="font-bold text-rose-900 block mb-1">Justification du Classement Risque & Criticité :</span>
                    <p class="text-rose-800 leading-relaxed">{{ $actif->justification_risque }}</p>
                </div>
                @endif
            </div>

            <!-- Historique des Mouvements & Transferts -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="flex items-center space-x-2">
                        <i class="fa-solid fa-timeline text-amber-500"></i>
                        <span>Traçabilité des Mouvements & Affectations ({{ $actif->mouvements->count() }})</span>
                    </span>
                    <a href="{{ route('mouvements.create', ['actif_id' => $actif->id]) }}" class="text-brand-600 hover:underline text-[11px] font-semibold">
                        + Initier un mouvement
                    </a>
                </h3>

                <div class="relative border-l-2 border-slate-200 ml-4 space-y-6 text-xs">
                    @forelse($actif->mouvements as $mvt)
                    <div class="relative pl-6">
                        <div class="absolute -left-2 top-0.5 w-4 h-4 rounded-full bg-brand-600 border-2 border-white shadow"></div>
                        <div class="flex items-center justify-between">
                            <span class="font-bold text-slate-900">{{ $mvt->type_libelle }}</span>
                            <span class="font-mono text-[11px] text-slate-400">{{ $mvt->date_mouvement->format('d/m/Y') }}</span>
                        </div>
                        <div class="text-slate-600 mt-1">
                            <span class="text-slate-500">De :</span> <strong>{{ $mvt->structureOrigine?->nom ?? 'Origine' }}</strong>
                            &rarr;
                            <span class="text-slate-500">Vers :</span> <strong>{{ $mvt->structureDestination?->nom ?? 'Destination' }}</strong>
                        </div>
                        <div class="text-[11px] text-slate-500 italic mt-0.5">
                            Motif : {{ $mvt->motif }}
                        </div>
                        <div class="mt-1 flex items-center space-x-2">
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $mvt->statut_badge_class }}">{{ ucfirst($mvt->statut_workflow) }}</span>
                            <span class="text-[10px] text-slate-400">Réf: {{ $mvt->reference_mouvement }}</span>
                        </div>
                    </div>
                    @empty
                    <div class="pl-6 text-slate-400 italic">Aucun mouvement n'a encore été enregistré pour ce bien.</div>
                    @endforelse
                </div>
            </div>

            <!-- Fiches de Maintenance & SAV -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center justify-between">
                    <span class="flex items-center space-x-2">
                        <i class="fa-solid fa-wrench text-rose-500"></i>
                        <span>Historique des Maintenances & Réparations ({{ $actif->maintenances->count() }})</span>
                    </span>
                    <a href="{{ route('maintenances.create', ['actif_id' => $actif->id]) }}" class="text-brand-600 hover:underline text-[11px] font-semibold">
                        + Nouvelle intervention
                    </a>
                </h3>

                <div class="space-y-3">
                    @forelse($actif->maintenances as $maint)
                    <div class="p-3.5 rounded-xl border border-slate-200 bg-slate-50/60 text-xs">
                        <div class="flex items-center justify-between">
                            <div class="font-bold text-slate-900">{{ $maint->type_libelle }} ({{ $maint->numero_fiche }})</div>
                            <span class="px-2 py-0.5 rounded text-[10px] font-bold {{ $maint->statut_badge_class }}">{{ ucfirst($maint->statut) }}</span>
                        </div>
                        <div class="text-slate-600 mt-1"><strong>Diagnostic :</strong> {{ $maint->diagnostic ?? $maint->description_panne }}</div>
                        <div class="text-[11px] text-slate-500 mt-1 flex flex-wrap items-center gap-x-4">
                            <span><i class="fa-regular fa-calendar mr-1"></i> {{ $maint->date_signalement->format('d/m/Y') }}</span>
                            <span><i class="fa-solid fa-user-tie mr-1"></i> Prestataire: {{ $maint->prestataire ?? 'Interne' }}</span>
                            <span><i class="fa-solid fa-coins mr-1"></i> Coût: {{ number_format($maint->cout_intervention, 0, ',', ' ') }} FCFA</span>
                        </div>
                    </div>
                    @empty
                    <div class="text-slate-400 italic text-xs">Aucun historique de panne ou de maintenance répertorié.</div>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Colonne Droite (1/3) : Affectation, Localisation GPS, QR Code -->
        <div class="space-y-6">

            <!-- Localisation & Détenteur Actuel -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-location-dot text-emerald-600"></i>
                    <span>Affectation & Positionnement GPS</span>
                </h3>

                <div class="space-y-3 text-xs">
                    <div>
                        <span class="text-slate-400 block mb-0.5">Structure Bénéficiaire</span>
                        <div class="font-bold text-slate-900 text-sm">{{ $actif->structureBeneficiaire?->nom }}</div>
                        <div class="text-slate-500 text-[11px]">{{ $actif->structureBeneficiaire?->region?->nom }} - {{ $actif->structureBeneficiaire?->ville }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">Service & Emplacement</span>
                        <div class="font-semibold text-slate-800">{{ $actif->service?->nom ?? 'Non spécifié' }}</div>
                        <div class="text-slate-500 text-[11px]">{{ $actif->emplacement_detaille ?? 'Salle non précisée' }}</div>
                    </div>

                    <div>
                        <span class="text-slate-400 block mb-0.5">Détenteur / Responsable</span>
                        <div class="font-bold text-slate-800">{{ $actif->detenteur?->nom_complet ?? 'Non assigné' }}</div>
                        @if($actif->detenteur)
                        <div class="text-slate-500 text-[11px]">{{ $actif->detenteur->fonction }} ({{ $actif->detenteur->telephone }})</div>
                        @endif
                    </div>

                    <div class="pt-2 border-t border-slate-100">
                        <span class="text-slate-400 block mb-0.5">Coordonnées GPS Certifiées</span>
                        <div class="font-mono font-bold text-slate-900">
                            {{ $actif->latitude ? number_format($actif->latitude, 6) : 'N/A' }}, {{ $actif->longitude ? number_format($actif->longitude, 6) : 'N/A' }}
                        </div>
                        <div class="text-[11px] text-slate-500 mt-0.5">
                            Précision : ±{{ $actif->precision_gps ?? 5 }} mètres
                            @if($actif->date_derniere_localisation)
                            (Mis à jour le {{ $actif->date_derniere_localisation->format('d/m/Y H:i') }})
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Mini Map de la fiche -->
                @if($actif->latitude && $actif->longitude)
                <div id="assetMiniMap" class="w-full h-48 rounded-xl border border-slate-200 mt-4 z-10"></div>
                @endif
            </div>

            <!-- QR Code & Étiquette Sécurisée -->
            <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm text-center">
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider mb-4 pb-2 border-b border-slate-100">
                    Étiquette & QR Code d'Inventaire
                </h3>

                <!-- Générateur de QR Code SVG / Canvas -->
                <div class="p-4 bg-slate-50 border-2 border-dashed border-slate-300 rounded-2xl inline-block shadow-inner">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode($actif->code_immobilisation) }}" alt="QR Code" class="w-32 h-32 mx-auto rounded-lg shadow-sm">
                    <div class="font-mono font-bold text-xs text-slate-800 mt-2">{{ $actif->code_immobilisation }}</div>
                    <div class="text-[10px] text-slate-400 uppercase font-semibold">UCP-FM CÔTE D'IVOIRE</div>
                </div>

                <div class="mt-4">
                    <a href="{{ route('immobilisations.etiquette', $actif->id) }}" target="_blank" class="w-full py-2 px-4 rounded-xl bg-slate-900 hover:bg-slate-800 text-white text-xs font-bold transition-all flex items-center justify-center space-x-2">
                        <i class="fa-solid fa-print"></i>
                        <span>Format d'Impression Étiquette</span>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>
@endsection

@push('scripts')
@if($actif->latitude && $actif->longitude)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lat = {{ $actif->latitude }};
    const lng = {{ $actif->longitude }};
    const map = L.map('assetMiniMap', { zoomControl: false }).setView([lat, lng], 15);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OSM'
    }).addTo(map);

    L.marker([lat, lng]).addTo(map)
        .bindPopup('<b>{{ $actif->code_immobilisation }}</b><br>{{ $actif->structureBeneficiaire?->nom }}')
        .openPopup();
});
</script>
@endif
@endpush
