<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('detenteurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_beneficiaire_id')->constrained('structures_beneficiaires')->cascadeOnDelete();
            $table->foreignId('service_id')->nullable()->constrained('services_structures')->nullOnDelete();
            $table->string('matricule', 50)->nullable();
            $table->string('nom');
            $table->string('prenom');
            $table->string('fonction')->nullable();
            $table->string('telephone')->nullable();
            $table->string('email')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('detenteurs');
    }
};
