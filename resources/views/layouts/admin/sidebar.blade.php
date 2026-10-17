<!-- Sidebar Administrateur - Design Simple & Moderne Light Flottant / Détaché & Réductible -->
<aside id="mainSidebar" style="border-color: #155E3B;" class="w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col rounded-2xl border-2 shadow-sm transition-all duration-300 ease-in-out overflow-hidden my-3 ml-3 h-[calc(100vh-1.5rem)] sticky top-3">
    <!-- Header / Brand avec Logo GEMMA -->
    <div class="h-16 px-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
        <div class="flex items-center space-x-2.5 overflow-hidden">
            <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
        </div>
        <div class="flex items-center space-x-2">
            <span class="sidebar-text-element inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #155E3B;">
                Admin
            </span>
            <button type="button" onclick="window.closeMobileSidebar && window.closeMobileSidebar()" class="md:hidden w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center cursor-pointer transition-colors" title="Fermer le menu">
                <i class="fa-solid fa-xmark text-xs"></i>
            </button>
        </div>
    </div>

    <!-- Navigation Admin -->
    <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-sidebar-scrollbar">
        <div class="sidebar-text-element px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Vue Générale</div>

        <!-- 1. Console Admin -->
        <a href="{{ route('admin.dashboard') }}"
           title="Tableau de bord"
           style="{{ request()->routeIs('admin.dashboard') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-gauge-high w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Tableau de bord</span>
        </a>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Référentiel & Actifs</div>

        <!-- 2. Immobilisations (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('immoSubmenu', 'immoMenuChevron')"
                    title="Immobilisations"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.immobilisations.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-boxes-stacked w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.immobilisations.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Immobilisations</span>
                </div>
                <i id="immoMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.immobilisations.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Immobilisations -->
            <div id="immoSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.immobilisations.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.immobilisations.index') }}"
                   style="{{ request()->routeIs('admin.immobilisations.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.immobilisations.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.immobilisations.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des actifs</span>
                </a>

                <a href="{{ route('admin.immobilisations.create') }}"
                   style="{{ request()->routeIs('admin.immobilisations.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.immobilisations.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('admin.immobilisations.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Ajouter un actif</span>
                </a>
            </div>
        </div>

        <!-- 3. Catégories d'actifs (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('catSubmenu', 'catMenuChevron')"
                    title="Catégories"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.categories.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-tags w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.categories.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Catégories</span>
                </div>
                <i id="catMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.categories.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Catégories -->
            <div id="catSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.categories.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.categories.index') }}"
                   style="{{ request()->routeIs('admin.categories.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.categories.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.categories.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des catégories</span>
                </a>

                <a href="{{ route('admin.categories.create') }}"
                   style="{{ request()->routeIs('admin.categories.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.categories.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('admin.categories.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Ajouter une catégorie</span>
                </a>
            </div>
        </div>

        <!-- 4. Structures & Emplacements (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('structuresSubmenu', 'structuresMenuChevron')"
                    title="Structures"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.structures.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-hospital w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.structures.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Structures</span>
                </div>
                <i id="structuresMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.structures.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Structures -->
            <div id="structuresSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.structures.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.structures.index') }}"
                   style="{{ request()->routeIs('admin.structures.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.structures.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.structures.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des structures</span>
                </a>

                <a href="{{ route('admin.structures.create') }}"
                   style="{{ request()->routeIs('admin.structures.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.structures.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('admin.structures.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Ajouter une structure</span>
                </a>
            </div>
        </div>

        <!-- 4b. Bailleurs de Fonds (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('bailleursSubmenu', 'bailleursMenuChevron')"
                    title="Bailleurs de Fonds"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.bailleurs.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-hand-holding-dollar w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.bailleurs.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Bailleurs de Fonds</span>
                </div>
                <i id="bailleursMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.bailleurs.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Bailleurs -->
            <div id="bailleursSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.bailleurs.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.bailleurs.index') }}"
                   style="{{ request()->routeIs('admin.bailleurs.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.bailleurs.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.bailleurs.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des bailleurs</span>
                </a>

                <a href="{{ route('admin.bailleurs.create') }}"
                   style="{{ request()->routeIs('admin.bailleurs.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.bailleurs.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('admin.bailleurs.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Ajouter un bailleur</span>
                </a>
            </div>
        </div>

        <!-- 4c. Programmes & Projets (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('programmesSubmenu', 'programmesMenuChevron')"
                    title="Programmes & Projets"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.programmes.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-diagram-project w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.programmes.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Programmes</span>
                </div>
                <i id="programmesMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.programmes.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Programmes -->
            <div id="programmesSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.programmes.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.programmes.index') }}"
                   style="{{ request()->routeIs('admin.programmes.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.programmes.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.programmes.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des programmes</span>
                </a>

                <a href="{{ route('admin.programmes.create') }}"
                   style="{{ request()->routeIs('admin.programmes.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.programmes.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('admin.programmes.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Ajouter un programme</span>
                </a>
            </div>
        </div>

        <!-- 4d. Subventions (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('subventionsSubmenu', 'subventionsMenuChevron')"
                    title="Subventions & Financements"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.subventions.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-sack-dollar w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.subventions.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Subventions</span>
                </div>
                <i id="subventionsMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.subventions.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Subventions -->
            <div id="subventionsSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.subventions.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.subventions.index') }}"
                   style="{{ request()->routeIs('admin.subventions.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.subventions.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.subventions.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des subventions</span>
                </a>

                <a href="{{ route('admin.subventions.create') }}"
                   style="{{ request()->routeIs('admin.subventions.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.subventions.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('admin.subventions.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Ajouter une subvention</span>
                </a>
            </div>
        </div>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Gestion des Flux</div>

        <!-- 5. Mouvements, Transferts & Réformes -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('mouvSubmenu', 'mouvMenuChevron')"
                    title="Mouvements & Transferts"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.mouvements.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-arrow-right-arrow-left w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.mouvements.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Mouvements & Transferts</span>
                </div>
                <i id="mouvMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.mouvements.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Mouvements -->
            <div id="mouvSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.mouvements.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.mouvements.index') }}"
                   style="{{ request()->routeIs('admin.mouvements.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.mouvements.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.mouvements.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Journal des mouvements</span>
                </a>

                <a href="{{ route('admin.mouvements.create') }}"
                   style="{{ request()->routeIs('admin.mouvements.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.mouvements.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('admin.mouvements.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Nouveau mouvement</span>
                </a>
            </div>
        </div>

        <!-- 6. Inventaires Physiques -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('invSubmenu', 'invMenuChevron')"
                    title="Inventaires"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.inventaires.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-clipboard-check w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.inventaires.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Inventaires Physiques</span>
                </div>
                <i id="invMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.inventaires.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Inventaires -->
            <div id="invSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.inventaires.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.inventaires.index') }}"
                   style="{{ request()->routeIs('admin.inventaires.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.inventaires.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.inventaires.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Campagnes d'inventaire</span>
                </a>

                <a href="{{ route('admin.inventaires.create') }}"
                   style="{{ request()->routeIs('admin.inventaires.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.inventaires.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-plus text-[11px] {{ request()->routeIs('admin.inventaires.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Nouvelle campagne</span>
                </a>
            </div>
        </div>

        <div class="sidebar-text-element pt-3 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Sécurité & Contrôle</div>

        <!-- 7. Utilisateurs (Dropdown Accordéon) -->
        <div class="space-y-1">
            <button type="button"
                    onclick="adminToggleMenu('usersSubmenu', 'usersMenuChevron')"
                    title="Utilisateurs"
                    class="w-full group flex items-center justify-between px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-sky-50 text-slate-900' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                <div class="flex items-center">
                    <i class="fa-solid fa-users-gear w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.users.*') ? 'text-[#155E3B]' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
                    <span class="sidebar-text-element ml-3 whitespace-nowrap">Utilisateurs</span>
                </div>
                <i id="usersMenuChevron" class="sidebar-text-element fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-600 transition-transform duration-200 {{ request()->routeIs('admin.users.*') ? 'rotate-180' : '' }}"></i>
            </button>

            <!-- Sous-onglets Utilisateurs : Liste et Ajouter -->
            <div id="usersSubmenu" class="sidebar-text-element {{ request()->routeIs('admin.users.*') ? 'block' : 'hidden' }} pl-4 pr-1 py-1 space-y-1 border-l-2 border-slate-100 ml-5 my-0.5">
                <a href="{{ route('admin.users.index') }}"
                   style="{{ request()->routeIs('admin.users.index') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.users.index') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-list-ul text-[11px] {{ request()->routeIs('admin.users.index') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Liste des utilisateurs</span>
                </a>

                <a href="{{ route('admin.users.create') }}"
                   style="{{ request()->routeIs('admin.users.create') ? 'background-color: #155E3B;' : '' }}"
                   class="flex items-center space-x-2.5 px-3 py-2 rounded-lg text-xs font-semibold transition-all duration-150 {{ request()->routeIs('admin.users.create') ? 'text-white shadow-xs' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
                    <i class="fa-solid fa-user-plus text-[11px] {{ request()->routeIs('admin.users.create') ? 'text-white' : 'text-slate-400' }}"></i>
                    <span>Ajouter un utilisateur</span>
                </a>
            </div>
        </div>

        <!-- 8. Journal d'Audit & Sécurité -->
        <a href="{{ route('admin.audit.index') }}"
           title="Journal d'Audit"
           style="{{ request()->routeIs('admin.audit.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.audit.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-shield-halved w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.audit.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Journal d'Audit</span>
        </a>

        <!-- 9. Cartographie SIG -->
        <a href="{{ route('admin.cartographie.index') }}"
           title="Cartographie SIG"
           style="{{ request()->routeIs('admin.cartographie.*') ? 'background-color: #155E3B;' : '' }}"
           class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 {{ request()->routeIs('admin.cartographie.*') ? 'text-white shadow-md shadow-[#155E3B]/25' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-50' }}">
            <i class="fa-solid fa-map-location-dot w-5 text-center flex-shrink-0 {{ request()->routeIs('admin.cartographie.*') ? 'text-white' : 'text-slate-400 group-hover:text-slate-700' }}"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap">Cartographie SIG</span>
        </a>

        <div class="sidebar-text-element pt-4 px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Rapports & Vues</div>

        <!-- Accès Registre Officiel -->
        <a href="{{ route('admin.reporting.registre') }}"
           title="Registre Officiel"
           class="group flex items-center px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200">
            <i class="fa-solid fa-file-invoice w-5 text-center flex-shrink-0 text-slate-400 group-hover:text-slate-700"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap flex-1">Registre Officiel</span>
            <i class="sidebar-text-element fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
        </a>

        <!-- Accès Gestionnaire Direct -->
        <a href="{{ route('gestionnaire.dashboard') }}"
           title="Espace Gestionnaire"
           class="group flex items-center px-3 py-2 rounded-xl text-xs font-semibold text-slate-600 hover:text-slate-900 hover:bg-slate-50 transition-all duration-200">
            <i class="fa-solid fa-cubes w-5 text-center flex-shrink-0 text-slate-400 group-hover:text-slate-700"></i>
            <span class="sidebar-text-element ml-3 whitespace-nowrap flex-1">Espace Métier</span>
            <i class="sidebar-text-element fa-solid fa-arrow-up-right-from-square text-[10px] text-slate-400"></i>
        </a>
    </nav>

    <!-- Bottom Sidebar Épuré avec Fond Bleu -->
    <div class="p-3 border-t border-slate-100 flex-shrink-0">
        <div style="background-color: #155E3B;" class="flex items-center space-x-2.5 p-2 rounded-xl text-white shadow-xs">
            @if(auth()->user()?->photo_url)
                <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->nom_complet }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 border border-white/40 shadow-xs">
            @else
                <div class="w-8 h-8 rounded-lg bg-white/20 border border-white/30 text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                    {{ substr(auth()->user()->name ?? 'A', 0, 1) }}{{ substr(auth()->user()->prenoms ?? 'D', 0, 1) }}
                </div>
            @endif
            <div class="sidebar-text-element min-w-0 flex-1">
                <div class="text-xs font-bold text-white truncate">{{ auth()->user()->nom_complet ?? 'Administrateur' }}</div>
                <div class="text-[10px] text-white/80 font-medium truncate">{{ auth()->user()->email ?? 'admin@gmail.com' }}</div>
            </div>
        </div>
    </div>
</aside>

<style>
    /* Scrollbar ultra-fine et moderne pour la Sidebar */
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
        background: rgba(203, 213, 225, 0.7); /* slate-300 transparent */
        border-radius: 9999px;
        transition: background-color 0.2s ease;
    }

    .custom-sidebar-scrollbar::-webkit-scrollbar-thumb:hover,
    #mainSidebar nav::-webkit-scrollbar-thumb:hover {
        background: #155E3B; /* Theme Forest Green */
    }

    /* Support Firefox */
    .custom-sidebar-scrollbar,
    #mainSidebar nav {
        scrollbar-width: thin;
        scrollbar-color: rgba(203, 213, 225, 0.7) transparent;
    }
</style>

<script>
    // Déclaration globale garantie pour le déroulement des menus
    window.adminToggleMenu = function(submenuId, chevronId) {
        var submenu = document.getElementById(submenuId);
        var chevron = document.getElementById(chevronId);
        if (!submenu) return;
        
        var isHidden = submenu.classList.contains('hidden');
        if (isHidden) {
            submenu.classList.remove('hidden');
            submenu.classList.add('block');
            if (chevron) {
                chevron.classList.add('rotate-180');
            }
        } else {
            submenu.classList.add('hidden');
            submenu.classList.remove('block');
            if (chevron) {
                chevron.classList.remove('rotate-180');
            }
        }
    };
</script>
