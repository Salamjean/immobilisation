<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AuditeurUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'auditeur@gmail.com'],
            [
                'name' => 'Cabinet PwC / KPMG (Auditeur Externe)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_AUDITEUR,
                'telephone' => '+225 27 20 30 40 50',
                'actif' => true,
            ]
        );
    }
}
