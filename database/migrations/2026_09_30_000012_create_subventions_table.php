<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('subventions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('programme_id')->constrained('programmes')->cascadeOnDelete();
            $table->foreignId('bailleur_id')->constrained('bailleurs')->cascadeOnDelete();
            $table->string('code_subvention', 100)->unique();
            $table->string('intitule');
            $table->date('date_debut')->nullable();
            $table->date('date_fin')->nullable();
            $table->decimal('montant_alloue', 18, 2)->default(0);
            $table->string('devise', 10)->default('FCFA');
            $table->string('statut', 50)->default('en_cours');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('subventions');
    }
};
