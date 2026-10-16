@extends('layouts.admin.app')

@section('title', 'Cartographie SIG & Géolocalisation')

@push('styles')
    <!-- Leaflet CSS Fallback -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <style>
        .leaflet-container {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            width: 100% !important;
            height: 100% !important;
            border-radius: 1rem;
        }
        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1);
            padding: 4px;
        }
        .custom-popup .leaflet-popup-content {
            margin: 8px 12px;
            line-height: 1.4;
        }
    </style>
@endpush

@section('content')
<div class="space-y-4 flex flex-col pb-6">

    <!-- 1. En-tête & Indicateurs SIG -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <div style="background-color: #155E3B;" class="w-11 h-11 rounded-xl text-white flex items-center justify-center text-lg shadow-xs flex-shrink-0">
                <i class="fa-solid fa-map-location-dot"></i>
            </div>
            <div>
                <div class="flex items-center space-x-2">
                    <h1 class="text-base font-bold text-slate-900 tracking-tight leading-tight">Cartographie SIG & Géolocalisation</h1>
                    <span id="map_engine_badge" class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold text-emerald-800 bg-emerald-100 border border-emerald-300">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse mr-1"></span> <span id="map_engine_text">Google Maps SIG</span>
                    </span>
                </div>
                <p class="text-xs text-slate-500 mt-0.5">Surveillance spatiale en temps réel du patrimoine matériel et des structures sanitaires en Côte d'Ivoire</p>
            </div>
        </div>

        <!-- Badges KPI en Temps Réel -->
        <div class="flex flex-wrap items-center gap-2">
            <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                <i class="fa-solid fa-boxes-stacked text-[#155E3B]"></i>
                <span class="text-slate-600">Actifs visibles :</span>
                <span id="kpi_visible_assets" class="font-extrabold text-[#155E3B] font-mono text-sm">{{ count($mapFeatures) }}</span>
                <span class="text-slate-400 font-mono text-[10px]">/ {{ count($mapFeatures) }}</span>
            </div>

            <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200 text-xs">
                <i class="fa-solid fa-hospital text-blue-600"></i>
                <span class="text-slate-600">Structures :</span>
                <span class="font-extrabold text-slate-900 font-mono text-sm">{{ count($structuresSites) }}</span>
            </div>

            @php
                $alertCount = collect($mapFeatures)->where('geofence_alerte', true)->count();
            @endphp
            <div id="kpi_geofence_box" class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-red-50 border border-red-200 text-xs text-red-700 font-bold {{ $alertCount > 0 ? '' : 'hidden' }}">
                <i class="fa-solid fa-triangle-exclamation animate-bounce"></i>
                <span id="kpi_geofence_count">{{ $alertCount }}</span>
                <span>Alerte(s) Geofence</span>
            </div>
        </div>
    </div>

    <!-- 2. Barre de Filtres Rapides (Temps Réel) -->
    <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
        <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-2.5 text-xs">
            <div>
                <label class="block font-bold text-slate-600 uppercase text-[10px] mb-1">Programme</label>
                <select id="filter_programme" onchange="applyLiveFilters()" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-[#155E3B] focus:bg-white focus:outline-none">
                    <option value="">Tous les programmes</option>
                    @foreach($programmes as $p)
                        <option value="{{ $p->sigle ?? $p->code }}">{{ $p->sigle ?? $p->code }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-600 uppercase text-[10px] mb-1">Région Sanitaire</label>
                <select id="filter_region" onchange="applyLiveFilters()" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-[#155E3B] focus:bg-white focus:outline-none">
                    <option value="">Toutes les régions</option>
                    @foreach($regions as $r)
                        <option value="{{ $r->nom }}">{{ $r->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-600 uppercase text-[10px] mb-1">Structure / Site</label>
                <select id="filter_structure" onchange="applyLiveFilters()" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-[#155E3B] focus:bg-white focus:outline-none">
                    <option value="">Tous les sites</option>
                    @foreach($structures as $st)
                        <option value="{{ $st->id }}">{{ $st->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-600 uppercase text-[10px] mb-1">Catégorie</label>
                <select id="filter_categorie" onchange="applyLiveFilters()" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-[#155E3B] focus:bg-white focus:outline-none">
                    <option value="">Toutes catégories</option>
                    @foreach($categories as $c)
                        <option value="{{ $c->nom }}">{{ $c->nom }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-bold text-slate-600 uppercase text-[10px] mb-1">État Matériel</label>
                <select id="filter_etat" onchange="applyLiveFilters()" class="w-full px-2.5 py-1.5 bg-slate-50 border border-slate-200 rounded-lg text-xs font-semibold focus:ring-2 focus:ring-[#155E3B] focus:bg-white focus:outline-none">
                    <option value="">Tous les états</option>
                    <option value="bon_etat">Bon état / En service</option>
                    <option value="panne">En panne</option>
                    <option value="hors_service">Hors service</option>
                    <option value="reforme">Réformé</option>
                </select>
            </div>

            <div class="flex items-end">
                <button type="button" onclick="resetAllFilters()" class="w-full py-1.5 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs text-center transition flex items-center justify-center space-x-1">
                    <i class="fa-solid fa-rotate-left"></i>
                    <span>Réinitialiser</span>
                </button>
            </div>
        </div>
    </div>

    <!-- 3. Zone Carte & Volet Latéral "Actifs Filtrés" -->
    <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-start">

        <!-- Colonne Carte (3/4) -->
        <div class="lg:col-span-3 bg-white rounded-2xl border border-slate-200/90 shadow-xs relative overflow-hidden flex flex-col h-[620px]">
            
            <!-- Contrôles flottants sur la carte -->
            <div class="absolute top-3 left-3 z-[1000] flex flex-wrap items-center gap-2">
                <div class="bg-white/95 backdrop-blur-md px-3 py-1.5 rounded-xl border border-slate-200 shadow-md flex items-center space-x-3 text-xs">
                    <label class="inline-flex items-center space-x-1.5 cursor-pointer select-none">
                        <input type="checkbox" id="toggle_assets" checked onchange="toggleLayer('assets')" class="rounded text-[#155E3B] focus:ring-[#155E3B]">
                        <span class="font-bold text-slate-700">Immobilisations</span>
                    </label>
                    <span class="text-slate-300">|</span>
                    <label class="inline-flex items-center space-x-1.5 cursor-pointer select-none">
                        <input type="checkbox" id="toggle_structures" checked onchange="toggleLayer('structures')" class="rounded text-blue-600 focus:ring-blue-500">
                        <span class="font-bold text-slate-700">Structures</span>
                    </label>
                </div>

                <button type="button" onclick="fitAllMapBounds()" title="Recentrer la carte sur tous les points" class="bg-white/95 backdrop-blur-md hover:bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200 shadow-md text-xs font-bold text-slate-700 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-expand text-[#155E3B]"></i>
                    <span>Vue globale</span>
                </button>

                <!-- Bouton basculer Moteur Cartographique si besoin -->
                <button type="button" onclick="toggleMapEngine()" title="Changer de vue cartographique" class="bg-white/95 backdrop-blur-md hover:bg-slate-50 px-3 py-1.5 rounded-xl border border-slate-200 shadow-md text-xs font-bold text-slate-700 transition flex items-center space-x-1.5">
                    <i class="fa-solid fa-layer-group text-blue-600"></i>
                    <span id="btn_toggle_engine_text">Passer en Vue Satellite</span>
                </button>
            </div>

            <!-- Conteneur Carte avec double support Google Maps + OSM garanti -->
            <div id="sig_map_canvas" style="width: 100%; height: 620px; min-height: 620px;" class="bg-slate-100 z-0"></div>

            <!-- Légende Flottante -->
            <div class="absolute bottom-4 left-4 z-[1000] bg-white/95 backdrop-blur-md p-3 rounded-xl border border-slate-200 shadow-lg text-[11px] space-y-1.5 max-w-xs pointer-events-none">
                <div class="font-bold text-slate-900 border-b border-slate-100 pb-1 flex items-center justify-between">
                    <span>Légende Carte SIG</span>
                    <span class="text-[10px] text-slate-400 font-mono">UCP-FM</span>
                </div>
                <div class="grid grid-cols-2 gap-x-3 gap-y-1">
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-full bg-emerald-600 border border-white shadow-2xs"></span>
                        <span class="text-slate-600">Bon état</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-full bg-amber-500 border border-white shadow-2xs"></span>
                        <span class="text-slate-600">En panne</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-600 border border-white shadow-2xs"></span>
                        <span class="text-slate-600">Alerte / Critique</span>
                    </div>
                    <div class="flex items-center space-x-1.5">
                        <span class="w-3 h-3 rounded-full bg-blue-600 border border-white shadow-2xs"></span>
                        <span class="text-slate-600">Structure</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Volet Latéral "Actifs Filtrés" (1/4) avec CHAMP DE RECHERCHE DEDANS -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs flex flex-col h-[620px]">
            
            <!-- Titre et Badge de compteur -->
            <div class="flex items-center justify-between pb-2.5 border-b border-slate-100 mb-2.5 flex-shrink-0">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wide">Actifs Filtrés</h3>
                    <div class="text-[10px] text-slate-400">Cliquez pour situer sur la carte</div>
                </div>
                <span id="sidebar_count_badge" class="px-2 py-0.5 rounded-lg text-xs font-extrabold text-[#155E3B] bg-emerald-50 border border-emerald-200 font-mono">
                    {{ count($mapFeatures) }}
                </span>
            </div>

            <!-- CHAMP DE RECHERCHE DANS LA CARTE ACTIFS FILTRÉS (Filtrage instantané à chaque frappe) -->
            <div class="relative mb-3 flex-shrink-0">
                <input type="text"
                       id="sidebar_search_input"
                       oninput="applyLiveFilters()"
                       onkeyup="applyLiveFilters()"
                       placeholder="Rechercher code, nom, site, détenteur..."
                       class="w-full pl-8 pr-7 py-2 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium focus:ring-2 focus:ring-[#155E3B] focus:bg-white focus:outline-none transition shadow-2xs">
                <i class="fa-solid fa-magnifying-glass absolute left-2.5 top-2.5 text-slate-400 text-xs"></i>
                <button type="button" onclick="clearSidebarSearch()" id="btn_clear_sidebar_search" class="hidden absolute right-2 top-2 text-slate-400 hover:text-slate-700 text-xs p-0.5">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Liste Scrollable des Actifs -->
            <div class="flex-1 overflow-y-auto space-y-2 pr-1" id="assetsSidebarContainer">
                @forelse($mapFeatures as $item)
                <div id="card_asset_{{ $item['id'] }}"
                     onclick="focusAssetMarker({{ $item['id'] }}, {{ $item['latitude'] }}, {{ $item['longitude'] }})"
                     class="asset-sidebar-card p-2.5 rounded-xl border border-slate-200 hover:border-[#155E3B] hover:bg-emerald-50/40 cursor-pointer transition-all text-xs bg-slate-50/60"
                     data-id="{{ $item['id'] }}"
                     data-code="{{ strtolower($item['code']) }}"
                     data-designation="{{ strtolower($item['designation']) }}"
                     data-structure-id="{{ $item['structure_id'] ?? '' }}"
                     data-structure-name="{{ strtolower($item['structure']) }}"
                     data-programme="{{ strtolower($item['programme']) }}"
                     data-categorie="{{ strtolower($item['categorie']) }}"
                     data-etat="{{ strtolower($item['etat'] ?? '') }}"
                     data-detenteur="{{ strtolower($item['detenteur'] ?? '') }}"
                     data-ville="{{ strtolower($item['ville'] ?? '') }}"
                     data-geofence="{{ $item['geofence_alerte'] ? 'true' : 'false' }}">
                    
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-[#155E3B]">{{ $item['code'] }}</span>
                        @if($item['etat'] === 'panne')
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-amber-100 text-amber-800">Panne</span>
                        @elseif($item['etat'] === 'hors_service')
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-red-100 text-red-800">Hors Service</span>
                        @else
                            <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-emerald-100 text-emerald-800">En Service</span>
                        @endif
                    </div>

                    <div class="font-bold text-slate-900 mt-1 truncate">{{ $item['designation'] }}</div>
                    
                    <div class="text-[11px] text-slate-500 mt-1 flex items-center justify-between">
                        <span class="truncate mr-2"><i class="fa-solid fa-hospital text-slate-400 mr-1"></i>{{ $item['structure'] }}</span>
                        <span class="font-mono font-bold text-slate-700 flex-shrink-0 text-[10px]">{{ $item['precision_gps'] }}</span>
                    </div>

                    @if($item['geofence_alerte'])
                    <div class="mt-2 p-1.5 rounded-lg bg-red-50 border border-red-200 text-red-700 text-[10px] font-semibold flex items-center space-x-1">
                        <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
                        <span>Écart: +{{ $item['ecart_metres'] }} m</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-12 text-slate-400">
                    <i class="fa-solid fa-map-pin text-3xl mb-2 text-slate-300"></i>
                    <p class="text-xs">Aucun équipement géolocalisé pour ces filtres.</p>
                </div>
                @endforelse

                <div id="no_results_placeholder" class="hidden text-center py-12 text-slate-400">
                    <i class="fa-solid fa-magnifying-glass text-3xl mb-2 text-slate-300"></i>
                    <p class="text-xs">Aucun équipement ne correspond à votre recherche.</p>
                </div>
            </div>
        </div>

    </div>

</div>

<!-- Leaflet Library toujours disponible en secours direct -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<!-- JavaScript Google Maps & Filtrage Instantané -->
<script>
    const mapFeatures = @json($mapFeatures);
    const structuresSites = @json($structuresSites);

    window.activeMapEngine = null; // 'google' ou 'leaflet'
    window.gMap = null;
    window.lMap = null;
    window.gAssetMarkers = {};
    window.gStructureMarkers = {};
    window.lAssetMarkers = {};
    window.lStructureMarkers = {};
    window.gActiveInfoWindow = null;

    // 1. Démarrer Leaflet par défaut pour affichage instantané
    function loadLeafletEngine() {
        if (window.activeMapEngine === 'leaflet') return;
        const canvas = document.getElementById('sig_map_canvas');
        if (!canvas) return;

        canvas.innerHTML = '';
        window.lMap = L.map('sig_map_canvas', {
            center: [7.539989, -5.547080],
            zoom: 7,
            zoomControl: true
        });

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors',
            maxZoom: 19
        }).addTo(window.lMap);

        window.activeMapEngine = 'leaflet';

        const badge = document.getElementById('map_engine_text');
        if (badge) badge.textContent = 'Carte SIG Active';

        const toggleBtn = document.getElementById('btn_toggle_engine_text');
        if (toggleBtn) toggleBtn.textContent = 'Passer sur Google Maps';

        const latLngs = [];

        structuresSites.forEach(st => {
            if (st.latitude && st.longitude) {
                const lat = parseFloat(st.latitude);
                const lng = parseFloat(st.longitude);
                if (!isNaN(lat) && !isNaN(lng) && lat !== 0) {
                    latLngs.push([lat, lng]);
                    const icon = L.divIcon({
                        className: 'st-icon',
                        html: `<div style="background:#2563eb; width:16px; height:16px; border-radius:50%; border:2px solid white; box-shadow:0 0 6px rgba(0,0,0,0.5);"></div>`,
                        iconSize: [16, 16],
                        iconAnchor: [8, 8]
                    });
                    const marker = L.marker([lat, lng], { icon: icon }).addTo(window.lMap);
                    marker.bindPopup(`
                        <div style="font-family:'Plus Jakarta Sans',sans-serif; min-width:180px; padding:2px;">
                            <div style="font-size:10px; font-weight:bold; color:#2563eb; text-transform:uppercase;">${st.type_structure || 'Structure'}</div>
                            <strong style="color:#0f172a; font-size:13px;">${st.nom}</strong><br>
                            <span style="font-size:11px; color:#64748b;">${st.ville || "Côte d'Ivoire"}</span>
                        </div>
                    `, { className: 'custom-popup' });
                    window.lStructureMarkers[st.id] = marker;
                }
            }
        });

        mapFeatures.forEach(actif => {
            if (actif.latitude && actif.longitude) {
                const lat = parseFloat(actif.latitude);
                const lng = parseFloat(actif.longitude);
                if (!isNaN(lat) && !isNaN(lng) && lat !== 0) {
                    latLngs.push([lat, lng]);

                    let pinColor = '#155E3B';
                    if (actif.geofence_alerte || actif.niveau_criticite === 'critique') pinColor = '#dc2626';
                    else if (actif.etat === 'panne') pinColor = '#d97706';
                    else if (actif.etat === 'hors_service') pinColor = '#64748b';

                    const icon = L.divIcon({
                        className: 'actif-icon',
                        html: `<div style="background:${pinColor}; width:22px; height:22px; border-radius:50%; border:2px solid white; box-shadow:0 0 8px rgba(0,0,0,0.5); display:flex; align-items:center; justify-content:center; color:white; font-size:10px;"><i class="fa-solid fa-cube"></i></div>`,
                        iconSize: [22, 22],
                        iconAnchor: [11, 11]
                    });

                    const marker = L.marker([lat, lng], { icon: icon }).addTo(window.lMap);
                    marker.bindPopup(`
                        <div style="font-family:'Plus Jakarta Sans',sans-serif; min-width:220px; font-size:12px; line-height:1.4;">
                            <div style="font-size:10px; font-weight:bold; color:#155E3B; text-transform:uppercase;">${actif.programme} &bull; ${actif.categorie}</div>
                            <div style="font-weight:800; font-size:13px; color:#0f172a; margin-top:1px;">${actif.code}</div>
                            <div style="font-weight:600; color:#334155; margin-bottom:4px;">${actif.designation}</div>
                            <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:4px 6px; font-size:11px; color:#475569;">
                                <div><strong>Site:</strong> ${actif.structure}</div>
                                <div><strong>Détenteur:</strong> ${actif.detenteur}</div>
                                <div><strong>Précision:</strong> ${actif.precision_gps}</div>
                            </div>
                            <div style="margin-top:6px; text-align:right;">
                                <a href="${actif.url}" target="_blank" style="display:inline-block; padding:3px 8px; background:#155E3B; color:white; border-radius:4px; font-size:10px; font-weight:bold; text-decoration:none;">Voir fiche &rarr;</a>
                            </div>
                        </div>
                    `, { className: 'custom-popup' });
                    window.lAssetMarkers[actif.id] = marker;
                }
            }
        });

        if (latLngs.length > 0) {
            window.lMap.fitBounds(L.latLngBounds(latLngs), { padding: [30, 30] });
        }
        setTimeout(() => { if (window.lMap) window.lMap.invalidateSize(); }, 200);
    }

    // 2. Initialisation Google Maps
    window.initGoogleSIGMap = function() {
        if (typeof google === 'undefined' || typeof google.maps === 'undefined') return;
        const canvas = document.getElementById('sig_map_canvas');
        if (!canvas) return;

        try {
            canvas.innerHTML = '';
            window.gMap = new google.maps.Map(canvas, {
                center: { lat: 7.539989, lng: -5.547080 },
                zoom: 7,
                mapTypeId: 'roadmap',
                mapTypeControl: true,
                mapTypeControlOptions: {
                    style: google.maps.MapTypeControlStyle.HORIZONTAL_BAR,
                    position: google.maps.ControlPosition.TOP_RIGHT
                },
                streetViewControl: true,
                fullscreenControl: true,
                zoomControl: true,
            });

            window.gActiveInfoWindow = new google.maps.InfoWindow();
            window.activeMapEngine = 'google';

            const badge = document.getElementById('map_engine_text');
            if (badge) badge.textContent = 'Google Maps Actif';

            const toggleBtn = document.getElementById('btn_toggle_engine_text');
            if (toggleBtn) toggleBtn.textContent = 'Passer en Vue Standard';

            const bounds = new google.maps.LatLngBounds();
            let hasPts = false;

            structuresSites.forEach(st => {
                if (st.latitude && st.longitude) {
                    const lat = parseFloat(st.latitude);
                    const lng = parseFloat(st.longitude);
                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0) {
                        const pos = { lat: lat, lng: lng };
                        bounds.extend(pos);
                        hasPts = true;

                        const marker = new google.maps.Marker({
                            position: pos,
                            map: window.gMap,
                            title: st.nom,
                            icon: {
                                path: google.maps.SymbolPath.CIRCLE,
                                scale: 8,
                                fillColor: '#2563eb',
                                fillOpacity: 0.95,
                                strokeColor: '#ffffff',
                                strokeWeight: 2,
                            },
                            zIndex: 10
                        });

                        marker.addListener('click', () => {
                            const content = `
                                <div style="font-family:'Plus Jakarta Sans',sans-serif; min-width:200px; padding:4px;">
                                    <div style="font-size:10px; font-weight:bold; color:#2563eb; text-transform:uppercase;">${st.type_structure || 'Structure'}</div>
                                    <h4 style="margin:3px 0; font-size:13px; font-weight:800; color:#0f172a;">${st.nom}</h4>
                                    <div style="font-size:11px; color:#64748b;"><i class="fa-solid fa-location-dot"></i> ${st.ville || "Côte d'Ivoire"}</div>
                                </div>
                            `;
                            window.gActiveInfoWindow.setContent(content);
                            window.gActiveInfoWindow.open(window.gMap, marker);
                        });

                        window.gStructureMarkers[st.id] = marker;
                    }
                }
            });

            mapFeatures.forEach(actif => {
                if (actif.latitude && actif.longitude) {
                    const lat = parseFloat(actif.latitude);
                    const lng = parseFloat(actif.longitude);
                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0) {
                        const pos = { lat: lat, lng: lng };
                        bounds.extend(pos);
                        hasPts = true;

                        let markerColor = '#155E3B';
                        if (actif.geofence_alerte || actif.niveau_criticite === 'critique') markerColor = '#dc2626';
                        else if (actif.etat === 'panne') markerColor = '#d97706';
                        else if (actif.etat === 'hors_service') markerColor = '#64748b';

                        const marker = new google.maps.Marker({
                            position: pos,
                            map: window.gMap,
                            title: `${actif.code} - ${actif.designation}`,
                            icon: {
                                path: "M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z",
                                fillColor: markerColor,
                                fillOpacity: 1,
                                strokeColor: '#ffffff',
                                strokeWeight: 1.5,
                                scale: 1.4,
                                anchor: new google.maps.Point(12, 22),
                            },
                            zIndex: 20
                        });

                        marker.addListener('click', () => {
                            const content = `
                                <div style="font-family:'Plus Jakarta Sans',sans-serif; min-width:230px; max-width:280px; padding:4px; line-height:1.4;">
                                    <div style="font-size:10px; font-weight:bold; color:#155E3B; text-transform:uppercase;">${actif.programme} &bull; ${actif.categorie}</div>
                                    <h4 style="margin:2px 0; font-size:13px; font-weight:800; color:#0f172a;">${actif.code}</h4>
                                    <div style="font-size:11px; font-weight:600; color:#334155; margin-bottom:4px;">${actif.designation}</div>
                                    <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:6px; padding:6px 8px; font-size:11px; color:#475569;">
                                        <div><strong>Site:</strong> ${actif.structure}</div>
                                        <div><strong>Détenteur:</strong> ${actif.detenteur}</div>
                                        <div><strong>Précision:</strong> ${actif.precision_gps}</div>
                                    </div>
                                    <div style="margin-top:8px; text-align:right;">
                                        <a href="${actif.url}" target="_blank" style="display:inline-block; padding:4px 10px; background:#155E3B; color:#ffffff; border-radius:6px; font-size:11px; font-weight:bold; text-decoration:none;">Voir fiche &rarr;</a>
                                    </div>
                                </div>
                            `;
                            window.gActiveInfoWindow.setContent(content);
                            window.gActiveInfoWindow.open(window.gMap, marker);
                        });

                        window.gAssetMarkers[actif.id] = marker;
                    }
                }
            });

            if (hasPts) {
                window.gMap.fitBounds(bounds);
            }
        } catch (e) {
            loadLeafletEngine();
        }
    };

    function toggleMapEngine() {
        if (window.activeMapEngine === 'google') {
            loadLeafletEngine();
        } else {
            if (typeof google !== 'undefined' && typeof google.maps !== 'undefined') {
                initGoogleSIGMap();
            } else {
                alert("Google Maps API n'a pas pu être chargée. La vue satellite OpenStreetMap reste active.");
            }
        }
        applyLiveFilters();
    }

    function focusAssetMarker(id, lat, lng) {
        const pLat = parseFloat(lat);
        const pLng = parseFloat(lng);

        if (window.activeMapEngine === 'google' && window.gMap && !isNaN(pLat) && !isNaN(pLng)) {
            window.gMap.panTo({ lat: pLat, lng: pLng });
            window.gMap.setZoom(17);
            const m = window.gAssetMarkers[id];
            if (m) google.maps.event.trigger(m, 'click');
        } else if (window.lMap) {
            window.lMap.setView([pLat, pLng], 16);
            const m = window.lAssetMarkers[id];
            if (m) m.openPopup();
        }

        document.querySelectorAll('.asset-sidebar-card').forEach(card => {
            card.classList.remove('ring-2', 'ring-[#155E3B]', 'bg-emerald-50');
        });
        const activeCard = document.getElementById('card_asset_' + id);
        if (activeCard) {
            activeCard.classList.add('ring-2', 'ring-[#155E3B]', 'bg-emerald-50');
        }
    }

    // Filtrage instantané en temps réel dans la liste ET sur la carte
    function applyLiveFilters() {
        const query = (document.getElementById('sidebar_search_input')?.value || '').trim().toLowerCase();
        const selProgramme = (document.getElementById('filter_programme')?.value || '').trim().toLowerCase();
        const selRegion = (document.getElementById('filter_region')?.value || '').trim().toLowerCase();
        const selStructureId = (document.getElementById('filter_structure')?.value || '').trim();
        const selCategorie = (document.getElementById('filter_categorie')?.value || '').trim().toLowerCase();
        const selEtat = (document.getElementById('filter_etat')?.value || '').trim().toLowerCase();

        const clearBtn = document.getElementById('btn_clear_sidebar_search');
        if (clearBtn) {
            clearBtn.classList.toggle('hidden', query.length === 0);
        }

        let visibleCount = 0;
        let visibleAlerts = 0;
        const gBounds = (window.activeMapEngine === 'google' && typeof google !== 'undefined') ? new google.maps.LatLngBounds() : null;
        const lLatLngs = [];
        let hasVisibleMarkers = false;

        document.querySelectorAll('.asset-sidebar-card').forEach(card => {
            const id = card.getAttribute('data-id');
            const code = card.getAttribute('data-code') || '';
            const designation = card.getAttribute('data-designation') || '';
            const structureId = card.getAttribute('data-structure-id') || '';
            const structureName = card.getAttribute('data-structure-name') || '';
            const programme = card.getAttribute('data-programme') || '';
            const categorie = card.getAttribute('data-categorie') || '';
            const etat = card.getAttribute('data-etat') || '';
            const detenteur = card.getAttribute('data-detenteur') || '';
            const ville = card.getAttribute('data-ville') || '';
            const isGeofence = card.getAttribute('data-geofence') === 'true';

            let matchesSearch = true;
            if (query) {
                const combined = `${code} ${designation} ${structureName} ${programme} ${categorie} ${detenteur} ${ville}`;
                matchesSearch = combined.includes(query);
            }

            let matchesProgramme = !selProgramme || programme.includes(selProgramme);
            let matchesRegion = !selRegion || ville.includes(selRegion);
            let matchesStructure = !selStructureId || structureId === selStructureId;
            let matchesCategorie = !selCategorie || categorie.includes(selCategorie);
            let matchesEtat = !selEtat || etat === selEtat;

            const isVisible = matchesSearch && matchesProgramme && matchesRegion && matchesStructure && matchesCategorie && matchesEtat;

            if (isVisible) {
                card.style.display = 'block';
                visibleCount++;
                if (isGeofence) visibleAlerts++;

                if (window.gAssetMarkers && window.gAssetMarkers[id]) {
                    window.gAssetMarkers[id].setVisible(true);
                    if (gBounds) gBounds.extend(window.gAssetMarkers[id].getPosition());
                    hasVisibleMarkers = true;
                }
                if (window.lAssetMarkers && window.lAssetMarkers[id]) {
                    if (window.lMap && !window.lMap.hasLayer(window.lAssetMarkers[id])) {
                        window.lAssetMarkers[id].addTo(window.lMap);
                    }
                    lLatLngs.push(window.lAssetMarkers[id].getLatLng());
                    hasVisibleMarkers = true;
                }
            } else {
                card.style.display = 'none';
                if (window.gAssetMarkers && window.gAssetMarkers[id]) {
                    window.gAssetMarkers[id].setVisible(false);
                }
                if (window.lAssetMarkers && window.lAssetMarkers[id] && window.lMap) {
                    window.lMap.removeLayer(window.lAssetMarkers[id]);
                }
            }
        });

        const kpiVisible = document.getElementById('kpi_visible_assets');
        if (kpiVisible) kpiVisible.textContent = visibleCount;

        const sidebarBadge = document.getElementById('sidebar_count_badge');
        if (sidebarBadge) sidebarBadge.textContent = visibleCount;

        const noResults = document.getElementById('no_results_placeholder');
        if (noResults) noResults.classList.toggle('hidden', visibleCount > 0);

        const kpiBox = document.getElementById('kpi_geofence_box');
        const kpiCount = document.getElementById('kpi_geofence_count');
        if (kpiBox && kpiCount) {
            kpiCount.textContent = visibleAlerts;
            kpiBox.classList.toggle('hidden', visibleAlerts === 0);
        }

        if (hasVisibleMarkers) {
            if (window.activeMapEngine === 'google' && window.gMap && gBounds) {
                window.gMap.fitBounds(gBounds);
                if (visibleCount === 1) window.gMap.setZoom(16);
            } else if (window.activeMapEngine === 'leaflet' && window.lMap && lLatLngs.length > 0) {
                window.lMap.fitBounds(L.latLngBounds(lLatLngs), { padding: [30, 30] });
                if (visibleCount === 1) window.lMap.setZoom(16);
            }
        }
    }

    function clearSidebarSearch() {
        const input = document.getElementById('sidebar_search_input');
        if (input) {
            input.value = '';
            applyLiveFilters();
        }
    }

    function resetAllFilters() {
        const search = document.getElementById('sidebar_search_input');
        const prog = document.getElementById('filter_programme');
        const reg = document.getElementById('filter_region');
        const st = document.getElementById('filter_structure');
        const cat = document.getElementById('filter_categorie');
        const etat = document.getElementById('filter_etat');

        if (search) search.value = '';
        if (prog) prog.value = '';
        if (reg) reg.value = '';
        if (st) st.value = '';
        if (cat) cat.value = '';
        if (etat) etat.value = '';

        applyLiveFilters();
        fitAllMapBounds();
    }

    function toggleLayer(type) {
        if (type === 'assets') {
            const show = document.getElementById('toggle_assets').checked;
            Object.values(window.gAssetMarkers).forEach(m => m.setVisible(show));
            Object.values(window.lAssetMarkers).forEach(m => {
                if (show) { if (window.lMap) m.addTo(window.lMap); }
                else { if (window.lMap) window.lMap.removeLayer(m); }
            });
        } else if (type === 'structures') {
            const show = document.getElementById('toggle_structures').checked;
            Object.values(window.gStructureMarkers).forEach(m => m.setVisible(show));
            Object.values(window.lStructureMarkers).forEach(m => {
                if (show) { if (window.lMap) m.addTo(window.lMap); }
                else { if (window.lMap) window.lMap.removeLayer(m); }
            });
        }
    }

    function fitAllMapBounds() {
        if (window.activeMapEngine === 'google' && window.gMap) {
            const bounds = new google.maps.LatLngBounds();
            let has = false;
            Object.values(window.gAssetMarkers).forEach(m => { if (m.getVisible()) { bounds.extend(m.getPosition()); has = true; } });
            Object.values(window.gStructureMarkers).forEach(m => { if (m.getVisible()) { bounds.extend(m.getPosition()); has = true; } });
            if (has) window.gMap.fitBounds(bounds);
        } else if (window.lMap) {
            const pts = [];
            Object.values(window.lAssetMarkers).forEach(m => { if (window.lMap.hasLayer(m)) pts.push(m.getLatLng()); });
            Object.values(window.lStructureMarkers).forEach(m => { if (window.lMap.hasLayer(m)) pts.push(m.getLatLng()); });
            if (pts.length > 0) window.lMap.fitBounds(L.latLngBounds(pts), { padding: [30, 30] });
        }
    }

    // Démarrage instantané
    loadLeafletEngine();
</script>

<!-- Script Google Maps API avec callback automatique -->
<script src="https://maps.googleapis.com/maps/api/js?key={{ config('services.google_maps.api_key', 'AIzaSyCRXwpzz4SVK_wQpOeh4p3-sSOq1c7Qqg4') }}&libraries=places&callback=initGoogleSIGMap" async defer></script>
@endsection
