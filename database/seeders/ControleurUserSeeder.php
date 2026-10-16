<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ControleurUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'controleur@gmail.com'],
            [
                'name' => 'N\'GUESSAN Yao (Contrôleur Interne)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_CONTROLEUR,
                'telephone' => '+225 07 44 33 22 11',
                'actif' => true,
            ]
        );
    }
}
