<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaire_releves', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes_inventaire')->cascadeOnDelete();
            $table->foreignId('immobilisation_id')->nullable()->constrained('immobilisations')->nullOnDelete();
            $table->foreignId('agent_user_id')->nullable()->constrained('users')->nullOnDelete();

            // Identifiant scanné ou relevé
            $table->string('code_scanne', 150)->nullable();
            $table->boolean('presence_constatee')->default(true);
            $table->string('etat_constate', 50);
            
            // Localisation constatée
            $table->foreignId('structure_constatee_id')->nullable()->constrained('structures_beneficiaires')->nullOnDelete();
            $table->foreignId('service_constate_id')->nullable()->constrained('services_structures')->nullOnDelete();
            $table->foreignId('detenteur_constate_id')->nullable()->constrained('detenteurs')->nullOnDelete();
            $table->string('emplacement_constate')->nullable();

            // Coordonnées GPS réelles capturées
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->decimal('precision_gps', 8, 2)->nullable();
            $table->timestamp('date_heure_scan')->nullable();

            // Photos & Médias constatés
            $table->string('photo_constatee')->nullable();
            $table->text('observations')->nullable();

            // Rapprochement & Analyse d'écart
            $table->string('statut_rapprochement', 50)->default('conforme');
            $table->text('analyse_ecart')->nullable();
            $table->string('decision_regularisation', 100)->nullable();
            $table->foreignId('valide_par_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation_rapprochement')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaire_releves');
    }
};
