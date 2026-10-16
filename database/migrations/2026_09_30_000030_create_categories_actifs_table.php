<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('categories_actifs', function (Blueprint $table) {
            $table->id();
            $table->string('code', 50)->unique();
            $table->string('nom');
            $table->text('description')->nullable();
            $table->integer('duree_vie_standard_mois')->default(60);
            $table->string('icone', 50)->default('fas fa-box');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories_actifs');
    }
};
