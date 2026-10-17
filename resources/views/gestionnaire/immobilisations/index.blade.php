@extends('layouts.app')

@section('title', 'Référentiel des Immobilisations')

@section('content')
<div class="space-y-5">

    <!-- En-tête de page, Compteurs & Actions rapides -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div style="background-color: #155E3B;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-boxes-stacked"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Référentiel des Immobilisations</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Registre officiel, valorisation financière, localisation et traçabilité du parc</p>
                </div>
            </div>

            <!-- Compteurs sur la même ligne (flex-wrap sur mobile) -->
            <div class="flex flex-wrap items-center gap-2 sm:border-l sm:border-slate-200 sm:pl-4">
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

                @if(isset($stats['valeur_totale']))
                <div class="hidden xl:flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-cyan-50 border border-cyan-200">
                    <i class="fa-solid fa-coins text-cyan-600 text-xs"></i>
                    <div class="text-xs font-semibold text-cyan-900 font-mono">
                        {{ number_format($stats['valeur_totale'], 0, ',', ' ') }} FCFA
                    </div>
                </div>
                @endif
            </div>
        </div>

        <div class="flex flex-wrap items-center gap-2.5 flex-shrink-0">
            <a href="{{ route('gestionnaire.immobilisations.export.csv') }}"
               class="inline-flex items-center space-x-2 px-3.5 py-2.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                <i class="fa-solid fa-file-csv text-xs text-emerald-600"></i>
                <span>Exporter CSV</span>
            </a>

            <a href="{{ route('gestionnaire.immobilisations.create') }}"
               style="background-color: #155E3B;"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>Ajouter une immobilisation</span>
            </a>
        </div>
    </div>

    <!-- Filtres Multi-critères -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('gestionnaire.immobilisations.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-3">
            <!-- Recherche libre -->
            <div class="lg:col-span-2 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Code, désignation, série, marque, tag..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
            </div>

            <!-- Programme -->
            <div>
                <select name="programme_id" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <option value="">Tous les programmes</option>
                    @foreach($programmes as $prog)
                        <option value="{{ $prog->id }}" {{ request('programme_id') == $prog->id ? 'selected' : '' }}>
                            {{ $prog->code ?? $prog->sigle ?? $prog->nom }}
                        </option>
                    @endforeach
                </select>
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

            <!-- État Physique -->
            <div>
                <select name="etat" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] transition-all">
                    <option value="">Tous les états</option>
                    <option value="bon_etat" {{ request('etat') == 'bon_etat' ? 'selected' : '' }}>Bon état / Fonctionnel</option>
                    <option value="panne" {{ request('etat') == 'panne' ? 'selected' : '' }}>En panne</option>
                    <option value="hors_service" {{ request('etat') == 'hors_service' ? 'selected' : '' }}>Hors service</option>
                    <option value="en_maintenance" {{ request('etat') == 'en_maintenance' ? 'selected' : '' }}>En maintenance</option>
                    <option value="transfere" {{ request('etat') == 'transfere' ? 'selected' : '' }}>Transféré</option>
                    <option value="non_retrouve" {{ request('etat') == 'non_retrouve' ? 'selected' : '' }}>Non retrouvé</option>
                    <option value="a_reformer" {{ request('etat') == 'a_reformer' ? 'selected' : '' }}>À réformer / Réformé</option>
                </select>
            </div>

            <!-- Boutons Filtrer & Reset -->
            <div class="flex items-center space-x-2 sm:col-span-2 md:col-span-3 lg:col-span-6 lg:justify-end">
                <button type="submit" style="background-color: #155E3B;" class="px-5 py-2.5 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all flex items-center space-x-1.5 cursor-pointer">
                    <i class="fa-solid fa-filter text-xs"></i>
                    <span>Appliquer les filtres</span>
                </button>
                @if(request()->hasAny(['search', 'programme_id', 'categorie_id', 'structure_id', 'etat', 'region_id']))
                    <a href="{{ route('gestionnaire.immobilisations.index') }}" title="Réinitialiser" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 transition-all flex items-center justify-center">
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
                        <th class="py-3.5 px-4 text-center">Programme & Catégorie</th>
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
                                    <div class="min-w-0 max-w-xs">
                                        <a href="{{ route('gestionnaire.immobilisations.show', $item->id) }}" class="font-bold text-slate-900 hover:text-[#155E3B] hover:underline truncate block">
                                            {{ $item->designation }}
                                        </a>
                                        <div class="text-[11px] text-slate-400 truncate">
                                            {{ $item->marque ?? 'Marque non spécifiée' }} {{ $item->modele ? '• '.$item->modele : '' }}
                                            @if($item->numero_serie)
                                                <span class="font-mono text-slate-400">(S/N: {{ $item->numero_serie }})</span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </td>

                            <!-- Code & Tag -->
                            <td class="py-3.5 px-4 text-center font-mono">
                                <a href="{{ route('gestionnaire.immobilisations.show', $item->id) }}" class="font-bold text-[#155E3B] hover:underline block text-xs">
                                    {{ $item->code_immobilisation }}
                                </a>
                                <span class="text-[10px] text-slate-400 font-medium">{{ $item->reference_etiquette ?? 'SANS-TAG' }}</span>
                            </td>

                            <!-- Programme & Catégorie -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex flex-col items-center justify-center space-y-1">
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-bold bg-slate-100 text-slate-800 border border-slate-200">
                                        {{ $item->programme?->code ?? $item->programme?->sigle ?? 'UCP-FM' }}
                                    </span>
                                    <span class="text-[10px] text-slate-500 font-medium">
                                        {{ $item->categorie?->nom ?? 'Non classé' }}
                                    </span>
                                </div>
                            </td>

                            <!-- Structure & Emplacement -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex flex-col items-center justify-center">
                                    <span class="font-semibold text-slate-800">{{ $item->structureBeneficiaire?->nom ?? 'Siège UCP-FM' }}</span>
                                    <span class="text-[11px] text-slate-400">
                                        {{ $item->service?->nom ?? $item->emplacement_detaille ?? 'Local non défini' }}
                                        @if($item->detenteur)
                                            • <span class="text-slate-500 font-medium">{{ $item->detenteur->nom_complet }}</span>
                                        @endif
                                    </span>
                                </div>
                            </td>

                            <!-- Valeur -->
                            <td class="py-3.5 px-4 text-center font-mono font-bold text-slate-900">
                                {{ number_format($item->valeur_acquisition, 0, ',', ' ') }} {{ $item->devise }}
                            </td>

                            <!-- État (Bouton Interactif avec Pop-up Rapide) -->
                            <td class="py-3.5 px-4 text-center">
                                <button type="button"
                                        id="badge-etat-{{ $item->id }}"
                                        onclick="ouvrirPopinChangementEtat({{ $item->id }}, '{{ addslashes($item->code_immobilisation) }}', '{{ addslashes($item->designation) }}', '{{ $item->etat }}')"
                                        title="Cliquer pour changer l'état du bien"
                                        class="inline-flex items-center justify-center space-x-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all hover:scale-105 hover:shadow-xs cursor-pointer group {{ $item->etat_badge_class }}">
                                    <span>{{ $item->etat_libelle }}</span>
                                    <i class="fa-solid fa-pen text-[8px] opacity-40 group-hover:opacity-100 transition-opacity"></i>
                                </button>
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center justify-center space-x-1">
                                    <!-- Changer État Rapide -->
                                    <button type="button"
                                            onclick="ouvrirPopinChangementEtat({{ $item->id }}, '{{ addslashes($item->code_immobilisation) }}', '{{ addslashes($item->designation) }}', '{{ $item->etat }}')"
                                            title="Changer l'état rapidement"
                                            class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-emerald-50 hover:bg-[#155E3B] text-[#155E3B] hover:text-white transition-colors cursor-pointer">
                                        <i class="fa-solid fa-arrows-rotate text-xs"></i>
                                    </button>

                                    <!-- Fiche 360° -->
                                    <a href="{{ route('gestionnaire.immobilisations.show', $item->id) }}"
                                       title="Consulter la fiche 360°"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#155E3B] text-slate-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>

                                    <!-- Modifier -->
                                    <a href="{{ route('gestionnaire.immobilisations.edit', $item->id) }}"
                                       title="Modifier la fiche complète"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#155E3B] text-slate-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <!-- QR Code -->
                                    <a href="{{ route('gestionnaire.immobilisations.etiquette', $item->id) }}"
                                       target="_blank"
                                       title="Étiquette QR Code & Code-Barres"
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

<!-- Pop-up Modal Interactif pour Changement d'État Rapide -->
<script>
function ouvrirPopinChangementEtat(id, code, designation, etatActuel) {
    const etatsOptions = [
        { value: 'bon_etat', label: 'Bon état / Fonctionnel', color: '#10b981' },
        { value: 'panne', label: 'En panne (Signalé)', color: '#f59e0b' },
        { value: 'hors_service', label: 'Hors service / Inutilisable', color: '#ef4444' },
        { value: 'en_maintenance', label: 'En maintenance / Atelier SAV', color: '#8b5cf6' },
        { value: 'transfere', label: 'Transféré / En transit', color: '#0ea5e9' },
        { value: 'non_retrouve', label: 'Non retrouvé / Écart inventaire', color: '#dc2626' },
        { value: 'a_reformer', label: 'À réformer / Mise au rebut', color: '#64748b' }
    ];

    let optionsHtml = '';
    etatsOptions.forEach(opt => {
        const isSelected = (opt.value === etatActuel) ? 'selected' : '';
        optionsHtml += `<option value="${opt.value}" ${isSelected}>${opt.label}</option>`;
    });

    const htmlContent = `
        <div class="text-left space-y-4 pt-2">
            <div class="p-3 bg-slate-50 rounded-xl border border-slate-200">
                <div class="text-xs text-slate-400 uppercase font-semibold">Immobilisation :</div>
                <div class="font-bold text-slate-900 text-sm mt-0.5">${designation}</div>
                <div class="font-mono text-xs text-[#155E3B] font-bold">${code}</div>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Nouvel État Physique <span class="text-red-500">*</span></label>
                <select id="swal-select-etat" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-300 rounded-xl text-xs font-semibold text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B]">
                    ${optionsHtml}
                </select>
            </div>

            <div>
                <label class="block text-xs font-bold text-slate-700 mb-1">Motif / Observation (Optionnel)</label>
                <textarea id="swal-textarea-motif" rows="2" placeholder="Précisez la raison (ex: constat visuel lors de la visite, panne signalée par le service...)" class="w-full px-3 py-2 bg-slate-50 border border-slate-300 rounded-xl text-xs text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B]"></textarea>
            </div>
        </div>
    `;

    Swal.fire({
        title: '<div class="flex items-center space-x-2 text-base font-bold text-slate-900"><i class="fa-solid fa-arrows-rotate text-[#155E3B]"></i><span>Modifier l\'état du bien</span></div>',
        html: htmlContent,
        showCancelButton: true,
        confirmButtonText: '<i class="fa-solid fa-check mr-1.5"></i> Enregistrer la modification',
        cancelButtonText: 'Annuler',
        confirmButtonColor: '#155E3B',
        cancelButtonColor: '#94a3b8',
        customClass: {
            popup: 'rounded-3xl border border-slate-200 shadow-2xl p-6',
            confirmButton: 'rounded-xl px-4 py-2.5 text-xs font-bold shadow-md shadow-emerald-900/20',
            cancelButton: 'rounded-xl px-4 py-2.5 text-xs font-bold'
        },
        focusConfirm: false,
        showLoaderOnConfirm: true,
        preConfirm: () => {
            const nouvelEtat = document.getElementById('swal-select-etat').value;
            const motif = document.getElementById('swal-textarea-motif').value;

            if (!nouvelEtat) {
                Swal.showValidationMessage('Veuillez sélectionner un état.');
                return false;
            }

            const token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

            return fetch(`/gestionnaire/immobilisations/${id}/etat`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    etat: nouvelEtat,
                    motif: motif
                })
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erreur lors de la mise à jour.');
                }
                return response.json();
            })
            .catch(error => {
                Swal.showValidationMessage(`Erreur : ${error.message}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
        if (result.isConfirmed && result.value && result.value.success) {
            const badge = document.getElementById(`badge-etat-${id}`);
            if (badge) {
                badge.className = `inline-flex items-center justify-center space-x-1 px-2.5 py-1 rounded-full text-[10px] font-bold transition-all hover:scale-105 hover:shadow-xs cursor-pointer group ${result.value.badge_class}`;
                badge.innerHTML = `<span>${result.value.etat_libelle}</span><i class="fa-solid fa-pen text-[8px] opacity-40 group-hover:opacity-100 transition-opacity"></i>`;
                badge.setAttribute('onclick', `ouvrirPopinChangementEtat(${id}, '${code}', '${designation}', '${result.value.nouvel_etat}')`);
            }

            Swal.fire({
                icon: 'success',
                title: 'État mis à jour !',
                text: result.value.message,
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3500,
                timerProgressBar: true
            });
        }
    });
}
</script>
@endsection

