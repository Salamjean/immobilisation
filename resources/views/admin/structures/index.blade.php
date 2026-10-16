@extends('layouts.admin.app')

@section('title', 'Gestion des Structures')

@section('content')
<div class="space-y-5">

    <!-- En-tête de page, 2 Compteurs & Action rapide sur la même ligne -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div style="background-color: #155E3B;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-hospital"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Répertoire des Structures</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Établissements sanitaires, hôpitaux et directions bénéficiaires</p>
                </div>
            </div>

            <!-- 2 Compteurs sur la même ligne -->
            <div class="flex items-center space-x-2.5 sm:border-l sm:border-slate-200 sm:pl-4">
                <!-- Compteur 1: Total -->
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                    <i class="fa-solid fa-hospital text-slate-400 text-xs"></i>
                    <div class="text-xs font-semibold text-slate-700">
                        <span class="text-slate-400 font-normal">Total :</span> <strong class="text-slate-900 font-bold">{{ $stats['total'] ?? $structures->total() }}</strong>
                    </div>
                </div>

                <!-- Compteur 2: Actives -->
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <div class="text-xs font-semibold text-emerald-800">
                        <span class="text-emerald-600 font-normal">Actives :</span> <strong>{{ $stats['actifs'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2.5 flex-shrink-0">
            <a href="{{ route('admin.structures.create') }}"
               style="background-color: #155E3B;"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Ajouter une structure</span>
            </a>
        </div>
    </div>

    <!-- Barre de Recherche & Filtres -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('admin.structures.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <!-- Recherche Mot-clé -->
            <div class="sm:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher nom, code, ville, responsable..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
            </div>

            <!-- Filtre Type -->
            <div class="sm:col-span-3">
                <select name="type_structure" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous les Types</option>
                    @foreach($typesStructure as $codeType => $libelleType)
                        <option value="{{ $codeType }}" {{ request('type_structure') == $codeType ? 'selected' : '' }}>
                            {{ $libelleType }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Région -->
            <div class="sm:col-span-3">
                <select name="region_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Toutes les Régions</option>
                    @foreach($regions as $reg)
                        <option value="{{ $reg->id }}" {{ request('region_id') == $reg->id ? 'selected' : '' }}>
                            {{ $reg->nom }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Boutons Filtrer & Reset -->
            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit" style="background-color: #155E3B;" class="flex-1 py-2.5 px-3 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all text-center">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'type_structure', 'region_id', 'actif']))
                    <a href="{{ route('admin.structures.index') }}" title="Réinitialiser les filtres" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau Moderne des Structures -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-left">Structure</th>
                        <th class="py-3.5 px-4 text-center">Type</th>
                        <th class="py-3.5 px-4 text-center">Région & District</th>
                        <th class="py-3.5 px-4 text-center">Localisation</th>
                        <th class="py-3.5 px-4 text-center">Immobilisations</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($structures as $struct)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Identité (Aligné à gauche) -->
                            <td class="py-3.5 px-4 text-left">
                                <div class="flex items-center space-x-3">
                                    <div style="background-color: #155E3B;" class="w-9 h-9 rounded-xl text-white flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                                        <i class="fa-solid fa-hospital text-xs"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 truncate">{{ $struct->nom }}</div>
                                        <div class="text-[11px] text-slate-400 font-mono truncate">Code : {{ $struct->code }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Type -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-[11px] font-bold bg-sky-50 text-[#155E3B] border border-sky-200">
                                    {{ $struct->type_structure }}
                                </span>
                            </td>

                            <!-- Région & District -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex flex-col items-center justify-center">
                                    <span class="font-semibold text-slate-800">{{ $struct->region->nom ?? '—' }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $struct->districtSanitaire->nom ?? 'District non défini' }}</span>
                                </div>
                            </td>

                            <!-- Ville & Commune -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center justify-center space-x-1 text-slate-700">
                                    <i class="fa-solid fa-location-dot text-rose-500 text-[10px]"></i>
                                    <span>{{ $struct->ville ?? 'Côte d\'Ivoire' }}</span>
                                </div>
                            </td>

                            <!-- Nbre Immobilisations -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                    <i class="fa-solid fa-cubes-stacked text-[10px] mr-1.5 text-slate-400"></i>
                                    {{ $struct->immobilisations_count ?? 0 }}
                                </span>
                            </td>

                            <!-- Statut -->
                            <td class="py-3.5 px-4 text-center">
                                @if($struct->actif)
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                        Active
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        Inactive
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center justify-center space-x-1">
                                    <!-- Modifier -->
                                    <a href="{{ route('admin.structures.edit', $struct->id) }}"
                                       title="Modifier la structure"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#155E3B] text-slate-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <!-- Activer / Désactiver -->
                                    <form action="{{ route('admin.structures.toggle', $struct->id) }}" method="POST" class="inline" onsubmit="return confirmStructureToggle(event, this, '{{ addslashes($struct->nom) }}', {{ $struct->actif ? 'true' : 'false' }});">
                                        @csrf
                                        <button type="submit"
                                                title="{{ $struct->actif ? 'Désactiver la structure' : 'Activer la structure' }}"
                                                class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $struct->actif ? 'bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white' : 'bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white' }} transition-colors">
                                            <i class="fa-solid {{ $struct->actif ? 'fa-ban' : 'fa-check' }} text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                                    <i class="fa-solid fa-hospital-user"></i>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">Aucune structure trouvée</div>
                                <p class="text-xs text-slate-400 mt-1">Modifiez vos critères de recherche ou ajoutez un nouvel établissement.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($structures->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Affichage de <strong>{{ $structures->firstItem() }}</strong> à <strong>{{ $structures->lastItem() }}</strong> sur <strong>{{ $structures->total() }}</strong> structures
                </div>
                <div>
                    {{ $structures->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- Script SweetAlert2 de confirmation -->
<script>
    function confirmStructureToggle(event, form, structureName, isCurrentlyActive) {
        event.preventDefault();
        const actionText = isCurrentlyActive ? 'désactiver' : 'activer';
        const iconType = isCurrentlyActive ? 'warning' : 'question';
        const confirmBtnColor = isCurrentlyActive ? '#ef4444' : '#155E3B';

        Swal.fire({
            title: `Confirmer l'opération`,
            text: `Êtes-vous sûr de vouloir ${actionText} la structure « ${structureName} » ?`,
            icon: iconType,
            showCancelButton: true,
            confirmButtonColor: confirmBtnColor,
            cancelButtonColor: '#64748b',
            confirmButtonText: `Oui, ${actionText}`,
            cancelButtonText: 'Annuler',
            customClass: { popup: 'swal-compact-popup' }
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
        return false;
    }
</script>
@endsection
