<?php

namespace Modules\GestionIngreso\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\GestionIngreso\Models\AdmissionProcess;
use Modules\GestionIngreso\Models\Applicant;
use Modules\GestionIngreso\Models\Enrollment;
use Modules\GestionIngreso\Models\EnrollmentSubject;
use Modules\GestionIngreso\Models\PaymentOrder;

class GestionIngresoDatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $period = AcademicPeriod::where('is_active', true)->first();
        $career = Career::where('code', 'ING-SIS')->first();

        if (! $period || ! $career) {
            return;
        }

        $process = AdmissionProcess::updateOrCreate(
            ['name' => 'Examen de Admisión 2026-I'],
            [
                'academic_period_id' => $period->id,
                'career_id' => $career->id,
                'vacancies' => 20,
                'modality' => 'ordinaria',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->subMonth(),
                'status' => 'cerrado',
            ]
        );

        $applicants = [
            ['dni' => '70123456', 'paterno' => 'Quispe', 'materno' => 'Huamán', 'nombres' => 'Ana', 'score' => 18.5, 'status' => 'ingresante'],
            ['dni' => '70234567', 'paterno' => 'Rojas', 'materno' => 'Mendoza', 'nombres' => 'Luis', 'score' => 17.2, 'status' => 'ingresante'],
            ['dni' => '70345678', 'paterno' => 'Cáceres', 'materno' => 'Torres', 'nombres' => 'María', 'score' => 16.8, 'status' => 'ingresante'],
            ['dni' => '70456789', 'paterno' => 'Díaz', 'materno' => 'Flores', 'nombres' => 'Pedro', 'score' => 16.1, 'status' => 'ingresante'],
            ['dni' => '70567890', 'paterno' => 'Salazar', 'materno' => 'Ramos', 'nombres' => 'Karla', 'score' => 15.4, 'status' => 'ingresante'],
            ['dni' => '70678901', 'paterno' => 'Paredes', 'materno' => 'Vega', 'nombres' => 'Jorge', 'score' => 13.2, 'status' => 'no_ingresante'],
            ['dni' => '70789012', 'paterno' => 'Cruz', 'materno' => 'Meza', 'nombres' => 'Rosa', 'score' => 12.5, 'status' => 'no_ingresante'],
            ['dni' => '70890123', 'paterno' => 'Vilca', 'materno' => 'Castro', 'nombres' => 'Carlos', 'score' => 11.0, 'status' => 'no_ingresante'],
        ];

        foreach ($applicants as $data) {
            Applicant::updateOrCreate(
                ['admission_process_id' => $process->id, 'dni' => $data['dni']],
                [
                    'paterno' => $data['paterno'],
                    'materno' => $data['materno'],
                    'nombres' => $data['nombres'],
                    'email' => $data['dni'].'@test.com',
                    'telefono' => '987654321',
                    'career_id' => $career->id,
                    'score' => $data['score'],
                    'status' => $data['status'],
                ]
            );
        }

        $subjects = Subject::where('career_id', $career->id)->get();
        $students = Student::with('user')->orderBy('id')->limit(3)->get();

        foreach ($students as $index => $student) {
            $enrollment = Enrollment::updateOrCreate(
                ['code' => 'MAT-2026-'.str_pad((string) ($index + 1), 4, '0', STR_PAD_LEFT)],
                [
                    'student_id' => $student->id,
                    'academic_period_id' => $period->id,
                    'career_id' => $career->id,
                    'status' => 'matriculado',
                    'enrolled_at' => now()->subMonths(2),
                ]
            );

            $assigned = $subjects->forPage(1, 2)->merge($subjects->forPage(2, 2))->unique('id');
            foreach ($assigned as $subject) {
                EnrollmentSubject::updateOrCreate(
                    ['enrollment_id' => $enrollment->id, 'subject_id' => $subject->id],
                    ['status' => 'regular']
                );
            }

            PaymentOrder::updateOrCreate(
                ['student_id' => $student->id, 'enrollment_id' => $enrollment->id],
                [
                    'concept' => 'Matrícula '.$period->name,
                    'amount' => 250.00,
                    'status' => $index === 0 ? 'pagado' : 'pendiente',
                    'receipt_number' => $index === 0 ? 'R-2026-00123' : null,
                ]
            );
        }
    }
}
