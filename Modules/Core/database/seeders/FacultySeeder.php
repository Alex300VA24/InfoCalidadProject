<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\Faculty;
use Illuminate\Database\Seeder;

class FacultySeeder extends Seeder
{
    public function run(): void
    {
        Faculty::updateOrCreate(
            ['code' => 'FIA'],
            [
                'name' => 'Ingeniería Informática',
                'description' => 'Facultad de Ingeniería Informática',
                'is_active' => true,
            ]
        );
    }
}
