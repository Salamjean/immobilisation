<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alertes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immobilisation_id')->nullable()->constrained('immobilisations')->cascadeOnDelete();
            $table->string('type_alerte', 50);
            $table->string('niveau_severite', 20)->default('warning');
            $table->string('titre');
            $table->text('message');
            $table->boolean('lue')->default(false);
            $table->timestamp('date_echeance')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alertes');
    }
};
