<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-100/70">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Administration') | UCP-FM Côte d'Ivoire</title>

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
                            50: '#eff4ff',
                            100: '#dbeafe',
                            200: '#bfdbfe',
                            300: '#93c5fd',
                            400: '#60a5fa',
                            500: '#3b82f6',
                            600: '#0048FA',
                            700: '#003ad1',
                            800: '#002eb3',
                            900: '#002080',
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
            .sidebar-collapsed nav a {
                justify-content: center !important;
                padding-left: 0 !important;
                padding-right: 0 !important;
            }
            .sidebar-collapsed .p-1\.5 {
                justify-content: center !important;
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
            #mainSidebar.sidebar-collapsed nav a {
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
            border-radius: 0.75rem !important; /* rounded-xl */
            border: 1px solid #e2e8f0 !important; /* border-slate-200 */
            background-color: #f8fafc !important; /* bg-slate-50 */
            font-size: 0.75rem !important; /* text-xs */
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
            border-color: #0048FA !important;
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
            background-color: #0048FA !important;
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

        /* Arrière-plan Topographique Moderne conforme à l'image */
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

        <!-- Sidebar Admin Light Flottante & Réductible -->
        @include('layouts.admin.sidebar')

        <!-- Zone Contenu & Navbar Admin Light Flottante -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            @include('layouts.admin.navbar')

            <main class="flex-1 p-3 md:p-5 overflow-y-auto app-bg-pattern">
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Toggle Collapse / Expand & Dropdown Handlers -->
    <script>
        function toggleUsersMenu() {
            toggleSubmenu('usersSubmenu', 'usersMenuChevron');
        }

        function toggleStructuresMenu() {
            toggleSubmenu('structuresSubmenu', 'structuresMenuChevron');
        }

        function toggleCategoriesMenu() {
            toggleSubmenu('catSubmenu', 'catMenuChevron');
        }

        function toggleImmobilisationsMenu() {
            toggleSubmenu('immoSubmenu', 'immoMenuChevron');
        }

        function toggleMouvementsMenu() {
            toggleSubmenu('mouvSubmenu', 'mouvMenuChevron');
        }

        function toggleInventairesMenu() {
            toggleSubmenu('invSubmenu', 'invMenuChevron');
        }

        function toggleSubmenu(submenuId, chevronId) {
            const submenu = document.getElementById(submenuId);
            const chevron = document.getElementById(chevronId);
            if (submenu) {
                const isHidden = submenu.classList.contains('hidden');
                if (isHidden) {
                    submenu.classList.remove('hidden');
                    if (chevron) chevron.classList.add('rotate-180');
                } else {
                    submenu.classList.add('hidden');
                    if (chevron) chevron.classList.remove('rotate-180');
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('mainSidebar');
            const toggleBtn = document.getElementById('sidebarToggleBtn');
            const navbarToggleBtn = document.getElementById('navbarSidebarToggle');
            const mobileBackdrop = document.getElementById('sidebarMobileBackdrop');

            function isMobileScreen() {
                return window.innerWidth < 768;
            }

            function openMobileSidebar() {
                if (sidebar) sidebar.classList.add('sidebar-mobile-open');
                if (mobileBackdrop) mobileBackdrop.classList.remove('hidden');
            }

            function closeMobileSidebar() {
                if (sidebar) sidebar.classList.remove('sidebar-mobile-open');
                if (mobileBackdrop) mobileBackdrop.classList.add('hidden');
            }

            window.openMobileSidebar = openMobileSidebar;
            window.closeMobileSidebar = closeMobileSidebar;

            function toggleSidebar() {
                if (!sidebar) return;
                if (isMobileScreen()) {
                    if (sidebar.classList.contains('sidebar-mobile-open')) {
                        closeMobileSidebar();
                    } else {
                        openMobileSidebar();
                    }
                } else {
                    const isCollapsed = sidebar.classList.toggle('sidebar-collapsed');
                    localStorage.setItem('admin_sidebar_collapsed', isCollapsed ? 'true' : 'false');
                }
            }

            // Restaurer l'état précédent sur grand écran
            if (!isMobileScreen() && localStorage.getItem('admin_sidebar_collapsed') === 'true' && sidebar) {
                sidebar.classList.add('sidebar-collapsed');
            }

            if (navbarToggleBtn) {
                navbarToggleBtn.addEventListener('click', toggleSidebar);
            }

            if (toggleBtn) {
                toggleBtn.addEventListener('click', toggleSidebar);
            }

            if (mobileBackdrop) {
                mobileBackdrop.addEventListener('click', closeMobileSidebar);
            }

            if (sidebar) {
                sidebar.querySelectorAll('nav a').forEach(function(link) {
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
                    if (localStorage.getItem('admin_sidebar_collapsed') === 'true' && sidebar) {
                        sidebar.classList.add('sidebar-collapsed');
                    }
                }
            });

            // Gestion Dropdown Profil & Paramètres dans la Navbar
            const navDropdownBtn = document.getElementById('navbarUserDropdownBtn');
            const navDropdownMenu = document.getElementById('navbarUserDropdownMenu');
            const navDropdownChevron = document.getElementById('navbarDropdownChevron');

            if (navDropdownBtn && navDropdownMenu) {
                navDropdownBtn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    const isOpen = !navDropdownMenu.classList.contains('hidden');
                    if (isOpen) {
                        navDropdownMenu.classList.add('hidden');
                        if (navDropdownChevron) navDropdownChevron.style.transform = 'rotate(0deg)';
                    } else {
                        navDropdownMenu.classList.remove('hidden');
                        if (navDropdownChevron) navDropdownChevron.style.transform = 'rotate(180deg)';
                    }
                });

                // Fermer si clic en dehors
                document.addEventListener('click', function(e) {
                    if (!navDropdownMenu.contains(e.target) && !navDropdownBtn.contains(e.target)) {
                        navDropdownMenu.classList.add('hidden');
                        if (navDropdownChevron) navDropdownChevron.style.transform = 'rotate(0deg)';
                    }
                });
            }

            // SweetAlert2 Toasts
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
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#0048FA',
                    customClass: { popup: 'swal-compact-popup' }
                });
            @endif

            @if($errors->any())
                Swal.fire({
                    icon: 'error',
                    title: 'Erreur de validation',
                    text: '{{ $errors->first() }}',
                    confirmButtonColor: '#0048FA'
                });
            @endif

            // Initialisation globale des Selects avec recherche instantanée
            window.initSearchableSelects = function() {
                if (typeof TomSelect === 'undefined') return;
                
                document.querySelectorAll('select:not(.no-search):not(.tomselected):not(.swal2-select)').forEach(function(select) {
                    // Ignorer les éléments SweetAlert2 et les balises template
                    if (select.closest('.swal2-container') || select.closest('.swal2-popup') || select.closest('template') || select.classList.contains('swal2-select')) {
                        return;
                    }

                    try {
                        new TomSelect(select, {
                            create: false,
                            sortField: false,
                            placeholder: select.getAttribute('placeholder') || 'Rechercher ou sélectionner...',
                            allowEmptyOption: true,
                            maxOptions: 1000,
                            plugins: ['dropdown_input'],
                            render: {
                                no_results: function(data, escape) {
                                    return '<div class="no-results p-2 text-xs text-slate-400">Aucun résultat trouvé pour "' + escape(data.input) + '"</div>';
                                }
                            }
                        });
                    } catch (e) {
                        console.warn('TomSelect init skipped:', e);
                    }
                });
            };

            window.initSearchableSelects();
        });
    </script>

    @stack('scripts')
</body>
</html>
