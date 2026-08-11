<?php

namespace Modules\Core\Http\Controllers\Dashboard;

use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\GestionIngreso\Models\AdmissionProcess;
use Modules\GestionIngreso\Models\Applicant;
use Modules\GestionIngreso\Models\Enrollment;
use Modules\ResultadosFormacion\Models\GraduateSurvey;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $activePeriod = AcademicPeriod::where('is_active', true)->first();

        $stats = [
            'usuarios' => User::count(),
            'carreras' => Career::count(),
            'asignaturas' => Subject::count(),
            'periodos' => AcademicPeriod::count(),
        ];

        $kpis = Cache::remember('dashboard.kpis', 360, fn () => $this->computeKpis($activePeriod));

        $kpis['ingresantesPorModalidad'] = collect($kpis['ingresantesPorModalidad'] ?? []);
        $kpis['matriculadosPorCarrera'] = collect($kpis['matriculadosPorCarrera'] ?? []);

        return Inertia::render('Dashboard/Index', [
            'activePeriod' => $activePeriod?->only(['id', 'name']),
            'stats' => $stats,
            'kpis' => $kpis,
        ]);
    }

    private function computeKpis(?AcademicPeriod $activePeriod): array
    {
        $totalVacantes = (int) AdmissionProcess::sum('vacancies');
        $ingresantes = Applicant::where('status', 'ingresante')->count();

        $processes = AdmissionProcess::withCount([
            'applicants as ingresantes' => fn ($query) => $query->where('status', 'ingresante'),
        ])->get();

        $eligible = $processes->where('vacancies', '>', 0);
        $cobertura = $eligible->isNotEmpty()
            ? (float) $eligible->avg(fn ($process) => ($process->ingresantes / $process->vacancies) * 100)
            : 0.0;

        $ingresantesPorModalidad = Applicant::query()
            ->where('app_gestion_ingreso.applicants.status', 'ingresante')
            ->leftJoin('app_gestion_ingreso.admission_processes as ap', 'ap.id', '=', 'app_gestion_ingreso.applicants.admission_process_id')
            ->selectRaw("COALESCE(NULLIF(TRIM(ap.modality), ''), 'Sin modalidad') as modalidad, COUNT(*) as total")
            ->groupBy('modalidad')
            ->orderByDesc('total')
            ->get()
            ->pluck('total', 'modalidad')
            ->toArray();

        $matriculados = Enrollment::where('status', 'matriculado')->count();

        $matriculadosPorCarrera = Enrollment::query()
            ->where('app_gestion_ingreso.enrollments.status', 'matriculado')
            ->when($activePeriod, fn ($query) => $query->where('academic_period_id', $activePeriod->id))
            ->leftJoin('core.careers as c', 'c.id', '=', 'app_gestion_ingreso.enrollments.career_id')
            ->selectRaw("COALESCE(NULLIF(TRIM(c.name), ''), 'Sin carrera') as carrera, COUNT(*) as total")
            ->groupBy('carrera')
            ->orderByDesc('total')
            ->get()
            ->pluck('total', 'carrera')
            ->toArray();

        $surveys = $this->surveyStats();

        return [
            'totalVacantes' => $totalVacantes,
            'ingresantes' => $ingresantes,
            'cobertura' => round($cobertura, 2),
            'ingresantesPorModalidad' => $ingresantesPorModalidad,
            'matriculados' => $matriculados,
            'tasaMatricula' => $ingresantes > 0 ? round(($matriculados / $ingresantes) * 100, 2) : 0,
            'matriculadosPorCarrera' => $matriculadosPorCarrera,
            ...$surveys,
        ];
    }

    private function surveyStats(): array
    {
        $count = GraduateSurvey::count();

        if ($count === 0) {
            return ['encuestas' => 0, 'insercionLaboral' => 0, 'competenciaPromedio' => 0];
        }

        $empleados = GraduateSurvey::where('employed', true)->count();
        $avgScore = (float) GraduateSurvey::whereNotNull('competency_level_score')->avg('competency_level_score');

        return [
            'encuestas' => $count,
            'insercionLaboral' => round(($empleados / $count) * 100, 2),
            'competenciaPromedio' => round($avgScore, 2),
        ];
    }
}
