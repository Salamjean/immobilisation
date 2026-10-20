@extends('layouts.app')

@section('title', "Modifier le Bailleur {$bailleur->nom}")

@section('content')
<div class="space-y-6">

    <!-- En-tête -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-center space-x-3">
            <a href="{{ route('gestionnaire.bailleurs.index') }}" title="Retour à la liste" class="w-9 h-9 rounded-xl border border-slate-200 text-slate-500 hover:text-slate-900 hover:bg-slate-50 flex items-center justify-center transition-all">
                <i class="fa-solid fa-arrow-left text-xs"></i>
            </a>
            <div>
                <h1 class="text-lg font-bold text-slate-900 tracking-tight flex items-center space-x-2">
                    <span>Modifier le Bailleur :</span>
                    <span class="font-mono text-[#0048FA]">{{ $bailleur->nom }}</span>
                </h1>
                <p class="text-xs text-slate-500 mt-0.5">Mise à jour des informations et contacts du partenaire financier</p>
            </div>
        </div>

        <div class="text-xs text-slate-400">
            Champs obligatoires <span class="text-rose-500 font-bold">*</span>
        </div>
    </div>

    @if($errors->any())
        <div class="p-4 rounded-2xl bg-rose-50 border border-rose-200 text-rose-800 text-xs">
            <div class="flex items-center space-x-2 font-bold mb-1">
                <i class="fa-solid fa-circle-exclamation text-rose-600"></i>
                <span>Veuillez corriger les erreurs suivantes :</span>
            </div>
            <ul class="list-disc list-inside space-y-0.5 pl-2">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('gestionnaire.bailleurs.update', $bailleur->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')

        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-5">
            <div class="flex items-center space-x-2.5 pb-3 border-b border-slate-100">
                <div style="background-color: #0048FA;" class="w-8 h-8 rounded-xl text-white flex items-center justify-center text-sm shadow-xs">
                    <i class="fa-solid fa-pen-to-square"></i>
                </div>
                <div>
                    <h2 class="text-sm font-bold text-slate-900">Informations du Bailleur</h2>
                    <p class="text-xs text-slate-400">Code unique et coordonnées du bailleur</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 items-start">
                <!-- Nom Complet (placé avant le code) -->
                <div class="md:col-span-2">
                    <label for="nom_bailleur" class="block text-xs font-semibold text-slate-700 mb-1.5">
                        Nom Complet du Bailleur <span class="text-rose-500">*</span>
                    </label>
                    <input type="text"
                           id="nom_bailleur"
                           name="nom"
                           value="{{ old('nom', $bailleur->nom) }}"
                           required
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <!-- Code Bailleur -->
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="code_bailleur" class="block text-xs font-semibold text-slate-700">
                            Code Unique Bailleur <span class="text-rose-500">*</span>
                        </label>
                        <span class="text-[10px] font-medium text-slate-400 flex items-center space-x-1">
                            <i class="fa-solid fa-lock text-[9px]"></i>
                            <span>Verrouillé</span>
                        </span>
                    </div>
                    <div class="relative">
                        <input type="text"
                               id="code_bailleur"
                               name="code"
                               value="{{ old('code', $bailleur->code) }}"
                               readonly
                               required
                               class="w-full px-3.5 py-2.5 bg-slate-100 border border-slate-200 rounded-xl text-xs font-mono font-bold uppercase text-slate-600 cursor-not-allowed select-all pr-8">
                        <span class="absolute right-2.5 top-1/2 -translate-y-1/2 text-slate-400 text-xs">
                            <i class="fa-solid fa-barcode"></i>
                        </span>
                    </div>
                    <span class="text-[10px] text-slate-400 mt-1 block">Identifiant unique du bailleur</span>
                </div>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-xs font-semibold text-slate-700 mb-1.5">Description</label>
                <textarea name="description" rows="3" class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">{{ old('description', $bailleur->description) }}</textarea>
            </div>

            <!-- Contacts -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 pt-3 border-t border-slate-100">
                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Nom du Contact / Point Focal</label>
                    <input type="text"
                           name="contact_nom"
                           value="{{ old('contact_nom', $bailleur->contact_nom) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>

                <div>
                    <label class="block text-xs font-semibold text-slate-700 mb-1.5">Email du Contact</label>
                    <input type="email"
                           name="contact_email"
                           value="{{ old('contact_email', $bailleur->contact_email) }}"
                           class="w-full px-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:ring-2 focus:ring-[#0048FA] transition-all">
                </div>
            </div>

            <!-- Statut Actif -->
            <div class="flex items-center space-x-3 pt-2">
                <input type="checkbox" name="actif" id="actif" value="1" {{ old('actif', $bailleur->actif) ? 'checked' : '' }} class="w-4 h-4 rounded text-[#0048FA] focus:ring-[#0048FA] border-slate-300">
                <label for="actif" class="text-xs font-semibold text-slate-700">Actif</label>
            </div>
        </div>

        <!-- Boutons d'Action -->
        <div class="flex items-center justify-between bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
            <a href="{{ route('gestionnaire.bailleurs.index') }}"
               class="px-5 py-2.5 rounded-xl border border-slate-200 text-slate-600 hover:bg-slate-100 text-xs font-bold transition-all">
                Annuler
            </a>

            <button type="submit"
                    style="background-color: #0048FA;"
                    class="inline-flex items-center space-x-2 px-6 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-floppy-disk"></i>
                <span>Enregistrer les Modifications</span>
            </button>
        </div>
    </form>

</div>
@endsection
