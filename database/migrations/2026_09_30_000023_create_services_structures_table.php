<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services_structures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('structure_beneficiaire_id')->constrained('structures_beneficiaires')->cascadeOnDelete();
            $table->string('code', 50)->nullable();
            $table->string('nom');
            $table->string('responsable')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services_structures');
    }
};
