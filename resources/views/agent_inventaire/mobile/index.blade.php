<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Inventaire Mobile Terrain | UCP-FM Côte d'Ivoire</title>

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
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#155E3B',
                            700: '#0f442a',
                            800: '#064e3b',
                            900: '#022c22',
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

    <!-- HTML5 QR Code Scanner Library -->
    <script src="https://unpkg.com/html5-qrcode"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        /* Arrière-plan Topographique Moderne Admin */
        .app-bg-pattern {
            background-color: #f3f6fc;
            background-image: url("{{ asset('images/topography-pattern.svg') }}");
            background-size: 700px 700px;
            background-repeat: repeat;
            background-attachment: fixed;
        }

        /* Animation pulsation laser scanner */
        @keyframes scanLaser {
            0% { top: 5%; opacity: 0.8; }
            50% { top: 90%; opacity: 1; }
            100% { top: 5%; opacity: 0.8; }
        }
        .scanner-laser {
            position: absolute;
            left: 5%;
            right: 5%;
            height: 2px;
            background: linear-gradient(90deg, transparent, #10b981, #155E3B, #10b981, transparent);
            box-shadow: 0 0 8px #10b981;
            animation: scanLaser 2s infinite ease-in-out;
        }
    </style>
</head>
<body class="h-full app-bg-pattern text-slate-800 flex flex-col antialiased">

    <!-- Header Mobile Moderne (Style Admin / Vert Émeraude) -->
    <header class="bg-white border-b border-slate-200/90 p-3.5 sticky top-0 z-30 flex items-center justify-between shadow-xs">
        <div class="flex items-center space-x-2.5">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo UCP-FM" class="h-8 w-auto max-w-[120px] object-contain flex-shrink-0">
            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #155E3B;">
                Terrain
            </span>
        </div>

        <div class="flex items-center space-x-2">
            <!-- Badge GPS -->
            <span id="gpsStatusBadge" class="px-2.5 py-1 rounded-xl text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200 flex items-center space-x-1.5 shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                <span id="gpsText">GPS: Recherche...</span>
            </span>

            <!-- Bouton Déconnexion -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Se déconnecter" class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-xs font-bold hover:bg-rose-100 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- Contenu Principal Mobile & Tablette Pleine Largeur -->
    <main class="flex-1 p-3.5 sm:p-5 md:p-6 overflow-y-auto space-y-4 pb-24 w-full">

        <!-- 1. Sélection Campagne Active -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3 relative">
            <!-- En-tête avec Statut -->
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-2">
                    <div class="w-7 h-7 rounded-lg bg-emerald-50 text-[#155E3B] flex items-center justify-center text-xs font-bold border border-emerald-200">
                        <i class="fa-solid fa-clipboard-check"></i>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Campagne de rattachement</span>
                        <h2 class="text-xs font-extrabold text-slate-900">Campagne Active de Terrain</h2>
                    </div>
                </div>
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shadow-2xs">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1.5"></span>
                    En cours
                </span>
            </div>

            @if($campagnes->isNotEmpty())
                <!-- CHAMP UNIQUE : SELECT AVEC FILTRE INTÉGRÉ (COMBOBOX RECHERCHABLE) -->
                <div class="relative" id="campagneComboboxWrapper">
                    <!-- Champ caché pour compatibilité formulaire et JS -->
                    <input type="hidden" id="selectCampagne" name="campagne_id" value="{{ $activeCampagne?->id }}">

                    <div class="relative flex items-center">
                        <div class="absolute left-3.5 pointer-events-none text-[#155E3B]">
                            <i class="fa-solid fa-magnifying-glass text-xs"></i>
                        </div>
                        <input type="text" 
                               id="campagneSearchInput" 
                               value="{{ $activeCampagne?->intitule }}" 
                               placeholder="Rechercher / Sélectionner une campagne..." 
                               autocomplete="off"
                               onclick="ouvrirDropdownCampagne()"
                               oninput="filtrerCampagnesCombobox(this.value)"
                               onfocus="this.select(); ouvrirDropdownCampagne();"
                               class="w-full pl-9 pr-10 py-3 bg-slate-50 hover:bg-slate-100/80 focus:bg-white border border-slate-200 rounded-xl text-xs sm:text-sm font-bold text-slate-800 focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all cursor-pointer">
                        <div class="absolute right-3.5 pointer-events-none text-slate-400 transition-transform duration-200" id="chevronCombobox">
                            <i class="fa-solid fa-chevron-down text-xs"></i>
                        </div>
                    </div>

                    <!-- Menu Déroulant Filtrable -->
                    <div id="campagneDropdownList" 
                         class="absolute left-0 right-0 top-full mt-1.5 bg-white border border-slate-200 rounded-2xl shadow-xl z-50 max-h-64 overflow-y-auto hidden divide-y divide-slate-100">
                        @foreach($campagnes as $c)
                            <div class="campagne-item p-3 hover:bg-emerald-50/70 cursor-pointer transition flex items-center justify-between gap-2"
                                 onclick="choisirCampagne({{ json_encode([
                                     'id' => $c->id,
                                     'code' => $c->code_campagne,
                                     'intitule' => $c->intitule,
                                     'programme' => $c->programme->sigle ?? $c->programme->code ?? 'Global',
                                     'region' => $c->region->nom ?? 'Toutes Régions',
                                     'dates' => ($c->date_debut ? $c->date_debut->format('d/m/Y') : '-') . ' au ' . ($c->date_fin ? $c->date_fin->format('d/m/Y') : '-'),
                                     'releves' => $c->releves->count(),
                                     'url' => route('agent.campagnes.show', $c->id)
                                 ]) }})"
                                 data-id="{{ $c->id }}"
                                 data-intitule="{{ strtolower($c->intitule) }}"
                                 data-code="{{ strtolower($c->code_campagne) }}"
                                 data-programme="{{ strtolower($c->programme->sigle ?? $c->programme->code ?? '') }}"
                                 data-region="{{ strtolower($c->region->nom ?? '') }}">
                                <div class="flex-1 min-w-0">
                                    <div class="font-bold text-xs sm:text-sm text-slate-900 truncate item-title">
                                        {{ $c->intitule }}
                                    </div>
                                    <div class="text-[10px] text-slate-500 mt-0.5 flex items-center gap-1.5 flex-wrap">
                                        <span class="bg-slate-100 text-slate-600 px-1.5 py-0.5 rounded font-semibold">{{ $c->programme->sigle ?? $c->programme->code ?? 'Global' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $c->region->nom ?? 'Toutes Régions' }}</span>
                                        <span>&bull;</span>
                                        <span>{{ $c->releves->count() }} scans</span>
                                    </div>
                                </div>
                                <i class="fa-solid fa-check text-[#155E3B] text-xs check-icon {{ ($activeCampagne && $activeCampagne->id == $c->id) ? '' : 'hidden' }}"></i>
                            </div>
                        @endforeach
                        <div id="noMatchCampagne" class="p-4 text-center text-xs text-slate-400 italic hidden">
                            Aucune campagne correspondante
                        </div>
                    </div>
                </div>

                <!-- Carte d'Aperçu & Métadonnées Dynamiques de la Campagne Sélectionnée -->
                <div id="campagneDetailsCard" class="bg-slate-50/90 rounded-xl p-3 border border-slate-200/80 space-y-2.5">
                    <div class="flex items-start justify-between gap-2">
                        <div class="space-y-1 flex-1 min-w-0">
                            <div class="flex items-center space-x-2 flex-wrap gap-y-1">
                                <span id="badgeCampagneCode" class="font-mono text-[11px] font-extrabold text-[#155E3B] bg-emerald-100/80 px-2 py-0.5 rounded-md border border-emerald-200">
                                    {{ $activeCampagne?->code_campagne ?? 'CODE' }}
                                </span>
                                <span id="badgeCampagneProg" class="text-[10px] font-semibold text-slate-600 bg-white px-2 py-0.5 rounded-md border border-slate-200">
                                    <i class="fa-solid fa-layer-group text-slate-400 mr-1"></i>{{ $activeCampagne?->programme->sigle ?? $activeCampagne?->programme->code ?? 'Global' }}
                                </span>
                            </div>
                            <h3 id="lblCampagneIntitule" class="font-bold text-slate-900 text-xs sm:text-sm truncate">
                                {{ $activeCampagne?->intitule ?? 'Titre de la campagne' }}
                            </h3>
                        </div>

                        <a id="linkCampagneDetail" 
                           href="{{ $activeCampagne ? route('agent.campagnes.show', $activeCampagne->id) : '#' }}" 
                           title="Voir statistiques et relevés"
                           class="flex-shrink-0 px-2.5 py-1.5 rounded-lg bg-white border border-slate-200 text-slate-700 hover:text-[#155E3B] hover:border-emerald-300 text-[11px] font-bold shadow-2xs flex items-center space-x-1.5 transition">
                            <i class="fa-solid fa-chart-pie text-[#155E3B]"></i>
                            <span class="hidden sm:inline">Détails</span>
                        </a>
                    </div>

                    <div class="flex flex-wrap items-center gap-2 pt-2 border-t border-slate-200/60 text-[10px] text-slate-500 font-medium">
                        <span id="badgeCampagneRegion" class="flex items-center space-x-1">
                            <i class="fa-solid fa-map-location-dot text-slate-400"></i>
                            <span>{{ $activeCampagne?->region->nom ?? 'Toutes Régions' }}</span>
                        </span>
                        <span class="text-slate-300">&bull;</span>
                        <span id="badgeCampagneDates" class="flex items-center space-x-1">
                            <i class="fa-regular fa-calendar text-slate-400"></i>
                            <span>{{ $activeCampagne?->date_debut ? $activeCampagne->date_debut->format('d/m/Y') : '-' }} au {{ $activeCampagne?->date_fin ? $activeCampagne->date_fin->format('d/m/Y') : '-' }}</span>
                        </span>
                        <span class="text-slate-300">&bull;</span>
                        <span id="badgeCampagneReleves" class="font-bold text-emerald-700 bg-emerald-50 px-1.5 py-0.5 rounded border border-emerald-200">
                            <i class="fa-solid fa-barcode text-emerald-600 mr-1"></i>{{ $activeCampagne?->releves->count() ?? 0 }} scans
                        </span>
                    </div>
                </div>
            @else
                <div class="p-3 bg-amber-50 border border-amber-200 rounded-xl text-xs text-amber-800 flex items-center space-x-2">
                    <i class="fa-solid fa-triangle-exclamation text-amber-600"></i>
                    <span>Aucune campagne d'inventaire active n'est assignée pour le moment.</span>
                </div>
            @endif
        </div>

        <!-- 2. Bloc Caméra Scanner / Saisie Rapide -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3.5">
            <!-- Viseur Caméra -->
            <div id="readerWrapper" class="relative rounded-2xl overflow-hidden bg-slate-900 border-2 border-slate-800 hidden">
                <div id="reader" class="w-full min-h-[240px]"></div>
                <div class="scanner-laser"></div>
            </div>

            <!-- Bouton Déclencher Caméra -->
            <button type="button"
                    id="btnToggleScanner"
                    onclick="toggleCameraScanner()"
                    style="background-color: #155E3B;"
                    class="w-full py-3.5 px-4 rounded-xl text-white font-bold text-xs shadow-md shadow-[#155E3B]/25 hover:opacity-95 flex items-center justify-center space-x-2 transition-all">
                <i class="fa-solid fa-camera text-sm"></i>
                <span id="scannerBtnText">Activer Caméra / Scan QR Code</span>
            </button>

            <!-- Saisie Manuelle de Secours -->
            <div class="relative">
                <input type="text"
                       id="inputManualCode"
                       placeholder="Saisir tag / n° série (ex: TAG-MED-001)"
                       class="w-full pl-9 pr-14 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-mono font-bold text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                <i class="fa-solid fa-barcode absolute left-3.5 top-3.5 text-slate-400 text-xs"></i>
                <button type="button"
                        onclick="rechercherCodeManuel()"
                        style="background-color: #155E3B;"
                        class="absolute right-1.5 top-1.5 px-3 py-1.5 rounded-lg text-white text-xs font-bold shadow-xs hover:opacity-95">
                    OK
                </button>
            </div>
        </div>

        <!-- 3. Fiche Actif Détecté (Scanned Card) -->
        <div id="assetScannedCard" class="bg-white rounded-2xl p-5 border-2 border-[#155E3B] space-y-4 hidden shadow-lg animate-fade-in">
            <div class="flex items-start justify-between gap-2 pb-3 border-b border-slate-100">
                <div>
                    <span id="scannedCode" class="font-mono font-extrabold text-sm text-[#155E3B] bg-emerald-50 px-2.5 py-0.5 rounded-md border border-emerald-200 inline-block">
                        UCPFM-2026-MED-0001
                    </span>
                    <h3 id="scannedDesignation" class="font-bold text-slate-900 text-sm mt-1.5">Désignation du Matériel</h3>
                    <div id="scannedMeta" class="text-xs text-slate-500 mt-0.5">Marque | Modèle | S/N</div>
                </div>
                <span id="scannedCriticite" class="px-2 py-0.5 rounded-full text-[10px] uppercase font-bold bg-rose-50 text-rose-700 border border-rose-200">
                    Critique
                </span>
            </div>

            <!-- Données Théoriques Enregistrées -->
            <div class="p-3.5 bg-slate-50 rounded-xl border border-slate-200 text-xs space-y-1.5 text-slate-700">
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Site sanitaire :</span>
                    <strong id="scannedStructure" class="text-slate-900 font-semibold text-right">CHU de Treichville</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">Détenteur :</span>
                    <strong id="scannedDetenteur" class="text-slate-800 text-right">Dr. KOFFI Marc</strong>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-slate-400">État enregistré :</span>
                    <span id="scannedEtat" class="font-bold text-emerald-700">Bon état</span>
                </div>
            </div>

            <!-- Formulaire de Saisie de Relevé Terrain -->
            <form id="formReleveMobile" onsubmit="soumettreReleve(event)" class="space-y-4 pt-1 text-xs">
                <input type="hidden" id="fieldImmoId" name="immobilisation_id">
                <input type="hidden" id="fieldCodeScanne" name="code_scanne">
                <input type="hidden" id="fieldLat" name="latitude">
                <input type="hidden" id="fieldLng" name="longitude">
                <input type="hidden" id="fieldPrecision" name="precision_gps">

                <!-- Présence constatée -->
                <div>
                    <label class="block font-bold text-slate-700 mb-1.5">
                        Présence Physique Constatée <span class="text-rose-500">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-2.5">
                        <label class="p-3 rounded-xl border-2 border-slate-200 bg-slate-50 flex items-center space-x-2.5 cursor-pointer has-[:checked]:border-[#155E3B] has-[:checked]:bg-emerald-50/70 transition-all">
                            <input type="radio" name="presence_constatee" value="1" checked class="text-[#155E3B] focus:ring-[#155E3B]">
                            <span class="font-bold text-emerald-800">Bien Retrouvé</span>
                        </label>
                        <label class="p-3 rounded-xl border-2 border-slate-200 bg-slate-50 flex items-center space-x-2.5 cursor-pointer has-[:checked]:border-rose-500 has-[:checked]:bg-rose-50/70 transition-all">
                            <input type="radio" name="presence_constatee" value="0" class="text-rose-600 focus:ring-rose-500">
                            <span class="font-bold text-rose-700">Non Retrouvé</span>
                        </label>
                    </div>
                </div>

                <!-- État constaté -->
                <div>
                    <label for="fieldEtatConstate" class="block font-bold text-slate-700 mb-1.5">
                        État Physique sur le Terrain <span class="text-rose-500">*</span>
                    </label>
                    <select id="fieldEtatConstate" name="etat_constate" required class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-bold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                        <option value="bon_etat">Bon état / Fonctionnel</option>
                        <option value="panne">En panne / Défaillant</option>
                        <option value="hors_service">Hors service / Déclassé</option>
                        <option value="en_maintenance">En maintenance</option>
                        <option value="transfere">Transféré vers autre site</option>
                        <option value="a_reformer">Proposé à la réforme</option>
                    </select>
                </div>

                <!-- Observations terrain -->
                <div>
                    <label for="fieldObservations" class="block font-bold text-slate-700 mb-1.5">
                        Observations & Précisions Terrain
                    </label>
                    <textarea id="fieldObservations"
                              name="observations"
                              rows="2"
                              placeholder="Remarques (nouveau bureau, usure constatée, détérioration de l'étiquette...)"
                              class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all"></textarea>
                </div>

                <!-- Bouton de synchronisation -->
                <button type="submit"
                        style="background-color: #155E3B;"
                        class="w-full py-3.5 rounded-xl text-white font-extrabold text-xs shadow-md shadow-[#155E3B]/25 hover:opacity-95 flex items-center justify-center space-x-2 transition-all">
                    <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                    <span>Valider & Synchroniser le Relevé</span>
                </button>
            </form>
        </div>

        <!-- 4. Historique Récent des Scans de la Session -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-2.5">
            <h4 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider flex items-center space-x-1.5">
                <i class="fa-solid fa-clock-rotate-left text-[#155E3B]"></i>
                <span>Scans récents (Session active)</span>
            </h4>
            <div id="localScansList" class="space-y-2 text-xs">
                <div class="text-slate-400 italic text-center py-3">Aucun scan enregistré dans cette session pour le moment.</div>
            </div>
        </div>

    </main>

    <!-- Bottom Navigation Bar Mobile & Tablette Pleine Largeur -->
    <nav class="bg-white/95 backdrop-blur-md border-t border-slate-200/90 fixed bottom-0 left-0 right-0 p-2 z-30 flex items-center justify-around text-[10px] sm:text-xs font-bold text-slate-500 shadow-xl w-full">
        <a href="{{ route('agent.mobile.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-[#155E3B]">
            <i class="fa-solid fa-qrcode text-base sm:text-lg mb-0.5"></i>
            <span>Scanner</span>
        </a>
        <a href="{{ route('agent.cartographie.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#155E3B] transition-colors">
            <i class="fa-solid fa-map-location-dot text-base sm:text-lg mb-0.5"></i>
            <span>Carte SIG</span>
        </a>
        <a href="{{ route('agent.campagnes.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#155E3B] transition-colors">
            <i class="fa-solid fa-clipboard-check text-base sm:text-lg mb-0.5"></i>
            <span>Campagnes</span>
        </a>
        <button type="button" onclick="recupererDonneesHorsLigne()" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#155E3B] transition-colors">
            <i class="fa-solid fa-cloud-arrow-down text-base sm:text-lg mb-0.5"></i>
            <span>Pack Offline</span>
        </button>
    </nav>

    <!-- Scripts Mobile App & Scanner -->
    <script>
        let html5QrCode = null;
        let isScanning = false;
        let currentPosition = { lat: null, lng: null, accuracy: null };

        // 1. Initialisation au chargement
        document.addEventListener('DOMContentLoaded', () => {
            initGpsWatch();
        });

        // Gestion du Combobox Select Recherchable Intégré
        function ouvrirDropdownCampagne() {
            const list = document.getElementById('campagneDropdownList');
            const chevron = document.getElementById('chevronCombobox');
            if (list) {
                list.classList.remove('hidden');
                if (chevron) chevron.style.transform = 'rotate(180deg)';
            }
        }

        function fermerDropdownCampagne() {
            const list = document.getElementById('campagneDropdownList');
            const chevron = document.getElementById('chevronCombobox');
            if (list) {
                list.classList.add('hidden');
                if (chevron) chevron.style.transform = 'rotate(0deg)';
            }
        }

        function filtrerCampagnesCombobox(query) {
            ouvrirDropdownCampagne();
            const q = (query || '').toLowerCase().trim();
            const items = document.querySelectorAll('.campagne-item');
            const noMatch = document.getElementById('noMatchCampagne');
            let visibleCount = 0;

            items.forEach(item => {
                const intitule = item.getAttribute('data-intitule') || '';
                const code = item.getAttribute('data-code') || '';
                const prog = item.getAttribute('data-programme') || '';
                const region = item.getAttribute('data-region') || '';

                const match = q === '' || intitule.includes(q) || prog.includes(q) || region.includes(q) || code.includes(q);
                if (match) {
                    item.classList.remove('hidden');
                    visibleCount++;
                } else {
                    item.classList.add('hidden');
                }
            });

            if (noMatch) {
                if (visibleCount === 0) {
                    noMatch.classList.remove('hidden');
                } else {
                    noMatch.classList.add('hidden');
                }
            }
        }

        function choisirCampagne(c) {
            // 1. Mettre à jour l'input caché pour soumission formulaire
            const selectCampagne = document.getElementById('selectCampagne');
            if (selectCampagne) selectCampagne.value = c.id;

            // 2. Mettre à jour le texte du champ visible
            const input = document.getElementById('campagneSearchInput');
            if (input) input.value = c.intitule;

            // 3. Mettre à jour les coches dans le dropdown
            document.querySelectorAll('.campagne-item').forEach(item => {
                const check = item.querySelector('.check-icon');
                if (check) {
                    if (item.getAttribute('data-id') == c.id) {
                        check.classList.remove('hidden');
                    } else {
                        check.classList.add('hidden');
                    }
                }
            });

            // 4. Mettre à jour la carte dynamique
            const badgeCode = document.getElementById('badgeCampagneCode');
            if (badgeCode) badgeCode.textContent = c.code || '';

            const lblIntitule = document.getElementById('lblCampagneIntitule');
            if (lblIntitule) lblIntitule.textContent = c.intitule || '';

            const badgeProg = document.getElementById('badgeCampagneProg');
            if (badgeProg) {
                badgeProg.innerHTML = `<i class="fa-solid fa-layer-group text-slate-400 mr-1"></i>`;
                const spanTxt = document.createElement('span');
                spanTxt.textContent = c.programme || 'Global';
                badgeProg.appendChild(spanTxt);
            }

            const badgeRegion = document.getElementById('badgeCampagneRegion');
            if (badgeRegion) {
                badgeRegion.innerHTML = `<i class="fa-solid fa-map-location-dot text-slate-400 mr-1"></i>`;
                const spanTxt = document.createElement('span');
                spanTxt.textContent = c.region || 'Toutes Régions';
                badgeRegion.appendChild(spanTxt);
            }

            const badgeDates = document.getElementById('badgeCampagneDates');
            if (badgeDates) {
                badgeDates.innerHTML = `<i class="fa-regular fa-calendar text-slate-400 mr-1"></i>`;
                const spanTxt = document.createElement('span');
                spanTxt.textContent = c.dates || '-';
                badgeDates.appendChild(spanTxt);
            }

            const badgeReleves = document.getElementById('badgeCampagneReleves');
            if (badgeReleves) {
                badgeReleves.innerHTML = `<i class="fa-solid fa-barcode text-emerald-600 mr-1"></i>`;
                const spanTxt = document.createElement('span');
                spanTxt.textContent = `${c.releves || 0} scans`;
                badgeReleves.appendChild(spanTxt);
            }

            const linkDetail = document.getElementById('linkCampagneDetail');
            if (linkDetail && c.url) linkDetail.href = c.url;

            // Fermer le dropdown
            fermerDropdownCampagne();
        }

        // Fermer le dropdown lors d'un clic extérieur
        document.addEventListener('click', (e) => {
            const wrapper = document.getElementById('campagneComboboxWrapper');
            if (wrapper && !wrapper.contains(e.target)) {
                fermerDropdownCampagne();
            }
        });

        function initGpsWatch() {
            if ("geolocation" in navigator) {
                navigator.geolocation.watchPosition(
                    (pos) => {
                        currentPosition.lat = pos.coords.latitude;
                        currentPosition.lng = pos.coords.longitude;
                        currentPosition.accuracy = pos.coords.accuracy;

                        const badge = document.getElementById('gpsStatusBadge');
                        const text = document.getElementById('gpsText');
                        badge.className = 'px-2.5 py-1 rounded-xl text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1.5 shadow-2xs';
                        text.innerText = `GPS: ±${Math.round(pos.coords.accuracy)}m`;

                        document.getElementById('fieldLat').value = currentPosition.lat;
                        document.getElementById('fieldLng').value = currentPosition.lng;
                        document.getElementById('fieldPrecision').value = currentPosition.accuracy;
                    },
                    (err) => {
                        console.warn("GPS Warning:", err.message);
                        currentPosition.lat = 5.326210;
                        currentPosition.lng = -4.019720;
                        currentPosition.accuracy = 10;
                        document.getElementById('gpsText').innerText = 'GPS: Fixe (Abidjan)';
                    },
                    { enableHighAccuracy: true, timeout: 10000, maximumAge: 0 }
                );
            }
        }

        // 2. Gestion du Scanner QR Caméra
        function toggleCameraScanner() {
            const wrapper = document.getElementById('readerWrapper');
            const btnText = document.getElementById('scannerBtnText');

            if (!isScanning) {
                wrapper.classList.remove('hidden');
                html5QrCode = new Html5Qrcode("reader");
                html5QrCode.start(
                    { facingMode: "environment" },
                    { fps: 10, qrbox: { width: 220, height: 220 } },
                    (decodedText) => {
                        onScanSuccess(decodedText);
                    },
                    (error) => {}
                ).then(() => {
                    isScanning = true;
                    btnText.innerText = 'Désactiver la caméra';
                }).catch(err => {
                    alert("Impossible d'accéder à la caméra: " + err);
                });
            } else {
                html5QrCode.stop().then(() => {
                    wrapper.classList.add('hidden');
                    isScanning = false;
                    btnText.innerText = 'Activer Caméra / Scan QR Code';
                });
            }
        }

        function onScanSuccess(code) {
            if (isScanning && html5QrCode) {
                toggleCameraScanner();
            }
            chercherActifParCode(code);
        }

        function rechercherCodeManuel() {
            const code = document.getElementById('inputManualCode').value.trim();
            if (code) {
                chercherActifParCode(code);
            }
        }

        // 3. Appel AJAX pour récupérer la fiche du bien
        function chercherActifParCode(code) {
            fetch(`{{ route('agent.mobile.scan.chercher') }}?code=${encodeURIComponent(code)}`)
                .then(res => res.json())
                .then(data => {
                    const card = document.getElementById('assetScannedCard');
                    if (data.found) {
                        const a = data.actif;
                        document.getElementById('fieldImmoId').value = a.id;
                        document.getElementById('fieldCodeScanne').value = a.code_immobilisation;
                        document.getElementById('scannedCode').innerText = a.code_immobilisation;
                        document.getElementById('scannedDesignation').innerText = a.designation;
                        document.getElementById('scannedMeta').innerText = `${a.marque || ''} ${a.modele || ''} | S/N: ${a.numero_serie || 'N/A'}`;
                        document.getElementById('scannedStructure').innerText = a.structure_nom || 'N/A';
                        document.getElementById('scannedDetenteur').innerText = a.detenteur_nom || 'N/A';
                        document.getElementById('scannedEtat').innerText = a.etat_libelle;
                        document.getElementById('scannedCriticite').innerText = (a.niveau_criticite || 'Modéré').toUpperCase();
                        card.classList.remove('hidden');
                        card.scrollIntoView({ behavior: 'smooth' });
                    } else {
                        if (confirm(`Le code [${code}] n'est pas encore répertorié. Souhaitez-vous enregistrer un nouveau relevé terrain pour ce bien ?`)) {
                            document.getElementById('fieldImmoId').value = '';
                            document.getElementById('fieldCodeScanne').value = code;
                            document.getElementById('scannedCode').innerText = code;
                            document.getElementById('scannedDesignation').innerText = 'Nouvel équipement physique non répertorié';
                            document.getElementById('scannedMeta').innerText = 'Scan terrain brut';
                            document.getElementById('scannedStructure').innerText = 'Structure actuelle';
                            document.getElementById('scannedDetenteur').innerText = 'À identifier';
                            document.getElementById('scannedEtat').innerText = 'À renseigner';
                            card.classList.remove('hidden');
                        }
                    }
                })
                .catch(err => {
                    alert("Erreur lors de la recherche du code: " + err);
                });
        }

        // 4. Soumission du relevé
        function soumettreReleve(e) {
            e.preventDefault();
            const form = document.getElementById('formReleveMobile');
            const formData = new FormData(form);
            formData.append('campagne_id', document.getElementById('selectCampagne').value);

            fetch(`{{ route('agent.mobile.releve.store') }}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert("✅ Relevé terrain synchronisé avec succès !");
                    document.getElementById('assetScannedCard').classList.add('hidden');
                    form.reset();

                    // Ajouter à la liste des scans locaux
                    const list = document.getElementById('localScansList');
                    const emptyMsg = list.querySelector('.italic');
                    if (emptyMsg) emptyMsg.remove();

                    const item = document.createElement('div');
                    item.className = 'p-3 bg-slate-50 rounded-xl border border-slate-200 flex items-center justify-between shadow-2xs';
                    item.innerHTML = `
                        <div>
                            <span class="font-mono font-bold text-[#155E3B] text-xs">${formData.get('code_scanne')}</span>
                            <div class="text-[10px] text-slate-400 mt-0.5">${new Date().toLocaleTimeString()}</div>
                        </div>
                        <span class="px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                            ${data.statut_rapprochement}
                        </span>
                    `;
                    list.prepend(item);
                }
            })
            .catch(err => {
                alert("Erreur d'enregistrement: " + err);
            });
        }

        function recupererDonneesHorsLigne() {
            fetch(`{{ route('agent.mobile.offline.data') }}`)
                .then(res => res.json())
                .then(data => {
                    localStorage.setItem('ucpfm_offline_assets', JSON.stringify(data.actifs));
                    alert(`✅ Base locale téléchargée : ${data.total} immobilisation(s) sauvegardées pour l'inventaire hors-ligne.`);
                });
        }
    </script>
</body>
</html>
