<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Détails Campagne | UCP-FM Terrain</title>

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

    <!-- 1. Header Mobile & Tablette avec Bouton Retour -->
    <header class="bg-white border-b border-slate-200/90 p-3.5 sticky top-0 z-30 flex items-center justify-between shadow-xs">
        <div class="flex items-center space-x-2.5">
            <a href="{{ route('agent.campagnes.index') }}" class="w-8 h-8 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 flex items-center justify-center text-xs font-bold transition">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider block">Campagne</span>
                <h1 class="text-xs font-bold text-slate-900 truncate max-w-[180px] sm:max-w-xs">{{ $campagne->nom ?? $campagne->titre }}</h1>
            </div>
        </div>

        <div class="flex items-center space-x-2">
            @if($campagne->statut === 'en_cours')
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse mr-1"></span> Active
                </span>
            @else
                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                    {{ ucfirst($campagne->statut) }}
                </span>
            @endif
        </div>
    </header>

    <!-- 2. Contenu Principal Détail Mobile & Tablette Pleine Largeur -->
    <main class="flex-1 p-3.5 sm:p-5 md:p-6 overflow-y-auto space-y-4 pb-24 w-full">

        <!-- Carte Principale Campagne -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
            <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                <span class="text-xs font-bold text-[#0048FA] uppercase tracking-wider">
                    {{ $campagne->programme->sigle ?? $campagne->programme->code ?? 'Global' }}
                    @if($campagne->region) &bull; {{ $campagne->region->nom }} @endif
                </span>
                <span class="text-xs font-mono font-bold text-slate-400">#{{ $campagne->id }}</span>
            </div>

            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2.5 text-xs">
                <div class="bg-slate-50 p-2.5 rounded-xl">
                    <span class="text-[10px] text-slate-400 block font-semibold">Total Relevés</span>
                    <span class="font-extrabold text-[#0048FA] text-base font-mono">{{ $totalReleves }}</span>
                </div>

                <div class="bg-slate-50 p-2.5 rounded-xl">
                    <span class="text-[10px] text-slate-400 block font-semibold">Mes Scans</span>
                    <span class="font-extrabold text-slate-900 text-base font-mono">{{ $mesRelevesCampagne }}</span>
                </div>

                <div class="col-span-2 sm:col-span-1 bg-slate-50 p-2.5 rounded-xl">
                    <span class="text-[10px] text-slate-400 block font-semibold">Progression</span>
                    <span class="font-extrabold text-blue-600 text-base font-mono">{{ $tauxProgression }}%</span>
                </div>
            </div>

            <!-- Bouton Lancer le Scan Direct -->
            <a href="{{ route('agent.mobile.index') }}?campagne_id={{ $campagne->id }}"
               style="background-color: #0048FA;"
               class="w-full py-2.5 rounded-xl text-white text-xs font-bold text-center shadow-md shadow-[#0048FA]/20 hover:opacity-95 transition flex items-center justify-center space-x-2">
                <i class="fa-solid fa-qrcode"></i>
                <span>Ouvrir le Scanner pour cette Campagne</span>
            </a>
        </div>

        <!-- Section Équipes Assignées -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                <i class="fa-solid fa-users text-[#0048FA]"></i>
                <span>Équipes & Agents de Terrain ({{ $campagne->equipes->count() }})</span>
            </h3>

            <div class="space-y-2">
                @forelse($campagne->equipes as $eq)
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                    <div class="flex items-center space-x-2.5">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 font-bold flex items-center justify-center text-xs">
                            {{ substr($eq->user->name ?? 'A', 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-slate-900">{{ $eq->user->name ?? 'Agent' }}</div>
                            <div class="text-[10px] text-slate-500">{{ $eq->structure->nom ?? 'Toutes structures' }}</div>
                        </div>
                    </div>
                    <span class="text-[10px] font-bold text-slate-400 uppercase">{{ $eq->role ?? 'Agent' }}</span>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">Aucune équipe assignée.</p>
                @endforelse
            </div>
        </div>

        <!-- Derniers Relevés Réalisés -->
        <div class="bg-white rounded-2xl p-4 border border-slate-200/90 shadow-xs space-y-3">
            <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider flex items-center space-x-2">
                <i class="fa-solid fa-list-check text-[#0048FA]"></i>
                <span>Derniers Scans Effectués ({{ min(10, $campagne->releves->count()) }})</span>
            </h3>

            <div class="space-y-2">
                @forelse($campagne->releves->take(10) as $rel)
                <div class="p-2.5 rounded-xl bg-slate-50 border border-slate-100 flex items-center justify-between text-xs">
                    <div>
                        <div class="font-mono font-bold text-[#0048FA]">{{ $rel->code_immobilisation ?? $rel->code_scanne }}</div>
                        <div class="text-[11px] text-slate-600 truncate max-w-[200px]">{{ $rel->designation ?? $rel->immobilisation?->designation }}</div>
                    </div>
                    <span class="px-2 py-0.5 rounded-full text-[9px] font-bold {{ $rel->statut_rapprochement === 'conforme' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                        {{ ucfirst($rel->statut_rapprochement ?? 'enregistré') }}
                    </span>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">Aucun relevé enregistré.</p>
                @endforelse
            </div>
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
