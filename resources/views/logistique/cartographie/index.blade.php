@extends('layouts.app')

@section('title', 'Cartographie SIG - Logistique')
@section('header_title', 'Cartographie SIG & Géolocalisation Flotte')

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold bg-amber-100 text-amber-900 border border-amber-300">
        <i class="fa-solid fa-map-location-dot mr-1.5 text-amber-600"></i>
        Logistique & SIG
    </span>
@endsection

@section('content')
<div class="space-y-6">

    <!-- En-tête & Filtres Carte -->
    <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div>
            <h2 class="text-base font-extrabold text-slate-900">Carte des Immobilisations & Équipements</h2>
            <p class="text-xs text-slate-500 mt-0.5">Visualisation spatiale du parc de santé et des matériels sur toute la Côte d'Ivoire.</p>
        </div>
        <div class="flex items-center space-x-3">
            <span class="inline-flex items-center px-3 py-1.5 rounded-xl bg-emerald-50 text-[#0048FA] font-bold text-xs border border-emerald-200">
                <i class="fa-solid fa-satellite-dish mr-1.5"></i>
                {{ $totalGeolocalises }} Actifs Géolocalisés
            </span>
        </div>
    </div>

    <!-- Carte Interactive Leaflet -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden p-2">
        <div id="map-logistique" class="w-full h-[600px] rounded-xl z-0"></div>
    </div>

</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const points = @json($points);

        // Centre par défaut sur la Côte d'Ivoire (Yamoussoukro / Abidjan)
        const map = L.map('map-logistique').setView([7.54, -5.55], 7);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        const markers = L.markerClusterGroup();

        points.forEach(p => {
            const popupContent = `
                <div style="font-family: 'Plus Jakarta Sans', sans-serif; font-size: 12px; line-height: 1.4; padding: 2px;">
                    <div style="font-weight: 800; color: #0048FA; font-family: monospace;">${p.code}</div>
                    <div style="font-weight: 700; color: #0f172a; margin-top: 2px;">${p.designation}</div>
                    <div style="color: #64748b; font-size: 11px;">${p.structure}</div>
                    <div style="margin-top: 6px; display: flex; align-items: center; justify-content: space-between;">
                        <span style="font-weight: 700; color: #0f172a;">${p.valeur}</span>
                        <a href="${p.url}" style="color: #0048FA; font-weight: 700; text-decoration: underline;">Voir fiche &rarr;</a>
                    </div>
                </div>
            `;
            const marker = L.marker([p.lat, p.lng]).bindPopup(popupContent);
            markers.addLayer(marker);
        });

        map.addLayer(markers);

        if (points.length > 0) {
            const bounds = L.latLngBounds(points.map(p => [p.lat, p.lng]));
            map.fitBounds(bounds, { padding: [50, 50] });
        }
    });
</script>
@endpush
