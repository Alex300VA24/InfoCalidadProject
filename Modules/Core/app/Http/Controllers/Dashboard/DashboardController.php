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

        $kpis = $this->computeKpis($activePeriod);

        return view('dashboard.index', compact('activePeriod', 'stats', 'kpis'));
    }

    private function computeKpis(?AcademicPeriod $activePeriod): array
    {
        $processes = AdmissionProcess::with('applicants')->get();
        $totalVacantes = (int) $processes->sum('vacancies');
        $ingresantes = Applicant::where('status', 'ingresante')->count();

        $cobertura = (float) $processes
            ->filter(fn ($process) => $process->vacancies > 0)
            ->avg(fn ($process) => $process->coveragePercentage());

        $ingresantesPorModalidad = Applicant::where('status', 'ingresante')
            ->with('admissionProcess')
            ->get()
            ->groupBy(fn ($applicant) => $applicant->admissionProcess?->modality ?? 'Sin modalidad')
            ->map->count()
            ->sortDesc();

        $matriculados = Enrollment::where('status', 'matriculado')->count();

        $matriculadosPorCarrera = Enrollment::where('status', 'matriculado')
            ->when($activePeriod, fn ($query) => $query->where('academic_period_id', $activePeriod->id))
            ->with('career')
            ->get()
            ->groupBy(fn ($enrollment) => $enrollment->career?->name ?? 'Sin carrera')
            ->map->count()
            ->sortDesc();

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
        $surveys = GraduateSurvey::all();

        if ($surveys->isEmpty()) {
            return ['encuestas' => 0, 'insercionLaboral' => 0, 'competenciaPromedio' => 0];
        }

        $empleados = $surveys->where('employed', true)->count();

        return [
            'encuestas' => $surveys->count(),
            'insercionLaboral' => round(($empleados / $surveys->count()) * 100, 2),
            'competenciaPromedio' => round((float) $surveys->avg('competency_level_score'), 2),
        ];
    }
}
