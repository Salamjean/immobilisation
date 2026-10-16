<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('immobilisations', function (Blueprint $table) {
            $table->id();
            
            // Identifiants
            $table->string('code_immobilisation', 100)->unique()->index();
            $table->string('code_bailleur', 100)->nullable()->index();
            $table->string('reference_etiquette', 100)->nullable()->index();
            $table->string('numero_serie', 150)->nullable()->index();
            $table->string('designation');
            
            // Classification
            $table->foreignId('categorie_id')->constrained('categories_actifs')->restrictOnDelete();
            $table->foreignId('sous_categorie_id')->nullable()->constrained('sous_categories_actifs')->nullOnDelete();
            $table->string('marque')->nullable();
            $table->string('modele')->nullable();
            $table->text('description_technique')->nullable();

            // Acquisition & Financement
            $table->foreignId('bailleur_id')->nullable()->constrained('bailleurs')->nullOnDelete();
            $table->foreignId('programme_id')->nullable()->constrained('programmes')->nullOnDelete();
            $table->foreignId('subvention_id')->nullable()->constrained('subventions')->nullOnDelete();
            $table->string('fournisseur')->nullable();
            $table->string('reference_marche')->nullable();
            $table->string('bon_commande')->nullable();
            $table->date('date_acquisition')->nullable();
            $table->date('date_reception')->nullable();
            $table->date('date_mise_en_service')->nullable();
            $table->decimal('valeur_acquisition', 18, 2)->default(0);
            $table->string('devise', 10)->default('FCFA');
            $table->date('date_fin_garantie')->nullable();
            $table->string('fournisseur_garantie')->nullable();

            // Affectation actuelle
            $table->foreignId('structure_beneficiaire_id')->constrained('structures_beneficiaires')->restrictOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services_structures')->nullOnDelete();
            $table->foreignId('detenteur_id')->nullable()->constrained('detenteurs')->nullOnDelete();
            $table->date('date_affectation')->nullable();
            $table->string('emplacement_detaille')->nullable();

            // Localisation Géographique
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('precision_gps', 8, 2)->nullable();
            $table->timestamp('date_derniere_localisation')->nullable();
            $table->integer('rayon_geofence_metres')->default(500);

            // États & Statuts
            $table->string('etat', 50)->default('bon_etat')->index();
            $table->string('statut_operationnel', 50)->default('en_service')->index();
            
            // Risque & Criticité
            $table->string('niveau_criticite', 50)->default('modere')->index();
            $table->integer('score_risque')->default(50);
            $table->text('justification_risque')->nullable();

            // Médias & Médias
            $table->string('photo_principale')->nullable();
            $table->string('qr_token', 64)->unique()->nullable();

            // Métadonnées d'inventaire
            $table->timestamp('date_dernier_inventaire')->nullable();
            $table->string('agent_dernier_inventaire')->nullable();
            $table->text('observations')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('immobilisations');
    }
};
