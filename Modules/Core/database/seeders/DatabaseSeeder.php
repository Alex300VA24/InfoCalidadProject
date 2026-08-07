<?php

namespace Modules\Core\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\EnsenanzaAprendizaje\Database\Seeders\EnsenanzaAprendizajeDatabaseSeeder;
use Modules\GestionIngreso\Database\Seeders\GestionIngresoDatabaseSeeder;
use Modules\ResultadosFormacion\Database\Seeders\ResultadosFormacionDatabaseSeeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            FacultySeeder::class,
            CareerSeeder::class,
            AcademicPeriodSeeder::class,
            RoleSeeder::class,
            UserSeeder::class,
            StudentSeeder::class,
            SubjectSeeder::class,
            GestionIngresoDatabaseSeeder::class,
            EnsenanzaAprendizajeDatabaseSeeder::class,
            ResultadosFormacionDatabaseSeeder::class,
        ]);
    }
}
