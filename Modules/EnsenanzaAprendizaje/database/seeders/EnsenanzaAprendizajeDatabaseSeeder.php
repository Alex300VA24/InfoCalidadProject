<?php

namespace Modules\EnsenanzaAprendizaje\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Models\AcademicTutoring;
use Modules\EnsenanzaAprendizaje\Models\Agreement;
use Modules\EnsenanzaAprendizaje\Models\MobilityApplication;
use Modules\EnsenanzaAprendizaje\Models\OfficialAct;
use Modules\EnsenanzaAprendizaje\Models\RemedialProgram;
use Modules\EnsenanzaAprendizaje\Models\SubjectExecution;
use Modules\EnsenanzaAprendizaje\Models\SyllabusSocialization;
use Modules\EnsenanzaAprendizaje\Models\TeacherPerformanceEvaluation;
use Modules\EnsenanzaAprendizaje\Models\TeachingLoad;
use Modules\GestionCurricular\Models\Syllabus;

class EnsenanzaAprendizajeDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $period = AcademicPeriod::where('is_active', true)->first();
        $career = Career::where('code', 'ING-SIS')->first();
        $docentes = User::whereHas('role', fn ($q) => $q->where('slug', 'docente'))->get();

        if (! $period || ! $career || $docentes->isEmpty()) {
            return;
        }

        $docente1 = $docentes->first();
        $docente2 = $docentes->last();
        $tutor = User::whereHas('role', fn ($q) => $q->where('slug', 'tutor_academico'))->first();

        $subjects = Subject::where('career_id', $career->id)->get();

        foreach ($subjects as $index => $subject) {
            $teacher = $index % 2 === 0 ? $docente1 : $docente2;

            $syllabus = Syllabus::updateOrCreate(
                ['subject_id' => $subject->id, 'academic_period_id' => $period->id, 'version' => '1.0'],
                [
                    'career_id' => $career->id,
                    'teacher_id' => $teacher->id,
                    'filename' => 'syllabus_'.$subject->code.'.pdf',
                    'file_path' => 'syllabi/'.$subject->code.'.pdf',
                    'file_size' => rand(180, 420) * 1024,
                    'mime_type' => 'application/pdf',
                    'is_visado' => true,
                    'visado_at' => now()->subWeeks(3),
                ]
            );

            TeachingLoad::updateOrCreate(
                ['teacher_id' => $teacher->id, 'subject_id' => $subject->id, 'academic_period_id' => $period->id],
                [
                    'section' => chr(65 + ($index % 3)),
                    'hours' => $subject->hours,
                    'status' => $index % 4 === 0 ? 'confirmado' : 'asignado',
                ]
            );

            SyllabusSocialization::updateOrCreate(
                ['syllabus_id' => $syllabus->id],
                [
                    'date' => now()->subWeeks(2),
                    'evidence_path' => 'socializaciones/'.$subject->code.'.pdf',
                    'notes' => 'Socialización del sílabo al inicio del semestre.',
                    'registered_by' => $docente1->id,
                ]
            );

            SubjectExecution::updateOrCreate(
                ['subject_id' => $subject->id, 'academic_period_id' => $period->id, 'teacher_id' => $teacher->id],
                [
                    'syllabus_id' => $syllabus->id,
                    'progress_pct' => min(rand(30, 90), 100),
                    'status' => 'en_ejecucion',
                ]
            );

            TeacherPerformanceEvaluation::updateOrCreate(
                ['teacher_id' => $teacher->id, 'academic_period_id' => $period->id],
                [
                    'score' => rand(14, 19),
                    'source' => 'encuesta_estudiante',
                    'observations' => 'Evaluación docente del periodo vigente.',
                    'evaluated_at' => now()->subDays(5),
                ]
            );

            if ($index % 2 === 0) {
                OfficialAct::updateOrCreate(
                    ['subject_id' => $subject->id, 'academic_period_id' => $period->id, 'teacher_id' => $teacher->id],
                    [
                        'status' => 'borrador',
                        'pdf_path' => 'actas/'.$subject->code.'.pdf',
                    ]
                );
            }
        }

        $agreement1 = Agreement::updateOrCreate(
            ['name' => 'Convenio con Universidad Nacional de Trujillo'],
            [
                'institution' => 'Universidad Nacional de Trujillo',
                'type' => 'nacional',
                'description' => 'Movilidad estudiantil y docente entre facultades de ingeniería.',
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addYears(1),
                'status' => 'vigente',
            ]
        );

        $agreement2 = Agreement::updateOrCreate(
            ['name' => 'Convenio con Universidad Politécnica de Valencia'],
            [
                'institution' => 'Universidad Politécnica de Valencia',
                'type' => 'internacional',
                'description' => 'Programa de intercambio internacional y becas para estudiantes.',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addYears(2),
                'status' => 'vigente',
            ]
        );

        $students = Student::orderBy('id')->limit(3)->get();

        foreach ($students as $index => $student) {
            AcademicTutoring::updateOrCreate(
                ['student_id' => $student->id, 'academic_period_id' => $period->id, 'tutoring_date' => now()->subDays(10)],
                [
                    'tutor_id' => $tutor?->id,
                    'type' => $index === 0 ? 'acompanamiento' : 'nivelacion',
                    'reason' => 'Refuerzo académico del ciclo en curso.',
                    'outcome' => 'Avance favorable en las asignaturas observadas.',
                    'status' => $index === 0 ? 'completada' : 'pendiente',
                ]
            );

            RemedialProgram::updateOrCreate(
                ['student_id' => $student->id, 'academic_period_id' => $period->id],
                [
                    'subject_id' => $subjects->get($index)->id,
                    'description' => 'Programa de nivelación de competencias básicas.',
                    'status' => $index === 0 ? 'completado' : 'pendiente',
                ]
            );

            MobilityApplication::updateOrCreate(
                ['student_id' => $student->id, 'academic_period_id' => $period->id, 'type' => 'movilidad_internacional'],
                [
                    'destination_institution' => $agreement2->institution,
                    'program_name' => 'Intercambio semestral de ingeniería',
                    'scholarship_name' => $index === 0 ? 'Beca UNT' : null,
                    'agreement_id' => $index === 0 ? $agreement2->id : $agreement1->id,
                    'application_date' => now()->subWeeks(2),
                    'start_date' => now()->addMonths(2),
                    'end_date' => now()->addMonths(6),
                    'status' => $index === 0 ? 'aprobada' : 'en_evaluacion',
                    'notes' => $index === 0 ? 'Postulación priorizada por rendimiento académico.' : null,
                ]
            );
        }
    }
}
