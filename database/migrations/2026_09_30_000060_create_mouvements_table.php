<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mouvements', function (Blueprint $table) {
            $table->id();
            $table->string('reference_mouvement', 100)->unique();
            $table->foreignId('immobilisation_id')->constrained('immobilisations')->cascadeOnDelete();
            
            // Type de mouvement
            $table->string('type_mouvement', 50);
            $table->string('statut_workflow', 50)->default('brouillon');

            // Origine
            $table->foreignId('structure_origine_id')->nullable()->constrained('structures_beneficiaires')->nullOnDelete();
            $table->foreignId('service_origine_id')->nullable()->constrained('services_structures')->nullOnDelete();
            $table->foreignId('detenteur_origine_id')->nullable()->constrained('detenteurs')->nullOnDelete();

            // Destination
            $table->foreignId('structure_destination_id')->nullable()->constrained('structures_beneficiaires')->nullOnDelete();
            $table->foreignId('service_destination_id')->nullable()->constrained('services_structures')->nullOnDelete();
            $table->foreignId('detenteur_destination_id')->nullable()->constrained('detenteurs')->nullOnDelete();

            // Données temporelles & acteurs
            $table->date('date_mouvement');
            $table->text('motif')->nullable();
            $table->foreignId('demande_par_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('valide_par_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('date_validation')->nullable();
            $table->timestamp('date_reception_effective')->nullable();
            $table->foreignId('receptionne_par_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('document_justificatif')->nullable();
            $table->text('commentaires')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mouvements');
    }
};
