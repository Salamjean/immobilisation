<!-- Sidebar Agent Inventaire - Style Identique Admin / Gestionnaire Light Flottant -->
<aside id="mainSidebar" style="border-color: #155E3B;" class="w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col rounded-2xl border-2 shadow-sm transition-all duration-300 ease-in-out overflow-hidden my-3 ml-3 h-[calc(100vh-1.5rem)] sticky top-3">
    <!-- Header / Brand avec Logo GEMMA -->
    <div class="h-16 px-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-2.5 overflow-hidden">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
        </div>
        <span class="sidebar-text-element inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #155E3B;">
            Agent
        </span>
    </div>

    <!-- Navigation Agent -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-sidebar-scrollbar">
        <div class="sidebar-text-element px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Collecte & Scan Terrain</div>

        <!-- 1. Scanner Mobile PWA -->
        <a href="{{ route('agent.mobile.index') }}"
           target="_blank"
           title="Application Mobile Scan"
           style="background-color: #155E3B;"
           class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold text-white shadow-md shadow-[#155E3B]/25 transition-all duration-200">
            <div class="flex items-center">
                <i class="fa-solid fa-mobile-screen-button w-5 text-center flex-shrink-0 text-white"></i>
                <span class="sidebar-text-element ml-3 whitespace-nowrap">Scanner Mobile QR</span>
            </div>
            <i class="sidebar-text-element fa-solid fa-arrow-up-right-from-square text-[10px] text-white/80"></i>
        </a>

        <!-- 2. Campagnes d'Inventaire -->
        <a href="{{ route('gestionnaire.inventaires.index') }}"
           title="Campagnes d'Inventaire"
           style="{{ request()->routeIs('gestionnaire.inventaires.*') ? 'background-color: #155E3B; color: white;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.inventaires.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-clipboard-check w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.inventaires.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Campagnes Inventaire</span>
        </a>

        <!-- 3. Recherche Actifs -->
        <a href="{{ route('gestionnaire.immobilisations.index') }}"
           title="Recherche d'actifs"
           style="{{ request()->routeIs('gestionnaire.immobilisations.*') ? 'background-color: #155E3B; color: white;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.immobilisations.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-boxes-stacked w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.immobilisations.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Référentiel des Biens</span>
        </a>

        <!-- 4. Cartographie SIG Indépendante Agent -->
        <a href="{{ route('agent.cartographie.index') }}"
           title="Cartographie SIG"
           style="{{ request()->routeIs('agent.cartographie.*') ? 'background-color: #155E3B; color: white;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('agent.cartographie.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-map-location-dot w-5 text-center flex-shrink-0 {{ request()->routeIs('agent.cartographie.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Carte des Équipements</span>
        </a>
    </nav>

    <!-- Footer Sidebar -->
    <div class="p-3 border-t border-slate-100 flex-shrink-0 bg-slate-50/70">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                    title="Se déconnecter"
                    class="w-full flex items-center justify-center space-x-2 px-3 py-2 rounded-xl text-xs font-semibold text-rose-600 hover:bg-rose-50 border border-transparent hover:border-rose-100 transition-all">
                <i class="fa-solid fa-arrow-right-from-bracket text-xs"></i>
                <span class="sidebar-text-element">Déconnexion</span>
            </button>
        </form>
    </div>
</aside>
