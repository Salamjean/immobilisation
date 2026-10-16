<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('programmes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bailleur_id')->nullable()->constrained('bailleurs')->nullOnDelete();
            $table->string('code', 50)->unique(); // Ex: PNLP, PNLS, PNLT, RSSH
            $table->string('nom');
            $table->text('description')->nullable();
            $table->string('coordonnateur')->nullable();
            $table->boolean('actif')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('programmes');
    }
};
