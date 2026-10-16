@extends('layouts.admin.app')

@section('title', 'Référentiel des Immobilisations')

@section('content')
<div class="space-y-5">

    <!-- En-tête de page, 2 Compteurs & Action rapide sur la même ligne -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div style="background-color: #155E3B;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Référentiel des Immobilisations</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Registre officiel, valorisation financière, localisation et traçabilité</p>
                </div>
            </div>

            <!-- 2 Compteurs sur la même ligne -->
            <div class="flex items-center space-x-2.5 sm:border-l sm:border-slate-200 sm:pl-4">
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                    <i class="fa-solid fa-cubes text-slate-400 text-xs"></i>
                    <div class="text-xs font-semibold text-slate-700">
                        <span class="text-slate-400 font-normal">Total :</span> <strong class="text-slate-900 font-bold">{{ $stats['total'] ?? $immobilisations->total() }}</strong>
                    </div>
                </div>

                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <div class="text-xs font-semibold text-emerald-800">
                        <span class="text-emerald-600 font-normal">En service :</span> <strong>{{ $stats['en_service'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2.5 flex-shrink-0">
            <a href="{{ route('admin.immobilisations.export.csv') }}"
               class="inline-flex items-center space-x-2 px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                <i class="fa-solid fa-file-csv text-xs text-emerald-600"></i>
                <span>Exporter CSV</span>
            </a>

            <a href="{{ route('admin.immobilisations.create') }}"
               style="background-color: #155E3B;"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Ajouter une immobilisation</span>
            </a>
        </div>
    </div>

    <!-- Filtres Multi-critères -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('admin.immobilisations.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <!-- Recherche libre -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Code, désignation, série, marque..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
            </div>

            <!-- Catégorie -->
            <div>
                <select name="categorie_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <option value="">Toutes les catégories</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->id }}" {{ request('categorie_id') == $cat->id ? 'selected' : '' }}>
                            {{ $cat->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Structure -->
            <div>
                <select name="structure_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <option value="">Toutes les structures</option>
                    @foreach($structures as $st)
                        <option value="{{ $st->id }}" {{ request('structure_id') == $st->id ? 'selected' : '' }}>
                            {{ $st->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- État -->
            <div>
                <select name="etat" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <option value="">Tous les états</option>
                    <option value="bon_etat" {{ request('etat') == 'bon_etat' ? 'selected' : '' }}>Bon état</option>
                    <option value="panne" {{ request('etat') == 'panne' ? 'selected' : '' }}>En panne</option>
                    <option value="hors_service" {{ request('etat') == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                    <option value="en_maintenance" {{ request('etat') == 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                </select>
            </div>

            <!-- Boutons Filtrer & Reset -->
            <div class="flex items-center space-x-2">
                <button type="submit" style="background-color: #155E3B;" class="flex-1 py-2.5 px-3 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all text-center">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'categorie_id', 'structure_id', 'etat']))
                    <a href="{{ route('admin.immobilisations.index') }}" title="Réinitialiser" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau Moderne des Immobilisations -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-left">Immobilisation</th>
                        <th class="py-3.5 px-4 text-center">Code & Tag</th>
                        <th class="py-3.5 px-4 text-center">Catégorie</th>
                        <th class="py-3.5 px-4 text-center">Structure & Emplacement</th>
                        <th class="py-3.5 px-4 text-center">Valeur d'Acquisition</th>
                        <th class="py-3.5 px-4 text-center">État</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($immobilisations as $item)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Désignation & Marque (Aligné à gauche) -->
                            <td class="py-3.5 px-4 text-left">
                                <div class="flex items-center space-x-3">
                                    <div style="background-color: #155E3B;" class="w-9 h-9 rounded-xl text-white flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                                        <i class="fa-solid {{ $item->categorie?->icone ?? 'fa-cube' }}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 truncate">{{ $item->designation }}</div>
                                        <div class="text-[11px] text-slate-400 truncate">
                                            {{ $item->marque ?? 'Marque non spécifiée' }} {{ $item->modele ? '• '.$item->modele : '' }}
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Code & Tag -->
                            <td class="py-3.5 px-4 text-center font-mono">
                                <span class="font-bold text-slate-900 block text-xs">{{ $item->code_immobilisation }}</span>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $item->reference_etiquette ?? 'SANS-TAG' }}</span>
                            </td>

                            <!-- Catégorie -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 text-[#155E3B] border border-sky-100">
                                    {{ $item->categorie?->nom ?? 'Non classé' }}
                                </span>
                            </td>

                            <!-- Structure & Emplacement -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex flex-col items-center justify-center">
                                    <span class="font-semibold text-slate-800">{{ $item->structureBeneficiaire?->nom ?? 'Siège UCP-FM' }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $item->service?->nom ?? $item->emplacement_detaille ?? 'Local non défini' }}</span>
                                </div>
                            </td>

                            <!-- Valeur -->
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-900">
                                {{ number_format($item->valeur_acquisition, 0, ',', ' ') }} {{ $item->devise }}
                            </td>

                            <!-- État -->
                            <td class="py-3.5 px-4 text-center">
                                @if($item->etat === 'bon_etat')
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        Bon état
                                    </span>
                                @elseif($item->etat === 'panne')
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-amber-50 text-amber-700 border border-amber-200">
                                        En panne
                                    </span>
                                @elseif($item->etat === 'hors_service')
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-rose-50 text-rose-700 border border-rose-200">
                                        Hors service
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-600 border border-slate-200">
                                        {{ ucfirst(str_replace('_', ' ', $item->etat)) }}
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center justify-center space-x-1">
                                    <!-- Fiche -->
                                    <a href="{{ route('admin.immobilisations.show', $item->id) }}"
                                       title="Consulter la fiche"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#155E3B] text-slate-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    <!-- Modifier -->
                                    <a href="{{ route('admin.immobilisations.edit', $item->id) }}"
                                       title="Modifier"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#155E3B] text-slate-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <!-- QR Code -->
                                    <a href="{{ route('admin.immobilisations.etiquette', $item->id) }}"
                                       target="_blank"
                                       title="Étiquette QR Code"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-slate-900 text-slate-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-qrcode text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                                    <i class="fa-solid fa-boxes-stacked"></i>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">Aucune immobilisation trouvée</div>
                                <p class="text-xs text-slate-400 mt-1">Ajustez vos filtres ou enregistrez un nouvel actif.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($immobilisations->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Affichage de <strong>{{ $immobilisations->firstItem() }}</strong> à <strong>{{ $immobilisations->lastItem() }}</strong> sur <strong>{{ $immobilisations->total() }}</strong> actifs
                </div>
                <div>
                    {{ $immobilisations->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
