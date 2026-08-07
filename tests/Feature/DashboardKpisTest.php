<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Role;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;
use Modules\GestionIngreso\Models\AdmissionProcess;
use Modules\GestionIngreso\Models\Applicant;
use Modules\ResultadosFormacion\Models\Graduate;
use Modules\ResultadosFormacion\Models\GraduateSurvey;
use Tests\TestCase;

class DashboardKpisTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Director de Escuela', 'slug' => 'director_escuela']);
        $this->user = User::create([
            'name' => 'Director Prueba',
            'email' => 'director.kpi@test.local',
            'password' => 'password',
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);
    }

    public function test_dashboard_renders_with_kpis(): void
    {
        $this->actingAs($this->user);

        $this->get('/dashboard')
            ->assertOk()
            ->assertSee('Vacantes ofrecidas')
            ->assertSee('Inserción laboral')
            ->assertSee('Logro de competencias')
            ->assertSee('Cobertura de vacantes');
    }

    public function test_dashboard_computes_admission_and_survey_kpis(): void
    {
        $career = Career::create(['code' => 'ING-SIS', 'name' => 'Ing. Sistemas', 'is_active' => true]);
        $period = AcademicPeriod::create(['name' => '2026-II', 'start_date' => now(), 'end_date' => now()->addMonths(5), 'is_active' => true]);

        $process = AdmissionProcess::create([
            'name' => 'Examen 2026-II',
            'academic_period_id' => $period->id,
            'career_id' => $career->id,
            'vacancies' => 10,
            'modality' => 'ordinaria',
            'status' => 'cerrado',
        ]);

        for ($i = 1; $i <= 5; $i++) {
            Applicant::create([
                'admission_process_id' => $process->id,
                'dni' => str_pad((string) $i, 8, '0', STR_PAD_LEFT),
                'paterno' => 'Apellido',
                'materno' => 'Materno',
                'nombres' => "Postulante {$i}",
                'career_id' => $career->id,
                'score' => 16,
                'status' => 'ingresante',
            ]);
        }

        $studentUser = User::create([
            'name' => 'Egresado',
            'email' => 'egresado@test.local',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);
        $student = Student::create(['user_id' => $studentUser->id, 'codigo' => '2025000001', 'estado' => 'activo']);

        $graduate = Graduate::create([
            'student_id' => $student->id,
            'graduation_date' => now(),
            'work_status' => 'empleado',
            'survey_date' => now(),
        ]);

        GraduateSurvey::create([
            'graduate_id' => $graduate->id,
            'period' => '2026-II',
            'survey_date' => now(),
            'employed' => true,
            'job_related_to_career' => true,
            'competency_level_score' => 18,
            'income' => 2500,
        ]);
        GraduateSurvey::create([
            'graduate_id' => $graduate->id,
            'period' => '2026-I',
            'survey_date' => now(),
            'employed' => false,
            'job_related_to_career' => false,
            'competency_level_score' => 14,
            'income' => 0,
        ]);

        $this->actingAs($this->user);

        $response = $this->get('/dashboard')->assertOk();

        $response->assertSee('Cobertura de vacantes');
        $response->assertSee('Ordinaria');
        $response->assertSee('5');
        $response->assertSee('50%');
        $response->assertSee('16/20');
    }
}
