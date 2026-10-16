<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('asset_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('immobilisation_id')->constrained('immobilisations')->cascadeOnDelete();
            $table->string('type_document', 50);
            $table->string('titre');
            $table->string('fichier_path');
            $table->string('taille_fichier', 50)->nullable();
            $table->string('extension', 20)->nullable();
            $table->foreignId('uploaded_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('asset_documents');
    }
};
