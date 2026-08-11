<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Execution;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreSubjectExecutionRequest;
use Modules\EnsenanzaAprendizaje\Models\SubjectExecution;
use Modules\GestionCurricular\Models\Syllabus;

class SubjectExecutionController extends Controller
{
    public function index(Request $request)
    {
        $query = SubjectExecution::with(['subject', 'teacher', 'academicPeriod']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $executions = $query->latest()->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $statuses = SubjectExecution::STATUSES;

        return Inertia::render('Execution/Executions/Index', [
            'executions' => $executions,
            'periods' => $periods,
            'subjects' => $subjects,
            'statuses' => $statuses,
            'filters' => $request->only(['academic_period_id', 'subject_id', 'status']),
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $teachers = User::withRole('docente')->orderBy('name')->limit(100)->get(['id', 'name']);
        $syllabi = Syllabus::with(['subject', 'career'])->orderByDesc('version')->limit(100)->get();
        $statuses = SubjectExecution::STATUSES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return Inertia::render('Execution/Executions/Create', [
            'periods' => $periods,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'syllabi' => $syllabi,
            'statuses' => $statuses,
            'defaultPeriod' => $defaultPeriod,
        ]);
    }

    public function store(StoreSubjectExecutionRequest $request)
    {
        SubjectExecution::create($request->validated());

        return redirect()->route('execution.executions.index')
            ->with('success', 'Ejecución de asignatura registrada correctamente.');
    }

    public function close(SubjectExecution $subjectExecution)
    {
        $subjectExecution->update(['status' => 'cerrado', 'progress_pct' => 100]);

        return back()->with('success', 'Ejecución de asignatura cerrada.');
    }
}
