<?php

namespace Modules\ResultadosFormacion\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;
use Modules\ResultadosFormacion\Models\Certificate;
use Modules\ResultadosFormacion\Models\DegreeApplication;
use Modules\ResultadosFormacion\Models\DegreeCommitteeAct;
use Modules\ResultadosFormacion\Models\Graduate;
use Modules\ResultadosFormacion\Models\GraduateSurvey;

class ResultadosFormacionDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::orderBy('id')->limit(3)->get();
        $docente = User::whereHas('role', fn ($q) => $q->where('slug', 'docente'))->first();
        $unidad = User::whereHas('role', fn ($q) => $q->where('slug', 'unidad_grados_titulos'))->first();

        if ($students->isEmpty()) {
            return;
        }

        foreach ($students as $index => $student) {
            $graduate = Graduate::updateOrCreate(
                ['student_id' => $student->id],
                [
                    'graduation_date' => now()->subMonths(6 - $index),
                    'work_status' => ['empleado', 'empleado', 'estudiando'][$index],
                    'employer' => $index < 2 ? 'Empresa Tecnológica SAC' : null,
                    'job_position' => $index < 2 ? 'Analista de sistemas' : null,
                    'monthly_income' => $index < 2 ? 3200 : 0,
                    'survey_date' => now()->subMonth(),
                    'employment_relationship' => $index < 2 ? 'dependiente' : null,
                ]
            );

            GraduateSurvey::updateOrCreate(
                ['graduate_id' => $graduate->id, 'period' => '2026-I'],
                [
                    'survey_date' => now()->subMonth(),
                    'employed' => $index < 2,
                    'job_related_to_career' => $index < 2,
                    'competency_level_score' => [17, 16, 13][$index],
                    'income' => $index < 2 ? 3200 : 0,
                    'observations' => $index < 2 ? 'Desempeño acorde al perfil de egreso.' : null,
                ]
            );

            $code = 'EXP-2026-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT);
            $application = DegreeApplication::updateOrCreate(
                ['code' => $code],
                [
                    'student_id' => $student->id,
                    'type' => $index === 0 ? 'titulo_ingeniero' : 'bachiller',
                    'thesis_title' => $index === 0 ? 'Sistema web para la gestión académica' : null,
                    'advisor_id' => $docente?->id,
                    'application_date' => now()->subMonths(3),
                    'resolution_date' => $index < 2 ? now()->subWeeks(2) : null,
                    'resolution_number' => $index < 2 ? 'R-2026-'.str_pad((string) ($index + 1), 3, '0', STR_PAD_LEFT) : null,
                    'status' => ['aprobado', 'en_tramite', 'en_tramite'][$index],
                ]
            );

            if ($index === 0) {
                DegreeCommitteeAct::updateOrCreate(
                    ['degree_application_id' => $application->id, 'act_type' => 'sustentacion'],
                    [
                        'session_date' => now()->subWeeks(3),
                        'result' => 'aprobado',
                        'score' => 17.5,
                        'pdf_path' => 'actas_comite/'.$application->code.'.pdf',
                    ]
                );
            }

            Certificate::updateOrCreate(
                ['code' => 'CER-2026-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'student_id' => $student->id,
                    'type' => $index === 0 ? 'constancia_egresado' : 'estudios',
                    'concept' => $index === 0 ? 'Constancia de egresado' : 'Certificado de estudios del ciclo en curso',
                    'issued_at' => now()->subWeeks(1),
                    'issued_by' => $unidad?->name ?? 'Unidad de Grados y Títulos',
                    'status' => 'emitido',
                ]
            );
        }
    }
}
