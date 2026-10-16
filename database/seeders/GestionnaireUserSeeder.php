<?php

namespace Database\Seeders;

use App\Models\StructureBeneficiaire;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class GestionnaireUserSeeder extends Seeder
{
    public function run(): void
    {
        $structure = StructureBeneficiaire::where('code', 'UCP-SIEGE')->first();

        User::updateOrCreate(
            ['email' => 'gestionnaire@gmail.com'],
            [
                'name' => 'KONE Mamadou (Gestionnaire)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_GESTIONNAIRE,
                'structure_beneficiaire_id' => $structure?->id,
                'telephone' => '+225 05 44 55 66 77',
                'actif' => true,
            ]
        );
    }
}
