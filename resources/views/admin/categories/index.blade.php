@extends('layouts.admin.app')

@section('title', 'Catégories d\'Immobilisations')

@section('content')
<div class="space-y-5">

    <!-- En-tête de page, 2 Compteurs & Action rapide sur la même ligne -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div style="background-color: #0048FA;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-layer-group"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Familles & Catégories d'Immobilisations</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Classification comptable, durées d'amortissement et sous-familles</p>
                </div>
            </div>

            <!-- 2 Compteurs sur la même ligne -->
            <div class="flex items-center space-x-2.5 sm:border-l sm:border-slate-200 sm:pl-4">
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                    <i class="fa-solid fa-shapes text-slate-400 text-xs"></i>
                    <div class="text-xs font-semibold text-slate-700">
                        <span class="text-slate-400 font-normal">Catégories :</span> <strong class="text-slate-900 font-bold">{{ $stats['total'] ?? 0 }}</strong>
                    </div>
                </div>

                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-sky-50 border border-sky-200">
                    <i class="fa-solid fa-tags text-[#0048FA] text-xs"></i>
                    <div class="text-xs font-semibold text-[#0048FA]">
                        <span class="text-slate-500 font-normal">Sous-catégories :</span> <strong>{{ $stats['total_sous_categories'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2.5 flex-shrink-0">
            <a href="{{ route('admin.categories.create') }}"
               style="background-color: #0048FA;"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#0048FA]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Ajouter une catégorie</span>
            </a>
        </div>
    </div>

    <!-- Barre de Recherche -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('admin.categories.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <div class="sm:col-span-10 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher par nom, code ou description..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#0048FA] focus:border-[#0048FA] transition-all">
            </div>

            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit" style="background-color: #0048FA;" class="flex-1 py-2.5 px-3 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all text-center">
                    Filtrer
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.categories.index') }}" title="Réinitialiser" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau Moderne des Catégories -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-left">Catégorie</th>
                        <th class="py-3.5 px-4 text-center">Code</th>
                        <th class="py-3.5 px-4 text-center">Durée de vie standard</th>
                        <th class="py-3.5 px-4 text-center">Sous-catégories</th>
                        <th class="py-3.5 px-4 text-center">Actifs rattachés</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($categories as $cat)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Nom & Icône (Aligné à gauche) -->
                            <td class="py-3.5 px-4 text-left">
                                <div class="flex items-center space-x-3">
                                    <div style="background-color: #0048FA;" class="w-9 h-9 rounded-xl text-white flex items-center justify-center font-bold text-xs shadow-xs flex-shrink-0">
                                        <i class="fa-solid {{ $cat->icone ?? 'fa-cube' }}"></i>
                                    </div>
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 truncate">{{ $cat->nom }}</div>
                                        <div class="text-[11px] text-slate-400 truncate">{{ $cat->description ?? 'Aucune description' }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Code -->
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-800">
                                <span class="px-2.5 py-1 rounded-md bg-slate-100 border border-slate-200 text-xs">
                                    {{ $cat->code }}
                                </span>
                            </td>

                            <!-- Durée de vie -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="font-semibold text-slate-800">{{ round($cat->duree_vie_standard_mois / 12, 1) }} ans</span>
                                <span class="text-[10px] text-slate-400 block">({{ $cat->duree_vie_standard_mois }} mois)</span>
                            </td>

                            <!-- Sous-catégories -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold bg-sky-50 text-[#0048FA] border border-sky-100">
                                    <i class="fa-solid fa-tags text-[10px] mr-1.5"></i>
                                    {{ $cat->sous_categories_count ?? 0 }}
                                </span>
                            </td>

                            <!-- Actifs rattachés -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-700">
                                    <i class="fa-solid fa-cubes-stacked text-[10px] mr-1.5 text-slate-400"></i>
                                    {{ $cat->immobilisations_count ?? 0 }}
                                </span>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <a href="{{ route('admin.categories.edit', $cat->id) }}"
                                   title="Modifier la catégorie"
                                   class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#0048FA] text-slate-600 hover:text-white transition-colors">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                                    <i class="fa-solid fa-layer-group"></i>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">Aucune catégorie trouvée</div>
                                <p class="text-xs text-slate-400 mt-1">Créez une catégorie pour structurer vos immobilisations.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($categories->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Affichage de <strong>{{ $categories->firstItem() }}</strong> à <strong>{{ $categories->lastItem() }}</strong> sur <strong>{{ $categories->total() }}</strong> catégories
                </div>
                <div>
                    {{ $categories->links() }}
                </div>
            </div>
        @endif
    </div>

</div>
@endsection
