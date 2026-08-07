<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Execution;

use Illuminate\Http\Request;
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

        $evaluations = $query->latest('evaluated_at')->paginate(15);
        $periods = AcademicPeriod::all();
        $teachers = User::withRole('docente')->orderBy('name')->get();
        $sources = TeacherPerformanceEvaluation::SOURCES;

        return view('execution.performance', compact('evaluations', 'periods', 'teachers', 'sources'));
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $teachers = User::withRole('docente')->orderBy('name')->get();
        $sources = TeacherPerformanceEvaluation::SOURCES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return view('execution.performance-create', compact('periods', 'teachers', 'sources', 'defaultPeriod'));
    }

    public function store(StoreTeacherPerformanceEvaluationRequest $request)
    {
        TeacherPerformanceEvaluation::create($request->validated());

        return redirect()->route('execution.performance.index')
            ->with('success', 'Evaluación de desempeño registrada correctamente.');
    }
}
