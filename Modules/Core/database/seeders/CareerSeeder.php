<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\Career;
use Modules\Core\Models\Faculty;
use Illuminate\Database\Seeder;

class CareerSeeder extends Seeder
{
    public function run(): void
    {
        $fia = Faculty::where('code', 'FIA')->first();

        $careers = [
            ['code' => 'ING-SIS', 'name' => 'Ingeniería de Sistemas', 'description' => 'Carrera profesional de Ingeniería de Sistemas - Pregrado'],
            ['code' => 'ING-CIV', 'name' => 'Ingeniería Civil', 'description' => 'Carrera profesional de Ingeniería Civil - Pregrado'],
            ['code' => 'ADM-EMP', 'name' => 'Administración de Empresas', 'description' => 'Carrera profesional de Administración de Empresas - Pregrado'],
            ['code' => 'SEG-ESP', 'name' => 'Segunda Especialidad', 'description' => 'Programa de Segunda Especialidad'],
        ];

        foreach ($careers as $data) {
            Career::updateOrCreate(
                ['code' => $data['code']],
                [
                    'faculty_id' => $fia?->id,
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'is_active' => true,
                ]
            );
        }
    }
}
