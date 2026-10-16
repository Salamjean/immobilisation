@extends('layouts.admin.app')

@section('title', 'Gestion des Utilisateurs')

@section('content')
<div class="space-y-5">

    <!-- En-tête de page, 2 Compteurs & Action rapide sur la même ligne -->
    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-xs">
        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
            <div class="flex items-center space-x-3">
                <div style="background-color: #155E3B;" class="w-10 h-10 rounded-xl text-white flex items-center justify-center text-base shadow-xs flex-shrink-0">
                    <i class="fa-solid fa-users-gear"></i>
                </div>
                <div>
                    <h1 class="text-lg font-bold text-slate-900 tracking-tight leading-tight">Répertoire des Utilisateurs</h1>
                    <p class="text-xs text-slate-500 mt-0.5">Gestion des accès et habilitations du système</p>
                </div>
            </div>

            <!-- 2 Compteurs sur la même ligne -->
            <div class="flex items-center space-x-2.5 sm:border-l sm:border-slate-200 sm:pl-4">
                <!-- Compteur 1: Total -->
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-slate-50 border border-slate-200">
                    <i class="fa-solid fa-users text-slate-400 text-xs"></i>
                    <div class="text-xs font-semibold text-slate-700">
                        <span class="text-slate-400 font-normal">Total :</span> <strong class="text-slate-900 font-bold">{{ $stats['total'] ?? $users->total() }}</strong>
                    </div>
                </div>

                <!-- Compteur 2: Actifs -->
                <div class="flex items-center space-x-2 px-3 py-1.5 rounded-xl bg-emerald-50 border border-emerald-200">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    <div class="text-xs font-semibold text-emerald-800">
                        <span class="text-emerald-600 font-normal">Actifs :</span> <strong>{{ $stats['actifs'] ?? 0 }}</strong>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex items-center space-x-2.5 flex-shrink-0">
            <a href="{{ route('admin.users.create') }}"
               style="background-color: #155E3B;"
               class="inline-flex items-center space-x-2 px-4 py-2.5 rounded-xl text-white text-xs font-bold shadow-md shadow-[#155E3B]/25 hover:opacity-95 transition-all">
                <i class="fa-solid fa-user-plus text-xs"></i>
                <span>Inscrire un utilisateur</span>
            </a>
        </div>
    </div>

    <!-- Barre de Recherche & Filtres -->
    <div class="bg-white p-4 rounded-2xl border border-slate-200/90 shadow-xs">
        <form method="GET" action="{{ route('admin.users.index') }}" class="grid grid-cols-1 sm:grid-cols-12 gap-3">
            <!-- Recherche Mot-clé -->
            <div class="sm:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 text-xs">
                    <i class="fa-solid fa-magnifying-glass"></i>
                </div>
                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Rechercher nom, email ou téléphone..."
                       class="w-full pl-9 pr-3.5 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 placeholder-slate-400 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
            </div>

            <!-- Filtre Rôle -->
            <div class="sm:col-span-3">
                <select name="role" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous les Rôles</option>
                    @foreach($roles as $codeRole => $libelleRole)
                        <option value="{{ $codeRole }}" {{ request('role') == $codeRole ? 'selected' : '' }}>
                            {{ $libelleRole }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filtre Statut -->
            <div class="sm:col-span-2">
                <select name="actif" class="w-full px-3 py-2.5 bg-slate-50 border border-slate-200 rounded-xl text-xs font-medium text-slate-800 focus:bg-white focus:outline-none focus:ring-2 focus:ring-[#155E3B] focus:border-[#155E3B] transition-all">
                    <option value="">Tous statuts</option>
                    <option value="1" {{ request('actif') === '1' ? 'selected' : '' }}>Actifs</option>
                    <option value="0" {{ request('actif') === '0' ? 'selected' : '' }}>Inactifs</option>
                </select>
            </div>

            <!-- Boutons Filtrer & Reset -->
            <div class="sm:col-span-2 flex items-center space-x-2">
                <button type="submit" style="background-color: #155E3B;" class="flex-1 py-2.5 px-3 rounded-xl text-white text-xs font-bold shadow-xs hover:opacity-95 transition-all text-center">
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'role', 'actif']))
                    <a href="{{ route('admin.users.index') }}" title="Réinitialiser les filtres" class="p-2.5 rounded-xl border border-slate-200 text-slate-500 hover:bg-slate-100 transition-all flex items-center justify-center">
                        <i class="fa-solid fa-rotate-left text-xs"></i>
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Tableau Moderne de la Liste des Utilisateurs (Infos Centrées) -->
    <div class="bg-white rounded-2xl border border-slate-200/90 shadow-xs overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-center border-collapse">
                <thead>
                    <tr class="bg-slate-50/80 border-b border-slate-100 text-[11px] font-bold text-slate-400 uppercase tracking-wider">
                        <th class="py-3.5 px-4 text-left">Utilisateur</th>
                        <th class="py-3.5 px-4 text-center">Rôle & Profil</th>
                        <th class="py-3.5 px-4 text-center">Rattachement</th>
                        <th class="py-3.5 px-4 text-center">Contact</th>
                        <th class="py-3.5 px-4 text-center">Statut</th>
                        <th class="py-3.5 px-4 text-center">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 text-xs">
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50/70 transition-colors">
                            <!-- Identité & Photo (Aligné à gauche) -->
                            <td class="py-3.5 px-4 text-left">
                                <div class="flex items-center space-x-3">
                                    @if($user->photo_url)
                                        <img src="{{ $user->photo_url }}" alt="{{ $user->nom_complet }}" class="w-9 h-9 rounded-xl object-cover border border-slate-200 shadow-xs flex-shrink-0">
                                    @else
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center font-bold text-xs shadow-xs text-white flex-shrink-0"
                                             style="background-color: {{ $user->role === 'admin' ? '#0f172a' : '#155E3B' }};">
                                            {{ substr($user->name, 0, 1) }}{{ substr($user->prenoms ?? $user->name, 0, 1) }}
                                        </div>
                                    @endif
                                    <div class="min-w-0">
                                        <div class="font-bold text-slate-900 truncate">{{ $user->nom_complet }}</div>
                                        <div class="text-[11px] text-slate-400 truncate">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>

                            <!-- Rôle -->
                            <td class="py-3.5 px-4 text-center">
                                <span class="inline-flex items-center justify-center px-2.5 py-1 rounded-lg text-[11px] font-semibold {{ $user->role_badge_class ?? 'bg-slate-100 text-slate-700' }} border">
                                    {{ $user->role_libelle }}
                                </span>
                            </td>

                            <!-- Rattachement Structure / Programme -->
                            <td class="py-3.5 px-4 text-center">
                                @if($user->programme)
                                    <div class="inline-flex items-center justify-center space-x-1.5 text-slate-700 font-medium">
                                        <i class="fa-solid fa-notes-medical text-indigo-500 text-xs"></i>
                                        <span>{{ $user->programme->sigle }}</span>
                                    </div>
                                @elseif($user->structure)
                                    <div class="inline-flex items-center justify-center space-x-1.5 text-slate-700 font-medium">
                                        <i class="fa-solid fa-hospital text-purple-500 text-xs"></i>
                                        <span>{{ $user->structure->nom }}</span>
                                    </div>
                                @else
                                    <span class="text-slate-400 italic text-[11px]">Siège Central / UCP</span>
                                @endif
                            </td>

                            <!-- Téléphone -->
                            <td class="py-3.5 px-4 text-center font-mono text-slate-600 text-[11px]">
                                {{ $user->telephone ?? '—' }}
                            </td>

                            <!-- Statut -->
                            <td class="py-3.5 px-4 text-center">
                                @if($user->actif)
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 mr-1.5 animate-pulse"></span>
                                        Actif
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-slate-100 text-slate-500 border border-slate-200">
                                        Inactif
                                    </span>
                                @endif
                            </td>

                            <!-- Actions -->
                            <td class="py-3.5 px-4 text-center">
                                <div class="inline-flex items-center justify-center space-x-1">
                                    <!-- Modifier -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                       title="Modifier l'utilisateur"
                                       class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-slate-100 hover:bg-[#155E3B] text-slate-600 hover:text-white transition-colors">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>

                                    <!-- Activer / Désactiver -->
                                    @if($user->id !== auth()->id())
                                        <form action="{{ route('admin.users.toggle', $user->id) }}" method="POST" class="inline" onsubmit="return confirmStatusToggle(event, this, '{{ addslashes($user->name) }}', {{ $user->actif ? 'true' : 'false' }});">
                                            @csrf
                                            <button type="submit"
                                                    title="{{ $user->actif ? 'Désactiver le compte' : 'Activer le compte' }}"
                                                    class="inline-flex items-center justify-center w-8 h-8 rounded-lg {{ $user->actif ? 'bg-rose-50 hover:bg-rose-600 text-rose-600 hover:text-white' : 'bg-emerald-50 hover:bg-emerald-600 text-emerald-600 hover:text-white' }} transition-colors">
                                                <i class="fa-solid {{ $user->actif ? 'fa-user-xmark' : 'fa-user-check' }} text-xs"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-12 text-center text-slate-400">
                                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center text-xl mx-auto mb-3">
                                    <i class="fa-solid fa-user-slash"></i>
                                </div>
                                <div class="text-sm font-semibold text-slate-700">Aucun utilisateur trouvé</div>
                                <p class="text-xs text-slate-400 mt-1">Essayez de modifier vos critères de recherche ou ajoutez un nouveau compte.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($users->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 bg-slate-50/50 flex items-center justify-between">
                <div class="text-xs text-slate-500">
                    Affichage de <strong>{{ $users->firstItem() }}</strong> à <strong>{{ $users->lastItem() }}</strong> sur <strong>{{ $users->total() }}</strong> utilisateurs
                </div>
                <div>
                    {{ $users->links() }}
                </div>
            </div>
        @endif
    </div>

</div>

<!-- Script SweetAlert2 de confirmation de désactivation / activation -->
<script>
    function confirmStatusToggle(event, form, userName, isCurrentlyActive) {
        event.preventDefault();
        const actionText = isCurrentlyActive ? 'désactiver' : 'activer';
        const iconType = isCurrentlyActive ? 'warning' : 'question';
        const confirmBtnColor = isCurrentlyActive ? '#ef4444' : '#155E3B';

        Swal.fire({
            title: `Confirmer l'opération`,
            text: `Êtes-vous sûr de vouloir ${actionText} le compte de ${userName} ?`,
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
