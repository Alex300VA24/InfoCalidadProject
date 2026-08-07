<?php

namespace Modules\Core\Database\Seeders;

use Modules\Core\Models\AcademicPeriod;
use Illuminate\Database\Seeder;

class AcademicPeriodSeeder extends Seeder
{
    public function run(): void
    {
        $periods = [
            ['name' => '2025-I', 'start_date' => '2025-03-01', 'end_date' => '2025-07-31', 'is_active' => false],
            ['name' => '2025-II', 'start_date' => '2025-08-01', 'end_date' => '2025-12-31', 'is_active' => false],
            ['name' => '2026-I', 'start_date' => '2026-03-01', 'end_date' => '2026-07-31', 'is_active' => true],
        ];

        foreach ($periods as $data) {
            AcademicPeriod::updateOrCreate(['name' => $data['name']], $data);
        }
    }
}
