<!-- Navbar Administrateur - Minimaliste Couleur #155E3B avec Bordure Blanche -->
<header style="background-color: #155E3B;" class="h-16 text-white rounded-2xl shadow-md border-2 border-white mx-3 mt-3 mb-0 flex items-center flex-shrink-0 z-30">
    <div class="w-full px-5 flex items-center justify-between">
        <!-- Gauche: Bouton Toggle Sidebar -->
        <div class="flex items-center">
            <button type="button" id="navbarSidebarToggle" title="Réduire / Agrandir le menu" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white flex items-center justify-center transition-all">
                <i class="fa-solid fa-bars text-sm"></i>
            </button>
        </div>

        <!-- Droite: Dropdown Profil & Paramètres avec bouton Fond Blanc -->
        <div class="flex items-center">
            <div class="relative">
                <button type="button"
                        id="navbarUserDropdownBtn"
                        class="flex items-center space-x-2.5 p-1.5 pr-3 rounded-xl bg-white hover:bg-slate-50 text-slate-900 shadow-sm border border-white transition-all text-left group">
                    @if(auth()->user()?->photo_url)
                        <img src="{{ auth()->user()->photo_url }}" alt="{{ auth()->user()->nom_complet }}" class="w-8 h-8 rounded-lg object-cover flex-shrink-0 shadow-xs border border-slate-200">
                    @else
                        <div style="background-color: #155E3B;" class="w-8 h-8 rounded-lg text-white flex items-center justify-center font-bold text-xs flex-shrink-0 shadow-xs">
                            {{ substr(auth()->user()->name ?? 'A', 0, 1) }}{{ substr(auth()->user()->prenoms ?? 'D', 0, 1) }}
                        </div>
                    @endif
                    <div class="hidden md:block min-w-0">
                        <div class="text-xs font-bold text-slate-900 leading-tight truncate">{{ auth()->user()->nom_complet ?? 'Administrateur' }}</div>
                        <div class="text-[10px] text-slate-500 font-medium truncate">{{ auth()->user()->role_libelle ?? 'Admin Système' }}</div>
                    </div>
                    <i id="navbarDropdownChevron" class="fa-solid fa-chevron-down text-[10px] text-slate-400 group-hover:text-slate-700 transition-transform duration-200 ml-1"></i>
                </button>

                <!-- Menu Déroulant Navbar -->
                <div id="navbarUserDropdownMenu" class="hidden absolute right-0 top-full mt-2 w-56 bg-white rounded-2xl border border-slate-200 shadow-2xl p-2 z-50 transition-all text-slate-800">
                    <div class="px-3 py-2 border-b border-slate-100 mb-1">
                        <div class="text-xs font-bold text-slate-900 truncate">{{ auth()->user()->nom_complet ?? 'Administrateur' }}</div>
                        <div class="text-[11px] text-slate-400 font-medium truncate">{{ auth()->user()->email ?? 'admin@gmail.com' }}</div>
                    </div>

                    <!-- Liens Profil & Paramètres -->
                    <div class="space-y-0.5">
                        <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <i class="fa-solid fa-user-gear w-4 text-center text-slate-400"></i>
                            <span>Mon Profil</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <i class="fa-solid fa-sliders w-4 text-center text-slate-400"></i>
                            <span>Paramètres & Droits</span>
                        </a>

                        <a href="{{ route('admin.audit.index') }}" class="flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-medium text-slate-700 hover:bg-slate-50 hover:text-slate-900 transition-colors">
                            <i class="fa-solid fa-key w-4 text-center text-slate-400"></i>
                            <span>Sécurité & Audit</span>
                        </a>
                    </div>

                    <!-- Déconnexion -->
                    <div class="border-t border-slate-100 mt-1 pt-1">
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="w-full flex items-center space-x-2.5 px-3 py-2 rounded-xl text-xs font-medium text-rose-600 hover:bg-rose-50 transition-colors text-left">
                                <i class="fa-solid fa-arrow-right-from-bracket w-4 text-center"></i>
                                <span>Se déconnecter</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
