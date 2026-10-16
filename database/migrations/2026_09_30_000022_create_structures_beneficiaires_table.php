<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('structures_beneficiaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();
            $table->foreignId('district_sanitaire_id')->nullable()->constrained('districts_sanitaires')->nullOnDelete();
            $table->string('code', 50)->unique();
            $table->string('nom');
            $table->string('type_structure', 100);
            $table->string('ville')->nullable();
            $table->string('commune')->nullable();
            $table->string('adresse')->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('responsable_nom')->nullable();
            $table->string('responsable_contact')->nullable();
            $table->string('responsable_email')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('structures_beneficiaires');
    }
};
