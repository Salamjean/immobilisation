<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('maintenances', function (Blueprint $table) {
            $table->id();
            $table->string('numero_fiche', 100)->unique();
            $table->foreignId('immobilisation_id')->constrained('immobilisations')->cascadeOnDelete();
            $table->string('type_maintenance', 50);
            $table->string('statut', 50)->default('signalee');
            $table->date('date_signalement');
            $table->date('date_intervention')->nullable();
            $table->date('date_fin_intervention')->nullable();
            $table->string('prestataire')->nullable();
            $table->string('technicien_intervenant')->nullable();
            $table->text('description_panne')->nullable();
            $table->text('diagnostic')->nullable();
            $table->text('travaux_effectues')->nullable();
            $table->text('pieces_remplacees')->nullable();
            $table->decimal('cout_intervention', 18, 2)->default(0);
            $table->integer('duree_immobilisation_jours')->default(0);
            $table->date('date_prochaine_maintenance')->nullable();
            $table->string('rapport_document')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('maintenances');
    }
};
