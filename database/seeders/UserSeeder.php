<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            GestionnaireUserSeeder::class,
            ResponsableLogistiqueUserSeeder::class,
            ProgrammeUserSeeder::class,
            AgentInventaireUserSeeder::class,
            ControleurUserSeeder::class,
            AuditeurUserSeeder::class,
            ConsultantUserSeeder::class,
            LecteurUserSeeder::class,
        ]);
    }
}
