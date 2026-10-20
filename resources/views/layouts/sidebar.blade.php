<!-- Sidebar Racine - Détecte le type d'utilisateur et charge son menu ou le menu par défaut -->
@php
    $userRole = auth()->user()?->role ?? 'gestionnaire';
@endphp

@if(view()->exists("layouts.{$userRole}.sidebar"))
    @include("layouts.{$userRole}.sidebar")
@elseif($userRole === 'utilisateur_programme' && view()->exists('layouts.programme.sidebar'))
    @include('layouts.programme.sidebar')
@elseif(view()->exists('layouts.gestionnaire.sidebar'))
    @include('layouts.gestionnaire.sidebar')
@else
    <!-- Sidebar Par Défaut - Style Identique Admin -->
    <aside id="mainSidebar" style="border-color: #0048FA;" class="w-64 bg-white text-slate-700 flex-shrink-0 flex flex-col rounded-2xl border-2 shadow-sm transition-all duration-300 ease-in-out overflow-hidden my-3 ml-3 h-[calc(100vh-1.5rem)] sticky top-3">
        <div class="h-16 px-4 border-b border-slate-100 flex items-center justify-between flex-shrink-0">
            <div class="flex items-center space-x-2.5 overflow-hidden">
                <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-9 w-auto max-w-[140px] object-contain flex-shrink-0">
            </div>
            <div class="flex items-center space-x-2">
                <span class="sidebar-text-element inline-flex items-center px-2 py-0.5 rounded-md text-[10px] font-bold text-white shadow-2xs" style="background-color: #0048FA;">
                    UCP-FM
                </span>
                <button type="button" onclick="window.closeMobileSidebar && window.closeMobileSidebar()" class="md:hidden w-7 h-7 rounded-lg bg-slate-100 hover:bg-slate-200 text-slate-500 flex items-center justify-center cursor-pointer transition-colors" title="Fermer le menu">
                    <i class="fa-solid fa-xmark text-xs"></i>
                </button>
            </div>
        </div>

        <nav class="flex-1 px-3 py-4 space-y-1.5 overflow-y-auto overflow-x-hidden custom-sidebar-scrollbar">
            <div class="sidebar-text-element px-3 pb-1.5 text-[10px] font-bold tracking-wider text-slate-400 uppercase whitespace-nowrap">Menu Principal</div>
            <a href="{{ route('dashboard') }}" class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50">
                <i class="fa-solid fa-chart-pie w-5 text-center flex-shrink-0 text-slate-400 group-hover:text-slate-700"></i>
                <span class="sidebar-text-element ml-3 whitespace-nowrap">Tableau de bord</span>
            </a>
            <a href="{{ route('gestionnaire.immobilisations.index') }}" class="group flex items-center px-3 py-2.5 rounded-xl text-sm font-semibold transition-all duration-200 text-slate-600 hover:text-slate-900 hover:bg-slate-50">
                <i class="fa-solid fa-boxes-stacked w-5 text-center flex-shrink-0 text-slate-400 group-hover:text-slate-700"></i>
                <span class="sidebar-text-element ml-3 whitespace-nowrap">Immobilisations</span>
            </a>
        </nav>

        <div class="p-3 border-t border-slate-100 flex-shrink-0">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full flex items-center justify-center space-x-2 px-3 py-2 rounded-xl bg-rose-50 hover:bg-rose-100 text-rose-600 text-xs font-bold transition-all">
                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                    <span class="sidebar-text-element">Se déconnecter</span>
                </button>
            </form>
        </div>
    </aside>
@endif

<script>
    if (typeof window.toggleSidebarSubmenu === 'undefined') {
        window.toggleSidebarSubmenu = function(submenuId, chevronId) {
            const submenu = document.getElementById(submenuId);
            const chevron = document.getElementById(chevronId);
            if (!submenu) return;

            if (submenu.classList.contains('hidden')) {
                submenu.classList.remove('hidden');
                submenu.classList.add('block');
                if (chevron) chevron.classList.add('rotate-180');
            } else {
                submenu.classList.add('hidden');
                submenu.classList.remove('block');
                if (chevron) chevron.classList.remove('rotate-180');
            }
        };

        window.logistiqueToggleMenu = window.toggleSidebarSubmenu;
        window.gestionnaireToggleMenu = window.toggleSidebarSubmenu;
        window.adminToggleMenu = window.toggleSidebarSubmenu;
        window.auditeurToggleMenu = window.toggleSidebarSubmenu;
        window.consultantToggleMenu = window.toggleSidebarSubmenu;
        window.controleurToggleMenu = window.toggleSidebarSubmenu;
        window.toggleSubmenu = window.toggleSidebarSubmenu;
    }
</script>
