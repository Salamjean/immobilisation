<!-- Sidebar Consultant Expert - Style Identique Admin Light Flottant / Détaché & Réductible -->
<aside id="mainSidebar" style="border-color: #155E3B;" class="w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col rounded-2xl border-2 shadow-sm transition-all duration-300 ease-in-out overflow-hidden my-3 ml-3 h-[calc(100vh-1.5rem)] sticky top-3">
    <!-- Header / Brand avec Logo GEMMA -->
    <div class="h-16 px-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-2.5 overflow-hidden">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
        </div>
        <span class="sidebar-text-element inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #155E3B;">
            Consultant
        </span>
    </div>

    <!-- Navigation Consultant -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-sidebar-scrollbar">
        <div class="sidebar-text-element px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Vue Générale</div>

        <!-- 1. Synthèse & Indicateurs -->
        <a href="{{ route('auditeur.dashboard') }}"
           title="Synthèse & Indicateurs"
           style="{{ request()->routeIs('auditeur.dashboard') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('auditeur.dashboard') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center flex-shrink-0 {{ request()->routeIs('auditeur.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Synthèse & Indicateurs</span>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Consultation Actifs</div>

        <!-- 2. Référentiel des Actifs -->
        <a href="{{ route('gestionnaire.immobilisations.index') }}"
           title="Référentiel Actifs"
           style="{{ request()->routeIs('gestionnaire.immobilisations.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.immobilisations.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-boxes-stacked w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.immobilisations.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Référentiel Actifs</span>
        </a>

        <!-- 3. Cartographie SIG & GPS -->
        <a href="{{ route('gestionnaire.cartographie.index') }}"
           title="Cartographie SIG"
           style="{{ request()->routeIs('gestionnaire.cartographie.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.cartographie.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-map-location-dot w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.cartographie.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Cartographie SIG & GPS</span>
        </a>

        <!-- 4. Campagnes d'Inventaire -->
        <a href="{{ route('gestionnaire.inventaires.index') }}"
           title="Inventaires Physiques"
           style="{{ request()->routeIs('gestionnaire.inventaires.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.inventaires.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-clipboard-check w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.inventaires.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Inventaires Physiques</span>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Restitution & Rapports</div>

        <!-- 5. Rapports & Registres (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="consultantToggleMenu('rapportsSubmenu', 'rapportsMenuChevron')"
                    title="Rapports & Registre"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('auditeur.reporting.*') ? 'bg-emerald-50/60 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-file-contract w-5 text-center flex-shrink-0 {{ request()->routeIs('auditeur.reporting.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Rapports & Registre</span>
                </div>
                <i id="rapportsMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('auditeur.reporting.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Rapports -->
            <div id="rapportsSubmenu" class="sidebar-text-element {{ request()->routeIs('auditeur.reporting.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('auditeur.reporting.index') }}"
                   style="{{ request()->routeIs('auditeur.reporting.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('auditeur.reporting.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-file-lines text-[11px] {{ request()->routeIs('auditeur.reporting.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>États réglementaires</span>
                </a>

                <a href="{{ route('auditeur.reporting.registre') }}"
                   style="{{ request()->routeIs('auditeur.reporting.registre') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('auditeur.reporting.registre') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-book-bookmark text-[11px] {{ request()->routeIs('auditeur.reporting.registre') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Registre Officiel</span>
                </a>
            </div>
        </div>
    </nav>

    <!-- Bottom Sidebar Épuré avec Fond Vert #155E3B -->
    <div class="p-3 border-t border-slate-100 flex-shrink-0">
        <div style="background-color: #155E3B;" class="flex items-center space-x-2.5 p-2 rounded-xl text-white shadow-xs">
            @if(auth()->user()?->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->nom_complet }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-white/40 shadow-xs">
            @else
                <div class="w-8 h-8 rounded-lg bg-white/20 border border-white/30 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                    {{ substr(auth()->user()->name ?? 'C', 0, 1) }}{{ substr(auth()->user()->prenoms ?? 'S', 0, 1) }}
                </div>
            @endif
            <div class="sidebar-text-element min-w-0 flex-1">
                <div class="text-xs font-bold text-white truncate">{{ auth()->user()->nom_complet ?: auth()->user()->name ?? 'Consultant' }}</div>
                <div class="text-[10px] text-white/80 font-medium truncate">{{ auth()->user()->email ?? 'consultant@ucpfm.ci' }}</div>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Scrollbar ultra-fine pour la Sidebar */
    .custom-sidebar-scrollbar::-webkit-scrollbar,
    #mainSidebar nav::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }
    .custom-sidebar-scrollbar::-webkit-scrollbar-track,
    #mainSidebar nav::-webkit-scrollbar-track {
        background: transparent;
    }
    .custom-sidebar-scrollbar::-webkit-scrollbar-thumb,
    #mainSidebar nav::-webkit-scrollbar-thumb {
        background: rgba(203, 213, 225, 0.7);
        border-radius: 9999px;
        transition: background-color 0.2s ease;
    }
    .custom-sidebar-scrollbar::-webkit-scrollbar-thumb:hover,
    #mainSidebar nav::-webkit-scrollbar-thumb:hover {
        background: #155E3B;
    }
    .custom-sidebar-scrollbar,
    #mainSidebar nav {
        scrollbar-width: thin;
        scrollbar-color: rgba(203, 213, 225, 0.7) transparent;
    }
</style>

<script>
    window.consultantToggleMenu = function(submenuId, chevronId) {
        var submenu = document.getElementById(submenuId);
        var chevron = document.getElementById(chevronId);
        if (!submenu) return;
        
        var isHidden = submenu.classList.contains('hidden');
        if (isHidden) {
            submenu.classList.remove('hidden');
            submenu.classList.add('block');
            if (chevron) chevron.classList.add('rotate-180');
        } else {
            submenu.classList.add('hidden');
            submenu.classList.remove('block');
            if (chevron) chevron.classList.remove('rotate-180');
        }
    };
</script>
