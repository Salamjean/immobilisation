@extends('layouts.app')

@section('title', "Modifier {$actif->code_immobilisation}")
@section('header_title', "Modification de l'Immobilisation")

@section('content')
<div class="max-w-5xl mx-auto space-y-6">

    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm">
        <form action="{{ route('immobilisations.update', $actif->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Identification -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-tag text-brand-600"></i>
                    <span>1. Identification & Nomenclature</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Code Immobilisation (Immuable)</label>
                        <input type="text" value="{{ $actif->code_immobilisation }}" readonly class="w-full px-3 py-2 bg-slate-100 border border-slate-300 rounded-lg font-mono font-bold text-slate-600">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Référence Tag QR Code</label>
                        <input type="text" name="reference_etiquette" value="{{ old('reference_etiquette', $actif->reference_etiquette) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Numéro de Série <span class="text-red-500">*</span></label>
                        <input type="text" name="numero_serie" value="{{ old('numero_serie', $actif->numero_serie) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white">
                    </div>

                    <div class="sm:col-span-2 lg:col-span-3">
                        <label class="block font-bold text-slate-700 mb-1">Désignation <span class="text-red-500">*</span></label>
                        <input type="text" name="designation" value="{{ old('designation', $actif->designation) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Catégorie Technique</label>
                        <select name="categorie_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ old('categorie_id', $actif->categorie_id) == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->nom }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Marque</label>
                        <input type="text" name="marque" value="{{ old('marque', $actif->marque) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Modèle</label>
                        <input type="text" name="modele" value="{{ old('modele', $actif->modele) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                    </div>
                </div>
            </div>

            <!-- État & Criticité -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-shield-halved text-amber-500"></i>
                    <span>2. État Physique & Risque</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Niveau de Criticité</label>
                        <select name="niveau_criticite" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            <option value="critique" {{ old('niveau_criticite', $actif->niveau_criticite) == 'critique' ? 'selected' : '' }}>Critique</option>
                            <option value="eleve" {{ old('niveau_criticite', $actif->niveau_criticite) == 'eleve' ? 'selected' : '' }}>Élevé</option>
                            <option value="modere" {{ old('niveau_criticite', $actif->niveau_criticite) == 'modere' ? 'selected' : '' }}>Modéré</option>
                            <option value="faible" {{ old('niveau_criticite', $actif->niveau_criticite) == 'faible' ? 'selected' : '' }}>Faible</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">État Physique</label>
                        <select name="etat" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            <option value="bon_etat" {{ old('etat', $actif->etat) == 'bon_etat' ? 'selected' : '' }}>Bon état / Fonctionnel</option>
                            <option value="panne" {{ old('etat', $actif->etat) == 'panne' ? 'selected' : '' }}>En panne</option>
                            <option value="hors_service" {{ old('etat', $actif->etat) == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                            <option value="en_maintenance" {{ old('etat', $actif->etat) == 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                            <option value="transfere" {{ old('etat', $actif->etat) == 'transfere' ? 'selected' : '' }}>Transféré</option>
                            <option value="non_retrouve" {{ old('etat', $actif->etat) == 'non_retrouve' ? 'selected' : '' }}>Non retrouvé</option>
                            <option value="a_reformer" {{ old('etat', $actif->etat) == 'a_reformer' ? 'selected' : '' }}>À réformer</option>
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Statut Opérationnel</label>
                        <select name="statut_operationnel" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            <option value="en_service" {{ old('statut_operationnel', $actif->statut_operationnel) == 'en_service' ? 'selected' : '' }}>En service</option>
                            <option value="en_stock" {{ old('statut_operationnel', $actif->statut_operationnel) == 'en_stock' ? 'selected' : '' }}>En stock</option>
                            <option value="en_transit" {{ old('statut_operationnel', $actif->statut_operationnel) == 'en_transit' ? 'selected' : '' }}>En transit</option>
                            <option value="en_reparation" {{ old('statut_operationnel', $actif->statut_operationnel) == 'en_reparation' ? 'selected' : '' }}>En réparation</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Affectation & Emplacement -->
            <div>
                <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100 flex items-center space-x-2">
                    <i class="fa-solid fa-location-dot text-emerald-600"></i>
                    <span>3. Affectation & Site</span>
                </h3>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mt-4 text-xs">
                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Structure Bénéficiaire</label>
                        <select name="structure_beneficiaire_id" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            @foreach($structures as $st)
                                <option value="{{ $st->id }}" {{ old('structure_beneficiaire_id', $actif->structure_beneficiaire_id) == $st->id ? 'selected' : '' }}>
                                    {{ $st->nom }} ({{ $st->ville }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Emplacement Détaillé</label>
                        <input type="text" name="emplacement_detaille" value="{{ old('emplacement_detaille', $actif->emplacement_detaille) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Détenteur</label>
                        <select name="detenteur_id" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg focus:ring-2 focus:ring-brand-500 focus:bg-white">
                            <option value="">Aucun détenteur assigné</option>
                            @foreach($detenteurs as $d)
                                <option value="{{ $d->id }}" {{ old('detenteur_id', $actif->detenteur_id) == $d->id ? 'selected' : '' }}>
                                    {{ $d->nom_complet }} ({{ $d->fonction }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Latitude GPS</label>
                        <input type="number" step="0.00000001" name="latitude" value="{{ old('latitude', $actif->latitude) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Longitude GPS</label>
                        <input type="number" step="0.00000001" name="longitude" value="{{ old('longitude', $actif->longitude) }}" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-mono focus:ring-2 focus:ring-brand-500 focus:bg-white">
                    </div>

                    <div>
                        <label class="block font-bold text-slate-700 mb-1">Valeur Acquisition (FCFA)</label>
                        <input type="number" step="0.01" name="valeur_acquisition" value="{{ old('valeur_acquisition', $actif->valeur_acquisition) }}" required class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-lg font-mono font-bold focus:ring-2 focus:ring-brand-500 focus:bg-white">
                        <input type="hidden" name="devise" value="FCFA">
                    </div>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-200 flex items-center justify-end space-x-3">
                <a href="{{ route('immobilisations.show', $actif->id) }}" class="px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-semibold">
                    Annuler
                </a>
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md shadow-brand-500/20 transition-all">
                    <i class="fa-solid fa-floppy-disk mr-1.5"></i> Mettre à jour l'Immobilisation
                </button>
            </div>
        </form>
    </div>

</div>
@endsection
