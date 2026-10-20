<!-- Sidebar Auditeur & Conformité - Style Identique Admin Light Flottant / Détaché & Réductible -->
<aside id="mainSidebar" style="border-color: #0048FA;" class="w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col rounded-2xl border-2 shadow-sm transition-all duration-300 ease-in-out overflow-hidden my-3 ml-3 h-[calc(100vh-1.5rem)] sticky top-3">
    <!-- Header / Brand avec Logo GEMMA -->
    <div class="h-16 px-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-2.5 overflow-hidden">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
        </div>
        <span class="sidebar-text-element inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #0048FA;">
            Audit
        </span>
    </div>

    <!-- Navigation Auditeur -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-sidebar-scrollbar">
        <div class="sidebar-text-element px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Vue Générale</div>

        <!-- 1. Tableau de bord -->
        <a href="{{ route('auditeur.dashboard') }}"
           title="Tableau de bord"
           style="{{ request()->routeIs('auditeur.dashboard') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('auditeur.dashboard') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center flex-shrink-0 {{ request()->routeIs('auditeur.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Tableau de bord</span>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Supervision & Traçabilité</div>

        <!-- 2. Journal d'Audit Intègre -->
        <a href="{{ route('auditeur.audit.index') }}"
           title="Journal d'Audit"
           style="{{ request()->routeIs('auditeur.audit.*') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('auditeur.audit.*') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-fingerprint w-5 text-center flex-shrink-0 {{ request()->routeIs('auditeur.audit.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Journal d'Audit Intègre</span>
        </a>

        <!-- 3. Rapports & Registres (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="auditeurToggleMenu('rapportsSubmenu', 'rapportsMenuChevron')"
                    title="Rapports & Registre"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('auditeur.reporting.*') ? 'bg-blue-50/70 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-file-contract w-5 text-center flex-shrink-0 {{ request()->routeIs('auditeur.reporting.*') ? 'text-[#0048FA]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Rapports & Registre</span>
                </div>
                <i id="rapportsMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('auditeur.reporting.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Rapports -->
            <div id="rapportsSubmenu" class="sidebar-text-element {{ request()->routeIs('auditeur.reporting.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('auditeur.reporting.index') }}"
                   style="{{ request()->routeIs('auditeur.reporting.index') ? 'background-color: #0048FA;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('auditeur.reporting.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-file-lines text-[11px] {{ request()->routeIs('auditeur.reporting.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>États réglementaires</span>
                </a>

                <a href="{{ route('auditeur.reporting.registre') }}"
                   style="{{ request()->routeIs('auditeur.reporting.registre') ? 'background-color: #0048FA;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('auditeur.reporting.registre') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-book-bookmark text-[11px] {{ request()->routeIs('auditeur.reporting.registre') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Registre Officiel</span>
                </a>
            </div>
        </div>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Consultation Actifs</div>

        <!-- 4. Référentiel des Actifs -->
        <a href="{{ route('gestionnaire.immobilisations.index') }}"
           title="Référentiel Actifs"
           style="{{ request()->routeIs('gestionnaire.immobilisations.*') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.immobilisations.*') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-boxes-stacked w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.immobilisations.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Référentiel Actifs</span>
        </a>

        <!-- 5. Campagnes d'Inventaire -->
        <a href="{{ route('gestionnaire.inventaires.index') }}"
           title="Campagnes d'Inventaire"
           style="{{ request()->routeIs('gestionnaire.inventaires.*') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.inventaires.*') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-clipboard-check w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.inventaires.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Inventaires Physiques</span>
        </a>
    </nav>

    <!-- Bottom Sidebar Épuré avec Fond Vert #0048FA -->
    <div class="p-3 border-t border-slate-100 flex-shrink-0">
        <div style="background-color: #0048FA;" class="flex items-center space-x-2.5 p-2 rounded-xl text-white shadow-xs">
            @if(auth()->user()?->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->nom_complet }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-white/40 shadow-xs">
            @else
                <div class="w-8 h-8 rounded-lg bg-white/20 border border-white/30 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}{{ substr(auth()->user()->prenoms ?? 'U', 0, 1) }}
                </div>
            @endif
            <div class="sidebar-text-element min-w-0 flex-1">
                <div class="text-xs font-bold text-white truncate">{{ auth()->user()->nom_complet ?: auth()->user()->name ?? 'Auditeur' }}</div>
                <div class="text-[10px] text-white/80 font-medium truncate">{{ auth()->user()->email ?? 'auditeur@ucpfm.ci' }}</div>
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
        background: #0048FA;
    }
    .custom-sidebar-scrollbar,
    #mainSidebar nav {
        scrollbar-width: thin;
        scrollbar-color: rgba(203, 213, 225, 0.7) transparent;
    }
</style>

<script>
    window.auditeurToggleMenu = function(submenuId, chevronId) {
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
