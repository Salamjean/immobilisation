@extends('layouts.admin.app')

@section('title', 'Inscrire un Utilisateur')

@section('content')
<div class="space-y-5">

    <!-- En-tête avec Bouton Retour -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.users.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Inscrire un nouvel utilisateur</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Création de compte, téléversement de photo et attribution de rôle</p>
            </div>
        </div>

        <div class="text-xs text-slate-400">
            Champs obligatoires <span class="text-rose-500 font-bold">*</span>
        </div>
    </div>

    <!-- Formulaire d'inscription en 3 Colonnes -->
    <form action="{{ route('admin.users.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">
            
            <!-- COLONNE 1 : Photo de profil & Identité -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">1. Identité & Photo</h2>
                    </div>

                    <!-- Téléversement Photo de Profil -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-center flex flex-col items-center">
                        <div class="relative group cursor-pointer mb-2.5" onclick="document.getElementById('photoInput').click()">
                            <!-- Cadre photo rond/arrondi avec aperçu -->
                            <div id="photoPreviewContainer" class="w-24 h-24 rounded-2xl bg-white border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shadow-xs group-hover:border-[#155E3B] transition-all">
                                <img id="photoPreview" src="" alt="Aperçu Photo" class="w-full h-full object-cover hidden">
                                <div id="photoPlaceholder" class="text-center text-slate-400 group-hover:text-[#155E3B] transition-colors">
                                    <i class="fa-solid fa-camera text-2xl"></i>
                                    <span class="block text-[10px] font-semibold mt-1">Ajouter photo</span>
                                </div>
                            </div>
                            <!-- Badge caméra -->
                            <div style="background-color: #155E3B;" class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full text-white flex items-center justify-center text-xs shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-plus"></i>
                            </div>
                        </div>

                        <label class="block text-xs font-bold text-slate-800">Photo de profil</label>
                        <p class="text-[10px] text-slate-500 mt-0.5">JPG, PNG, WEBP (Max 3 Mo)</p>
                        <input type="file"
                               id="photoInput"
                               name="photo"
                               accept="image/jpeg,image/png,image/webp"
                               class="hidden"
                               onchange="previewProfilePhoto(this)">
                        <div id="photoFileName" class="text-[11px] font-semibold text-[#155E3B] hidden pt-1 truncate max-w-[200px]"></div>
                    </div>

                    <!-- Nom de famille -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nom de famille <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-user-tag"></i>
                            </div>
                            <input type="text"
                                   name="name"
                                   value="{{ old('name') }}"
                                   required
                                   placeholder="Ex: KOUASSI"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Prénom(s) -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Prénom(s) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-signature"></i>
                            </div>
                            <input type="text"
                                   name="prenoms"
                                   value="{{ old('prenoms') }}"
                                   required
                                   placeholder="Ex: Jean-Eudes"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Téléphone -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Numéro de téléphone
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-phone"></i>
                            </div>
                            <input type="text"
                                   name="telephone"
                                   value="{{ old('telephone') }}"
                                   placeholder="+225 07 00 00 00 00"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>
                </div>

                <!-- Activation Immédiate -->
                <div class="pt-3 border-t border-slate-100">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="actif" value="1" {{ old('actif', '1') == '1' ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-[#155E3B] focus:ring-[#155E3B]">
                        <span class="text-xs font-semibold text-slate-700">Activer le compte immédiatement</span>
                    </label>
                </div>
            </div>

            <!-- COLONNE 2 : Sécurité & Connexion -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-lock"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">2. Sécurité & Connexion</h2>
                    </div>

                    <!-- Email -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Adresse Email (Identifiant) <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email') }}"
                                   required
                                   placeholder="utilisateur@gmail.com"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Mot de passe initial avec bouton oeil -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Mot de passe initial <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <input type="password"
                                   id="newPasswordInput"
                                   name="password"
                                   required
                                   placeholder="Minimum 6 caractères"
                                   class="w-full pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <button type="button" onclick="togglePasswordVisibility('newPasswordInput', 'newPasswordToggleIcon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#155E3B] text-xs transition-colors focus:outline-none" title="Afficher/Masquer le mot de passe">
                                <i id="newPasswordToggleIcon" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Confirmation du Mot de passe avec bouton oeil -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Confirmer le mot de passe <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-shield-check"></i>
                            </div>
                            <input type="password"
                                   id="confirmPasswordInput"
                                   name="password_confirmation"
                                   required
                                   placeholder="Répétez le mot de passe"
                                   class="w-full pl-9 pr-10 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <button type="button" onclick="togglePasswordVisibility('confirmPasswordInput', 'confirmPasswordToggleIcon')" class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-slate-400 hover:text-[#155E3B] text-xs transition-colors focus:outline-none" title="Afficher/Masquer le mot de passe">
                                <i id="confirmPasswordToggleIcon" class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Note d'aide Sécurité -->
                    <div class="p-3 rounded-xl bg-sky-50/70 border border-sky-100 text-sky-900 space-y-1">
                        <div class="text-xs font-bold flex items-center space-x-1.5 text-[#155E3B]">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>Recommandations</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            Les deux mots de passe doivent être parfaitement identiques (min. 6 caractères).
                        </p>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 italic pt-2">
                    <i class="fa-solid fa-shield-check text-emerald-500 mr-1"></i> Chiffrement Bcrypt sécurisé
                </div>
            </div>

            <!-- COLONNE 3 : Rôle & Rattachement -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">3. Rôle & Rattachement</h2>
                    </div>

                    <!-- Choix du Rôle -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Rôle & Habilitation <span class="text-rose-500">*</span>
                        </label>
                        <select name="role"
                                required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <option value="" disabled selected>-- Choisissez un rôle --</option>
                            @foreach($roles as $codeRole => $libelleRole)
                                <option value="{{ $codeRole }}" {{ old('role') == $codeRole ? 'selected' : '' }}>
                                    {{ $libelleRole }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Programme -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Programme Santé GC7 <span class="text-slate-400 font-normal">(Optionnel)</span>
                        </label>
                        <select name="programme_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <option value="">-- Aucun / Tous programmes --</option>
                            @foreach($programmes as $prog)
                                <option value="{{ $prog->id }}" {{ old('programme_id') == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->sigle }} - {{ $prog->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Structure Sanitaire -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Structure Bénéficiaire / Hôpital <span class="text-slate-400 font-normal">(Optionnel)</span>
                        </label>
                        <select name="structure_beneficiaire_id"
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <option value="">-- Aucune / Siège Central --</option>
                            @foreach($structures as $struct)
                                <option value="{{ $struct->id }}" {{ old('structure_beneficiaire_id') == $struct->id ? 'selected' : '' }}>
                                    {{ $struct->nom }} ({{ $struct->ville ?? 'CI' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2">
                    <i class="fa-solid fa-hospital-user text-[#155E3B] mr-1"></i> Définit le périmètre d'accès aux immobilisations
                </div>
            </div>

        </div>

        <!-- Barre d'Action & Soumission -->
        <div class="flex items-center justify-end space-x-3 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('admin.users.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <button type="submit"
                    style="background-color: #155E3B;"
                    class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-check"></i>
                <span>Enregistrer & Inscrire l'utilisateur</span>
            </button>
        </div>
    </form>

</div>

<!-- Scripts pour Prévisualisation Photo & Toggle Mot de Passe -->
<script>
    function previewProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewImg = document.getElementById('photoPreview');
                const placeholder = document.getElementById('photoPlaceholder');
                const fileNameDisplay = document.getElementById('photoFileName');
                
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                placeholder.classList.add('hidden');
                
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = 'Fichier sélectionné : ' + file.name;
                    fileNameDisplay.classList.remove('hidden');
                }
            };
            
            reader.readAsDataURL(file);
        }
    }

    function togglePasswordVisibility(inputId = 'newPasswordInput', iconId = 'newPasswordToggleIcon') {
        const passwordInput = document.getElementById(inputId);
        const toggleIcon = document.getElementById(iconId);
        
        if (!passwordInput || !toggleIcon) return;

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
</script>
@endsection
