<!-- Sidebar Utilisateur Programme - Style Identique Admin / Gestionnaire Light Flottant / Détaché & Réductible -->
<aside id="mainSidebar" style="border-color: #0048FA;" class="w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col rounded-2xl border-2 shadow-sm transition-all duration-300 ease-in-out overflow-hidden my-3 ml-3 h-[calc(100vh-1.5rem)] sticky top-3">
    <!-- Header / Brand avec Logo GEMMA -->
    <div class="h-16 px-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-2.5 overflow-hidden">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
        </div>
        <span class="sidebar-text-element inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #0048FA;">
            Programme
        </span>
    </div>

    <!-- Info Programme Rattaché -->
    <div class="sidebar-text-element mx-3 mt-3 p-2.5 rounded-xl bg-blue-50/70 border border-emerald-100 text-xs">
        <div class="text-slate-500 text-[10px] font-bold uppercase tracking-wider flex items-center justify-between">
            <span>Programme Assigné</span>
            <span class="font-extrabold text-[#0048FA]">{{ auth()->user()->programme->sigle ?? 'GC7 / CI' }}</span>
        </div>
        <div class="text-slate-800 font-bold truncate mt-0.5 text-[11px]">
            {{ auth()->user()->programme->nom ?? 'Suivi des Actifs Sanitaires' }}
        </div>
    </div>

    <!-- Navigation Programme -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-sidebar-scrollbar">
        <div class="sidebar-text-element px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Vue Générale</div>

        <!-- 1. Tableau de bord -->
        <a href="{{ route('programme.dashboard') }}"
           title="Tableau de bord"
           style="{{ request()->routeIs('programme.dashboard') || request()->is('/') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('programme.dashboard') || request()->is('/') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center flex-shrink-0 {{ request()->routeIs('programme.dashboard') || request()->is('/') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Tableau de bord</span>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Parc & Dotations</div>

        <!-- 2. Actifs du Programme -->
        <a href="{{ route('programme.immobilisations.index') }}"
           title="Actifs du Programme"
           style="{{ request()->routeIs('programme.immobilisations.*') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('programme.immobilisations.*') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-boxes-packing w-5 text-center flex-shrink-0 {{ request()->routeIs('programme.immobilisations.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Actifs du Programme</span>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Outils Terrain</div>

        <!-- 3. Scanner Mobile QR -->
        <a href="{{ route('agent.mobile.index') }}"
           target="_blank"
           title="Scanner Mobile QR"
           class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-bold bg-emerald-50 text-[#0048FA] border border-emerald-200/80 hover:bg-emerald-100 transition-all duration-200 shadow-2xs">
            <div class="flex items-center">
                <i class="fa-solid fa-mobile-screen-button w-5 text-center flex-shrink-0 text-[#0048FA]"></i>
                <span class="sidebar-text-element ml-3 whitespace-nowrap">Scanner Mobile</span>
            </div>
            <i class="sidebar-text-element fa-solid fa-arrow-up-right-from-square text-xs text-[#0048FA]/70"></i>
        </a>
    </nav>

    <!-- Bottom Sidebar Épuré avec Fond Vert #0048FA -->
    <div class="p-3 border-t border-slate-100 flex-shrink-0">
        <div style="background-color: #0048FA;" class="flex items-center space-x-2.5 p-2 rounded-xl text-white shadow-xs">
            @if(auth()->user()?->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->nom_complet }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-white/40 shadow-xs">
            @else
                <div class="w-8 h-8 rounded-lg bg-white/20 border border-white/30 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                    {{ substr(auth()->user()->name ?? 'P', 0, 1) }}{{ substr(auth()->user()->prenoms ?? 'R', 0, 1) }}
                </div>
            @endif
            <div class="sidebar-text-element min-w-0 flex-1">
                <div class="text-xs font-bold text-white truncate">{{ auth()->user()->nom_complet ?: auth()->user()->name ?? 'Programme' }}</div>
                <div class="text-[10px] text-emerald-100 font-medium truncate">{{ auth()->user()->role_libelle ?? 'Utilisateur Programme' }}</div>
            </div>
            <form action="{{ route('logout') }}" method="POST" class="sidebar-text-element">
                @csrf
                <button type="submit" title="Se déconnecter" class="w-7 h-7 rounded-lg bg-white/15 hover:bg-white/25 flex items-center justify-center text-white transition-colors">
                    <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                </button>
            </form>
        </div>
    </div>
</aside>
