@extends('layouts.app')

@section('title', "Fiche SAV {$maintenance->numero_fiche}")
@section('header_title', "Fiche d'Intervention SAV")

@section('header_badge')
    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold font-mono bg-amber-100 text-amber-900 border border-amber-300">
        {{ $maintenance->numero_fiche }}
    </span>
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">

    <!-- En-tête Fiche Maintenance -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm flex flex-col md:flex-row items-start md:items-center justify-between gap-4">
        <div class="flex items-start space-x-4">
            <div class="w-14 h-14 rounded-2xl bg-gradient-to-tr from-rose-600 to-amber-500 flex items-center justify-center text-white text-2xl font-black shadow-md shadow-rose-500/20 flex-shrink-0">
                <i class="fa-solid fa-screwdriver-wrench"></i>
            </div>
            <div>
                <div class="flex flex-wrap items-center gap-2">
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold {{ $maintenance->statut_badge_class }}">
                        {{ $maintenance->statut_libelle }}
                    </span>
                    <span class="px-2.5 py-0.5 rounded-full text-[10px] font-semibold bg-slate-100 text-slate-700">
                        {{ ucfirst($maintenance->type_maintenance) }}
                    </span>
                    <span class="text-xs text-slate-400 font-medium">
                        Signalé le : <strong class="text-slate-700">{{ $maintenance->date_signalement?->format('d/m/Y') }}</strong>
                    </span>
                </div>
                <h1 class="text-xl font-extrabold text-slate-900 mt-1">{{ $maintenance->numero_fiche }}</h1>
                <div class="text-xs text-slate-500 mt-0.5">
                    Actif : <strong class="text-slate-800">{{ $maintenance->immobilisation?->designation }}</strong> (<span class="font-mono text-[#155E3B] font-bold">{{ $maintenance->immobilisation?->code_immobilisation }}</span>)
                </div>
            </div>
        </div>

        <div class="flex items-center gap-2">
            @if($maintenance->statut !== 'terminee')
            <button type="button" onclick="document.getElementById('clotureModal').classList.remove('hidden')" class="px-4 py-2 rounded-xl bg-[#155E3B] hover:bg-[#0f442a] text-white text-xs font-bold shadow-sm transition-all flex items-center space-x-1.5">
                <i class="fa-solid fa-check-double"></i>
                <span>Clôturer l'intervention</span>
            </button>
            @endif

            <a href="{{ route('logistique.maintenances.index') }}" class="px-3.5 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-700 text-xs font-bold transition-all">
                Retour
            </a>
        </div>
    </div>

    <!-- Grille Informations Interventions -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 text-xs">
        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3">
            <h3 class="font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">Détails d'Intervention</h3>
            <div>
                <span class="text-slate-400 block mb-0.5">Prestataire / Réparateur</span>
                <div class="font-bold text-slate-800">{{ $maintenance->prestataire ?? 'Interne UCP-FM' }}</div>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Technicien</span>
                <div class="font-semibold text-slate-800">{{ $maintenance->technicien_intervenant ?? 'Non renseigné' }}</div>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Coût de l'Intervention</span>
                <div class="font-extrabold font-mono text-slate-900 text-sm">{{ number_format($maintenance->cout_intervention ?? 0, 0, ',', ' ') }} FCFA</div>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-5 border border-slate-200 shadow-sm space-y-3">
            <h3 class="font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">Dates & Délais</h3>
            <div>
                <span class="text-slate-400 block mb-0.5">Date Prévue d'Intervention</span>
                <div class="font-semibold text-slate-800">{{ $maintenance->date_intervention?->format('d/m/Y') ?? 'Non planifiée' }}</div>
            </div>
            <div>
                <span class="text-slate-400 block mb-0.5">Date de Fin Effective</span>
                <div class="font-semibold text-slate-800">{{ $maintenance->date_fin_intervention?->format('d/m/Y') ?? 'En cours' }}</div>
            </div>
        </div>
    </div>

    <!-- Symptômes & Diagnostics -->
    <div class="bg-white rounded-2xl p-6 border border-slate-200 shadow-sm space-y-4">
        <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider pb-2 border-b border-slate-100">
            Description de la Panne & Diagnostic
        </h3>
        <div class="space-y-3 text-xs">
            <div>
                <span class="text-slate-400 font-bold block mb-1">Description des Symptômes :</span>
                <div class="p-3 bg-slate-50 rounded-xl text-slate-800 font-medium">{{ $maintenance->description_panne ?? 'Aucune description fournie.' }}</div>
            </div>
            @if($maintenance->diagnostic)
            <div>
                <span class="text-slate-400 font-bold block mb-1">Diagnostic Technique :</span>
                <div class="p-3 bg-slate-50 rounded-xl text-slate-800 font-medium">{{ $maintenance->diagnostic }}</div>
            </div>
            @endif
            @if($maintenance->travaux_effectues)
            <div>
                <span class="text-slate-400 font-bold block mb-1">Travaux Réalisés :</span>
                <div class="p-3 bg-emerald-50/60 border border-emerald-100 rounded-xl text-emerald-900 font-medium">{{ $maintenance->travaux_effectues }}</div>
            </div>
            @endif
        </div>
    </div>

    <!-- Modal Clôture -->
    <div id="clotureModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-xs p-4">
        <div class="bg-white rounded-2xl max-w-lg w-full p-6 shadow-2xl space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900">Clôturer la Maintenance {{ $maintenance->numero_fiche }}</h3>
                <button type="button" onclick="document.getElementById('clotureModal').classList.add('hidden')" class="text-slate-400 hover:text-slate-600">
                    <i class="fa-solid fa-xmark text-base"></i>
                </button>
            </div>
            <form method="POST" action="{{ route('logistique.maintenances.cloturer', $maintenance->id) }}" class="space-y-4 text-xs">
                @csrf
                <div>
                    <label class="block font-bold text-slate-800 mb-1">Rapport des Travaux Effectués *</label>
                    <textarea name="travaux_effectues" rows="3" required placeholder="Détaillez les réparations effectuées et tests validés..." class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl outline-none">{{ $maintenance->travaux_effectues }}</textarea>
                </div>
                <div>
                    <label class="block font-bold text-slate-800 mb-1">Coût Réel Final (FCFA)</label>
                    <input type="number" step="0.01" name="cout_intervention" value="{{ $maintenance->cout_intervention }}" class="w-full p-2.5 bg-slate-50 border border-slate-200 rounded-xl font-mono">
                </div>
                <div class="flex items-center justify-end space-x-2 pt-2">
                    <button type="button" onclick="document.getElementById('clotureModal').classList.add('hidden')" class="px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold">Annuler</button>
                    <button type="submit" class="px-4 py-2 rounded-xl bg-[#155E3B] text-white font-bold">Confirmer et Remettre en Service</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection
