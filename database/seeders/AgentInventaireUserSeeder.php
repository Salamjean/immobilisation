<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AgentInventaireUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'agent.terrain@gmail.com'],
            [
                'name' => 'TRAORE Fanta (Recenseur Abidjan)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_AGENT_INVENTAIRE,
                'telephone' => '+225 01 22 33 44 55',
                'actif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'agent.interieur@gmail.com'],
            [
                'name' => 'COULIBALY Sekou (Recenseur Bouaké & Nord)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_AGENT_INVENTAIRE,
                'telephone' => '+225 05 11 22 33 44',
                'actif' => true,
            ]
        );
    }
}
