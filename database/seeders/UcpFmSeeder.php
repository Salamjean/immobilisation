<?php

namespace Database\Seeders;

use App\Models\Alerte;
use App\Models\AssetDocument;
use App\Models\AuditLog;
use App\Models\Bailleur;
use App\Models\CampagneInventaire;
use App\Models\CategorieActif;
use App\Models\Detenteur;
use App\Models\DistrictSanitaire;
use App\Models\Immobilisation;
use App\Models\InventaireEquipe;
use App\Models\InventaireReleve;
use App\Models\Maintenance;
use App\Models\Mouvement;
use App\Models\Programme;
use App\Models\Region;
use App\Models\ServiceStructure;
use App\Models\SousCategorieActif;
use App\Models\StructureBeneficiaire;
use App\Models\Subvention;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class UcpFmSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Bailleurs
        $fm = Bailleur::create([
            'code' => 'FM-GLOBAL',
            'nom' => 'Le Fonds mondial de lutte contre le Sida, la Tuberculose et le Paludisme',
            'description' => 'Principal bailleur international finançant les programmes de santé en Côte d\'Ivoire.',
            'contact_nom' => 'Gestionnaire de Portefeuille Pays',
            'contact_email' => 'portfolio.ci@theglobalfund.org',
            'actif' => true,
        ]);

        $etat = Bailleur::create([
            'code' => 'ETAT-CI',
            'nom' => 'État de Côte d\'Ivoire (Contrepartie Nationale)',
            'description' => 'Fonds de contrepartie et subventions nationales.',
            'contact_nom' => 'Direction Générale de la Santé',
            'contact_email' => 'contact@sante.gouv.ci',
            'actif' => true,
        ]);

        // 2. Programmes
        $pnlp = Programme::create([
            'bailleur_id' => $fm->id,
            'code' => 'PNLP',
            'nom' => 'Programme National de Lutte contre le Paludisme',
            'description' => 'Coordination de la lutte contre le paludisme sur le territoire ivoirien.',
            'coordonnateur' => 'Dr. KOUAME Jean-Baptiste',
            'actif' => true,
        ]);

        $pnls = Programme::create([
            'bailleur_id' => $fm->id,
            'code' => 'PNLS',
            'nom' => 'Programme National de Lutte contre le Sida',
            'description' => 'Coordination de la riposte nationale au VIH/Sida.',
            'coordonnateur' => 'Pr. EHUI Eboi',
            'actif' => true,
        ]);

        $pnlt = Programme::create([
            'bailleur_id' => $fm->id,
            'code' => 'PNLT',
            'nom' => 'Programme National de Lutte contre la Tuberculose',
            'description' => 'Dépistage, traitement et prise en charge de la tuberculose.',
            'coordonnateur' => 'Dr. BAKAYOKO Amadou',
            'actif' => true,
        ]);

        $rssh = Programme::create([
            'bailleur_id' => $fm->id,
            'code' => 'RSSH',
            'nom' => 'Renforcement du Système de Santé (RSSH / UCP-FM)',
            'description' => 'Infrastructures transversales, laboratoires, chaîne du froid et systèmes d\'information.',
            'coordonnateur' => 'Dr. DIOMANDE Salimata',
            'actif' => true,
        ]);

        // 3. Subventions
        $subvPnlp = Subvention::create([
            'programme_id' => $pnlp->id,
            'bailleur_id' => $fm->id,
            'code_subvention' => 'CIV-M-MOH-GC7-2024-2026',
            'intitule' => 'Subvention Paludisme GC7 (2024-2026)',
            'date_debut' => '2024-01-01',
            'date_fin' => '2026-12-31',
            'montant_alloue' => 45000000000,
            'devise' => 'FCFA',
            'statut' => 'en_cours',
        ]);

        $subvPnls = Subvention::create([
            'programme_id' => $pnls->id,
            'bailleur_id' => $fm->id,
            'code_subvention' => 'CIV-H-UCP-GC7-2024-2026',
            'intitule' => 'Subvention VIH / Sida GC7 (2024-2026)',
            'date_debut' => '2024-01-01',
            'date_fin' => '2026-12-31',
            'montant_alloue' => 38000000000,
            'devise' => 'FCFA',
            'statut' => 'en_cours',
        ]);

        $subvPnlt = Subvention::create([
            'programme_id' => $pnlt->id,
            'bailleur_id' => $fm->id,
            'code_subvention' => 'CIV-T-UCP-GC7-2024-2026',
            'intitule' => 'Subvention Tuberculose GC7 (2024-2026)',
            'date_debut' => '2024-01-01',
            'date_fin' => '2026-12-31',
            'montant_alloue' => 19500000000,
            'devise' => 'FCFA',
            'statut' => 'en_cours',
        ]);

        $subvRssh = Subvention::create([
            'programme_id' => $rssh->id,
            'bailleur_id' => $fm->id,
            'code_subvention' => 'CIV-S-RSSH-GC7-2024-2026',
            'intitule' => 'Subvention Renforcement Systèmes de Santé & Laboratoires',
            'date_debut' => '2024-01-01',
            'date_fin' => '2026-12-31',
            'montant_alloue' => 22000000000,
            'devise' => 'FCFA',
            'statut' => 'en_cours',
        ]);

        // 4. Régions & Districts
        $regAbidjan = Region::create(['nom' => 'Abidjan (Lagunes)', 'chef_lieu' => 'Abidjan']);
        $regGbeke = Region::create(['nom' => 'Gbêkê', 'chef_lieu' => 'Bouaké']);
        $regPoro = Region::create(['nom' => 'Poro', 'chef_lieu' => 'Korhogo']);
        $regSanPedro = Region::create(['nom' => 'San-Pédro', 'chef_lieu' => 'San-Pédro']);
        $regTonkpi = Region::create(['nom' => 'Tonkpi', 'chef_lieu' => 'Man']);
        $regHautSassandra = Region::create(['nom' => 'Haut-Sassandra', 'chef_lieu' => 'Daloa']);
        $regIndenie = Region::create(['nom' => 'Indénié-Djuablin', 'chef_lieu' => 'Abengourou']);
        $regBelier = Region::create(['nom' => 'Bélier', 'chef_lieu' => 'Yamoussoukro']);

        $distPlateau = DistrictSanitaire::create(['region_id' => $regAbidjan->id, 'nom' => 'District Abidjan Plateau - Treichville', 'code' => 'DS-ABJ-01']);
        $distCocody = DistrictSanitaire::create(['region_id' => $regAbidjan->id, 'nom' => 'District Abidjan Cocody - Bingerville', 'code' => 'DS-ABJ-02']);
        $distBouake = DistrictSanitaire::create(['region_id' => $regGbeke->id, 'nom' => 'District Sanitaire Bouaké Ouest', 'code' => 'DS-GBK-01']);
        $distKorhogo = DistrictSanitaire::create(['region_id' => $regPoro->id, 'nom' => 'District Sanitaire Korhogo', 'code' => 'DS-POR-01']);
        $distSanPedro = DistrictSanitaire::create(['region_id' => $regSanPedro->id, 'nom' => 'District Sanitaire San-Pédro', 'code' => 'DS-SPD-01']);
        $distMan = DistrictSanitaire::create(['region_id' => $regTonkpi->id, 'nom' => 'District Sanitaire Man', 'code' => 'DS-TNK-01']);
        $distDaloa = DistrictSanitaire::create(['region_id' => $regHautSassandra->id, 'nom' => 'District Sanitaire Daloa', 'code' => 'DS-HSS-01']);
        $distAbengourou = DistrictSanitaire::create(['region_id' => $regIndenie->id, 'nom' => 'District Sanitaire Abengourou', 'code' => 'DS-IND-01']);

        // 5. Structures Bénéficiaires
        $ucpSiege = StructureBeneficiaire::create([
            'region_id' => $regAbidjan->id,
            'district_sanitaire_id' => $distPlateau->id,
            'code' => 'STR-UCP-001',
            'nom' => 'UCP-FM Siège National',
            'type_structure' => 'UCP_SIEGE',
            'ville' => 'Abidjan',
            'commune' => 'Plateau',
            'adresse' => 'Immeuble CCIA, Plateau, Abidjan',
            'latitude' => 5.32621000,
            'longitude' => -4.01972000,
            'responsable_nom' => 'M. KOFFI Kouassi Paul (Coordonnateur)',
            'responsable_contact' => '+225 27 20 21 00 00',
            'responsable_email' => 'coordination@ucpfmci.org',
            'actif' => true,
        ]);

        $chuTreichville = StructureBeneficiaire::create([
            'region_id' => $regAbidjan->id,
            'district_sanitaire_id' => $distPlateau->id,
            'code' => 'STR-CHU-TREICH',
            'nom' => 'CHU de Treichville (SMIT & Laboratoire National)',
            'type_structure' => 'CHU',
            'ville' => 'Abidjan',
            'commune' => 'Treichville',
            'adresse' => 'Boulevard de Marseille, Treichville',
            'latitude' => 5.30560000,
            'longitude' => -4.00420000,
            'responsable_nom' => 'Dr. KRA Ouffoué (Chef de Service)',
            'responsable_contact' => '+225 07 08 12 34 56',
            'responsable_email' => 'treichville.labo@sante.gouv.ci',
            'actif' => true,
        ]);

        $chuCocody = StructureBeneficiaire::create([
            'region_id' => $regAbidjan->id,
            'district_sanitaire_id' => $distCocody->id,
            'code' => 'STR-CHU-COCODY',
            'nom' => 'CHU de Cocody (Pôle Mère-Enfant & Radiologie)',
            'type_structure' => 'CHU',
            'ville' => 'Abidjan',
            'commune' => 'Cocody',
            'adresse' => 'Boulevard de l\'Université, Cocody',
            'latitude' => 5.34110000,
            'longitude' => -3.99220000,
            'responsable_nom' => 'Pr. CISSE Lassana',
            'responsable_contact' => '+225 01 02 03 04 05',
            'responsable_email' => 'contact.chucocody@sante.ci',
            'actif' => true,
        ]);

        $chrBouake = StructureBeneficiaire::create([
            'region_id' => $regGbeke->id,
            'district_sanitaire_id' => $distBouake->id,
            'code' => 'STR-CHR-BOUAKE',
            'nom' => 'CHR de Bouaké',
            'type_structure' => 'CHR',
            'ville' => 'Bouaké',
            'commune' => 'Bouaké Centre',
            'adresse' => 'Quartier Commerce, Face Stade',
            'latitude' => 7.69080000,
            'longitude' => -5.03000000,
            'responsable_nom' => 'Dr. YAO Kouakou (Directeur Médical)',
            'responsable_contact' => '+225 05 06 77 88 99',
            'responsable_email' => 'chr.bouake@sante.gouv.ci',
            'actif' => true,
        ]);

        $chrKorhogo = StructureBeneficiaire::create([
            'region_id' => $regPoro->id,
            'district_sanitaire_id' => $distKorhogo->id,
            'code' => 'STR-CHR-KORHOGO',
            'nom' => 'CHR de Korhogo',
            'type_structure' => 'CHR',
            'ville' => 'Korhogo',
            'commune' => 'Korhogo',
            'adresse' => 'Avenue Félix Houphouët-Boigny, Korhogo',
            'latitude' => 9.45800000,
            'longitude' => -5.62960000,
            'responsable_nom' => 'Dr. COULIBALY Moussa',
            'responsable_contact' => '+225 07 48 11 22 33',
            'responsable_email' => 'chr.korhogo@sante.gouv.ci',
            'actif' => true,
        ]);

        $chrSanPedro = StructureBeneficiaire::create([
            'region_id' => $regSanPedro->id,
            'district_sanitaire_id' => $distSanPedro->id,
            'code' => 'STR-CHR-SANPEDRO',
            'nom' => 'CHR de San-Pédro',
            'type_structure' => 'CHR',
            'ville' => 'San-Pédro',
            'commune' => 'San-Pédro',
            'adresse' => 'Quartier Balmer, San-Pédro',
            'latitude' => 4.75700000,
            'longitude' => -6.64320000,
            'responsable_nom' => 'Dr. TOURE Mariam',
            'responsable_contact' => '+225 01 44 55 66 77',
            'responsable_email' => 'chr.sanpedro@sante.gouv.ci',
            'actif' => true,
        ]);

        $chrMan = StructureBeneficiaire::create([
            'region_id' => $regTonkpi->id,
            'district_sanitaire_id' => $distMan->id,
            'code' => 'STR-CHR-MAN',
            'nom' => 'CHR de Man',
            'type_structure' => 'CHR',
            'ville' => 'Man',
            'commune' => 'Man',
            'adresse' => 'Route de Danané, Man',
            'latitude' => 7.41250000,
            'longitude' => -7.55380000,
            'responsable_nom' => 'Dr. GOHO Daniel',
            'responsable_contact' => '+225 07 89 65 41 23',
            'responsable_email' => 'chr.man@sante.gouv.ci',
            'actif' => true,
        ]);

        $hgDaloa = StructureBeneficiaire::create([
            'region_id' => $regHautSassandra->id,
            'district_sanitaire_id' => $distDaloa->id,
            'code' => 'STR-HG-DALOA',
            'nom' => 'Hôpital Général de Daloa',
            'type_structure' => 'HOPITAL_GENERAL',
            'ville' => 'Daloa',
            'commune' => 'Daloa',
            'adresse' => 'Quartier Tazibouo, Daloa',
            'latitude' => 6.87700000,
            'longitude' => -6.45020000,
            'responsable_nom' => 'Dr. SERI Bi Tra',
            'responsable_contact' => '+225 05 12 34 56 78',
            'responsable_email' => 'hg.daloa@sante.gouv.ci',
            'actif' => true,
        ]);

        $pnlpSiege = StructureBeneficiaire::create([
            'region_id' => $regAbidjan->id,
            'district_sanitaire_id' => $distCocody->id,
            'code' => 'STR-PNLP-SIEGE',
            'nom' => 'Direction Coordonnatrice du PNLP',
            'type_structure' => 'PROGRAMME_NATIONAL',
            'ville' => 'Abidjan',
            'commune' => 'Cocody',
            'adresse' => 'Cocody II Plateaux, 7ème Tranche',
            'latitude' => 5.34890000,
            'longitude' => -3.99260000,
            'responsable_nom' => 'Dr. KOUAME Jean-Baptiste',
            'responsable_contact' => '+225 27 22 41 00 11',
            'responsable_email' => 'pnlp.direction@sante.gouv.ci',
            'actif' => true,
        ]);

        // 6. Services & Détenteurs
        $servUcpLog = ServiceStructure::create(['structure_beneficiaire_id' => $ucpSiege->id, 'code' => 'SRV-LOG', 'nom' => 'Service Logistique, Patrimoine & IT', 'responsable' => 'M. KONE Bakary']);
        $servUcpFin = ServiceStructure::create(['structure_beneficiaire_id' => $ucpSiege->id, 'code' => 'SRV-FIN', 'nom' => 'Service Administratif & Financier', 'responsable' => 'Mme DIABATE Awa']);
        
        $servTreichLabo = ServiceStructure::create(['structure_beneficiaire_id' => $chuTreichville->id, 'code' => 'SRV-LABO', 'nom' => 'Laboratoire de Biologie Moléculaire (GeneXpert / CD4)', 'responsable' => 'Dr. KOFFI Marc']);
        $servTreichPharma = ServiceStructure::create(['structure_beneficiaire_id' => $chuTreichville->id, 'code' => 'SRV-PHARM', 'nom' => 'Pharmacie Principale & Magasin Central', 'responsable' => 'Dr. AKE Danielle']);
        
        $servCocodyRadio = ServiceStructure::create(['structure_beneficiaire_id' => $chuCocody->id, 'code' => 'SRV-RADIO', 'nom' => 'Service Radiologie & Diagnostic Numérique', 'responsable' => 'Dr. KOUASSI Patrick']);
        $servBouakeLabo = ServiceStructure::create(['structure_beneficiaire_id' => $chrBouake->id, 'code' => 'SRV-LABO', 'nom' => 'Laboratoire d\'Analyses Médicales', 'responsable' => 'Dr. TRAORE Souleymane']);
        $servKorhogoLabo = ServiceStructure::create(['structure_beneficiaire_id' => $chrKorhogo->id, 'code' => 'SRV-LABO', 'nom' => 'Laboratoire & Dépistage Tuberculose', 'responsable' => 'Dr. SORO Adama']);
        $servSanPedroUrg = ServiceStructure::create(['structure_beneficiaire_id' => $chrSanPedro->id, 'code' => 'SRV-URG', 'nom' => 'Service des Urgences & Réanimation', 'responsable' => 'Dr. BAMBA Laciné']);
        $servManRea = ServiceStructure::create(['structure_beneficiaire_id' => $chrMan->id, 'code' => 'SRV-REA', 'nom' => 'Service Réanimation & Bloc Technique', 'responsable' => 'Dr. TIEHI Jean']);
        $servPnlpLog = ServiceStructure::create(['structure_beneficiaire_id' => $pnlpSiege->id, 'code' => 'SRV-LOG', 'nom' => 'Logistique & Gestion des Stocks MILD/CTA', 'responsable' => 'M. N\'GUESSAN Yao']);

        // Détenteurs
        $detKone = Detenteur::create([
            'structure_beneficiaire_id' => $ucpSiege->id,
            'service_id' => $servUcpLog->id,
            'matricule' => 'MAT-UCP-014',
            'nom' => 'KONE',
            'prenom' => 'Bakary',
            'fonction' => 'Responsable Logistique & Parc Véhicules',
            'telephone' => '+225 07 07 11 22 33',
            'email' => 'bakary.kone@ucpfmci.org',
            'actif' => true,
        ]);

        $detKoffi = Detenteur::create([
            'structure_beneficiaire_id' => $chuTreichville->id,
            'service_id' => $servTreichLabo->id,
            'matricule' => 'MAT-CHU-088',
            'nom' => 'KOFFI',
            'prenom' => 'Marc',
            'fonction' => 'Chef Biologiste / Spécialiste GeneXpert',
            'telephone' => '+225 01 02 44 55 66',
            'email' => 'marc.koffi@sante.gouv.ci',
            'actif' => true,
        ]);

        $detTraore = Detenteur::create([
            'structure_beneficiaire_id' => $chrBouake->id,
            'service_id' => $servBouakeLabo->id,
            'matricule' => 'MAT-CHR-052',
            'nom' => 'TRAORE',
            'prenom' => 'Souleymane',
            'fonction' => 'Responsable Laboratoire Régional',
            'telephone' => '+225 05 05 99 88 77',
            'email' => 's.traore@chr-bouake.ci',
            'actif' => true,
        ]);

        $detSoro = Detenteur::create([
            'structure_beneficiaire_id' => $chrKorhogo->id,
            'service_id' => $servKorhogoLabo->id,
            'matricule' => 'MAT-CHR-104',
            'nom' => 'SORO',
            'prenom' => 'Adama',
            'fonction' => 'Technicien Supérieur de Laboratoire',
            'telephone' => '+225 07 08 23 45 67',
            'email' => 'a.soro@chr-korhogo.ci',
            'actif' => true,
        ]);

        $detNguessan = Detenteur::create([
            'structure_beneficiaire_id' => $pnlpSiege->id,
            'service_id' => $servPnlpLog->id,
            'matricule' => 'MAT-PNLP-029',
            'nom' => 'N\'GUESSAN',
            'prenom' => 'Yao Fabrice',
            'fonction' => 'Chef Parc Automobile & Chaîne Logistique',
            'telephone' => '+225 01 44 77 88 99',
            'email' => 'f.nguessan@pnlp.ci',
            'actif' => true,
        ]);

        // 7. Catégories & Sous-catégories
        $catMed = CategorieActif::create([
            'code' => 'CAT-MED',
            'nom' => 'Équipements Médicaux & Biomédicaux',
            'description' => 'Appareils de diagnostic, automates d\'analyses, concentrateurs, stérilisateurs, etc.',
            'duree_vie_standard_mois' => 84, // 7 ans
            'icone' => 'fas fa-heartbeat',
        ]);
        $scGeneXpert = SousCategorieActif::create(['categorie_id' => $catMed->id, 'code' => 'SC-GENEXPERT', 'nom' => 'Systèmes de diagnostic moléculaire GeneXpert']);
        $scCd4 = SousCategorieActif::create(['categorie_id' => $catMed->id, 'code' => 'SC-CD4', 'nom' => 'Automates de numération CD4 / Charge virale']);
        $scAnesth = SousCategorieActif::create(['categorie_id' => $catMed->id, 'code' => 'SC-ANESTH', 'nom' => 'Appareils d\'anesthésie et monitoring']);

        $catRadio = CategorieActif::create([
            'code' => 'CAT-RADIO',
            'nom' => 'Équipements de Radiologie & Imagerie',
            'description' => 'Radiographie numérique, échographes Doppler, scanners mobiles.',
            'duree_vie_standard_mois' => 120, // 10 ans
            'icone' => 'fas fa-x-ray',
        ]);
        $scRadioNum = SousCategorieActif::create(['categorie_id' => $catRadio->id, 'code' => 'SC-RAD-NUM', 'nom' => 'Tables de radiographie numérique avec capteur plan']);
        $scEcho = SousCategorieActif::create(['categorie_id' => $catRadio->id, 'code' => 'SC-ECHO', 'nom' => 'Échographes Doppler couleur haute résolution']);

        $catFroid = CategorieActif::create([
            'code' => 'CAT-FROID',
            'nom' => 'Équipements de Chaîne du Froid & Conservation',
            'description' => 'Réfrigérateurs solaires biomédicaux, congélateurs -80°C pour réactifs et vaccins.',
            'duree_vie_standard_mois' => 96, // 8 ans
            'icone' => 'fas fa-snowflake',
        ]);
        $scRefrigSol = SousCategorieActif::create(['categorie_id' => $catFroid->id, 'code' => 'SC-REF-SOL', 'nom' => 'Réfrigérateurs solaires autonomes direct-drive']);
        $scCongel80 = SousCategorieActif::create(['categorie_id' => $catFroid->id, 'code' => 'SC-CONG-80', 'nom' => 'Congélateurs ultra-basse température (-80°C)']);

        $catEnergie = CategorieActif::create([
            'code' => 'CAT-ENERGIE',
            'nom' => 'Groupes Électrogènes & Systèmes Énergétiques',
            'description' => 'Groupes électrogènes industriels, onduleurs, installations solaires.',
            'duree_vie_standard_mois' => 120,
            'icone' => 'fas fa-bolt',
        ]);
        $scGroupe = SousCategorieActif::create(['categorie_id' => $catEnergie->id, 'code' => 'SC-GRP-ELEC', 'nom' => 'Groupes électrogènes insonorisés 50 à 150 kVA']);
        $scOnduleur = SousCategorieActif::create(['categorie_id' => $catEnergie->id, 'code' => 'SC-OND-IND', 'nom' => 'Onduleurs industriels en ligne haute puissance']);

        $catVehicule = CategorieActif::create([
            'code' => 'CAT-VEH',
            'nom' => 'Véhicules & Moyens Roulants',
            'description' => 'Véhicules 4x4 tout-terrain de supervision, ambulances médicalisées, motocyclettes.',
            'duree_vie_standard_mois' => 60, // 5 ans
            'icone' => 'fas fa-truck-pickup',
        ]);
        $sc4x4 = SousCategorieActif::create(['categorie_id' => $catVehicule->id, 'code' => 'SC-4X4', 'nom' => 'Véhicules 4x4 tout-terrain de supervision']);
        $scAmbulance = SousCategorieActif::create(['categorie_id' => $catVehicule->id, 'code' => 'SC-AMBULANCE', 'nom' => 'Ambulances médicalisées de transfert d\'urgence']);

        $catInfo = CategorieActif::create([
            'code' => 'CAT-INFO',
            'nom' => 'Matériels Informatiques & Réseaux',
            'description' => 'Serveurs, stations de travail, ordinateurs portables, serveurs réseau.',
            'duree_vie_standard_mois' => 36, // 3 ans
            'icone' => 'fas fa-laptop',
        ]);
        $scLaptop = SousCategorieActif::create(['categorie_id' => $catInfo->id, 'code' => 'SC-LAPTOP', 'nom' => 'Ordinateurs portables renforcés de gestion']);
        $scServeur = SousCategorieActif::create(['categorie_id' => $catInfo->id, 'code' => 'SC-SERVER', 'nom' => 'Serveurs de données et baies de stockage']);

        // 8. Immobilisations représentatives
        $actifsData = [
            [
                'code_immobilisation' => 'UCPFM-2024-MED-0001',
                'code_bailleur' => 'FM-CIV-T-0042',
                'reference_etiquette' => 'TAG-QR-88001',
                'numero_serie' => 'GX16-SN-9982410',
                'designation' => 'Système d\'analyse moléculaire automatisé GeneXpert XVI (16 modules)',
                'categorie_id' => $catMed->id,
                'sous_categorie_id' => $scGeneXpert->id,
                'marque' => 'Cepheid',
                'modele' => 'GeneXpert Infinity-48 / 16 Modules',
                'description_technique' => 'Automate de diagnostic rapide TB-MDR, charge virale VIH et HPV en temps réel avec onduleur intégré.',
                'bailleur_id' => $fm->id,
                'programme_id' => $pnlt->id,
                'subvention_id' => $subvPnlt->id,
                'fournisseur' => 'Cepheid International SAS / Copharmed CI',
                'reference_marche' => 'DAO-UCPFM-MED-2024-003',
                'bon_commande' => 'BC-2024-0198',
                'date_acquisition' => '2024-03-15',
                'date_reception' => '2024-04-10',
                'date_mise_en_service' => '2024-04-20',
                'valeur_acquisition' => 78500000.00,
                'devise' => 'FCFA',
                'date_fin_garantie' => '2027-04-20',
                'fournisseur_garantie' => 'Cepheid Support Afrique de l\'Ouest',
                'structure_beneficiaire_id' => $chuTreichville->id,
                'service_id' => $servTreichLabo->id,
                'detenteur_id' => $detKoffi->id,
                'date_affectation' => '2024-04-20',
                'emplacement_detaille' => 'Bâtiment SMIT, Salle Blanche Diagnostic Moléculaire N°2',
                'latitude' => 5.30564000,
                'longitude' => -4.00418000,
                'precision_gps' => 3.20,
                'date_derniere_localisation' => now()->subDays(12),
                'rayon_geofence_metres' => 200,
                'etat' => 'bon_etat',
                'statut_operationnel' => 'en_service',
                'niveau_criticite' => 'critique',
                'score_risque' => 92,
                'justification_risque' => 'Équipement hautement stratégique pour le diagnostic national TB/VIH, coût supérieur à 75M FCFA, impact vital immédiat sur la continuité des soins.',
                'photo_principale' => 'assets/images/genexpert.jpg',
                'qr_token' => Str::random(32),
                'date_dernier_inventaire' => now()->subMonths(2),
                'agent_dernier_inventaire' => 'KRA Yao Sylvain',
                'observations' => 'En parfait état de marche, calibrations annuelles conformes aux normes OMS.',
            ],
            [
                'code_immobilisation' => 'UCPFM-2024-RAD-0002',
                'code_bailleur' => 'FM-CIV-RSSH-0105',
                'reference_etiquette' => 'TAG-QR-88002',
                'numero_serie' => 'XR-DIG-2024-0881',
                'designation' => 'Table de Radiographie Numérique Os-Poumon à Capteur Plan',
                'categorie_id' => $catRadio->id,
                'sous_categorie_id' => $scRadioNum->id,
                'marque' => 'Siemens Healthineers',
                'modele' => 'Multix Impact C',
                'description_technique' => 'Générateur haute fréquence 65 kW avec capteur plan sans fil Wi-Fi et station PACS intégrée.',
                'bailleur_id' => $fm->id,
                'programme_id' => $rssh->id,
                'subvention_id' => $subvRssh->id,
                'fournisseur' => 'Siemens Medical Solutions CI',
                'reference_marche' => 'DAO-UCPFM-RAD-2023-011',
                'bon_commande' => 'BC-2024-0044',
                'date_acquisition' => '2024-02-01',
                'date_reception' => '2024-02-28',
                'date_mise_en_service' => '2024-03-10',
                'valeur_acquisition' => 112000000.00,
                'devise' => 'FCFA',
                'date_fin_garantie' => '2027-03-10',
                'fournisseur_garantie' => 'Siemens Medical CI Service Après-Vente',
                'structure_beneficiaire_id' => $chuCocody->id,
                'service_id' => $servCocodyRadio->id,
                'detenteur_id' => null,
                'date_affectation' => '2024-03-10',
                'emplacement_detaille' => 'Pavillon Imagerie, Salle de Radio 1 (Rez-de-chaussée)',
                'latitude' => 5.34112000,
                'longitude' => -3.99225000,
                'precision_gps' => 4.10,
                'date_derniere_localisation' => now()->subDays(8),
                'rayon_geofence_metres' => 300,
                'etat' => 'bon_etat',
                'statut_operationnel' => 'en_service',
                'niveau_criticite' => 'critique',
                'score_risque' => 95,
                'justification_risque' => 'Valeur unitaire supérieure à 100M FCFA, équipement lourd indispensable au diagnostic thoracique et osseux régional.',
                'photo_principale' => 'assets/images/radio_siemens.jpg',
                'qr_token' => Str::random(32),
                'date_dernier_inventaire' => now()->subMonths(1),
                'agent_dernier_inventaire' => 'KRA Yao Sylvain',
                'observations' => 'Maintenance préventive effectuée le mois dernier.',
            ],
            [
                'code_immobilisation' => 'UCPFM-2024-FRO-0003',
                'code_bailleur' => 'FM-CIV-M-0220',
                'reference_etiquette' => 'TAG-QR-88003',
                'numero_serie' => 'BD-SOL-882190',
                'designation' => 'Réfrigérateur Biomédical Solaire Direct-Drive 150L (Vaccins & Réactifs)',
                'categorie_id' => $catFroid->id,
                'sous_categorie_id' => $scRefrigSol->id,
                'marque' => 'B Medical Systems',
                'modele' => 'SDD TCW 40 SDD',
                'description_technique' => 'Réfrigérateur et congélateur combiné sans batterie fonctionnant à l\'énergie solaire directe certifié PQS OMS.',
                'bailleur_id' => $fm->id,
                'programme_id' => $pnlp->id,
                'subvention_id' => $subvPnlp->id,
                'fournisseur' => 'Solargen Technologies SARL',
                'reference_marche' => 'DAO-UCPFM-FROID-2024-001',
                'bon_commande' => 'BC-2024-0312',
                'date_acquisition' => '2024-05-10',
                'date_reception' => '2024-06-02',
                'date_mise_en_service' => '2024-06-15',
                'valeur_acquisition' => 14200000.00,
                'devise' => 'FCFA',
                'date_fin_garantie' => '2029-06-15',
                'fournisseur_garantie' => 'B Medical Systems Direct Support',
                'structure_beneficiaire_id' => $chrKorhogo->id,
                'service_id' => $servKorhogoLabo->id,
                'detenteur_id' => $detSoro->id,
                'date_affectation' => '2024-06-15',
                'emplacement_detaille' => 'Laboratoire d\'Épidémiologie, Local Chaîne du Froid',
                'latitude' => 9.45802000,
                'longitude' => -5.62958000,
                'precision_gps' => 2.80,
                'date_derniere_localisation' => now()->subDays(5),
                'rayon_geofence_metres' => 200,
                'etat' => 'bon_etat',
                'statut_operationnel' => 'en_service',
                'niveau_criticite' => 'eleve',
                'score_risque' => 78,
                'justification_risque' => 'Actif dans une zone septentrionale éloignée, conservation indispensable des réactifs sensibles aux températures élevées.',
                'photo_principale' => 'assets/images/refrigerateur_solaire.jpg',
                'qr_token' => Str::random(32),
                'date_dernier_inventaire' => now()->subDays(25),
                'agent_dernier_inventaire' => 'KRA Yao Sylvain',
                'observations' => 'Panneaux solaires propres, température constante à +4°C.',
            ],
            [
                'code_immobilisation' => 'UCPFM-2024-VEH-0004',
                'code_bailleur' => 'FM-CIV-M-0089',
                'reference_etiquette' => 'TAG-QR-88004',
                'numero_serie' => 'TYT-HLX-4X4-77821',
                'designation' => 'Véhicule Tout-Terrain 4x4 Toyota Hilux Double Cabine de Supervision',
                'categorie_id' => $catVehicule->id,
                'sous_categorie_id' => $sc4x4->id,
                'marque' => 'Toyota',
                'modele' => 'Hilux D-4D Double Cabine 2.8L Diesel',
                'description_technique' => 'Véhicule tout-terrain équipé treuil, arceau de sécurité, balise GPS géolocalisée et caisson isotherme.',
                'bailleur_id' => $fm->id,
                'programme_id' => $pnlp->id,
                'subvention_id' => $subvPnlp->id,
                'fournisseur' => 'CFAO Motors Côte d\'Ivoire',
                'reference_marche' => 'DAO-UCPFM-VEH-2023-005',
                'bon_commande' => 'BC-2024-0012',
                'date_acquisition' => '2024-01-20',
                'date_reception' => '2024-02-15',
                'date_mise_en_service' => '2024-02-20',
                'valeur_acquisition' => 38900000.00,
                'devise' => 'FCFA',
                'date_fin_garantie' => '2027-02-20',
                'fournisseur_garantie' => 'CFAO Motors SAV Abidjan',
                'structure_beneficiaire_id' => $pnlpSiege->id,
                'service_id' => $servPnlpLog->id,
                'detenteur_id' => $detNguessan->id,
                'date_affectation' => '2024-02-20',
                'emplacement_detaille' => 'Parking Direction PNLP, Immatriculation 8945 KH 01',
                'latitude' => 5.34892000,
                'longitude' => -3.99263000,
                'precision_gps' => 1.50,
                'date_derniere_localisation' => now()->subHours(3),
                'rayon_geofence_metres' => 50000, // Véhicule en circulation
                'etat' => 'bon_etat',
                'statut_operationnel' => 'en_service',
                'niveau_criticite' => 'critique',
                'score_risque' => 88,
                'justification_risque' => 'Bien à très forte mobilité, exposé aux risques de vol, substitution de pièces et déplacements non autorisés hors mission.',
                'photo_principale' => 'assets/images/toyota_hilux.jpg',
                'qr_token' => Str::random(32),
                'date_dernier_inventaire' => now()->subDays(10),
                'agent_dernier_inventaire' => 'KRA Yao Sylvain',
                'observations' => 'Vidange effectuée à 25 000 km, carnet de bord à jour.',
            ],
            [
                'code_immobilisation' => 'UCPFM-2023-ENG-0005',
                'code_bailleur' => 'FM-CIV-RSSH-0077',
                'reference_etiquette' => 'TAG-QR-88005',
                'numero_serie' => 'CUMM-100KVA-99014',
                'designation' => 'Groupe Électrogène Insonorisé 100 kVA avec Inverseur Automatique',
                'categorie_id' => $catEnergie->id,
                'sous_categorie_id' => $scGroupe->id,
                'marque' => 'Cummins / FG Wilson',
                'modele' => 'P110-3 Insonorisé',
                'description_technique' => 'Moteur Diesel turbo, alternateur Leroy-Somer, armoire d\'inversion de source automatique avec disjoncteur différentiel.',
                'bailleur_id' => $fm->id,
                'programme_id' => $rssh->id,
                'subvention_id' => $subvRssh->id,
                'fournisseur' => 'SDMO Côte d\'Ivoire',
                'reference_marche' => 'DAO-UCPFM-ENG-2023-008',
                'bon_commande' => 'BC-2023-0889',
                'date_acquisition' => '2023-11-05',
                'date_reception' => '2023-11-25',
                'date_mise_en_service' => '2023-12-01',
                'valeur_acquisition' => 28500000.00,
                'devise' => 'FCFA',
                'date_fin_garantie' => '2025-12-01',
                'fournisseur_garantie' => 'SDMO SAV',
                'structure_beneficiaire_id' => $chrBouake->id,
                'service_id' => $servBouakeLabo->id,
                'detenteur_id' => $detTraore->id,
                'date_affectation' => '2023-12-01',
                'emplacement_detaille' => 'Local Technique Énergie, Bâtiment Central CHR Bouaké',
                'latitude' => 7.69085000,
                'longitude' => -5.03005000,
                'precision_gps' => 3.50,
                'date_derniere_localisation' => now()->subDays(4),
                'rayon_geofence_metres' => 150,
                'etat' => 'bon_etat',
                'statut_operationnel' => 'en_service',
                'niveau_criticite' => 'eleve',
                'score_risque' => 80,
                'justification_risque' => 'Alimentation vitale de secours pour le laboratoire régional et le bloc opératoire en cas de coupure CIE.',
                'photo_principale' => 'assets/images/groupe_electrogene.jpg',
                'qr_token' => Str::random(32),
                'date_dernier_inventaire' => now()->subMonths(3),
                'agent_dernier_inventaire' => 'KRA Yao Sylvain',
                'observations' => 'Compteur horaire : 1 420 heures. Entretien filtre et huile réalisé.',
            ],
            [
                'code_immobilisation' => 'UCPFM-2024-INF-0006',
                'code_bailleur' => 'FM-CIV-H-0450',
                'reference_etiquette' => 'TAG-QR-88006',
                'numero_serie' => 'DELL-LAT-7440-9812A',
                'designation' => 'Ordinateur Portable Professionnel Dell Latitude 7440 Core i7 / 32GB RAM / 1TB SSD',
                'categorie_id' => $catInfo->id,
                'sous_categorie_id' => $scLaptop->id,
                'marque' => 'Dell Technologies',
                'modele' => 'Latitude 7440 Ultrabook',
                'description_technique' => 'Intel Core i7 13ème génération, 32 Go DDR5, SSD NVMe 1 To, Windows 11 Pro, Office 365, Chiffrement BitLocker.',
                'bailleur_id' => $fm->id,
                'programme_id' => $pnls->id,
                'subvention_id' => $subvPnls->id,
                'fournisseur' => 'Microdata CI',
                'reference_marche' => 'DAO-UCPFM-INFO-2024-002',
                'bon_commande' => 'BC-2024-0105',
                'date_acquisition' => '2024-04-05',
                'date_reception' => '2024-04-18',
                'date_mise_en_service' => '2024-04-22',
                'valeur_acquisition' => 1850000.00,
                'devise' => 'FCFA',
                'date_fin_garantie' => '2027-04-22',
                'fournisseur_garantie' => 'Dell ProSupport 3 ans sur site',
                'structure_beneficiaire_id' => $ucpSiege->id,
                'service_id' => $servUcpFin->id,
                'detenteur_id' => null,
                'date_affectation' => '2024-04-22',
                'emplacement_detaille' => 'Bureau Responsable Suivi Financier, 3ème étage CCIA',
                'latitude' => 5.32623000,
                'longitude' => -4.01974000,
                'precision_gps' => 2.00,
                'date_derniere_localisation' => now()->subDays(1),
                'rayon_geofence_metres' => 500,
                'etat' => 'bon_etat',
                'statut_operationnel' => 'en_service',
                'niveau_criticite' => 'modere',
                'score_risque' => 55,
                'justification_risque' => 'Matériel mobile informatique contenant des données financières de subventions.',
                'photo_principale' => 'assets/images/laptop_dell.jpg',
                'qr_token' => Str::random(32),
                'date_dernier_inventaire' => now()->subMonths(1),
                'agent_dernier_inventaire' => 'KRA Yao Sylvain',
                'observations' => 'Poste configuré avec VPN UCP-FM et antivirus entreprise.',
            ],
            [
                'code_immobilisation' => 'UCPFM-2023-MED-0007',
                'code_bailleur' => 'FM-CIV-T-0019',
                'reference_etiquette' => 'TAG-QR-88007',
                'numero_serie' => 'GX04-SN-443100',
                'designation' => 'Système GeneXpert IV (4 modules) pour diagnostic TB/Rifampicine',
                'categorie_id' => $catMed->id,
                'sous_categorie_id' => $scGeneXpert->id,
                'marque' => 'Cepheid',
                'modele' => 'GeneXpert IV',
                'description_technique' => 'Appareil compact 4 modules avec PC portable et lecteur code-barre.',
                'bailleur_id' => $fm->id,
                'programme_id' => $pnlt->id,
                'subvention_id' => $subvPnlt->id,
                'fournisseur' => 'Copharmed CI',
                'reference_marche' => 'DAO-UCPFM-MED-2023-001',
                'bon_commande' => 'BC-2023-0512',
                'date_acquisition' => '2023-06-10',
                'date_reception' => '2023-06-30',
                'date_mise_en_service' => '2023-07-15',
                'valeur_acquisition' => 24500000.00,
                'devise' => 'FCFA',
                'date_fin_garantie' => '2026-07-15',
                'fournisseur_garantie' => 'Cepheid SAV',
                'structure_beneficiaire_id' => $chrMan->id,
                'service_id' => $servManRea->id,
                'detenteur_id' => null,
                'date_affectation' => '2023-07-15',
                'emplacement_detaille' => 'Laboratoire de diagnostic TB, CHR Man',
                'latitude' => 7.41255000,
                'longitude' => -7.55382000,
                'precision_gps' => 5.20,
                'date_derniere_localisation' => now()->subDays(20),
                'rayon_geofence_metres' => 200,
                'etat' => 'panne',
                'statut_operationnel' => 'en_reparation',
                'niveau_criticite' => 'critique',
                'score_risque' => 89,
                'justification_risque' => 'Module optique N°3 défaillant, blocage partiel des tests de charge virale de l\'ouest ivoirien.',
                'photo_principale' => 'assets/images/genexpert_panne.jpg',
                'qr_token' => Str::random(32),
                'date_dernier_inventaire' => now()->subMonths(4),
                'agent_dernier_inventaire' => 'KRA Yao Sylvain',
                'observations' => 'Demande d\'intervention de maintenance transmise au prestataire agréé.',
            ],
            [
                'code_immobilisation' => 'UCPFM-2022-VEH-0008',
                'code_bailleur' => 'FM-CIV-H-0033',
                'reference_etiquette' => 'TAG-QR-88008',
                'numero_serie' => 'TYT-AMB-2022-004',
                'designation' => 'Ambulance Médicalisée Tout-Terrain 4x4 Toyota Land Cruiser',
                'categorie_id' => $catVehicule->id,
                'sous_categorie_id' => $scAmbulance->id,
                'marque' => 'Toyota / WAS Ambulance',
                'modele' => 'Land Cruiser HZJ78 4x4 Médicalisé',
                'description_technique' => 'Équipée brancard à chargement automatique, bouteilles d\'oxygène avec débitmètre, défibrillateur et aspirateur de mucosités.',
                'bailleur_id' => $fm->id,
                'programme_id' => $pnls->id,
                'subvention_id' => $subvPnls->id,
                'fournisseur' => 'CFAO Motors CI',
                'reference_marche' => 'DAO-UCPFM-VEH-2022-002',
                'bon_commande' => 'BC-2022-0774',
                'date_acquisition' => '2022-09-12',
                'date_reception' => '2022-10-05',
                'date_mise_en_service' => '2022-10-15',
                'valeur_acquisition' => 54000000.00,
                'devise' => 'FCFA',
                'date_fin_garantie' => '2025-10-15',
                'fournisseur_garantie' => 'CFAO SAV',
                'structure_beneficiaire_id' => $chrSanPedro->id,
                'service_id' => $servSanPedroUrg->id,
                'detenteur_id' => null,
                'date_affectation' => '2022-10-15',
                'emplacement_detaille' => 'Service Urgences CHR San-Pédro, Immatriculation 5514 JJ 01',
                'latitude' => 4.75704000,
                'longitude' => -6.64324000,
                'precision_gps' => 3.10,
                'date_derniere_localisation' => now()->subHours(6),
                'rayon_geofence_metres' => 80000,
                'etat' => 'bon_etat',
                'statut_operationnel' => 'en_service',
                'niveau_criticite' => 'critique',
                'score_risque' => 90,
                'justification_risque' => 'Véhicule d\'urgence vitale pour les transferts sanitaires de la région côtière du Bas-Sassandra.',
                'photo_principale' => 'assets/images/ambulance.jpg',
                'qr_token' => Str::random(32),
                'date_dernier_inventaire' => now()->subMonths(2),
                'agent_dernier_inventaire' => 'KRA Yao Sylvain',
                'observations' => 'Matériel médical embarqué inspecté et certifié opérationnel.',
            ],
        ];

        $createdActifs = [];
        foreach ($actifsData as $item) {
            $createdActifs[] = Immobilisation::create($item);
        }

        // 10. Mouvements d'historique
        $mvt1 = Mouvement::create([
            'reference_mouvement' => 'MVT-2024-0012',
            'immobilisation_id' => $createdActifs[0]->id,
            'type_mouvement' => 'affectation',
            'statut_workflow' => 'receptionne',
            'structure_origine_id' => $ucpSiege->id,
            'service_origine_id' => $servUcpLog->id,
            'detenteur_origine_id' => $detKone->id,
            'structure_destination_id' => $chuTreichville->id,
            'service_destination_id' => $servTreichLabo->id,
            'detenteur_destination_id' => $detKoffi->id,
            'date_mouvement' => '2024-04-20',
            'motif' => 'Dotation initiale subvention PNLT / GC7 pour renforcement du laboratoire SMIT.',
            'demande_par_user_id' => null,
            'valide_par_user_id' => null,
            'date_validation' => '2024-04-18 10:30:00',
            'date_reception_effective' => '2024-04-20 14:15:00',
            'receptionne_par_user_id' => null,
            'document_justificatif' => 'PV_Reception_UCPFM_2024_0012.pdf',
            'commentaires' => 'Installation et tests concluants par l\'ingénieur biomédical de Cepheid.',
        ]);

        $mvt2 = Mouvement::create([
            'reference_mouvement' => 'MVT-2024-0045',
            'immobilisation_id' => $createdActifs[3]->id,
            'type_mouvement' => 'affectation',
            'statut_workflow' => 'receptionne',
            'structure_origine_id' => $ucpSiege->id,
            'service_origine_id' => $servUcpLog->id,
            'detenteur_origine_id' => $detKone->id,
            'structure_destination_id' => $pnlpSiege->id,
            'service_destination_id' => $servPnlpLog->id,
            'detenteur_destination_id' => $detNguessan->id,
            'date_mouvement' => '2024-02-20',
            'motif' => 'Mise à disposition pour la coordination des missions de supervision de distribution des moustiquaires.',
            'demande_par_user_id' => null,
            'valide_par_user_id' => null,
            'date_validation' => '2024-02-18 09:00:00',
            'date_reception_effective' => '2024-02-20 11:45:00',
            'receptionne_par_user_id' => null,
            'document_justificatif' => 'Fiche_Mise_Disposition_PNLP_0045.pdf',
            'commentaires' => 'Véhicule équipé de balise GPS conforme aux directives de l\'UCP-FM.',
        ]);

        // 11. Maintenance pour l'actif en panne
        Maintenance::create([
            'numero_fiche' => 'MAINT-2026-0003',
            'immobilisation_id' => $createdActifs[6]->id, // GeneXpert IV CHR Man
            'type_maintenance' => 'corrective',
            'statut' => 'en_cours',
            'date_signalement' => now()->subDays(6),
            'date_intervention' => now()->subDays(2),
            'date_fin_intervention' => null,
            'prestataire' => 'Copharmed CI / Cepheid Service Ouest',
            'technicien_intervenant' => 'Ing. KOUADIO Thierry',
            'description_panne' => 'Message d\'erreur "Module Error 5011" sur le module d\'analyse N°3.',
            'diagnostic' => 'Défaillance du bloc optique de fluorescence sur le module 3. Remplacement du module nécessaire.',
            'travaux_effectues' => 'Diagnostic complet et commande de la pièce de rechange auprès du fabricant.',
            'pieces_remplacees' => 'Module d\'analyse optique Xpert 4-Bay (en attente de réception)',
            'cout_intervention' => 3200000.00,
            'duree_immobilisation_jours' => 6,
            'date_prochaine_maintenance' => now()->addMonths(6),
            'rapport_document' => 'Rapport_Diagnostic_Cepheid_Man_0003.pdf',
        ]);

        // 12. Campagnes d'Inventaire
        $campagne2026 = CampagneInventaire::create([
            'code_campagne' => 'INV-NAT-2026',
            'intitule' => 'Campagne Nationale d\'Inventaire Physique & Géolocalisation 2026',
            'type_campagne' => 'annuel',
            'date_debut' => '2026-09-01',
            'date_fin' => '2026-11-30',
            'statut' => 'en_cours',
            'programme_id' => null,
            'region_id' => null,
            'superviseur_id' => null,
            'objectifs' => 'Recensement physique exhaustif de 100% des immobilisations financées par le Fonds mondial sur l\'ensemble des 33 régions sanitaires, étiquetage QR Code et géoréférencement GPS de précision.',
        ]);

        InventaireEquipe::create([
            'campagne_id' => $campagne2026->id,
            'user_id' => null,
            'structure_beneficiaire_id' => $chuTreichville->id,
            'role_equipe' => 'agent_recenseur',
        ]);

        // Relevé d'inventaire
        InventaireReleve::create([
            'campagne_id' => $campagne2026->id,
            'immobilisation_id' => $createdActifs[0]->id,
            'agent_user_id' => null,
            'code_scanne' => 'TAG-QR-88001',
            'presence_constatee' => true,
            'etat_constate' => 'bon_etat',
            'structure_constatee_id' => $chuTreichville->id,
            'service_constate_id' => $servTreichLabo->id,
            'detenteur_constate_id' => $detKoffi->id,
            'emplacement_constate' => 'Bâtiment SMIT, Salle Blanche Diagnostic Moléculaire N°2',
            'latitude' => 5.30564500,
            'longitude' => -4.00418200,
            'precision_gps' => 2.90,
            'date_heure_scan' => now()->subDays(12),
            'photo_constatee' => 'inventaire/scan_genexpert_001.jpg',
            'observations' => 'Scan QR Code réussi, numéro de série physique vérifié et conforme à la plaque d\'origine.',
            'statut_rapprochement' => 'conforme',
            'analyse_ecart' => 'Aucun écart détecté. Données physiques en parfaite concordance avec le registre patrimonial.',
            'decision_regularisation' => 'approuve',
            'valide_par_user_id' => null,
            'date_validation_rapprochement' => now()->subDays(10),
        ]);

        // 13. Alertes actives
        Alerte::create([
            'immobilisation_id' => $createdActifs[6]->id,
            'type_alerte' => 'actif_hors_service',
            'niveau_severite' => 'danger',
            'titre' => 'Panne bloquante sur actif critique : GeneXpert IV (CHR Man)',
            'message' => 'L\'appareil GeneXpert IV (UCPFM-2023-MED-0007) est immobilisé pour défaillance de module au CHR de Man. Diagnostic en cours.',
            'lue' => false,
            'date_echeance' => now()->addDays(3),
        ]);

        Alerte::create([
            'immobilisation_id' => $createdActifs[4]->id,
            'type_alerte' => 'garantie_expiration',
            'niveau_severite' => 'warning',
            'titre' => 'Échéance de garantie : Groupe Électrogène 100 kVA (CHR Bouaké)',
            'message' => 'La garantie constructeur du groupe électrogène (UCPFM-2023-ENG-0005) expire dans moins de 60 jours. Prévoir le contrat de maintenance préventive.',
            'lue' => false,
            'date_echeance' => now()->addDays(45),
        ]);

        Alerte::create([
            'immobilisation_id' => null,
            'type_alerte' => 'actif_non_inventorie',
            'niveau_severite' => 'info',
            'titre' => 'Campagne Nationale d\'Inventaire 2026 en cours',
            'message' => '12 structures sanitaires sur 33 restent à inspecter pour finaliser le cycle trimestriel.',
            'lue' => false,
            'date_echeance' => now()->addDays(20),
        ]);

        // 14. Piste d'audit initiale
        AuditLog::create([
            'user_id' => null,
            'user_name' => 'Système (Initialisation Référentiel)',
            'action' => 'INITIALISATION_REFERENTIEL',
            'module' => 'IMMOBILISATIONS',
            'auditable_type' => Immobilisation::class,
            'auditable_id' => $createdActifs[0]->id,
            'code_reference' => 'UCPFM-2024-MED-0001',
            'donnees_anciennes' => null,
            'donnees_nouvelles' => ['statut' => 'en_service', 'valeur' => 78500000],
            'ip_address' => '127.0.0.1',
            'user_agent' => 'UCPFM-System/Seed',
            'created_at' => now()->subDays(15),
        ]);
    }
}
