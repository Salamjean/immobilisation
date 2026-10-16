<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campagnes_inventaire', function (Blueprint $table) {
            $table->id();
            $table->string('code_campagne', 100)->unique();
            $table->string('intitule');
            $table->string('type_campagne', 50)->default('annuel');
            $table->date('date_debut');
            $table->date('date_fin');
            $table->string('statut', 50)->default('planifiee');
            $table->foreignId('programme_id')->nullable()->constrained('programmes')->nullOnDelete();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('superviseur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('objectifs')->nullable();
            $table->timestamp('date_cloture')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campagnes_inventaire');
    }
};
