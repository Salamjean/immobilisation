<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Carte SIG Mobile | UCP-FM Terrain</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500;600;700&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff4ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#0048FA',
                            700: '#003ad1',
                            800: '#002eb3',
                            900: '#002080',
                        }
                    },
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        mono: ['"JetBrains Mono"', 'monospace'],
                    },
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- Leaflet CSS & JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .app-bg-pattern {
            background-color: #f3f6fc;
            background-image: url("{{ asset('images/topography-pattern.svg') }}");
            background-size: 700px 700px;
            background-repeat: repeat;
            background-attachment: fixed;
        }

        .leaflet-container {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
            width: 100% !important;
            height: 100% !important;
            z-index: 10;
        }

        .custom-popup .leaflet-popup-content-wrapper {
            border-radius: 1rem;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.2);
            padding: 4px;
        }
        .custom-popup .leaflet-popup-content {
            margin: 8px 12px;
            line-height: 1.4;
        }

        /* Pulsation Marker Position Actuelle */
        @keyframes pulseUser {
            0% { transform: scale(0.9); opacity: 0.9; }
            50% { transform: scale(1.4); opacity: 0.3; }
            100% { transform: scale(0.9); opacity: 0.9; }
        }
        .user-pulse-ring {
            position: absolute;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background: rgba(16, 185, 129, 0.4);
            animation: pulseUser 2s infinite ease-out;
            top: -6px;
            left: -6px;
        }
    </style>
</head>
<body class="h-full app-bg-pattern text-slate-800 flex flex-col antialiased overflow-hidden select-none">

    <!-- 1. Header Mobile (Identique Scanner PWA) -->
    <header class="bg-white border-b border-slate-200/90 p-3.5 sticky top-0 z-30 flex items-center justify-between shadow-xs">
        <div class="flex items-center space-x-2.5">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo UCP-FM" class="h-8 w-auto max-w-[120px] object-contain flex-shrink-0">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #0048FA;">
                SIG Mobile
            </span>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Badge GPS Live -->
            <span id="gpsStatusBadge" class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center space-x-1.5 shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span id="gpsText">GPS: Actif</span>
            </span>

            <!-- Déconnexion -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Se déconnecter" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-xs font-bold hover:bg-rose-100 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- 2. Zone Carte Plein Écran & Overlays Mobiles -->
    <main class="flex-1 relative overflow-hidden flex flex-col">

        <!-- Barre de recherche & Filtres flottants supérieurs -->
        <div class="absolute top-3 left-3 right-3 z-20 space-y-2 pointer-events-none">
            
            <!-- Champ de recherche instantané -->
            <div class="relative pointer-events-auto shadow-lg rounded-2xl">
                <input type="text"
                       id="mobile_search_input"
                       oninput="applyLiveFilters()"
                       onkeyup="applyLiveFilters()"
                       placeholder="Rechercher code, bien, structure..."
                       class="w-full pl-9 pr-8 py-2.5 bg-white/95 backdrop-blur-md border border-slate-200/90 rounded-2xl text-xs font-bold text-slate-800 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-[#0048FA] shadow-sm">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-3 text-slate-400 text-xs"></i>
                <button type="button" onclick="clearMobileSearch()" id="btn_clear_search" class="hidden absolute right-3 top-2.5 text-slate-400 hover:text-slate-700 text-xs p-1">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>

            <!-- Chips / Filtres Horizontaux Déroulants -->
            <div class="flex items-center space-x-1.5 overflow-x-auto pb-1 no-scrollbar pointer-events-auto">
                <button type="button" onclick="setQuickFilter('all')" id="chip_all" class="filter-chip px-3.5 py-1.5 rounded-xl text-[11px] font-bold bg-[#0048FA] text-white shadow-md transition-all whitespace-nowrap">
                    Tous ({{ count($mapFeatures) }})
                </button>
                <button type="button" onclick="setQuickFilter('panne')" id="chip_panne" class="filter-chip px-3.5 py-1.5 rounded-xl text-[11px] font-bold bg-white/95 backdrop-blur-md border border-slate-200 text-amber-700 shadow-sm transition-all whitespace-nowrap">
                    <i class="fa-solid fa-triangle-exclamation mr-1 text-amber-500"></i> En Panne
                </button>
                <button type="button" onclick="setQuickFilter('bon_etat')" id="chip_bon_etat" class="filter-chip px-3.5 py-1.5 rounded-xl text-[11px] font-bold bg-white/95 backdrop-blur-md border border-slate-200 text-emerald-700 shadow-sm transition-all whitespace-nowrap">
                    <i class="fa-solid fa-circle-check mr-1 text-emerald-500"></i> En Service
                </button>
            </div>
        </div>

        <!-- Boutons d'Action Flottants (Droite) -->
        <div class="absolute right-3 top-28 z-20 flex flex-col space-y-2 pointer-events-auto">
            <!-- Bouton Centrer sur ma Position -->
            <button type="button" onclick="centerOnUserPosition()" title="Ma position GPS" class="w-10 h-10 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200 text-emerald-700 shadow-lg flex items-center justify-center hover:bg-emerald-50 transition-all">
                <i class="fa-solid fa-location-crosshairs text-base"></i>
            </button>

            <!-- Bouton Vue Globale -->
            <button type="button" onclick="fitAllMapBounds()" title="Vue Globale" class="w-10 h-10 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200 text-slate-700 shadow-lg flex items-center justify-center hover:bg-slate-50 transition-all">
                <i class="fa-solid fa-expand text-sm"></i>
            </button>

            <!-- Bouton Alterner Tiroir Liste -->
            <button type="button" onclick="toggleDrawer()" id="btn_toggle_drawer" title="Afficher la liste" class="w-10 h-10 rounded-2xl bg-white/95 backdrop-blur-md border border-slate-200 text-[#0048FA] shadow-lg flex items-center justify-center hover:bg-emerald-50 transition-all">
                <i class="fa-solid fa-list-ul text-sm"></i>
            </button>
        </div>

        <!-- Canvas Carte Leaflet & Google Maps -->
        <div id="sig_map_canvas" class="w-full h-full bg-slate-200 z-10"></div>

        <!-- 3. Tiroir / Bottom Sheet Rétractable pour la liste des Actifs -->
        <div id="assetsDrawer" class="absolute bottom-16 left-0 right-0 z-20 bg-white/98 backdrop-blur-xl border-t border-slate-200 shadow-2xl rounded-t-3xl transition-transform duration-300 max-h-[55vh] flex flex-col transform translate-y-full">
            
            <!-- Poignée du tiroir -->
            <div class="p-3 border-b border-slate-100 flex items-center justify-between cursor-pointer" onclick="toggleDrawer()">
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 rounded-full bg-[#0048FA]"></span>
                    <span class="text-xs font-bold text-slate-900 uppercase">Actifs à proximité (<span id="drawer_count">{{ count($mapFeatures) }}</span>)</span>
                </div>
                <div class="w-8 h-1 bg-slate-300 rounded-full mx-auto"></div>
                <button type="button" class="text-slate-400 hover:text-slate-700 text-xs">
                    <i id="drawerChevron" class="fa-solid fa-chevron-up transition-transform"></i>
                </button>
            </div>

            <!-- Liste scrollable des cartes -->
            <div class="flex-1 overflow-y-auto p-3 space-y-2.5 max-h-[45vh]" id="drawerCardsContainer">
                @forelse($mapFeatures as $item)
                <div id="card_asset_{{ $item['id'] }}"
                     onclick="focusAssetMarker({{ $item['id'] }}, {{ $item['latitude'] }}, {{ $item['longitude'] }})"
                     class="mobile-asset-card p-3 rounded-2xl border border-slate-200 hover:border-[#0048FA] bg-slate-50/70 cursor-pointer transition-all text-xs"
                     data-id="{{ $item['id'] }}"
                     data-code="{{ strtolower($item['code']) }}"
                     data-designation="{{ strtolower($item['designation']) }}"
                     data-structure="{{ strtolower($item['structure']) }}"
                     data-programme="{{ strtolower($item['programme']) }}"
                     data-etat="{{ strtolower($item['etat'] ?? '') }}">
                    
                    <div class="flex items-center justify-between">
                        <span class="font-mono font-bold text-[#0048FA]">{{ $item['code'] }}</span>
                        @if($item['etat'] === 'panne')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-amber-100 text-amber-800">Panne</span>
                        @elseif($item['etat'] === 'hors_service')
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-red-100 text-red-800">Hors Service</span>
                        @else
                            <span class="px-2 py-0.5 rounded-full text-[9px] font-bold bg-emerald-100 text-emerald-800">En Service</span>
                        @endif
                    </div>

                    <div class="font-bold text-slate-900 mt-1 truncate">{{ $item['designation'] }}</div>
                    
                    <div class="text-[11px] text-slate-500 mt-1 flex items-center justify-between">
                        <span class="truncate mr-2"><i class="fa-solid fa-hospital text-slate-400 mr-1"></i>{{ $item['structure'] }}</span>
                        <span class="font-mono font-bold text-slate-700 text-[10px]">{{ $item['precision_gps'] }}</span>
                    </div>

                    @if($item['geofence_alerte'])
                    <div class="mt-2 p-1.5 rounded-xl bg-red-50 border border-red-200 text-red-700 text-[10px] font-semibold flex items-center space-x-1">
                        <i class="fa-solid fa-triangle-exclamation flex-shrink-0"></i>
                        <span>Écart: +{{ $item['ecart_metres'] }} m</span>
                    </div>
                    @endif
                </div>
                @empty
                <div class="text-center py-8 text-slate-400 text-xs">
                    Aucun actif géolocalisé.
                </div>
                @endforelse
            </div>
        </div>

    </main>

    <!-- 4. Bottom Navigation Bar Mobile & Tablette Pleine Largeur -->
    <nav class="bg-white/95 backdrop-blur-md border-t border-slate-200/90 fixed bottom-0 left-0 right-0 p-2 z-30 flex items-center justify-around text-[10px] sm:text-xs font-bold text-slate-500 shadow-xl w-full">
        <a href="{{ route('agent.mobile.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#0048FA] transition-colors">
            <i class="fa-solid fa-qrcode text-base sm:text-lg mb-0.5"></i>
            <span>Scanner</span>
        </a>
        <a href="{{ route('agent.cartographie.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-[#0048FA]">
            <i class="fa-solid fa-map-location-dot text-base sm:text-lg mb-0.5"></i>
            <span>Carte SIG</span>
        </a>
        <a href="{{ route('agent.campagnes.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#0048FA] transition-colors">
            <i class="fa-solid fa-clipboard-check text-base sm:text-lg mb-0.5"></i>
            <span>Campagnes</span>
        </a>
        <button type="button" onclick="recupererDonneesHorsLigne()" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#0048FA] transition-colors">
            <i class="fa-solid fa-cloud-arrow-down text-base sm:text-lg mb-0.5"></i>
            <span>Pack Offline</span>
        </button>
    </nav>

    <!-- Scripts Cartographie SIG Mobile -->
    <script>
        const mapFeatures = @json($mapFeatures);
        const structuresSites = @json($structuresSites);

        window.lMap = null;
        window.lAssetMarkers = {};
        window.lStructureMarkers = {};
        window.userMarker = null;
        window.userCircle = null;
        let activeFilter = 'all';
        let isDrawerOpen = false;

        function initMobileMap() {
            const canvas = document.getElementById('sig_map_canvas');
            if (!canvas) return;

            window.lMap = L.map('sig_map_canvas', {
                center: [7.539989, -5.547080],
                zoom: 7,
                zoomControl: false // Commandes mobiles dédiées
            });

            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap',
                maxZoom: 19
            }).addTo(window.lMap);

            const latLngs = [];

            // 1. Structures
            structuresSites.forEach(st => {
                if (st.latitude && st.longitude) {
                    const lat = parseFloat(st.latitude);
                    const lng = parseFloat(st.longitude);
                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0) {
                        latLngs.push([lat, lng]);
                        const icon = L.divIcon({
                            className: 'st-icon',
                            html: `<div style="background:#2563eb; width:18px; height:18px; border-radius:50%; border:2px solid white; box-shadow:0 0 6px rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; color:white; font-size:9px;"><i class="fa-solid fa-hospital"></i></div>`,
                            iconSize: [18, 18],
                            iconAnchor: [9, 9]
                        });
                        const marker = L.marker([lat, lng], { icon: icon }).addTo(window.lMap);
                        marker.bindPopup(`
                            <div style="font-family:'Plus Jakarta Sans',sans-serif; min-width:180px; padding:2px;">
                                <div style="font-size:9px; font-weight:bold; color:#2563eb; text-transform:uppercase;">${st.type_structure || 'Structure'}</div>
                                <strong style="color:#0f172a; font-size:12px;">${st.nom}</strong><br>
                                <span style="font-size:10px; color:#64748b;">${st.ville || "Côte d'Ivoire"}</span>
                            </div>
                        `, { className: 'custom-popup' });
                        window.lStructureMarkers[st.id] = marker;
                    }
                }
            });

            // 2. Actifs Immobilisés
            mapFeatures.forEach(actif => {
                if (actif.latitude && actif.longitude) {
                    const lat = parseFloat(actif.latitude);
                    const lng = parseFloat(actif.longitude);
                    if (!isNaN(lat) && !isNaN(lng) && lat !== 0) {
                        latLngs.push([lat, lng]);

                        let pinColor = '#0048FA';
                        if (actif.geofence_alerte || actif.niveau_criticite === 'critique') pinColor = '#dc2626';
                        else if (actif.etat === 'panne') pinColor = '#d97706';
                        else if (actif.etat === 'hors_service') pinColor = '#64748b';

                        const icon = L.divIcon({
                            className: 'actif-icon',
                            html: `<div style="background:${pinColor}; width:24px; height:24px; border-radius:50%; border:2px solid white; box-shadow:0 0 8px rgba(0,0,0,0.4); display:flex; align-items:center; justify-content:center; color:white; font-size:10px;"><i class="fa-solid fa-cube"></i></div>`,
                            iconSize: [24, 24],
                            iconAnchor: [12, 12]
                        });

                        const marker = L.marker([lat, lng], { icon: icon }).addTo(window.lMap);
                        marker.bindPopup(`
                            <div style="font-family:'Plus Jakarta Sans',sans-serif; min-width:210px; font-size:11px; line-height:1.4;">
                                <div style="font-size:9px; font-weight:800; color:#0048FA; text-transform:uppercase;">${actif.programme} &bull; ${actif.categorie}</div>
                                <div style="font-weight:800; font-size:12px; color:#0f172a; margin-top:1px;">${actif.code}</div>
                                <div style="font-weight:600; color:#334155; margin-bottom:4px;">${actif.designation}</div>
                                <div style="background:#f8fafc; border:1px solid #e2e8f0; border-radius:8px; padding:5px; font-size:10px; color:#475569;">
                                    <div><strong>Site:</strong> ${actif.structure}</div>
                                    <div><strong>Détenteur:</strong> ${actif.detenteur}</div>
                                    <div><strong>Précision:</strong> ${actif.precision_gps}</div>
                                </div>
                            </div>
                        `, { className: 'custom-popup' });
                        window.lAssetMarkers[actif.id] = marker;
                    }
                }
            });

            if (latLngs.length > 0) {
                window.lMap.fitBounds(L.latLngBounds(latLngs), { padding: [40, 40] });
            }

            // Géolocalisation automatique de l'agent
            centerOnUserPosition(false);
        }

        // Géolocalisation GPS de l'agent
        function centerOnUserPosition(zoom = true) {
            if (!navigator.geolocation) {
                document.getElementById('gpsText').textContent = 'GPS Non Supporté';
                return;
            }

            navigator.geolocation.getCurrentPosition(pos => {
                const lat = pos.coords.latitude;
                const lng = pos.coords.longitude;
                const acc = pos.coords.accuracy;

                document.getElementById('gpsText').textContent = `GPS: ±${Math.round(acc)}m`;
                document.getElementById('gpsStatusBadge').className = 'px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1.5 shadow-2xs';

                if (window.userMarker) window.lMap.removeLayer(window.userMarker);
                if (window.userCircle) window.lMap.removeLayer(window.userCircle);

                const userIcon = L.divIcon({
                    className: 'user-pos-icon',
                    html: `
                        <div style="position:relative; width:20px; height:20px;">
                            <div class="user-pulse-ring"></div>
                            <div style="width:20px; height:20px; border-radius:50%; background:#10b981; border:3px solid white; box-shadow:0 0 10px rgba(0,0,0,0.5);"></div>
                        </div>
                    `,
                    iconSize: [20, 20],
                    iconAnchor: [10, 10]
                });

                window.userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(window.lMap);
                window.userCircle = L.circle([lat, lng], { radius: acc, color: '#10b981', fillOpacity: 0.1, weight: 1 }).addTo(window.lMap);

                if (zoom && window.lMap) {
                    window.lMap.setView([lat, lng], 16);
                }
            }, err => {
                document.getElementById('gpsText').textContent = 'GPS Inactif';
            }, { enableHighAccuracy: true, timeout: 8000 });
        }

        function fitAllMapBounds() {
            if (!window.lMap) return;
            const pts = [];
            Object.values(window.lAssetMarkers).forEach(m => { if (window.lMap.hasLayer(m)) pts.push(m.getLatLng()); });
            Object.values(window.lStructureMarkers).forEach(m => { if (window.lMap.hasLayer(m)) pts.push(m.getLatLng()); });
            if (pts.length > 0) window.lMap.fitBounds(L.latLngBounds(pts), { padding: [40, 40] });
        }

        // Filtrage instantané
        function setQuickFilter(type) {
            activeFilter = type;
            document.querySelectorAll('.filter-chip').forEach(c => {
                c.className = 'filter-chip px-3 py-1.5 rounded-xl text-[11px] font-bold bg-white/95 backdrop-blur-md border border-slate-200 text-slate-700 shadow-sm transition-all whitespace-nowrap';
            });

            const activeBtn = document.getElementById('chip_' + type);
            if (activeBtn) {
                activeBtn.className = 'filter-chip px-3 py-1.5 rounded-xl text-[11px] font-bold bg-[#0048FA] text-white shadow-md transition-all whitespace-nowrap';
            }

            applyLiveFilters();
        }

        function applyLiveFilters() {
            const search = (document.getElementById('mobile_search_input')?.value || '').toLowerCase().trim();
            const btnClear = document.getElementById('btn_clear_search');
            if (btnClear) btnClear.classList.toggle('hidden', search === '');

            let visibleCount = 0;
            const cards = document.querySelectorAll('.mobile-asset-card');
            const lLatLngs = [];

            // Gérer couches structures
            const showStructures = (activeFilter === 'all' || activeFilter === 'structures');
            Object.values(window.lStructureMarkers).forEach(m => {
                if (showStructures) { if (!window.lMap.hasLayer(m)) m.addTo(window.lMap); }
                else { if (window.lMap.hasLayer(m)) window.lMap.removeLayer(m); }
            });

            cards.forEach(card => {
                const id = card.dataset.id;
                const code = card.dataset.code || '';
                const desig = card.dataset.designation || '';
                const st = card.dataset.structure || '';
                const prog = card.dataset.programme || '';
                const etat = card.dataset.etat || '';

                let match = true;

                if (search !== '') {
                    const combined = `${code} ${desig} ${st} ${prog}`;
                    if (!combined.includes(search)) match = false;
                }

                if (match && activeFilter === 'panne' && etat !== 'panne') match = false;
                if (match && activeFilter === 'bon_etat' && (etat === 'panne' || etat === 'hors_service')) match = false;
                if (match && activeFilter === 'structures') match = false;

                if (match) {
                    card.style.display = 'block';
                    visibleCount++;
                    if (window.lAssetMarkers[id]) {
                        if (!window.lMap.hasLayer(window.lAssetMarkers[id])) {
                            window.lAssetMarkers[id].addTo(window.lMap);
                        }
                        lLatLngs.push(window.lAssetMarkers[id].getLatLng());
                    }
                } else {
                    card.style.display = 'none';
                    if (window.lAssetMarkers[id] && window.lMap.hasLayer(window.lAssetMarkers[id])) {
                        window.lMap.removeLayer(window.lAssetMarkers[id]);
                    }
                }
            });

            const countElem = document.getElementById('drawer_count');
            if (countElem) countElem.textContent = visibleCount;

            if (visibleCount > 0 && lLatLngs.length > 0 && search !== '') {
                window.lMap.fitBounds(L.latLngBounds(lLatLngs), { padding: [40, 40] });
            }
        }

        function clearMobileSearch() {
            const input = document.getElementById('mobile_search_input');
            if (input) {
                input.value = '';
                applyLiveFilters();
            }
        }

        function focusAssetMarker(id, lat, lng) {
            if (!lat || !lng || !window.lMap) return;
            window.lMap.setView([parseFloat(lat), parseFloat(lng)], 17);
            const m = window.lAssetMarkers[id];
            if (m) m.openPopup();
            toggleDrawer(false); // fermer tiroir
        }

        function toggleDrawer(forceState = null) {
            const drawer = document.getElementById('assetsDrawer');
            const chevron = document.getElementById('drawerChevron');
            if (!drawer) return;

            isDrawerOpen = forceState !== null ? forceState : !isDrawerOpen;

            if (isDrawerOpen) {
                drawer.classList.remove('translate-y-full');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                drawer.classList.add('translate-y-full');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }

        function recupererDonneesHorsLigne() {
            alert("Les données SIG sont prêtes pour consultation locale hors-ligne.");
        }

        document.addEventListener('DOMContentLoaded', () => {
            initMobileMap();
        });
    </script>
</body>
</html>
