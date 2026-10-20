<!-- Sidebar Contrôleur Interne - Style Identique Admin / Auditeur Light Flottant / Détaché & Réductible -->
<aside id="mainSidebar" style="border-color: #0048FA;" class="w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col rounded-2xl border-2 shadow-sm transition-all duration-300 ease-in-out overflow-hidden my-3 ml-3 h-[calc(100vh-1.5rem)] sticky top-3">
    <!-- Header / Brand avec Logo GEMMA -->
    <div class="h-16 px-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-2.5 overflow-hidden">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
        </div>
        <span class="sidebar-text-element inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #0048FA;">
            Contrôle
        </span>
    </div>

    <!-- Navigation Contrôleur -->
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

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Contrôle & Traçabilité</div>

        <!-- 2. Piste d'Audit -->
        <a href="{{ route('auditeur.audit.index') }}"
           title="Piste d'Audit"
           style="{{ request()->routeIs('auditeur.audit.*') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('auditeur.audit.*') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-fingerprint w-5 text-center flex-shrink-0 {{ request()->routeIs('auditeur.audit.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Piste d'Audit Intègre</span>
        </a>

        <!-- 3. Rapports & Registres -->
        <a href="{{ route('auditeur.reporting.index') }}"
           title="Rapports & Registre"
           style="{{ request()->routeIs('auditeur.reporting.*') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('auditeur.reporting.*') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-file-contract w-5 text-center flex-shrink-0 {{ request()->routeIs('auditeur.reporting.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Rapports & Registre</span>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Consultation Actifs</div>

        <!-- 4. Référentiel Actifs -->
        <a href="{{ route('gestionnaire.immobilisations.index') }}"
           title="Référentiel Actifs"
           style="{{ request()->routeIs('gestionnaire.immobilisations.*') ? 'background-color: #0048FA;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.immobilisations.*') ? 'text-white shadow-md shadow-[#0048FA]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-boxes-stacked w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.immobilisations.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Référentiel Actifs</span>
        </a>
    </nav>

    <!-- Bottom Sidebar Épuré avec Fond Vert #0048FA -->
    <div class="p-3 border-t border-slate-100 flex-shrink-0">
        <div style="background-color: #0048FA;" class="flex items-center space-x-2.5 p-2 rounded-xl text-white shadow-xs">
            @if(auth()->user()?->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->nom_complet }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-white/40 shadow-xs">
            @else
                <div class="w-8 h-8 rounded-lg bg-white/20 border border-white/30 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                    {{ substr(auth()->user()->name ?? 'C', 0, 1) }}{{ substr(auth()->user()->prenoms ?? 'O', 0, 1) }}
                </div>
            @endif
            <div class="sidebar-text-element min-w-0 flex-1">
                <div class="text-xs font-bold text-white truncate">{{ auth()->user()->nom_complet ?: auth()->user()->name ?? 'Contrôleur' }}</div>
                <div class="text-[10px] text-emerald-100 font-medium truncate">{{ auth()->user()->role_libelle ?? 'Contrôleur' }}</div>
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
