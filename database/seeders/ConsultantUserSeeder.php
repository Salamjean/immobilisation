<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ConsultantUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'consultant@gmail.com'],
            [
                'name' => 'Expert Patrimonial (Consultant TDR)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_CONSULTANT,
                'telephone' => '+225 07 99 88 77 66',
                'actif' => true,
            ]
        );
    }
}
