@extends('layouts.admin.app')

@section('title', "Fiche {$actif->code_immobilisation} - {$actif->designation}")

@section('content')
<div class="space-y-6">

    <!-- En-tête de la Fiche Actif -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-6 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex items-start space-x-4">
            @if($actif->photo_principale)
                <img src="{{ $actif->photo_principale }}" alt="{{ $actif->designation }}" class="w-16 h-16 rounded-2xl object-cover border border-slate-200 shadow-xs flex-shrink-0">
            @else
                <div style="background-color: #0048FA;" class="w-16 h-16 rounded-2xl text-white flex items-center justify-center text-2xl font-bold shadow-md shadow-[#0048FA]/25 flex-shrink-0">
                    <i class="fa-solid {{ $actif->categorie?->icone ?? 'fa-cube' }}"></i>
                </div>
            @endif

            <div class="space-y-1">
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold font-mono bg-sky-50 text-[#0048FA] border border-sky-200">
                        {{ $actif->code_immobilisation }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold {{ $actif->etat === 'bon_etat' ? 'bg-emerald-50 text-emerald-700 border border-emerald-200' : ($actif->etat === 'panne' ? 'bg-amber-50 text-amber-700 border border-amber-200' : 'bg-rose-50 text-rose-700 border border-rose-200') }}">
                        {{ $actif->etat_libelle }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-700">
                        {{ $actif->programme?->code ?? 'Sans Programme' }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                        Criticité : <strong class="uppercase text-slate-800">{{ $actif->niveau_criticite }}</strong>
                    </span>
                </div>

                <h1 class="text-xl font-bold text-slate-900 tracking-tight">{{ $actif->designation }}</h1>

                <div class="text-xs text-slate-500 flex flex-wrap items-center gap-x-4 gap-y-1">
                    <span><strong class="text-slate-700">Catégorie :</strong> {{ $actif->categorie?->nom ?? 'N/A' }} {{ $actif->sousCategorie ? '('.$actif->sousCategorie->nom.')' : '' }}</span>
                    <span><strong class="text-slate-700">Marque :</strong> {{ $actif->marque ?? 'N/A' }} {{ $actif->modele ? '- '.$actif->modele : '' }}</span>
                    <span><strong class="text-slate-700">N° Série :</strong> <span class="font-mono text-slate-800">{{ $actif->numero_serie ?? 'N/A' }}</span></span>
                    <span><strong class="text-slate-700">Tag QR :</strong> <span class="font-mono font-bold text-[#0048FA]">{{ $actif->reference_etiquette ?? 'N/A' }}</span></span>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="flex items-center flex-wrap gap-2 flex-shrink-0">
            <a href="{{ route('admin.immobilisations.etiquette', $actif->id) }}" target="_blank" class="px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all flex items-center space-x-1.5 shadow-2xs">
                <i class="fa-solid fa-qrcode text-slate-600"></i>
                <span>Étiquette QR</span>
            </a>

            <a href="{{ route('admin.mouvements.create', ['actif_id' => $actif->id]) }}" class="px-3.5 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white text-xs font-bold transition-all flex items-center space-x-1.5 shadow-xs">
                <i class="fa-solid fa-arrow-right-arrow-left"></i>
                <span>Transférer</span>
            </a>

            <a href="{{ route('admin.immobilisations.edit', $actif->id) }}" style="background-color: #0048FA;" class="px-4 py-2.5 rounded-xl text-white text-xs font-bold transition-all flex items-center space-x-1.5 shadow-md shadow-[#0048FA]/25 hover:opacity-95">
                <i class="fa-solid fa-pen-to-square"></i>
                <span>Modifier</span>
            </a>
        </div>
    </div>

    <!-- Grille des 7 Volets Normés du Cahier des Charges -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- 1. IDENTIFICATION -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-barcode"></i>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900">1. Identification</h2>
                </div>
                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-600">Volet 1</span>
            </div>

            <div class="space-y-2.5 text-xs">
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Identifiant Unique</span>
                    <span class="font-mono font-bold text-slate-800">{{ $actif->code_immobilisation }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Code Bailleur</span>
                    <span class="font-mono font-medium text-slate-700">{{ $actif->code_bailleur ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Réf. Étiquette / QR</span>
                    <span class="font-mono font-bold text-[#0048FA]">{{ $actif->reference_etiquette ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Catégorie</span>
                    <span class="font-semibold text-slate-800">{{ $actif->categorie?->nom ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Sous-Catégorie</span>
                    <span class="font-medium text-slate-700">{{ $actif->sousCategorie?->nom ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Marque / Modèle</span>
                    <span class="font-medium text-slate-800">{{ $actif->marque ?? 'N/A' }} {{ $actif->modele ? '('.$actif->modele.')' : '' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">N° de Série</span>
                    <span class="font-mono font-bold text-slate-800">{{ $actif->numero_serie ?? '—' }}</span>
                </div>
            </div>
        </div>

        <!-- 2. ACQUISITION & FINANCEMENT -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-file-invoice-dollar"></i>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900">2. Acquisition & Valeur</h2>
                </div>
                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-600">Volet 2</span>
            </div>

            <div class="space-y-2.5 text-xs">
                <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                    <span class="text-slate-400 block text-[10px] uppercase font-bold tracking-wider">Valeur d'Acquisition</span>
                    <div class="text-lg font-mono font-bold text-slate-900 mt-0.5">
                        {{ number_format($actif->valeur_acquisition, 0, ',', ' ') }} {{ $actif->devise }}
                    </div>
                </div>

                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Bailleur de Fonds</span>
                    <span class="font-bold text-slate-800">{{ $actif->bailleur?->nom ?? 'Non assigné' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Subvention</span>
                    <span class="font-medium text-slate-700">{{ $actif->subvention?->code_subvention ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Fournisseur</span>
                    <span class="font-medium text-slate-800">{{ $actif->fournisseur ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Réf. Marché / BC</span>
                    <span class="font-mono text-slate-700">{{ $actif->reference_marche ?? $actif->bon_commande ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1">
                    <span class="text-slate-400">Date Acquisition / Réception</span>
                    <span class="font-semibold text-slate-800">
                        {{ $actif->date_acquisition ? \Carbon\Carbon::parse($actif->date_acquisition)->format('d/m/Y') : '—' }}
                    </span>
                </div>
            </div>
        </div>

        <!-- 3. AFFECTATION & GÉOGRAPHIE -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-map-location-dot"></i>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900">3. Affectation & Géographie</h2>
                </div>
                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-600">Volets 3 & 4</span>
            </div>

            <div class="space-y-2.5 text-xs">
                <div>
                    <span class="text-slate-400 block text-[11px]">Établissement / Structure</span>
                    <div class="font-bold text-slate-900 mt-0.5">{{ $actif->structureBeneficiaire?->nom ?? 'Siège' }}</div>
                    <div class="text-[11px] text-slate-500">
                        {{ $actif->structureBeneficiaire?->region?->nom ?? 'Région' }} &bull; {{ $actif->structureBeneficiaire?->districtSanitaire?->nom ?? 'District' }} &bull; {{ $actif->structureBeneficiaire?->ville ?? 'CI' }}
                    </div>
                </div>

                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Service</span>
                    <span class="font-semibold text-slate-800">{{ $actif->service?->nom ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Emplacement</span>
                    <span class="font-medium text-slate-700">{{ $actif->emplacement_detaille ?? '—' }}</span>
                </div>
                <div class="flex justify-between py-1 border-b border-slate-50">
                    <span class="text-slate-400">Détenteur</span>
                    <span class="font-semibold text-slate-800">{{ $actif->detenteur?->nom_complet ?? 'Non assigné' }}</span>
                </div>
                <div class="flex items-center justify-between py-1">
                    <span class="text-slate-400">Coordonnées GPS</span>
                    @if($actif->latitude && $actif->longitude)
                        <a href="https://www.google.com/maps?q={{ $actif->latitude }},{{ $actif->longitude }}" target="_blank" class="font-mono text-xs text-[#0048FA] hover:underline font-bold flex items-center">
                            <span>{{ number_format($actif->latitude, 6) }}, {{ number_format($actif->longitude, 6) }}</span>
                            <i class="fa-solid fa-location-arrow text-[10px] ml-1.5"></i>
                        </a>
                    @else
                        <span class="text-slate-400 italic">Non géolocalisé</span>
                    @endif
                </div>
            </div>
        </div>

    </div>

    <!-- Deuxième Ligne : État, Risque, Documents & Mouvements -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <!-- 4. ÉTAT & CRITICITÉ -->
        <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-triangle-exclamation"></i>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900">4. État & Risque</h2>
                </div>
                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-600">Volets 5 & 6</span>
            </div>

            <div class="space-y-3 text-xs">
                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-semibold">Statut Opérationnel :</span>
                    <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-800">
                        {{ ucfirst(str_replace('_', ' ', $actif->statut_operationnel ?? 'en_service')) }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <span class="text-slate-500 font-semibold">Score de Risque :</span>
                    <div class="flex items-center space-x-2">
                        <div class="w-24 bg-slate-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-amber-500 h-2 rounded-full" style="width: {{ $actif->score_risque ?? 50 }}%"></div>
                        </div>
                        <span class="font-mono font-bold text-slate-800">{{ $actif->score_risque ?? 50 }}/100</span>
                    </div>
                </div>

                @if($actif->justification_risque)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Justification du Risque</span>
                        <p class="text-slate-700 text-xs mt-1">{{ $actif->justification_risque }}</p>
                    </div>
                @endif

                @if($actif->observations)
                    <div class="p-3 rounded-xl bg-slate-50 border border-slate-100">
                        <span class="text-slate-400 block text-[10px] uppercase font-bold">Observations</span>
                        <p class="text-slate-700 text-xs mt-1">{{ $actif->observations }}</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- 5. PIÈCES JUSTIFICATIVES & DOCUMENTS -->
        <div class="lg:col-span-2 bg-white rounded-2xl border border-slate-200/90 shadow-xs p-6 space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div class="flex items-center space-x-2.5">
                    <div style="background-color: #0048FA;" class="w-7 h-7 rounded-lg text-white flex items-center justify-center text-xs shadow-xs">
                        <i class="fa-solid fa-paperclip"></i>
                    </div>
                    <h2 class="text-sm font-bold text-slate-900">5. Documents & Pièces Justificatives</h2>
                </div>
                <span class="text-[10px] font-semibold uppercase px-2 py-0.5 rounded bg-slate-100 text-slate-600">Volet 7</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 text-xs">
                <div class="p-3 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-sky-50 text-[#0048FA] flex items-center justify-center text-sm">
                            <i class="fa-solid fa-file-contract"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">PV de Réception / Mise en service</div>
                            <div class="text-[10px] text-slate-400">Document d'admission technique</div>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400">Archivé</span>
                </div>

                <div class="p-3 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-file-invoice"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">Facture & Bon de commande</div>
                            <div class="text-[10px] text-slate-400">Pièces comptables d'achat</div>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400">Archivé</span>
                </div>

                <div class="p-3 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-shield"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">Certificat de Garantie</div>
                            <div class="text-[10px] text-slate-400">{{ $actif->fournisseur_garantie ?? 'Garantie constructeur' }}</div>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400">
                        {{ $actif->date_fin_garantie ? \Carbon\Carbon::parse($actif->date_fin_garantie)->format('d/m/Y') : 'N/A' }}
                    </span>
                </div>

                <div class="p-3 rounded-xl border border-slate-200 flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center text-sm">
                            <i class="fa-solid fa-book-open"></i>
                        </div>
                        <div>
                            <div class="font-bold text-slate-800">Fiche Technique & Manuel</div>
                            <div class="text-[10px] text-slate-400">Guide constructeur et maintenance</div>
                        </div>
                    </div>
                    <span class="text-[10px] font-semibold text-slate-400">Disponible</span>
                </div>
            </div>
        </div>

    </div>

    <!-- Historique des Mouvements & Traçabilité -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="p-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-sm font-bold text-slate-900 flex items-center space-x-2">
                <i class="fa-solid fa-timeline text-[#0048FA]"></i>
                <span>Historique des Mouvements & Transferts</span>
            </h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse text-xs">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3 px-4 text-left">Référence</th>
                        <th class="py-3 px-4 text-center">Type</th>
                        <th class="py-3 px-4 text-center">Origine</th>
                        <th class="py-3 px-4 text-center">Destination</th>
                        <th class="py-3 px-4 text-center">Date</th>
                        <th class="py-3 px-4 text-center">Statut</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($actif->mouvements as $m)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <td class="py-3 px-4 text-left font-mono font-bold text-slate-900">{{ $m->reference_mouvement }}</td>
                            <td class="py-3 px-4 text-center"><span class="px-2 py-0.5 rounded bg-sky-50 text-[#0048FA] font-semibold">{{ $m->type_mouvement }}</span></td>
                            <td class="py-3 px-4 text-center">{{ $m->structureOrigine?->nom ?? 'Siège' }}</td>
                            <td class="py-3 px-4 text-center">{{ $m->structureDestination?->nom ?? '—' }}</td>
                            <td class="py-3 px-4 text-center font-mono">{{ $m->date_mouvement ? \Carbon\Carbon::parse($m->date_mouvement)->format('d/m/Y') : '—' }}</td>
                            <td class="py-3 px-4 text-center"><span class="px-2 py-0.5 rounded bg-slate-100 text-slate-700 font-bold text-[10px]">{{ $m->statut_workflow }}</span></td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-slate-400">Aucun mouvement enregistré pour cette immobilisation.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
