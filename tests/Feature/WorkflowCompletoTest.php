<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Role;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Models\OfficialAct;
use Modules\GestionIngreso\Models\AdmissionProcess;
use Modules\GestionIngreso\Models\Applicant;
use Modules\ResultadosFormacion\Models\Certificate;
use Modules\ResultadosFormacion\Models\DegreeApplication;
use Tests\TestCase;

class WorkflowCompletoTest extends TestCase
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

    public function test_applicant_result_ingresante_creates_student_and_constancia(): void
    {
        [$career, $period] = $this->makeBaseData();
        $user = $this->makeUser('coordinador_admision', 'coord@test.local');
        Role::create(['name' => 'Estudiante', 'slug' => 'estudiante']);

        $process = AdmissionProcess::create([
            'name' => 'Examen 2026-II',
            'academic_period_id' => $period->id,
            'career_id' => $career->id,
            'vacancies' => 10,
            'modality' => 'ordinaria',
            'status' => 'abierto',
        ]);

        $applicant = Applicant::create([
            'admission_process_id' => $process->id,
            'dni' => '12345678',
            'paterno' => 'Perez',
            'materno' => 'Rojas',
            'nombres' => 'Juan',
            'email' => 'juan@test.local',
            'career_id' => $career->id,
            'status' => 'pendiente',
        ]);

        $this->actingAs($user);

        $this->post("/admission/applicants/{$applicant->id}/result", [
            'score' => 85,
            'status' => 'ingresante',
        ])->assertRedirect();

        $applicant->refresh();
        $this->assertSame('ingresante', $applicant->status);
        $this->assertSame('85.00', $applicant->score);
        $this->assertNotNull($applicant->constancia_path);

        $this->assertDatabaseHas('users', ['dni' => '12345678', 'email' => 'juan@test.local']);
        $this->assertDatabaseHas('students', ['codigo' => date('Y').'-00001']);

        Storage::disk('public')->assertExists($applicant->constancia_path);
        $this->get("/admission/applicants/{$applicant->id}/constancia")->assertOk();
    }

    public function test_applicant_result_no_ingresante_does_not_create_student(): void
    {
        [$career, $period] = $this->makeBaseData();
        $user = $this->makeUser('coordinador_admision', 'coord2@test.local');

        $process = AdmissionProcess::create([
            'name' => 'Examen 2026-II',
            'academic_period_id' => $period->id,
            'career_id' => $career->id,
            'vacancies' => 10,
            'modality' => 'ordinaria',
            'status' => 'abierto',
        ]);

        $applicant = Applicant::create([
            'admission_process_id' => $process->id,
            'dni' => '87654321',
            'paterno' => 'Lopez',
            'nombres' => 'Maria',
            'career_id' => $career->id,
            'status' => 'pendiente',
        ]);

        $this->actingAs($user);

        $this->post("/admission/applicants/{$applicant->id}/result", [
            'score' => 45,
            'status' => 'no_ingresante',
        ])->assertRedirect();

        $this->assertDatabaseHas('applicants', ['id' => $applicant->id, 'status' => 'no_ingresante', 'score' => '45.00']);
        $this->assertDatabaseMissing('users', ['dni' => '87654321']);
        $this->assertDatabaseCount('students', 0);
    }

    public function test_certificate_store_emits_with_sequential_code_and_pdf(): void
    {
        $this->makeBaseData();
        $user = $this->makeUser('director_escuela', 'director@test.local');
        $student = $this->makeStudent('2026000001');

        $this->actingAs($user);

        $this->post('/degrees/certificates', [
            'student_id' => $student->id,
            'type' => 'estudios',
            'concept' => 'Estudios completos aprobados',
            'issued_at' => now()->toDateString(),
        ])->assertRedirect();

        $code = 'CER-'.date('Y').'-00001';
        $this->assertDatabaseHas('certificates', ['code' => $code, 'status' => 'emitido']);

        $certificate = Certificate::where('code', $code)->first();
        Storage::disk('public')->assertExists($certificate->pdf_path);
        $this->get("/degrees/certificates/{$certificate->id}/download")->assertOk();
    }

    public function test_degree_application_workflow_to_otorgado_with_committee_act(): void
    {
        [$career] = $this->makeBaseData();
        $user = $this->makeUser('director_escuela', 'director2@test.local');
        $student = $this->makeStudent('2026000002');

        $this->actingAs($user);

        $this->post('/degrees/applications', [
            'student_id' => $student->id,
            'type' => 'bachiller',
            'thesis_title' => 'Análisis estructural de puentes',
            'application_date' => now()->toDateString(),
        ])->assertRedirect();

        $code = 'EXP-'.date('Y').'-00001';
        $this->assertDatabaseHas('degree_applications', ['code' => $code, 'status' => 'en_tramite']);

        $application = DegreeApplication::where('code', $code)->first();

        $this->post("/degrees/applications/{$application->id}/status", [
            'status' => 'aprobado',
            'resolution_number' => 'RES-001-2026',
            'resolution_date' => now()->toDateString(),
        ])->assertRedirect();
        $this->assertDatabaseHas('degree_applications', ['id' => $application->id, 'status' => 'aprobado', 'resolution_number' => 'RES-001-2026']);

        $this->post("/degrees/applications/{$application->id}/status", ['status' => 'otorgado'])->assertRedirect();
        $this->assertDatabaseHas('degree_applications', ['id' => $application->id, 'status' => 'otorgado']);

        $this->post("/degrees/applications/{$application->id}/acts", [
            'act_type' => 'sustentacion',
            'session_date' => now()->toDateString(),
            'result' => 'aprobado',
            'score' => 17.5,
        ])->assertRedirect();
        $this->assertDatabaseHas('degree_committee_acts', [
            'degree_application_id' => $application->id,
            'act_type' => 'sustentacion',
            'result' => 'aprobado',
        ]);
        $this->assertDatabaseCount('degree_committee_acts', 1);
    }

    public function test_evaluations_weighted_average_and_acta_generation(): void
    {
        [, $period, $subject] = $this->makeBaseData();
        $user = $this->makeUser('director_escuela', 'director3@test.local');
        $student = $this->makeStudent('2026000003');

        $this->actingAs($user);

        $scores = [
            'practica_1' => 10,
            'practica_2' => 10,
            'practica_3' => 10,
            'examen_parcial' => 10,
            'examen_final' => 14,
        ];

        foreach ($scores as $type => $score) {
            $this->post('/evaluations', [
                'student_id' => $student->id,
                'subject_id' => $subject->id,
                'academic_period_id' => $period->id,
                'evaluation_type' => $type,
                'score' => $score,
                'evaluation_date' => now()->toDateString(),
            ])->assertRedirect();
        }

        $this->assertDatabaseCount('student_evaluations', 5);

        $this->get('/evaluations/record?'.http_build_query(['academic_period_id' => $period->id, 'subject_id' => $subject->id]))
            ->assertOk()
            ->assertSee('11.6');

        $this->post('/evaluations/actas/generar', [
            'academic_period_id' => $period->id,
            'subject_id' => $subject->id,
        ])->assertRedirect();

        $act = OfficialAct::where('subject_id', $subject->id)
            ->where('academic_period_id', $period->id)
            ->where('teacher_id', $user->id)
            ->first();
        $this->assertNotNull($act);
        $this->assertSame('borrador', $act->status);
        Storage::disk('local')->assertExists($act->pdf_path);

        $this->post("/evaluations/actas/{$act->id}/cerrar")->assertRedirect();
        $this->assertDatabaseHas('official_acts', ['id' => $act->id, 'status' => 'cerrado']);

        $this->get("/evaluations/actas/{$act->id}/descargar")->assertOk();
    }
}
