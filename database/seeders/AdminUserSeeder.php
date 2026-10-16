<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'KOUADIO Ange (Admin)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_ADMIN,
                'telephone' => '+225 07 07 11 22 33',
                'actif' => true,
            ]
        );
    }
}
