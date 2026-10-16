<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('inventaire_equipes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('campagne_id')->constrained('campagnes_inventaire')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('structure_beneficiaire_id')->nullable()->constrained('structures_beneficiaires')->nullOnDelete();
            $table->string('role_equipe', 50)->default('agent_recenseur');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('inventaire_equipes');
    }
};
