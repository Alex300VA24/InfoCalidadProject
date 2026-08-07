<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        foreach ($this->schemas() as $schema) {
            DB::statement("CREATE SCHEMA IF NOT EXISTS \"{$schema}\"");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        foreach (array_reverse($this->schemas()) as $schema) {
            DB::statement("DROP SCHEMA IF EXISTS \"{$schema}\" CASCADE");
        }
    }

    private function schemas(): array
    {
        return [
            'core',
            'app_gestion_curricular',
            'app_gestion_ingreso',
            'app_ensenanza_aprendizaje',
            'app_resultados_formacion',
        ];
    }
};
