<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bordereau de Mouvement & Décharge - {{ $mouvement->reference_mouvement }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            body { print-color-adjust: exact; -webkit-print-color-adjust: exact; }
            @page { size: A4 portrait; margin: 12mm 15mm; }
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
            color: #1e293b;
        }
    </style>
</head>
<body class="bg-slate-100 p-4 sm:p-8">

    <!-- Actions barre d'outils -->
    <div class="max-w-4xl mx-auto mb-6 flex items-center justify-between no-print">
        <a href="{{ route('gestionnaire.mouvements.show', $mouvement->id) }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-white border border-slate-300 text-slate-700 font-bold text-xs hover:bg-slate-50 transition-all shadow-xs">
            <i class="fa-solid fa-arrow-left mr-2"></i> Retour au Mouvement
        </a>
        <button onclick="window.print()" class="inline-flex items-center px-5 py-2.5 rounded-xl bg-[#155E3B] hover:bg-[#0f442a] text-white font-bold text-xs shadow-md shadow-emerald-900/20 transition-all cursor-pointer">
            <i class="fa-solid fa-print mr-2"></i> Imprimer le Bordereau / Décharge
        </button>
    </div>

    <!-- Document officiel imprimable A4 -->
    <div class="max-w-4xl mx-auto bg-white p-8 sm:p-12 rounded-2xl shadow-md border border-slate-200">
        
        <!-- En-tête officiel République / Ministère / UCP-FM -->
        <div class="grid grid-cols-3 items-center pb-6 border-b-2 border-[#155E3B]">
            <div class="text-left text-xs leading-tight">
                <p class="font-extrabold uppercase text-slate-900">RÉPUBLIQUE DE CÔTE D'IVOIRE</p>
                <p class="text-[10px] text-slate-500 italic mt-0.5">Union – Discipline – Travail</p>
                <div class="mt-2 text-[11px] text-slate-700 font-semibold">
                    Ministère de la Santé, de l'Hygiène Publique<br>et de la Couverture Maladie Universelle
                </div>
            </div>

            <div class="text-center">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-2xl bg-emerald-50 border-2 border-[#155E3B] text-[#155E3B] font-extrabold text-lg shadow-xs">
                    <i class="fa-solid fa-building-columns"></i>
                </div>
                <div class="font-extrabold text-sm text-[#155E3B] tracking-wider mt-1">UCP-FM</div>
                <div class="text-[9px] text-slate-500 uppercase tracking-tight">Gestion des Immobilisations</div>
            </div>

            <div class="text-right text-xs">
                <div class="inline-block px-3 py-1.5 rounded-lg bg-emerald-50 border border-emerald-200 font-mono font-bold text-emerald-900 text-xs">
                    N° {{ $mouvement->reference_mouvement }}
                </div>
                <p class="text-[11px] text-slate-500 mt-2">Date : <strong>{{ $mouvement->date_mouvement->format('d/m/Y') }}</strong></p>
                <p class="text-[10px] text-slate-400">Statut : <span class="uppercase font-bold text-slate-700">{{ $mouvement->statut_workflow }}</span></p>
            </div>
        </div>

        <!-- Titre du Document -->
        <div class="my-6 text-center">
            <h1 class="text-lg font-black uppercase tracking-wider text-slate-900">
                BORDEREAU DE MOUVEMENT & FICHE DE DÉCHARGE
            </h1>
            <p class="text-xs text-slate-500 mt-1 uppercase font-semibold">
                Type d'opération : <span class="text-[#155E3B] font-bold">{{ $mouvement->type_libelle }}</span>
            </p>
        </div>

        <!-- Informations Immobilisation -->
        <div class="mb-6 rounded-xl border border-slate-200 p-4 bg-slate-50/50">
            <h2 class="text-xs font-bold uppercase text-[#155E3B] tracking-wider mb-3 flex items-center">
                <i class="fa-solid fa-cube mr-2"></i> Identification de l'Immobilisation
            </h2>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-xs">
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Code Actif</span>
                    <span class="font-mono font-bold text-slate-900">{{ $mouvement->immobilisation?->code_immobilisation ?? 'N/A' }}</span>
                </div>
                <div class="sm:col-span-2">
                    <span class="text-slate-400 block text-[10px] uppercase">Désignation</span>
                    <span class="font-bold text-slate-900">{{ $mouvement->immobilisation?->designation ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">N° Série / Châssis</span>
                    <span class="font-mono text-slate-700">{{ $mouvement->immobilisation?->numero_serie ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Catégorie</span>
                    <span class="text-slate-800">{{ $mouvement->immobilisation?->categorie?->libelle ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Marque & Modèle</span>
                    <span class="text-slate-800">{{ $mouvement->immobilisation?->marque ?? '-' }} {{ $mouvement->immobilisation?->modele ?? '' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Programme Financier</span>
                    <span class="text-slate-800">{{ $mouvement->immobilisation?->programme?->sigle ?? 'N/A' }}</span>
                </div>
                <div>
                    <span class="text-slate-400 block text-[10px] uppercase">Valeur Comptable (VNC)</span>
                    <span class="font-mono font-bold text-slate-900">{{ $mouvement->immobilisation?->valeur_formatee ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Tableau Comparatif Origine vs Destination -->
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 text-xs">
            <!-- Origine -->
            <div class="p-4 rounded-xl border border-slate-200 bg-white">
                <div class="font-bold text-rose-700 uppercase text-[10px] mb-2 flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    <span>Structure d'Origine (Émetteur)</span>
                </div>
                <p class="font-bold text-slate-900 text-sm">{{ $mouvement->structureOrigine?->nom ?? 'Siège UCP-FM' }}</p>
                <p class="text-slate-500 mt-1">Ville : {{ $mouvement->structureOrigine?->ville ?? '-' }}</p>
                <p class="text-slate-500">Service : {{ $mouvement->serviceOrigine?->nom ?? 'Non spécifié' }}</p>
                <p class="text-slate-500">Détenteur cédant : <strong>{{ $mouvement->detenteurOrigine?->nom_complet ?? 'N/A' }}</strong></p>
            </div>

            <!-- Destination -->
            <div class="p-4 rounded-xl border border-emerald-200 bg-emerald-50/30">
                <div class="font-bold text-[#155E3B] uppercase text-[10px] mb-2 flex items-center space-x-1.5">
                    <i class="fa-solid fa-arrow-down-to-bracket"></i>
                    <span>Structure Destinataire (Récepteur)</span>
                </div>
                <p class="font-bold text-slate-900 text-sm">{{ $mouvement->structureDestination?->nom ?? 'Nouvelle Affectation' }}</p>
                <p class="text-slate-500 mt-1">Ville : {{ $mouvement->structureDestination?->ville ?? '-' }}</p>
                <p class="text-slate-500">Service : {{ $mouvement->serviceDestination?->nom ?? 'À définir' }}</p>
                <p class="text-slate-500">Détenteur récepteur : <strong>{{ $mouvement->detenteurDestination?->nom_complet ?? 'À désigner' }}</strong></p>
            </div>
        </div>

        <!-- Justification & Modalités -->
        <div class="mb-6 p-4 rounded-xl border border-slate-200 text-xs space-y-2">
            <div>
                <span class="font-bold text-slate-700">Motif administratif du mouvement :</span>
                <p class="text-slate-600 mt-0.5">{{ $mouvement->motif }}</p>
            </div>
            @if($mouvement->commentaires)
            <div>
                <span class="font-bold text-slate-700">Observations et consignes particulières :</span>
                <p class="text-slate-600 mt-0.5">{{ $mouvement->commentaires }}</p>
            </div>
            @endif
        </div>

        <!-- Cadre d'engagement & Signatures -->
        <div class="mt-8 pt-4 border-t border-slate-200 text-xs">
            <p class="text-[10px] text-slate-500 italic mb-4">
                En signant ce document, le détenteur récepteur atteste avoir pris possession du matériel décrit ci-dessus en parfait état de fonctionnement (sauf mention contraire) et en assume l'entière garde et responsabilité administrative et patrimoniale.
            </p>

            <div class="grid grid-cols-3 gap-4 pt-2 text-center">
                <div class="border border-slate-200 rounded-xl p-3 h-32 flex flex-col justify-between">
                    <span class="font-bold text-slate-700 uppercase text-[10px]">L'Émetteur (Cédant)</span>
                    <div class="text-[10px] text-slate-400">Date et Signature</div>
                </div>

                <div class="border border-slate-200 rounded-xl p-3 h-32 flex flex-col justify-between">
                    <span class="font-bold text-[#155E3B] uppercase text-[10px]">Le Gestionnaire de Patrimoine</span>
                    <div class="text-[10px] text-slate-400">Cachet & Signature</div>
                </div>

                <div class="border border-slate-200 rounded-xl p-3 h-32 flex flex-col justify-between">
                    <span class="font-bold text-slate-700 uppercase text-[10px]">Le Récepteur (Bénéficiaire)</span>
                    <div class="text-[10px] text-slate-400">Date, Nom & Signature</div>
                </div>
            </div>
        </div>

        <!-- Pied de page -->
        <div class="mt-8 pt-4 border-t border-slate-100 flex items-center justify-between text-[9px] text-slate-400">
            <span>UCP-FM • Plateforme GEMMA de Gestion des Actes & Immobilisations</span>
            <span>Document généré le {{ now()->format('d/m/Y à H:i') }}</span>
        </div>

    </div>

</body>
</html>
