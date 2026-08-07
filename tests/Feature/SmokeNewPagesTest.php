<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Role;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\ResultadosFormacion\Models\DegreeApplication;
use Modules\ResultadosFormacion\Models\Graduate;
use Tests\TestCase;

class SmokeNewPagesTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $role = Role::create(['name' => 'Director de Escuela', 'slug' => 'director_escuela']);
        $this->user = User::create([
            'name' => 'Director Prueba',
            'email' => 'director@test.local',
            'password' => 'password',
            'role_id' => $role->id,
            'email_verified_at' => now(),
        ]);

        $career = Career::create(['code' => 'ING-SIS', 'name' => 'Ing. Sistemas', 'is_active' => true]);
        $period = AcademicPeriod::create(['name' => '2026-II', 'start_date' => now(), 'end_date' => now()->addMonths(5), 'is_active' => true]);
        $subject = Subject::create(['career_id' => $career->id, 'code' => 'SIS101', 'name' => 'Programación', 'credits' => 4, 'hours' => 64, 'type' => 'obligatoria', 'is_active' => true]);

        $studentUser = User::create([
            'name' => 'Estudiante Prueba',
            'email' => 'student@test.local',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);
        $student = Student::create(['user_id' => $studentUser->id, 'codigo' => '2020000001', 'ciclo' => 5, 'estado' => 'activo']);

        DegreeApplication::create([
            'student_id' => $student->id,
            'code' => 'EXP-2026-00001',
            'type' => 'bachiller',
            'application_date' => now(),
            'status' => 'en_tramite',
        ]);

        Graduate::create([
            'student_id' => $student->id,
            'graduation_date' => now(),
            'work_status' => 'empleado',
            'survey_date' => now(),
        ]);
    }

    public function test_new_pages_render(): void
    {
        $this->actingAs($this->user);

        $urls = [
            '/execution/loads',
            '/execution/loads/create',
            '/execution/socializations',
            '/execution/socializations/create',
            '/execution/executions',
            '/execution/executions/create',
            '/execution/performance',
            '/execution/performance/create',
            '/mobility/agreements',
            '/mobility/agreements/create',
            '/tutoring/remedial',
            '/tutoring/remedial/create',
            '/evaluations/actas',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertStatus(200);
        }
    }

    public function test_nested_pages_render(): void
    {
        $this->actingAs($this->user);

        $app = DegreeApplication::firstOrFail();
        $graduate = Graduate::firstOrFail();

        $this->get("/degrees/applications/{$app->id}/acts")->assertStatus(200);
        $this->get("/degrees/applications/{$app->id}/acts/create")->assertStatus(200);
        $this->get("/graduates/{$graduate->id}/surveys/create")->assertStatus(200);
        $this->get("/graduates/{$graduate->id}")->assertStatus(200);
    }

    public function test_store_flows_create_records(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::firstOrFail();
        $period = AcademicPeriod::firstOrFail();
        $student = Student::firstOrFail();
        $app = DegreeApplication::firstOrFail();
        $graduate = Graduate::firstOrFail();

        $this->post('/execution/loads', [
            'teacher_id' => $this->user->id,
            'subject_id' => $subject->id,
            'academic_period_id' => $period->id,
            'section' => 'A',
            'hours' => 4,
            'status' => 'asignado',
        ])->assertRedirect();

        $this->assertDatabaseHas('teaching_loads', ['subject_id' => $subject->id]);

        $this->post('/execution/executions', [
            'subject_id' => $subject->id,
            'teacher_id' => $this->user->id,
            'academic_period_id' => $period->id,
            'progress_pct' => 25,
            'status' => 'en_ejecucion',
        ])->assertRedirect();

        $this->assertDatabaseHas('subject_executions', ['subject_id' => $subject->id, 'progress_pct' => 25]);

        $this->post('/execution/performance', [
            'teacher_id' => $this->user->id,
            'academic_period_id' => $period->id,
            'score' => 18,
            'source' => 'encuesta_estudiante',
            'evaluated_at' => now()->toDateString(),
        ])->assertRedirect();

        $this->assertDatabaseHas('teacher_performance_evaluations', ['teacher_id' => $this->user->id, 'score' => 18]);

        $this->post('/mobility/agreements', [
            'name' => 'Convenio UPT',
            'institution' => 'UPT',
            'type' => 'nacional',
            'status' => 'vigente',
            'start_date' => now()->toDateString(),
        ])->assertRedirect();

        $this->assertDatabaseHas('agreements', ['name' => 'Convenio UPT']);

        $this->post('/tutoring/remedial', [
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'subject_id' => $subject->id,
            'description' => 'Nivelación',
            'status' => 'pendiente',
        ])->assertRedirect();

        $this->assertDatabaseHas('remedial_programs', ['student_id' => $student->id, 'status' => 'pendiente']);

        $this->post("/degrees/applications/{$app->id}/acts", [
            'act_type' => 'sustentacion',
            'session_date' => now()->toDateString(),
            'result' => 'aprobado',
            'score' => 17,
        ])->assertRedirect();

        $this->assertDatabaseHas('degree_committee_acts', ['degree_application_id' => $app->id, 'score' => 17]);

        $this->post("/graduates/{$graduate->id}/surveys", [
            'period' => '2026-II',
            'survey_date' => now()->toDateString(),
            'employed' => 1,
            'job_related_to_career' => 1,
            'competency_level_score' => 16,
            'income' => 2500,
        ])->assertRedirect();

        $this->assertDatabaseHas('graduate_surveys', ['graduate_id' => $graduate->id, 'income' => 2500]);
    }

    public function test_generar_acta_creates_official_act(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::firstOrFail();
        $period = AcademicPeriod::firstOrFail();

        $this->post('/evaluations/actas/generar', [
            'subject_id' => $subject->id,
            'academic_period_id' => $period->id,
        ])->assertRedirect();

        $this->assertDatabaseHas('official_acts', [
            'subject_id' => $subject->id,
            'academic_period_id' => $period->id,
            'status' => 'borrador',
        ]);
    }
}
