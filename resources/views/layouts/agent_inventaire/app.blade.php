<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100/70">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Agent Inventaire') | UCP-FM Recensement</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#ecfdf5',
                            100: '#d1fae5',
                            200: '#a7f3d0',
                            300: '#6ee7b7',
                            400: '#34d399',
                            500: '#10b981',
                            600: '#155E3B',
                            700: '#0f442a',
                            800: '#064e3b',
                            900: '#022c22',
                        }
                    }
                }
            }
        }
    </script>

    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- TomSelect CDN (Searchable Selects) -->
    <link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.bootstrap5.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; }

        /* Styles pour Sidebar Réduite (Desktop) */
        @media (min-width: 768px) {
            .sidebar-collapsed {
                width: 5rem !important; /* w-20 */
            }
            .sidebar-collapsed .sidebar-text-element {
                display: none !important;
            }
            .sidebar-collapsed nav a, .sidebar-collapsed nav button {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
        }

        /* Styles Responsive Sidebar pour Mobile (< 768px) */
        @media (max-width: 767.98px) {
            #mainSidebar {
                position: fixed !important;
                top: 0 !important;
                bottom: 0 !important;
                left: 0 !important;
                height: 100vh !important;
                height: 100dvh !important;
                width: 17rem !important;
                max-width: 85vw !important;
                z-index: 50 !important;
                margin: 0 !important;
                border-radius: 0 1.25rem 1.25rem 0 !important;
                transform: translateX(-105%) !important;
                transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
                box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35) !important;
            }

            #mainSidebar.sidebar-mobile-open {
                transform: translateX(0) !important;
            }

            #mainSidebar.sidebar-collapsed {
                width: 17rem !important;
            }
            #mainSidebar.sidebar-collapsed .sidebar-text-element {
                display: inline !important;
            }
            #mainSidebar.sidebar-collapsed nav a, #mainSidebar.sidebar-collapsed nav button {
                justify-content: flex-start !important;
                padding-left: 0.75rem !important;
                padding-right: 0.75rem !important;
            }
        }

        /* TomSelect Theme Customization */
        .ts-wrapper {
            width: 100% !important;
        }
        .ts-control {
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            background-color: #f8fafc !important;
            font-size: 0.75rem !important;
            font-weight: 500 !important;
            padding: 0.5rem 0.875rem !important;
            color: #1e293b !important;
            box-shadow: none !important;
            transition: all 0.2s ease !important;
            min-height: 38px !important;
            display: flex !important;
            align-items: center !important;
        }
        .ts-control.focus {
            background-color: #ffffff !important;
            border-color: #155E3B !important;
            box-shadow: 0 0 0 2px rgba(21, 94, 59, 0.2) !important;
        }
        .ts-dropdown {
            border-radius: 0.75rem !important;
            border: 1px solid #e2e8f0 !important;
            box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1) !important;
            font-size: 0.75rem !important;
            z-index: 9999 !important;
            padding: 0.375rem !important;
            background-color: #ffffff !important;
            margin-top: 4px !important;
        }
        .ts-dropdown .option {
            padding: 0.5rem 0.75rem !important;
            border-radius: 0.5rem !important;
            transition: all 0.15s ease !important;
            cursor: pointer !important;
        }
        .ts-dropdown .active {
            background-color: #155E3B !important;
            color: #ffffff !important;
        }
        .ts-dropdown .active.selected {
            background-color: #114a2f !important;
            color: #ffffff !important;
        }
        .ts-dropdown .selected {
            background-color: #ecfdf5 !important;
            color: #065f46 !important;
            font-weight: 600 !important;
        }

        /* Arrière-plan Topographique Moderne */
        .app-bg-pattern {
            background-color: #f3f6fc;
            background-image: url("{{ asset('images/topography-pattern.svg') }}");
            background-size: 750px 750px;
            background-repeat: repeat;
            background-attachment: fixed;
        }
    </style>

    @stack('styles')
</head>
<body class="h-full app-bg-pattern text-slate-800 antialiased overflow-hidden">

    <div class="h-full flex flex-col md:flex-row overflow-hidden relative">
        <!-- Backdrop mobile pour Sidebar Drawer -->
        <div id="sidebarMobileBackdrop" class="fixed inset-0 bg-slate-900/60 backdrop-blur-xs z-40 hidden md:hidden transition-opacity duration-300"></div>

        <!-- Sidebar Agent Inventaire Flottante -->
        @include('layouts.agent_inventaire.sidebar')

        <!-- Zone Contenu & Navbar Agent Flottante -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.agent_inventaire.navbar')

            <main class="flex-1 p-3 md:p-5 overflow-y-auto app-bg-pattern">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toggle Collapse / Expand & Handlers -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle de la Sidebar (Desktop: collapse / Mobile: drawer)
            const sidebarToggleBtn = document.getElementById('navbarSidebarToggle');
            const mainSidebar = document.getElementById('mainSidebar');
            const mobileBackdrop = document.getElementById('sidebarMobileBackdrop');

            function isMobileScreen() {
                return window.innerWidth < 768;
            }

            function openMobileSidebar() {
                if (mainSidebar) mainSidebar.classList.add('sidebar-mobile-open');
                if (mobileBackdrop) mobileBackdrop.classList.remove('hidden');
            }

            function closeMobileSidebar() {
                if (mainSidebar) mainSidebar.classList.remove('sidebar-mobile-open');
                if (mobileBackdrop) mobileBackdrop.classList.add('hidden');
            }

            window.openMobileSidebar = openMobileSidebar;
            window.closeMobileSidebar = closeMobileSidebar;

            if (sidebarToggleBtn && mainSidebar) {
                if (!isMobileScreen()) {
                    const isCollapsed = localStorage.getItem('sidebar_collapsed') === 'true';
                    if (isCollapsed) {
                        mainSidebar.classList.add('sidebar-collapsed');
                    }
                }

                sidebarToggleBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    if (isMobileScreen()) {
                        if (mainSidebar.classList.contains('sidebar-mobile-open')) {
                            closeMobileSidebar();
                        } else {
                            openMobileSidebar();
                        }
                    } else {
                        mainSidebar.classList.toggle('sidebar-collapsed');
                        localStorage.setItem('sidebar_collapsed', mainSidebar.classList.contains('sidebar-collapsed'));
                    }
                });
            }

            if (mobileBackdrop) {
                mobileBackdrop.addEventListener('click', closeMobileSidebar);
            }

            if (mainSidebar) {
                mainSidebar.querySelectorAll('nav a').forEach(function(link) {
                    link.addEventListener('click', function() {
                        if (isMobileScreen()) {
                            closeMobileSidebar();
                        }
                    });
                });
            }

            window.addEventListener('resize', function() {
                if (!isMobileScreen()) {
                    closeMobileSidebar();
                    if (localStorage.getItem('sidebar_collapsed') === 'true' && mainSidebar) {
                        mainSidebar.classList.add('sidebar-collapsed');
                    }
                }
            });

            // Dropdown Utilisateur Navbar
            const userDropdownBtn = document.getElementById('navbarUserDropdownBtn');
            const userDropdownMenu = document.getElementById('navbarUserDropdownMenu');
            const dropdownChevron = document.getElementById('navbarDropdownChevron');

            if (userDropdownBtn && userDropdownMenu) {
                userDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    userDropdownMenu.classList.toggle('hidden');
                    if (dropdownChevron) dropdownChevron.classList.toggle('rotate-180');
                });

                document.addEventListener('click', function(e) {
                    if (!userDropdownMenu.contains(e.target) && !userDropdownBtn.contains(e.target)) {
                        userDropdownMenu.classList.add('hidden');
                        if (dropdownChevron) dropdownChevron.classList.remove('rotate-180');
                    }
                });
            }

            // SweetAlert2 Toast Global Handler
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });

            @if(session('success'))
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif

            @if(session('error'))
                Swal.fire({ icon: 'error', title: 'Erreur', text: '{{ session('error') }}', confirmButtonColor: '#155E3B' });
            @endif

            @if($errors->any())
                Swal.fire({ icon: 'error', title: 'Erreur de validation', text: '{{ $errors->first() }}', confirmButtonColor: '#155E3B' });
            @endif
        });
    </script>

    @stack('scripts')
</body>
</html>
