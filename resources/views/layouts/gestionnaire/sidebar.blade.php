<!-- Sidebar Gestionnaire de Patrimoine - Style Identique Admin Light Flottant / Détaché & Réductible -->
<aside id="mainSidebar" style="border-color: #155E3B;" class="w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col rounded-2xl border-2 shadow-sm transition-all duration-300 ease-in-out overflow-hidden my-3 ml-3 h-[calc(100vh-1.5rem)] sticky top-3">
    <!-- Header / Brand avec Logo GEMMA -->
    <div class="h-16 px-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-2.5 overflow-hidden">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
        </div>
        <span class="sidebar-text-element inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #155E3B;">
            Gestion
        </span>
    </div>

    <!-- Navigation Gestionnaire -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-sidebar-scrollbar">
        <div class="sidebar-text-element px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Vue Générale</div>

        <!-- 1. Tableau de bord -->
        <a href="{{ route('gestionnaire.dashboard') }}"
           title="Tableau de bord"
           style="{{ request()->routeIs('gestionnaire.dashboard') || request()->is('/') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.dashboard') || request()->is('/') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-chart-pie w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.dashboard') || request()->is('/') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Tableau de bord</span>
        </a>

        <!-- 2. Cartographie SIG & GPS -->
        <a href="{{ route('gestionnaire.cartographie.index') }}"
           title="Cartographie SIG"
           style="{{ request()->routeIs('gestionnaire.cartographie.*') || request()->routeIs('cartographie.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.cartographie.*') || request()->routeIs('cartographie.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-map-location-dot w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.cartographie.*') || request()->routeIs('cartographie.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Cartographie SIG & GPS</span>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Cycle de Vie & Actifs</div>

        <!-- 3. Immobilisations (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="gestionnaireToggleMenu('immoSubmenu', 'immoMenuChevron')"
                    title="Immobilisations"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.immobilisations.*') || request()->routeIs('immobilisations.*') ? 'bg-emerald-50/60 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.immobilisations.*') || request()->routeIs('immobilisations.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Immobilisations</span>
                </div>
                <i id="immoMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('gestionnaire.immobilisations.*') || request()->routeIs('immobilisations.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Immobilisations -->
            <div id="immoSubmenu" class="sidebar-text-element {{ request()->routeIs('gestionnaire.immobilisations.*') || request()->routeIs('immobilisations.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('gestionnaire.immobilisations.index') }}"
                   style="{{ request()->routeIs('gestionnaire.immobilisations.index') || (request()->routeIs('immobilisations.index') && !request()->routeIs('gestionnaire.immobilisations.create')) ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.immobilisations.index') || (request()->routeIs('immobilisations.index') && !request()->routeIs('gestionnaire.immobilisations.create')) ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('gestionnaire.immobilisations.index') || (request()->routeIs('immobilisations.index') && !request()->routeIs('gestionnaire.immobilisations.create')) ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des actifs</span>
                </a>

                <a href="{{ route('gestionnaire.immobilisations.create') }}"
                   style="{{ request()->routeIs('gestionnaire.immobilisations.create') || request()->routeIs('immobilisations.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.immobilisations.create') || request()->routeIs('immobilisations.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('gestionnaire.immobilisations.create') || request()->routeIs('immobilisations.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Ajouter un actif</span>
                </a>
            </div>
        </div>

        <!-- 4. Mouvements & Transferts (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="gestionnaireToggleMenu('mouvSubmenu', 'mouvMenuChevron')"
                    title="Mouvements"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.mouvements.*') || request()->routeIs('mouvements.*') ? 'bg-emerald-50/60 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-arrow-right-arrow-left w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.mouvements.*') || request()->routeIs('mouvements.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Mouvements</span>
                </div>
                <i id="mouvMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('gestionnaire.mouvements.*') || request()->routeIs('mouvements.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Mouvements -->
            <div id="mouvSubmenu" class="sidebar-text-element {{ request()->routeIs('gestionnaire.mouvements.*') || request()->routeIs('mouvements.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('gestionnaire.mouvements.index') }}"
                   style="{{ request()->routeIs('gestionnaire.mouvements.index') || (request()->routeIs('mouvements.index') && !request()->routeIs('gestionnaire.mouvements.create')) ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.mouvements.index') || (request()->routeIs('mouvements.index') && !request()->routeIs('gestionnaire.mouvements.create')) ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('gestionnaire.mouvements.index') || (request()->routeIs('mouvements.index') && !request()->routeIs('gestionnaire.mouvements.create')) ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Journal des mouvements</span>
                </a>

                <a href="{{ route('gestionnaire.mouvements.create') }}"
                   style="{{ request()->routeIs('gestionnaire.mouvements.create') || request()->routeIs('mouvements.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.mouvements.create') || request()->routeIs('mouvements.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('gestionnaire.mouvements.create') || request()->routeIs('mouvements.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Nouveau mouvement</span>
                </a>
            </div>
        </div>

        <!-- 5. Maintenance & SAV (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="gestionnaireToggleMenu('maintSubmenu', 'maintMenuChevron')"
                    title="Maintenance"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.maintenances.*') || request()->routeIs('maintenances.*') ? 'bg-emerald-50/60 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-wrench w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.maintenances.*') || request()->routeIs('maintenances.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Maintenance & SAV</span>
                </div>
                <i id="maintMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('gestionnaire.maintenances.*') || request()->routeIs('maintenances.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Maintenance -->
            <div id="maintSubmenu" class="sidebar-text-element {{ request()->routeIs('gestionnaire.maintenances.*') || request()->routeIs('maintenances.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('gestionnaire.maintenances.index') }}"
                   style="{{ request()->routeIs('gestionnaire.maintenances.index') || (request()->routeIs('maintenances.index') && !request()->routeIs('gestionnaire.maintenances.create')) ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.maintenances.index') || (request()->routeIs('maintenances.index') && !request()->routeIs('gestionnaire.maintenances.create')) ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('gestionnaire.maintenances.index') || (request()->routeIs('maintenances.index') && !request()->routeIs('gestionnaire.maintenances.create')) ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste interventions</span>
                </a>

                <a href="{{ route('gestionnaire.maintenances.create') }}"
                   style="{{ request()->routeIs('gestionnaire.maintenances.create') || request()->routeIs('maintenances.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.maintenances.create') || request()->routeIs('maintenances.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('gestionnaire.maintenances.create') || request()->routeIs('maintenances.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Déclarer une panne</span>
                </a>
            </div>
        </div>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Inventaires & Restitution</div>

        <!-- 6. Campagnes d'Inventaire (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="gestionnaireToggleMenu('invSubmenu', 'invMenuChevron')"
                    title="Inventaires"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.inventaires.*') || request()->routeIs('inventaires.*') ? 'bg-emerald-50/60 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-clipboard-check w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.inventaires.*') || request()->routeIs('inventaires.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Campagnes Inventaire</span>
                </div>
                <i id="invMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('gestionnaire.inventaires.*') || request()->routeIs('inventaires.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Inventaires -->
            <div id="invSubmenu" class="sidebar-text-element {{ request()->routeIs('gestionnaire.inventaires.*') || request()->routeIs('inventaires.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('gestionnaire.inventaires.index') }}"
                   style="{{ request()->routeIs('gestionnaire.inventaires.index') || (request()->routeIs('inventaires.index') && !request()->routeIs('gestionnaire.inventaires.create')) ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.inventaires.index') || (request()->routeIs('inventaires.index') && !request()->routeIs('gestionnaire.inventaires.create')) ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('gestionnaire.inventaires.index') || (request()->routeIs('inventaires.index') && !request()->routeIs('gestionnaire.inventaires.create')) ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste campagnes</span>
                </a>

                <a href="{{ route('gestionnaire.inventaires.create') }}"
                   style="{{ request()->routeIs('gestionnaire.inventaires.create') || request()->routeIs('inventaires.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.inventaires.create') || request()->routeIs('inventaires.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('gestionnaire.inventaires.create') || request()->routeIs('inventaires.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Nouvelle campagne</span>
                </a>
            </div>
        </div>

        <!-- 7. Application Mobile Recenseur -->
        <a href="{{ route('agent.mobile.index') }}"
           target="_blank"
           title="Application Mobile Recenseur Terrain"
           class="group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50">
            <div class="flex items-center">
                <i class="fa-solid fa-mobile-screen-button w-5 text-center flex-shrink-0 text-slate-400 group-hover:text-slate-700"></i>
                <span class="sidebar-text-element ml-3 whitespace-nowrap">App Mobile Terrain</span>
            </div>
            <i class="sidebar-text-element fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Référentiels Patrimoine</div>

        <!-- 8. Structures & Sites Sanitaires (Lien Direct) -->
        <a href="{{ route('gestionnaire.structures.index') }}"
           title="Structures & Sites"
           style="{{ request()->routeIs('gestionnaire.structures.*') || request()->routeIs('admin.structures.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.structures.*') || request()->routeIs('admin.structures.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-hospital w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.structures.*') || request()->routeIs('admin.structures.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Structures & Sites</span>
        </a>

        <!-- 9. Programmes & Bailleurs (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="gestionnaireToggleMenu('progSubmenu', 'progMenuChevron')"
                    title="Programmes & Bailleurs"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.programmes.*') || request()->routeIs('gestionnaire.bailleurs.*') || request()->routeIs('admin.programmes.*') || request()->routeIs('admin.bailleurs.*') ? 'bg-emerald-50/60 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-hand-holding-dollar w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.programmes.*') || request()->routeIs('gestionnaire.bailleurs.*') || request()->routeIs('admin.programmes.*') || request()->routeIs('admin.bailleurs.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Bailleurs & Projets</span>
                </div>
                <i id="progMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('gestionnaire.programmes.*') || request()->routeIs('gestionnaire.bailleurs.*') || request()->routeIs('admin.programmes.*') || request()->routeIs('admin.bailleurs.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Programmes & Bailleurs -->
            <div id="progSubmenu" class="sidebar-text-element {{ request()->routeIs('gestionnaire.programmes.*') || request()->routeIs('gestionnaire.bailleurs.*') || request()->routeIs('gestionnaire.subventions.*') || request()->routeIs('admin.programmes.*') || request()->routeIs('admin.bailleurs.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('gestionnaire.programmes.index') }}"
                   style="{{ request()->routeIs('gestionnaire.programmes.*') || request()->routeIs('admin.programmes.*') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.programmes.*') || request()->routeIs('admin.programmes.*') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-layer-group text-[11px] {{ request()->routeIs('gestionnaire.programmes.*') || request()->routeIs('admin.programmes.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Programmes UCP</span>
                </a>

                <a href="{{ route('gestionnaire.bailleurs.index') }}"
                   style="{{ request()->routeIs('gestionnaire.bailleurs.*') || request()->routeIs('admin.bailleurs.*') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.bailleurs.*') || request()->routeIs('admin.bailleurs.*') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-building-columns text-[11px] {{ request()->routeIs('gestionnaire.bailleurs.*') || request()->routeIs('admin.bailleurs.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Bailleurs de fonds</span>
                </a>

                <a href="{{ route('gestionnaire.subventions.index') }}"
                   style="{{ request()->routeIs('gestionnaire.subventions.*') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.subventions.*') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-sack-dollar text-[11px] {{ request()->routeIs('gestionnaire.subventions.*') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Subventions & Lignes</span>
                </a>
            </div>
        </div>

        <!-- 10. Catégories & Nomenclature (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="gestionnaireToggleMenu('catSubmenu', 'catMenuChevron')"
                    title="Catégories"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.categories.*') || request()->routeIs('admin.categories.*') ? 'bg-emerald-50/60 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-tags w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.categories.*') || request()->routeIs('admin.categories.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Nomenclatures</span>
                </div>
                <i id="catMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('gestionnaire.categories.*') || request()->routeIs('admin.categories.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Catégories -->
            <div id="catSubmenu" class="sidebar-text-element {{ request()->routeIs('gestionnaire.categories.*') || request()->routeIs('admin.categories.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('gestionnaire.categories.index') }}"
                   style="{{ request()->routeIs('gestionnaire.categories.index') || request()->routeIs('admin.categories.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.categories.index') || request()->routeIs('admin.categories.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('gestionnaire.categories.index') || request()->routeIs('admin.categories.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des catégories</span>
                </a>

                <a href="{{ route('gestionnaire.categories.create') }}"
                   style="{{ request()->routeIs('gestionnaire.categories.create') || request()->routeIs('admin.categories.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('gestionnaire.categories.create') || request()->routeIs('admin.categories.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('gestionnaire.categories.create') || request()->routeIs('admin.categories.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Nouvelle catégorie</span>
                </a>
            </div>
        </div>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Restitution & Audit</div>

        <!-- 11. Reporting & Registre Officiel -->
        <a href="{{ route('gestionnaire.reporting.index') }}"
           title="Rapports & Registre"
           style="{{ request()->routeIs('gestionnaire.reporting.*') || request()->routeIs('reporting.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.reporting.*') || request()->routeIs('reporting.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-file-invoice w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.reporting.*') || request()->routeIs('reporting.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Rapports & Registre</span>
        </a>

        <!-- 12. Journal d'Audit & Traçabilité -->
        <a href="{{ route('gestionnaire.audit.index') }}"
           title="Journal d'audit"
           style="{{ request()->routeIs('gestionnaire.audit.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('gestionnaire.audit.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-clock-rotate-left w-5 text-center flex-shrink-0 {{ request()->routeIs('gestionnaire.audit.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Piste d'Audit & Logs</span>
        </a>
    </nav>

    <!-- Footer Sidebar Gestionnaire -->
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

<script>
    function gestionnaireToggleMenu(submenuId, chevronId) {
        const submenu = document.getElementById(submenuId);
        const chevron = document.getElementById(chevronId);
        if (submenu) {
            const isHidden = submenu.classList.contains('hidden');
            if (isHidden) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                submenu.classList.remove('block');
                submenu.classList.add('hidden');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        }
    }
</script>

<style>
    /* Scrollbar ultra-fine et moderne pour la Sidebar Gestionnaire */
    .custom-sidebar-scrollbar::-webkit-scrollbar,
    aside nav::-webkit-scrollbar {
        width: 4px;
        height: 4px;
    }

    .custom-sidebar-scrollbar::-webkit-scrollbar-track,
    aside nav::-webkit-scrollbar-track {
        background: transparent;
    }

    .custom-sidebar-scrollbar::-webkit-scrollbar-thumb,
    aside nav::-webkit-scrollbar-thumb {
        background: rgba(148, 163, 184, 0.4); /* slate-400 */
        border-radius: 9999px;
        transition: background-color 0.2s ease;
    }

    .custom-sidebar-scrollbar::-webkit-scrollbar-thumb:hover,
    aside nav::-webkit-scrollbar-thumb:hover {
        background: #155E3B; /* Vert émeraude officiel UCP-FM */
    }

    /* Support Firefox */
    .custom-sidebar-scrollbar,
    aside nav {
        scrollbar-width: thin;
        scrollbar-color: rgba(148, 163, 184, 0.4) transparent;
    }
</style>
