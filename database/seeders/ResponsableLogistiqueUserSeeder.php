<?php

namespace Database\Seeders;

use App\Models\StructureBeneficiaire;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ResponsableLogistiqueUserSeeder extends Seeder
{
    public function run(): void
    {
        $structure = StructureBeneficiaire::where('code', 'UCP-SIEGE')->first();

        User::updateOrCreate(
            ['email' => 'logistique@gmail.com'],
            [
                'name' => 'BAMBA Siaka (Logistique & Matériel)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_RESPONSABLE_LOGISTIQUE,
                'structure_beneficiaire_id' => $structure?->id,
                'telephone' => '+225 01 02 03 04 05',
                'actif' => true,
            ]
        );
    }
}
