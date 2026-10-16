<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LecteurUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'lecteur@gmail.com'],
            [
                'name' => 'Agent Ministère Santé (Lecteur Simple)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_LECTEUR,
                'telephone' => '+225 05 99 00 11 22',
                'actif' => true,
            ]
        );
    }
}
