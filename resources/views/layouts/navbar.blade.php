<!-- Navbar Racine - Charge la navbar selon le rôle de l'utilisateur ou la navbar standard -->
@php
    $userRole = auth()->user()?->role ?? 'gestionnaire';
@endphp

@if(view()->exists("layouts.{$userRole}.navbar"))
    @include("layouts.{$userRole}.navbar")
@elseif($userRole === 'utilisateur_programme' && view()->exists('layouts.programme.navbar'))
    @include('layouts.programme.navbar')
@elseif(view()->exists('layouts.gestionnaire.navbar'))
    @include('layouts.gestionnaire.navbar')
@else
    <!-- Navbar Par Défaut - Style Identique Admin -->
    <header style="background-color: #155E3B;" class="h-16 text-white rounded-2xl shadow-md border-2 border-white mx-3 mt-3 mb-0 flex items-center flex-shrink-0 z-30">
        <div class="w-full px-5 flex items-center justify-between">
            <div class="flex items-center space-x-3">
                <button type="button" id="navbarSidebarToggle" title="Réduire / Agrandir le menu" class="w-9 h-9 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white flex items-center justify-center transition-all">
                    <i class="fa-solid fa-bars text-sm"></i>
                </button>
                <div class="text-xs font-semibold text-white/90">
                    @yield('header_title', 'Plateforme UCP-FM')
                </div>
            </div>

            <div class="flex items-center space-x-3">
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" title="Se déconnecter" class="inline-flex items-center space-x-1.5 px-3 py-1.5 rounded-xl bg-white/15 hover:bg-white/25 border border-white/25 text-white text-xs font-bold transition-all shadow-sm">
                        <i class="fa-solid fa-power-off"></i>
                        <span class="hidden sm:inline">Déconnexion</span>
                    </button>
                </form>
            </div>
        </div>
    </header>
@endif
