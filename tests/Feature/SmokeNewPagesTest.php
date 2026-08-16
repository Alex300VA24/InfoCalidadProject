<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Http\Middleware\HandleInertiaRequests;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Role;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Models\AcademicTutoring;
use Modules\EnsenanzaAprendizaje\Models\MobilityApplication;
use Modules\EnsenanzaAprendizaje\Models\RemedialProgram;
use Modules\EnsenanzaAprendizaje\Models\ResearchProject;
use Modules\ResultadosFormacion\Models\Certificate;
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
            '/mobility',
            '/mobility/create',
            '/mobility/agreements',
            '/mobility/agreements/create',
            '/research',
            '/research/create',
            '/tutoring',
            '/tutoring/create',
            '/tutoring/remedial',
            '/tutoring/remedial/create',
            '/evaluations/actas',
        ];

        foreach ($urls as $url) {
            $this->get($url)->assertStatus(200);
        }
    }

    public function test_native_modal_request_returns_an_inertia_page_payload(): void
    {
        $this->actingAs($this->user);

        $this->get('/graduates/create', [
            'X-Inertia' => 'true',
            'X-Inertia-Version' => app(HandleInertiaRequests::class)->version(request()),
            'X-Requested-With' => 'XMLHttpRequest',
            'Accept' => 'text/html, application/xhtml+xml',
        ])->assertOk()
            ->assertHeader('X-Inertia', 'true')
            ->assertJsonPath('component', 'Graduates/Create')
            ->assertJsonStructure(['component', 'props', 'url', 'version']);
    }

    public function test_evaluations_pages_use_inertia(): void
    {
        $this->actingAs($this->user);

        $this->get('/evaluations')->assertStatus(200)->assertSee('Evaluations\\/Index');
        $this->get('/evaluations/create')->assertStatus(200)->assertSee('Evaluations\\/Create');
        $this->get('/evaluations/record')->assertStatus(200)->assertSee('Evaluations\\/Record');
        $this->get('/evaluations/actas')->assertStatus(200)->assertSee('Evaluations\\/Actas');
    }

    public function test_execution_pages_use_inertia(): void
    {
        $this->actingAs($this->user);

        $this->get('/execution/loads')->assertStatus(200)->assertSee('Execution\\/Loads\\/Index');
        $this->get('/execution/loads/create')->assertStatus(200)->assertSee('Execution\\/Loads\\/Create');
        $this->get('/execution/socializations')->assertStatus(200)->assertSee('Execution\\/Socializations\\/Index');
        $this->get('/execution/socializations/create')->assertStatus(200)->assertSee('Execution\\/Socializations\\/Create');
        $this->get('/execution/executions')->assertStatus(200)->assertSee('Execution\\/Executions\\/Index');
        $this->get('/execution/executions/create')->assertStatus(200)->assertSee('Execution\\/Executions\\/Create');
        $this->get('/execution/performance')->assertStatus(200)->assertSee('Execution\\/Performance\\/Index');
        $this->get('/execution/performance/create')->assertStatus(200)->assertSee('Execution\\/Performance\\/Create');
        $this->get('/execution')->assertStatus(200)->assertSee('Execution\\/ClassSessions\\/Index');
        $this->get('/execution/coverage')->assertStatus(200)->assertSee('Execution\\/ClassSessions\\/Coverage');
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

    public function test_degree_pages_use_inertia(): void
    {
        $this->actingAs($this->user);

        $this->get('/degrees/certificates')->assertStatus(200)->assertSee('Certificates\\/Index');
        $this->get('/degrees/certificates/create')->assertStatus(200)->assertSee('Certificates\\/Create');
        $this->get('/degrees/applications')->assertStatus(200)->assertSee('DegreeApplications\\/Index');
        $this->get('/degrees/applications/create')->assertStatus(200)->assertSee('DegreeApplications\\/Create');

        $app = DegreeApplication::firstOrFail();
        $this->get("/degrees/applications/{$app->id}")->assertStatus(200)->assertSee('DegreeApplications\\/Show');
        $this->get("/degrees/applications/{$app->id}/acts")->assertStatus(200)->assertSee('CommitteeActs\\/Index');
        $this->get("/degrees/applications/{$app->id}/acts/create")->assertStatus(200)->assertSee('CommitteeActs\\/Create');
    }

    public function test_certificate_show_uses_inertia(): void
    {
        $this->actingAs($this->user);

        $certificate = Certificate::create([
            'student_id' => Student::firstOrFail()->id,
            'code' => 'CER-2026-00001',
            'type' => 'estudios',
            'concept' => 'Conclusión satisfactoria de estudios',
            'issued_at' => now()->toDateString(),
            'issued_by' => 'Dirección',
            'status' => 'emitido',
        ]);

        $this->get("/degrees/certificates/{$certificate->id}")->assertStatus(200)->assertSee('Certificates\\/Show');
    }

    public function test_graduate_pages_use_inertia(): void
    {
        $this->actingAs($this->user);

        $this->get('/graduates')->assertStatus(200)->assertSee('Graduates\\/Index');
        $this->get('/graduates/create')->assertStatus(200)->assertSee('Graduates\\/Create');
        $this->get('/graduates/stats')->assertStatus(200)->assertSee('Graduates\\/Stats');

        $graduate = Graduate::firstOrFail();
        $this->get("/graduates/{$graduate->id}")->assertStatus(200)->assertSee('Graduates\\/Show');
        $this->get("/graduates/{$graduate->id}/edit")->assertStatus(200)->assertSee('Graduates\\/Edit');
        $this->get("/graduates/{$graduate->id}/surveys/create")->assertStatus(200)->assertSee('GraduateSurveys\\/Create');
    }

    public function test_graduate_store_flows_create_records(): void
    {
        $this->actingAs($this->user);

        $studentUser = User::create([
            'name' => 'Egresado Nuevo',
            'email' => 'graduate@test.local',
            'password' => 'password',
            'email_verified_at' => now(),
        ]);
        $student = Student::create(['user_id' => $studentUser->id, 'codigo' => '2021000002', 'ciclo' => 10, 'estado' => 'egresado']);

        $this->post('/graduates', [
            'student_id' => $student->id,
            'work_status' => 'independiente',
            'graduation_date' => now()->toDateString(),
            'employer' => 'Independiente',
            'job_position' => 'Desarrollador Freelance',
            'monthly_income' => 3200,
            'survey_date' => now()->toDateString(),
            'employment_relationship' => 'Locación',
            'observations' => 'Ingreso registrado',
        ])->assertRedirect(route('graduates.index'));

        $this->assertDatabaseHas('graduates', [
            'student_id' => $student->id,
            'work_status' => 'independiente',
            'monthly_income' => 3200,
        ]);

        $graduate = Graduate::where('student_id', $student->id)->where('work_status', 'independiente')->firstOrFail();

        $this->put("/graduates/{$graduate->id}", [
            'student_id' => $student->id,
            'work_status' => 'empleado',
            'employer' => 'Banco XYZ',
            'job_position' => 'Analista',
            'monthly_income' => 4000,
            'survey_date' => now()->toDateString(),
        ])->assertRedirect(route('graduates.show', $graduate));

        $this->assertDatabaseHas('graduates', [
            'id' => $graduate->id,
            'work_status' => 'empleado',
            'employer' => 'Banco XYZ',
        ]);
    }

    public function test_degree_store_flows_create_records(): void
    {
        $this->actingAs($this->user);

        $student = Student::firstOrFail();

        $this->post('/degrees/applications', [
            'student_id' => $student->id,
            'type' => 'bachiller',
            'thesis_title' => 'Optimización de redes',
            'application_date' => now()->toDateString(),
            'notes' => 'Expediente inicial',
        ])->assertRedirect(route('degree.applications.index'));

        $application = DegreeApplication::latest('id')->first();
        $this->assertNotNull($application);
        $this->assertMatchesRegularExpression('/^EXP-\d{4}-\d{5}$/', $application->code);
        $this->assertDatabaseHas('degree_applications', ['id' => $application->id, 'status' => 'en_tramite']);

        $this->post("/degrees/applications/{$application->id}/status", [
            'status' => 'aprobado',
            'resolution_number' => 'R-2026-100',
            'resolution_date' => now()->toDateString(),
        ])->assertRedirect();

        $this->assertDatabaseHas('degree_applications', [
            'id' => $application->id,
            'status' => 'aprobado',
            'resolution_number' => 'R-2026-100',
        ]);

        $this->post('/degrees/certificates', [
            'student_id' => $student->id,
            'type' => 'practicas',
            'concept' => 'Prácticas pre-profesionales culminadas',
            'issued_at' => now()->toDateString(),
            'issued_by' => 'Dirección de Escuela',
        ])->assertRedirect();

        $this->assertDatabaseHas('certificates', [
            'student_id' => $student->id,
            'type' => 'practicas',
            'status' => 'emitido',
        ]);
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

        $this->post('/mobility', [
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'type' => 'movilidad_nacional',
            'application_date' => now()->toDateString(),
            'destination_institution' => 'UNI',
            'status' => 'en_evaluacion',
        ])->assertRedirect(route('mobility.index'));

        $this->assertDatabaseHas('mobility_applications', ['student_id' => $student->id, 'type' => 'movilidad_nacional']);

        $this->post('/research', [
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'title' => 'Sistemas Inteligentes',
            'area' => 'IA',
            'status' => 'borrador',
        ])->assertRedirect(route('research.index'));

        $this->assertDatabaseHas('research_projects', ['student_id' => $student->id, 'title' => 'Sistemas Inteligentes']);

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

    public function test_tutoring_pages_use_inertia(): void
    {
        $this->actingAs($this->user);

        $this->get('/tutoring')->assertStatus(200)->assertSee('Tutoring\\/Index');
        $this->get('/tutoring/create')->assertStatus(200)->assertSee('Tutoring\\/Create');
        $this->get('/tutoring/remedial')->assertStatus(200)->assertSee('RemedialPrograms\\/Index');
        $this->get('/tutoring/remedial/create')->assertStatus(200)->assertSee('RemedialPrograms\\/Create');

        $tutoring = AcademicTutoring::create([
            'student_id' => Student::firstOrFail()->id,
            'academic_period_id' => AcademicPeriod::firstOrFail()->id,
            'tutor_id' => $this->user->id,
            'tutoring_date' => now()->toDateString(),
            'type' => 'acompanamiento',
            'reason' => 'Bajo rendimiento',
            'status' => 'pendiente',
        ]);

        $this->get("/tutoring/{$tutoring->id}")->assertStatus(200)->assertSee('Tutoring\\/Show');
    }

    public function test_tutoring_store_flow_creates_records(): void
    {
        $this->actingAs($this->user);

        $subject = Subject::firstOrFail();
        $period = AcademicPeriod::firstOrFail();
        $student = Student::firstOrFail();

        $this->post('/tutoring', [
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'tutoring_date' => now()->toDateString(),
            'type' => 'nivelacion',
            'reason' => 'Reforzar competencias',
            'status' => 'pendiente',
        ])->assertRedirect(route('tutoring.index'));

        $this->assertDatabaseHas('academic_tutoring', ['student_id' => $student->id, 'type' => 'nivelacion']);

        $tutoring = AcademicTutoring::firstOrFail();

        $this->post("/tutoring/{$tutoring->id}/complete")->assertRedirect();
        $this->assertDatabaseHas('academic_tutoring', ['id' => $tutoring->id, 'status' => 'atendida']);

        $this->post('/tutoring/remedial', [
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'subject_id' => $subject->id,
            'description' => 'Nivelación',
            'status' => 'pendiente',
        ])->assertRedirect(route('tutoring.remedial.index'));

        $program = RemedialProgram::firstOrFail();

        $this->post("/tutoring/remedial/{$program->id}/status", [
            'status' => 'en_curso',
        ])->assertRedirect();

        $this->assertDatabaseHas('remedial_programs', ['id' => $program->id, 'status' => 'en_curso']);
    }

    public function test_mobility_and_research_pages_use_inertia(): void
    {
        $this->actingAs($this->user);

        $student = Student::firstOrFail();
        $period = AcademicPeriod::firstOrFail();

        $mobility = MobilityApplication::create([
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'type' => 'beca_externa',
            'application_date' => now()->toDateString(),
            'destination_institution' => 'Harvard',
            'status' => 'en_evaluacion',
        ]);

        $project = ResearchProject::create([
            'student_id' => $student->id,
            'academic_period_id' => $period->id,
            'title' => 'Visión Computacional',
            'area' => 'IA',
            'status' => 'en_desarrollo',
        ]);

        $this->get('/mobility')->assertStatus(200)->assertSee('Mobility\\/Index');
        $this->get('/mobility/create')->assertStatus(200)->assertSee('Mobility\\/Create');
        $this->get("/mobility/{$mobility->id}")->assertStatus(200)->assertSee('Mobility\\/Show');
        $this->get('/mobility/agreements')->assertStatus(200)->assertSee('Agreements\\/Index');
        $this->get('/mobility/agreements/create')->assertStatus(200)->assertSee('Agreements\\/Create');
        $this->get('/research')->assertStatus(200)->assertSee('ResearchProjects\\/Index');
        $this->get('/research/create')->assertStatus(200)->assertSee('ResearchProjects\\/Create');
        $this->get("/research/{$project->id}")->assertStatus(200)->assertSee('ResearchProjects\\/Show');
    }

    public function test_welcome_and_guest_auth_pages_use_inertia(): void
    {
        $this->get('/')->assertStatus(200)->assertSee('Welcome');
        $this->get('/login')->assertStatus(200)->assertSee('Auth\\/Login');
        $this->get('/register')->assertStatus(200)->assertSee('Auth\\/Register');
        $this->get('/forgot-password')->assertStatus(200)->assertSee('Auth\\/ForgotPassword');
        $this->get('/reset-password/demo-token')->assertStatus(200)->assertSee('Auth\\/ResetPassword');
    }

    public function test_authenticated_auth_and_profile_pages_use_inertia(): void
    {
        $this->actingAs($this->user);

        $this->get('/confirm-password')->assertStatus(200)->assertSee('Auth\\/ConfirmPassword');
        $this->get('/profile')->assertStatus(200)->assertSee('Profile\\/Edit');
    }

    public function test_verify_email_page_renders_for_unverified_user(): void
    {
        $role = Role::where('slug', 'director_escuela')->firstOrFail();

        $unverified = User::create([
            'name' => 'Sin Verificar',
            'email' => 'sinverificar@test.local',
            'password' => 'password',
            'role_id' => $role->id,
        ]);

        $this->actingAs($unverified);

        $this->get('/verify-email')->assertStatus(200)->assertSee('Auth\\/VerifyEmail');
    }

    public function test_login_flow_authenticates(): void
    {
        $this->post('/login', [
            'email' => $this->user->email,
            'password' => 'password',
            'remember' => 1,
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($this->user);
    }

    public function test_registration_flow_creates_user(): void
    {
        $role = Role::create(['name' => 'Docente', 'slug' => 'docente']);

        $this->post('/register', [
            'name' => 'Nuevo Usuario',
            'email' => 'nuevo@test.local',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role_id' => $role->id,
        ])->assertRedirect(route('dashboard'));

        $this->assertDatabaseHas('core.users', [
            'email' => 'nuevo@test.local',
            'role_id' => $role->id,
        ]);
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
