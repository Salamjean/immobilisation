<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sous_categories_actifs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('categorie_id')->constrained('categories_actifs')->cascadeOnDelete();
            $table->string('code', 50);
            $table->string('nom');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sous_categories_actifs');
    }
};
