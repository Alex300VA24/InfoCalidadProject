<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Execution;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreClassSessionRequest;
use Modules\EnsenanzaAprendizaje\Models\ClassSession;

class ClassSessionController extends Controller
{
    public function index(Request $request)
    {
        $query = ClassSession::with(['subject', 'academicPeriod', 'teacher']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $sessions = $query->latest('session_date')->paginate(15);
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();

        return view('execution.index', compact('sessions', 'periods', 'subjects'));
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $teachers = User::withRole('docente')->orderBy('name')->get();
        $statuses = ClassSession::STATUSES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return view('execution.create', compact('periods', 'subjects', 'teachers', 'statuses', 'defaultPeriod'));
    }

    public function store(StoreClassSessionRequest $request)
    {
        $data = $request->validated();
        $data['teacher_id'] = $request->filled('teacher_id')
            ? $request->teacher_id
            : $request->user()->id;

        ClassSession::create($data);

        return redirect()->route('execution.index')
            ->with('success', 'Sesión registrada correctamente.');
    }

    public function show(ClassSession $classSession)
    {
        $classSession->load(['subject.career', 'academicPeriod', 'teacher']);

        return view('execution.show', compact('classSession'));
    }

    public function coverage(Request $request)
    {
        $period = $request->filled('academic_period_id')
            ? AcademicPeriod::find($request->academic_period_id)
            : AcademicPeriod::where('is_active', true)->first();

        $rows = collect();
        if ($period) {
            $rows = Subject::with('career')
                ->where('is_active', true)
                ->get()
                ->map(function (Subject $subject) use ($period) {
                    $sessions = ClassSession::where('subject_id', $subject->id)
                        ->where('academic_period_id', $period->id)
                        ->where('status', '!=', 'cancelada')
                        ->get();

                    $executedHours = (float) $sessions->sum('hours');
                    $plannedHours = (float) $subject->hours;
                    $percentage = $plannedHours > 0 ? round(($executedHours / $plannedHours) * 100, 2) : 0;

                    return [
                        'subject' => $subject,
                        'sessions_count' => $sessions->count(),
                        'executed_hours' => $executedHours,
                        'planned_hours' => $plannedHours,
                        'percentage' => $percentage,
                    ];
                })
                ->sortByDesc('percentage');
        }

        $periods = AcademicPeriod::all();

        return view('execution.coverage', compact('rows', 'period', 'periods'));
    }
}
