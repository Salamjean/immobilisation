<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-900">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Connexion | GEMMA - UCP-FM Côte d'Ivoire</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    
    <!-- FontAwesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        html, body {
            height: 100%;
            overflow: hidden !important;
            padding-right: 0 !important;
            margin: 0;
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* Styles Compacts & Fins pour SweetAlert2 */
        .swal2-popup.swal-compact {
            width: 320px !important;
            max-width: 90vw !important;
            padding: 1.25rem 1rem !important;
            border-radius: 1.25rem !important;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2), 0 8px 10px -6px rgba(0, 0, 0, 0.2) !important;
        }
        .swal2-popup.swal-compact .swal2-icon {
            transform: scale(0.65) !important;
            margin: 0 auto 0.25rem auto !important;
        }
        .swal2-popup.swal-compact .swal2-title {
            font-size: 0.95rem !important;
            font-weight: 700 !important;
            color: #0f172a !important;
            padding: 0 !important;
            margin: 0.25rem 0 0 0 !important;
        }
        .swal2-popup.swal-compact .swal2-html-container {
            font-size: 0.8rem !important;
            color: #64748b !important;
            margin: 0.35rem 0 0.5rem 0 !important;
            line-height: 1.35 !important;
        }
        .swal2-popup.swal-compact .swal2-actions {
            margin: 0.5rem auto 0 auto !important;
        }
        .swal2-popup.swal-compact .swal2-confirm {
            padding: 0.45rem 1.25rem !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            border-radius: 0.65rem !important;
            background-color: #155E3B !important;
        }
    </style>
</head>
<body class="h-full relative flex items-center justify-center p-4 antialiased bg-slate-900 overflow-hidden">

    <!-- Image d'arrière-plan fixe -->
    <div class="fixed inset-0 z-0 pointer-events-none select-none">
        <img src="{{ asset('assets/images/login-bg.jpg') }}" alt="Arrière-plan UCP-FM" class="w-full h-full object-cover object-center">
        <div class="absolute inset-0 bg-slate-950/60"></div>
    </div>

    <!-- Conteneur Carte Fixe -->
    <div class="relative z-10 w-full max-w-sm m-auto">
        
        <!-- Carte de Connexion Épurée -->
        <div class="bg-white rounded-2xl p-7 sm:p-8 shadow-2xl border border-slate-200/90">
            
            <!-- En-tête simplifié avec Logo GEMMA -->
            <div class="mb-6 text-center">
                <div class="flex justify-center mb-3">
                    <img src="{{ asset('assets/images/logo.png') }}" alt="Logo GEMMA" class="h-12 w-auto object-contain">
                </div>
                <h1 class="text-lg font-bold text-slate-900">GESTION DES IMMOBILISATIONS</h1>
            </div>

            <!-- Formulaire -->
            <form action="{{ route('login.post') }}" method="POST" class="space-y-4">
                @csrf
                
                <!-- Email -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-envelope"></i>
                        </div>
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus placeholder="nom@gmail.com" class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    </div>
                </div>

                <!-- Mot de passe avec bouton œil (toggle visibilité) -->
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1">Mot de passe</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <input type="password" id="passwordInput" name="password" required placeholder="••••••••" class="w-full pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-slate-900 text-xs font-medium focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        <button type="button" onclick="togglePasswordVisibility()" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#155E3B] text-xs transition-colors focus:outline-none" title="Afficher/Masquer le mot de passe">
                            <i id="passwordToggleIcon" class="fa-solid fa-eye"></i>
                        </button>
                    </div>
                </div>

                <!-- Case Se souvenir de moi & Mot de passe oublié sur la même ligne -->
                <div class="flex items-center justify-between pt-0.5">
                    <label class="flex items-center space-x-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 rounded border-slate-300 text-[#155E3B] focus:ring-[#155E3B]">
                        <span class="text-xs text-slate-600 font-medium">Se souvenir de moi</span>
                    </label>
                    <a href="javascript:void(0)" onclick="showForgotModal()" class="text-xs text-slate-500 hover:text-[#155E3B] font-medium transition-colors">
                        Mot de passe oublié ?
                    </a>
                </div>

                <!-- Bouton Connexion avec couleur #155E3B -->
                <div class="pt-2">
                    <button type="submit" style="background-color: #155E3B;" class="w-full py-2.5 px-4 rounded-xl text-white font-bold text-xs tracking-wide shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                        Se connecter
                    </button>
                </div>
            </form>

        </div>

        <!-- Copyright discret -->
        <div class="text-center mt-5 text-slate-300 text-[11px]">
            &copy; {{ date('Y') }} GEMMA &bull; UCP-FM Côte d'Ivoire
        </div>

    </div>

    <!-- Scripts -->
    <script>
        function togglePasswordVisibility() {
            const passwordInput = document.getElementById('passwordInput');
            const toggleIcon = document.getElementById('passwordToggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Configuration Compacte SweetAlert2 sans décalage de page
        const CompactSwal = Swal.mixin({
            scrollbarPadding: false,
            heightAuto: false,
            customClass: {
                popup: 'swal-compact'
            }
        });

        function showForgotModal() {
            CompactSwal.fire({
                icon: 'info',
                title: 'Assistance',
                text: 'Veuillez contacter votre administrateur système pour réinitialiser votre mot de passe.',
                confirmButtonText: 'Compris',
                confirmButtonColor: '#155E3B'
            });
        }

        document.addEventListener('DOMContentLoaded', function() {
            @if(session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Succès',
                    text: '{{ session('success') }}',
                    timer: 2500,
                    showConfirmButton: false,
                    toast: true,
                    position: 'top-end',
                    scrollbarPadding: false
                });
            @endif

            @if(session('error'))
                CompactSwal.fire({
                    icon: 'error',
                    title: 'Erreur',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'D\'accord',
                    confirmButtonColor: '#155E3B'
                });
            @endif

            @if($errors->any())
                CompactSwal.fire({
                    icon: 'error',
                    title: 'Identifiants incorrects',
                    text: '{{ $errors->first() }}',
                    confirmButtonText: 'Réessayer',
                    confirmButtonColor: '#155E3B'
                });
            @endif
        });
    </script>

</body>
</html>
