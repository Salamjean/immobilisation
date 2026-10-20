<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Campagnes d'Inventaire | UCP-FM Terrain</title>

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

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }
        
        .app-bg-pattern {
            background-color: #f3f6fc;
            background-image: url("{{ asset('images/topography-pattern.svg') }}");
            background-size: 700px 700px;
            background-repeat: repeat;
            background-attachment: fixed;
        }
    </style>
</head>
<body class="h-full app-bg-pattern text-slate-800 flex flex-col antialiased">

    <!-- 1. Header Mobile & Tablette Pleine Largeur -->
    <header class="bg-white border-b border-slate-200/90 px-4 py-3.5 sm:px-6 sticky top-0 z-30 flex items-center justify-between shadow-xs w-full">
        <div class="flex items-center space-x-2.5">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo UCP-FM" class="h-8 sm:h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #0048FA;">
                Campagnes
            </span>
        </div>

        <div class="flex items-center space-x-2 sm:space-x-3">
            <!-- Badge Campagnes Actives -->
            <span class="px-2.5 py-1 sm:px-3 rounded-xl text-[10px] sm:text-xs font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex items-center space-x-1.5 shadow-2xs">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-600 animate-pulse"></span>
                <span>{{ $campagnesActivesCount }} Active(s)</span>
            </span>

            <!-- Bouton Déconnexion -->
            <form action="{{ route('logout') }}" method="POST" class="inline">
                @csrf
                <button type="submit" title="Se déconnecter" class="w-8 h-8 sm:w-9 sm:h-9 rounded-xl bg-rose-50 text-rose-600 border border-rose-100 flex items-center justify-center text-xs font-bold hover:bg-rose-100 transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                </button>
            </form>
        </div>
    </header>

    <!-- 2. Contenu Principal Plein Écran pour Téléphone & Tablette -->
    <main class="flex-1 p-3.5 sm:p-5 md:p-6 overflow-y-auto space-y-4 pb-24 w-full">

        <!-- Bannière KPI Pleine Largeur -->
        <div class="grid grid-cols-2 md:grid-cols-3 gap-3 sm:gap-4">
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
                <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block">Campagnes Actives</span>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-xl sm:text-2xl font-black text-[#0048FA] font-mono">{{ $campagnesActivesCount }}</span>
                    <div class="w-9 h-9 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center">
                        <i class="fa-solid fa-clipboard-check text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
                <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block">Mes Relevés</span>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ $mesRelevesCount }}</span>
                    <div class="w-9 h-9 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center">
                        <i class="fa-solid fa-qrcode text-lg"></i>
                    </div>
                </div>
            </div>

            <div class="col-span-2 md:col-span-1 bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs">
                <span class="text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider block">Total Recensé</span>
                <div class="flex items-center justify-between mt-1">
                    <span class="text-xl sm:text-2xl font-black text-slate-900 font-mono">{{ $campagnes->sum('releves_count') }}</span>
                    <div class="w-9 h-9 rounded-xl bg-purple-50 text-purple-600 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked text-lg"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Titre de la section -->
        <div class="flex items-center justify-between pt-1">
            <h2 class="text-xs sm:text-sm font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                <i class="fa-solid fa-layer-group text-[#0048FA]"></i>
                <span>Liste des Campagnes de Recensement</span>
            </h2>
            <span class="text-[11px] sm:text-xs text-slate-500 font-semibold font-mono">{{ $campagnes->total() }} campagne(s)</span>
        </div>

        <!-- Grille Responsive des Cartes Campagnes (1 col sur Phone, 2-3 cols sur Tablette) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 sm:gap-4">
            @forelse($campagnes as $campagne)
            <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3 hover:border-[#0048FA] transition-all flex flex-col justify-between">
                
                <div class="space-y-3">
                    <!-- En-tête de la carte -->
                    <div class="flex items-start justify-between gap-2">
                        <div>
                            <span class="text-[10px] font-bold text-[#0048FA] uppercase tracking-wider block">
                                {{ $campagne->programme->sigle ?? $campagne->programme->code ?? 'Global' }}
                                @if($campagne->region) &bull; {{ $campagne->region->nom }} @endif
                            </span>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-tight mt-0.5">
                                {{ $campagne->nom ?? $campagne->titre }}
                            </h3>
                        </div>

                        <!-- Badge de statut -->
                        <div class="flex items-center space-x-1.5 flex-wrap gap-y-1">
                            @if(auth()->check() && $campagne->equipes->pluck('user_id')->contains(auth()->id()))
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-purple-100 text-purple-800 border border-purple-200">
                                    <i class="fa-solid fa-user-check text-[9px] mr-1"></i> Attribuée à vous
                                </span>
                            @endif

                            @if($campagne->statut === 'en_cours')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 flex-shrink-0">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1"></span> En cours
                                </span>
                            @elseif($campagne->statut === 'cloturee')
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200 flex-shrink-0">
                                    Clôturée
                                </span>
                            @else
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-blue-50 text-blue-700 border border-blue-200 flex-shrink-0">
                                    Planifiée
                                </span>
                            @endif
                        </div>
                    </div>

                    <!-- Informations Période & Superviseur -->
                    <div class="grid grid-cols-2 gap-2 text-xs bg-slate-50/80 p-2.5 rounded-xl border border-slate-100">
                        <div>
                            <span class="text-[10px] text-slate-400 block font-semibold">Période</span>
                            <span class="font-bold text-slate-700 text-[11px]">
                                {{ $campagne->date_debut ? \Carbon\Carbon::parse($campagne->date_debut)->format('d/m/Y') : '-' }} au {{ $campagne->date_fin ? \Carbon\Carbon::parse($campagne->date_fin)->format('d/m/Y') : '-' }}
                            </span>
                        </div>

                        <div>
                            <span class="text-[10px] text-slate-400 block font-semibold">Superviseur</span>
                            <span class="font-bold text-slate-700 text-[11px] truncate block">
                                {{ $campagne->superviseur->name ?? 'Non assigné' }}
                            </span>
                        </div>
                    </div>

                    <!-- Progression -->
                    @php
                        $progression = $totalBiens > 0 ? min(100, round(($campagne->releves_count / $totalBiens) * 100)) : 0;
                    @endphp
                    <div>
                        <div class="flex items-center justify-between text-[11px] mb-1">
                            <span class="text-slate-500 font-semibold">Progression du recensement</span>
                            <span class="font-bold text-slate-900 font-mono">{{ $campagne->releves_count }} relevé(s)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2 rounded-full overflow-hidden">
                            <div class="bg-[#0048FA] h-full rounded-full transition-all duration-500" style="width: {{ $progression }}%;"></div>
                        </div>
                    </div>
                </div>

                <!-- Actions Mobiles & Tablettes -->
                <div class="pt-2 flex items-center space-x-2 border-t border-slate-100">
                    <a href="{{ route('agent.mobile.index') }}?campagne_id={{ $campagne->id }}"
                       style="background-color: #0048FA;"
                       class="flex-1 py-2.5 rounded-xl text-white text-xs font-bold text-center shadow-md shadow-[#0048FA]/20 hover:opacity-95 transition flex items-center justify-center space-x-1.5">
                        <i class="fa-solid fa-qrcode text-xs"></i>
                        <span>Scanner pour cette campagne</span>
                    </a>

                    <a href="{{ route('agent.campagnes.show', $campagne->id) }}"
                       class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition flex items-center justify-center">
                        <i class="fa-solid fa-circle-info"></i>
                    </a>
                </div>

            </div>
            @empty
            <div class="col-span-full bg-white rounded-2xl p-10 border border-slate-200/90 shadow-xs text-center space-y-2">
                <i class="fa-solid fa-clipboard-list text-4xl text-slate-300"></i>
                <p class="text-sm font-bold text-slate-600">Aucune campagne d'inventaire disponible.</p>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        <div class="pt-2">
            {{ $campagnes->links() }}
        </div>

    </main>

    <!-- 3. Bottom Navigation Bar Mobile & Tablette Pleine Largeur -->
    <nav class="bg-white/95 backdrop-blur-md border-t border-slate-200/90 fixed bottom-0 left-0 right-0 p-2 z-30 flex items-center justify-around text-[10px] sm:text-xs font-bold text-slate-500 shadow-xl w-full">
        <a href="{{ route('agent.mobile.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#0048FA] transition-colors">
            <i class="fa-solid fa-qrcode text-base sm:text-lg mb-0.5"></i>
            <span>Scanner</span>
        </a>
        <a href="{{ route('agent.cartographie.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#0048FA] transition-colors">
            <i class="fa-solid fa-map-location-dot text-base sm:text-lg mb-0.5"></i>
            <span>Carte SIG</span>
        </a>
        <a href="{{ route('agent.campagnes.index') }}" class="flex flex-col items-center py-1 px-4 sm:px-6 text-[#0048FA]">
            <i class="fa-solid fa-clipboard-check text-base sm:text-lg mb-0.5"></i>
            <span>Campagnes</span>
        </a>
        <button type="button" onclick="recupererDonneesHorsLigne()" class="flex flex-col items-center py-1 px-4 sm:px-6 text-slate-500 hover:text-[#0048FA] transition-colors">
            <i class="fa-solid fa-cloud-arrow-down text-base sm:text-lg mb-0.5"></i>
            <span>Pack Offline</span>
        </button>
    </nav>

    <script>
        function recupererDonneesHorsLigne() {
            alert("Les campagnes et immobilisations sont prêtes pour consultation locale hors-ligne.");
        }
    </script>
</body>
</html>
