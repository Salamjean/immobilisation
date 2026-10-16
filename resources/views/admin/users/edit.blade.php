@extends('layouts.admin.app')

@section('title', 'Modifier un Utilisateur')

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
                    <span>Modifier l'utilisateur : {{ $user->name }}</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Mise à jour des informations, rôle et structure de rattachement</p>
            </div>
        </div>
    </div>

    <!-- Formulaire d'édition en 3 Colonnes -->
    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
        @csrf
        @method('PUT')

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 items-stretch">

            <!-- COLONNE 1 : Photo & Identité -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-user"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">1. Identité & Photo</h2>
                    </div>

                    <!-- Upload Photo de Profil avec Prévisualisation en Direct -->
                    <div class="p-4 rounded-2xl bg-slate-50 border border-slate-200/80 text-center flex flex-col items-center">
                        <div class="relative group cursor-pointer mb-2.5" onclick="document.getElementById('editPhotoInput').click()">
                            <!-- Cadre photo avec aperçu -->
                            <div id="editPhotoPreviewContainer" class="w-24 h-24 rounded-2xl bg-white border-2 border-dashed border-slate-300 flex items-center justify-center overflow-hidden shadow-xs group-hover:border-[#155E3B] transition-all">
                                @if($user->photo_url)
                                    <img id="editPhotoPreview" src="{{ $user->photo_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    <div id="editPhotoPlaceholder" class="text-center text-slate-400 group-hover:text-[#155E3B] transition-colors hidden">
                                        <i class="fa-solid fa-camera text-2xl"></i>
                                    </div>
                                @else
                                    <img id="editPhotoPreview" src="" alt="Aperçu Photo" class="w-full h-full object-cover hidden">
                                    <div id="editPhotoPlaceholder" class="text-center text-slate-400 group-hover:text-[#155E3B] transition-colors">
                                        <i class="fa-solid fa-camera text-2xl"></i>
                                        <span class="block text-[10px] font-semibold mt-1">Ajouter photo</span>
                                    </div>
                                @endif
                            </div>
                            <!-- Badge caméra -->
                            <div style="background-color: #155E3B;" class="absolute -bottom-1 -right-1 w-7 h-7 rounded-full text-white flex items-center justify-center text-xs shadow-sm group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-camera"></i>
                            </div>
                        </div>

                        <label class="block text-xs font-bold text-slate-800">Photo de profil</label>
                        <p class="text-[10px] text-slate-500 mt-0.5">JPG, PNG, WEBP (Max 3 Mo)</p>
                        <input type="file"
                               id="editPhotoInput"
                               name="photo"
                               accept="image/jpeg,image/png,image/webp"
                               class="hidden"
                               onchange="previewEditProfilePhoto(this)">
                        <div id="editPhotoFileName" class="text-[11px] font-semibold text-[#155E3B] hidden pt-1 truncate max-w-[200px]"></div>
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
                                   value="{{ old('name', $user->name) }}"
                                   required
                                   placeholder="Ex: KOUASSI"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
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
                                   value="{{ old('prenoms', $user->prenoms) }}"
                                   required
                                   placeholder="Ex: Jean-Eudes"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
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
                                   value="{{ old('telephone', $user->telephone) }}"
                                   placeholder="+225 07 00 00 00 00"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>
                </div>

                <!-- Statut Actif -->
                <div class="pt-3 border-t border-slate-100">
                    <label class="flex items-center space-x-2.5 cursor-pointer">
                        <input type="checkbox" name="actif" value="1" {{ old('actif', $user->actif) ? 'checked' : '' }} class="w-4 h-4 rounded border-slate-300 text-[#155E3B] focus:ring-[#155E3B]">
                        <span class="text-xs font-semibold text-slate-700">Compte actif</span>
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
                            Adresse Email <span class="text-rose-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-envelope"></i>
                            </div>
                            <input type="email"
                                   name="email"
                                   value="{{ old('email', $user->email) }}"
                                   required
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Mot de passe optionnel -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Nouveau mot de passe <span class="text-slate-400 font-normal">(Laisser vide si inchangé)</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                                <i class="fa-solid fa-key"></i>
                            </div>
                            <input type="password"
                                   name="password"
                                   placeholder="••••••••"
                                   class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                        </div>
                    </div>

                    <!-- Info Sécurité -->
                    <div class="p-3.5 rounded-xl bg-sky-50/70 border border-sky-100 text-sky-900 space-y-1">
                        <div class="text-xs font-bold flex items-center space-x-1.5 text-[#155E3B]">
                            <i class="fa-solid fa-circle-info"></i>
                            <span>Modification sécurisée</span>
                        </div>
                        <p class="text-[11px] text-slate-600 leading-relaxed">
                            Si le mot de passe reste vide, l'ancien mot de passe de l'utilisateur est conservé.
                        </p>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 italic pt-2">
                    <i class="fa-solid fa-shield-check text-emerald-500 mr-1"></i> Données chiffrées & journalisées
                </div>
            </div>

            <!-- COLONNE 3 : Rôle & Affectation -->
            <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 flex flex-col justify-between space-y-4">
                <div class="space-y-4">
                    <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                        <div style="background-color: #155E3B;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                            <i class="fa-solid fa-shield-halved"></i>
                        </div>
                        <h2 class="text-sm font-bold text-slate-900">3. Rôle & Rattachement</h2>
                    </div>

                    <!-- Rôle -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">
                            Rôle de l'utilisateur <span class="text-rose-500">*</span>
                        </label>
                        <select name="role"
                                required
                                class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            @foreach($roles as $codeRole => $libelleRole)
                                <option value="{{ $codeRole }}" {{ old('role', $user->role) == $codeRole ? 'selected' : '' }}>
                                    {{ $libelleRole }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Programme -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Programme Santé GC7 <span class="text-slate-400 font-normal">(Optionnel)</span></label>
                        <select name="programme_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <option value="">-- Aucun / Tous les programmes --</option>
                            @foreach($programmes as $prog)
                                <option value="{{ $prog->id }}" {{ old('programme_id', $user->programme_id) == $prog->id ? 'selected' : '' }}>
                                    {{ $prog->sigle }} - {{ $prog->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Structure Sanitaire -->
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1.5">Structure Bénéficiaire / Hôpital <span class="text-slate-400 font-normal">(Optionnel)</span></label>
                        <select name="structure_beneficiaire_id" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                            <option value="">-- Aucune / Siège Central --</option>
                            @foreach($structures as $struct)
                                <option value="{{ $struct->id }}" {{ old('structure_beneficiaire_id', $user->structure_beneficiaire_id) == $struct->id ? 'selected' : '' }}>
                                    {{ $struct->nom }} ({{ $struct->ville ?? 'CI' }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="text-[11px] text-slate-400 pt-2">
                    <i class="fa-solid fa-hospital-user text-[#155E3B] mr-1"></i> Périmètre d'habilitation
                </div>
            </div>

        </div>

        <!-- Boutons d'Action -->
        <div class="flex items-center justify-end space-x-3 bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('admin.users.index') }}" class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <button type="submit" style="background-color: #155E3B;" class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Enregistrer les modifications</span>
            </button>
        </div>
    </form>

</div>

<!-- Scripts pour Prévisualisation Photo -->
<script>
    function previewEditProfilePhoto(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            const reader = new FileReader();
            
            reader.onload = function(e) {
                const previewImg = document.getElementById('editPhotoPreview');
                const placeholder = document.getElementById('editPhotoPlaceholder');
                const fileNameDisplay = document.getElementById('editPhotoFileName');
                
                previewImg.src = e.target.result;
                previewImg.classList.remove('hidden');
                if (placeholder) {
                    placeholder.classList.add('hidden');
                }
                
                if (fileNameDisplay) {
                    fileNameDisplay.textContent = 'Nouvelle photo : ' + file.name;
                    fileNameDisplay.classList.remove('hidden');
                }
            };
            
            reader.readAsDataURL(file);
        }
    }
</script>
@endsection
