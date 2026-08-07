<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\Career;
use Modules\Core\Models\Subject;
use Illuminate\Database\Seeder;

class SubjectSeeder extends Seeder
{
    public function run(): void
    {
        $careerId = Career::where('code', 'ING-SIS')->value('id');

        $subjects = [
            ['code' => 'INF-101', 'name' => 'Algoritmos y Programación', 'credits' => 4, 'hours' => 64, 'type' => 'Obligatorio'],
            ['code' => 'INF-102', 'name' => 'Complejidad de Algoritmos', 'credits' => 4, 'hours' => 64, 'type' => 'Obligatorio'],
            ['code' => 'INF-201', 'name' => 'Inteligencia Artificial', 'credits' => 4, 'hours' => 64, 'type' => 'Obligatorio'],
            ['code' => 'INF-202', 'name' => 'Técnicas Digitales', 'credits' => 3, 'hours' => 48, 'type' => 'Obligatorio'],
            ['code' => 'INF-301', 'name' => 'Redes de Computadoras', 'credits' => 3, 'hours' => 48, 'type' => 'Obligatorio'],
        ];

        foreach ($subjects as $data) {
            Subject::updateOrCreate(
                ['code' => $data['code']],
                ['career_id' => $careerId] + $data
            );
        }
    }
}
