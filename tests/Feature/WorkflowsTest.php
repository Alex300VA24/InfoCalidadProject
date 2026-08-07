<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Role;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Models\RemedialProgram;
use Modules\GestionIngreso\Models\AdmissionProcess;
use Modules\GestionIngreso\Models\Enrollment;
use Modules\GestionIngreso\Models\PaymentOrder;
use Tests\TestCase;

class WorkflowsTest extends TestCase
{
    use RefreshDatabase;

    private function makeUser(string $roleSlug, string $email): User
    {
        $role = Role::create(['name' => ucwords(str_replace('_', ' ', $roleSlug)), 'slug' => $roleSlug]);

        return User::create([
            'name' => 'Usuario Prueba',
            'email' => $email,
            'password' => 'password',
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);
    }

    private function makeBaseData(): array
    {
        $career = Career::create(['code' => 'ING-CIV', 'name' => 'Ing. Civil', 'is_active' => true]);
        $period = AcademicPeriod::create(['name' => '2026-II', 'start_date' => now(), 'end_date' => now()->addMonths(5), 'is_active' => true]);
        $subject = Subject::create(['career_id' => $career->id, 'code' => 'CIV101', 'name' => 'Matemática', 'credits' => 4, 'hours' => 64, 'type' => 'obligatoria', 'is_active' => true]);

        return [$career, $period, $subject];
    }

    private function makeStudent(string $codigo): Student
    {
        $user = User::create([
            'name' => 'Estudiante',
            'email' => "{$codigo}@test.local",
            'password' => 'password',
            'email_verified_at' => now(),
        ]);

        return Student::create(['user_id' => $user->id, 'codigo' => $codigo, 'ciclo' => 5, 'estado' => 'activo']);
    }

    public function test_admission_process_finalize_workflow(): void
    {
        [$career, $period] = $this->makeBaseData();
        $user = $this->makeUser('coordinador_admision', 'coord@test.local');

        $process = AdmissionProcess::create([
            'name' => 'Examen 2026-II',
            'academic_period_id' => $period->id,
            'career_id' => $career->id,
            'vacancies' => 10,
            'modality' => 'ordinaria',
            'status' => 'borrador',
        ]);

        $this->actingAs($user);

        $this->post("/admission/processes/{$process->id}/finalize")->assertRedirect();
        $this->assertDatabaseHas('admission_processes', ['id' => $process->id, 'status' => 'abierto']);

        $this->post("/admission/processes/{$process->id}/finalize")->assertRedirect();
        $this->assertDatabaseHas('admission_processes', ['id' => $process->id, 'status' => 'cerrado']);

        $this->post("/admission/processes/{$process->id}/finalize")->assertRedirect();
        $this->assertDatabaseHas('admission_processes', ['id' => $process->id, 'status' => 'cerrado']);
    }

    public function test_register_payment_marks_order_as_pagado(): void
    {
        [$career, $period] = $this->makeBaseData();
        $user = $this->makeUser('personal_matricula', 'matricula@test.local');
        $student = $this->makeStudent('2026000001');

        $enrollment = Enrollment::create([
            'code' => 'MAT-2026-0001',
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'career_id' => $career->id,
            'status' => 'matriculado',
            'enrolled_at' => now(),
        ]);

        $payment = PaymentOrder::create([
            'student_id' => $student->id,
            'enrollment_id' => $enrollment->id,
            'concept' => 'Matrícula 2026-II',
            'amount' => 250,
            'status' => 'pendiente',
        ]);

        $this->actingAs($user);

        $this->post("/enrollment/payments/{$payment->id}/register", [
            'receipt_number' => 'R-000123',
        ])->assertRedirect();

        $this->assertDatabaseHas('payment_orders', [
            'id' => $payment->id,
            'status' => 'pagado',
            'receipt_number' => 'R-000123',
        ]);
    }

    public function test_enrollment_blocked_when_student_has_pending_remedial_program(): void
    {
        [, $period, $subject] = $this->makeBaseData();
        $user = $this->makeUser('personal_matricula', 'matricula2@test.local');
        $student = $this->makeStudent('2026000002');

        RemedialProgram::create([
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'subject_id' => $subject->id,
            'description' => 'Nivelación',
            'status' => 'pendiente',
        ]);

        $this->actingAs($user);

        $this->post('/enrollment', [
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'career_id' => $subject->career_id,
            'subjects' => [$subject->id],
        ])->assertRedirect()->assertSessionHas('error');

        $this->assertDatabaseMissing('enrollments', ['student_id' => $student->id]);
    }

    public function test_enrollment_created_for_eligible_student(): void
    {
        [, $period, $subject] = $this->makeBaseData();
        $user = $this->makeUser('personal_matricula', 'matricula3@test.local');
        $student = $this->makeStudent('2026000003');

        $this->actingAs($user);

        $this->post('/enrollment', [
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'career_id' => $subject->career_id,
            'subjects' => [$subject->id],
            'matricula_fee' => 250,
        ])->assertRedirect()->assertSessionHas('success');

        $this->assertDatabaseHas('enrollments', ['student_id' => $student->id, 'status' => 'matriculado']);
        $this->assertDatabaseHas('payment_orders', ['student_id' => $student->id, 'amount' => 250, 'status' => 'pendiente']);
    }
}
