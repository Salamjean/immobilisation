<?php

namespace Database\Seeders;

use App\Models\Programme;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProgrammeUserSeeder extends Seeder
{
    public function run(): void
    {
        $progPnlp = Programme::where('code', 'PNLP')->first();
        $progPnt = Programme::where('code', 'PNLT')->first();

        User::updateOrCreate(
            ['email' => 'programme.pnlp@gmail.com'],
            [
                'name' => 'Dr. ASSI Jean-Baptiste (Prog. Paludisme)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_UTILISATEUR_PROGRAMME,
                'programme_id' => $progPnlp?->id,
                'telephone' => '+225 07 88 99 00 11',
                'actif' => true,
            ]
        );

        User::updateOrCreate(
            ['email' => 'programme.tuberculose@gmail.com'],
            [
                'name' => 'Dr. BROU Estelle (Prog. Tuberculose)',
                'password' => Hash::make('azertyui'),
                'role' => User::ROLE_UTILISATEUR_PROGRAMME,
                'programme_id' => $progPnt?->id,
                'telephone' => '+225 07 22 33 44 55',
                'actif' => true,
            ]
        );
    }
}
