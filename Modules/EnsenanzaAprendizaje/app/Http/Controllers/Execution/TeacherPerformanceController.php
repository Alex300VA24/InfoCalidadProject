<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Execution;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreTeacherPerformanceEvaluationRequest;
use Modules\EnsenanzaAprendizaje\Models\TeacherPerformanceEvaluation;

class TeacherPerformanceController extends Controller
{
    public function index(Request $request)
    {
        $query = TeacherPerformanceEvaluation::with(['teacher', 'academicPeriod']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        $evaluations = $query->latest('evaluated_at')->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $teachers = User::withRole('docente')->orderBy('name')->limit(100)->get(['id', 'name']);
        $sources = TeacherPerformanceEvaluation::SOURCES;

        return Inertia::render('Execution/Performance/Index', [
            'evaluations' => $evaluations,
            'periods' => $periods,
            'teachers' => $teachers,
            'sources' => $sources,
            'filters' => $request->only(['academic_period_id', 'teacher_id', 'source']),
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $teachers = User::withRole('docente')->orderBy('name')->limit(100)->get(['id', 'name']);
        $sources = TeacherPerformanceEvaluation::SOURCES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return Inertia::render('Execution/Performance/Create', [
            'periods' => $periods,
            'teachers' => $teachers,
            'sources' => $sources,
            'defaultPeriod' => $defaultPeriod,
        ]);
    }

    public function store(StoreTeacherPerformanceEvaluationRequest $request)
    {
        TeacherPerformanceEvaluation::create($request->validated());

        return redirect()->route('execution.performance.index')
            ->with('success', 'Evaluación de desempeño registrada correctamente.');
    }
}
