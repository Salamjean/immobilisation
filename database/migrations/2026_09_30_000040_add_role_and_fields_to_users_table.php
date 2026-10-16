<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role', 50)->default('gestionnaire')->after('email');
            $table->foreignId('structure_beneficiaire_id')->nullable()->after('role')->constrained('structures_beneficiaires')->nullOnDelete();
            $table->foreignId('programme_id')->nullable()->after('structure_beneficiaire_id')->constrained('programmes')->nullOnDelete();
            $table->string('telephone', 50)->nullable()->after('programme_id');
            $table->boolean('actif')->default(true)->after('telephone');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['structure_beneficiaire_id']);
            $table->dropForeign(['programme_id']);
            $table->dropColumn(['role', 'structure_beneficiaire_id', 'programme_id', 'telephone', 'actif']);
        });
    }
};
