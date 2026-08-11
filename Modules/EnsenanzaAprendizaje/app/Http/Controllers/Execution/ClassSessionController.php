<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Execution;

use Illuminate\Http\Request;
use Inertia\Inertia;
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

        $sessions = $query->latest('session_date')->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();

        return Inertia::render('Execution/ClassSessions/Index', [
            'sessions' => $sessions,
            'periods' => $periods,
            'subjects' => $subjects,
            'filters' => $request->only(['academic_period_id', 'subject_id', 'status']),
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $teachers = User::withRole('docente')->orderBy('name')->limit(100)->get(['id', 'name']);
        $statuses = ClassSession::STATUSES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return Inertia::render('Execution/ClassSessions/Create', [
            'periods' => $periods,
            'subjects' => $subjects,
            'teachers' => $teachers,
            'statuses' => $statuses,
            'defaultPeriod' => $defaultPeriod,
        ]);
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

        return Inertia::render('Execution/ClassSessions/Show', [
            'classSession' => $classSession,
        ]);
    }

    public function coverage(Request $request)
    {
        $period = $request->filled('academic_period_id')
            ? AcademicPeriod::find($request->academic_period_id)
            : AcademicPeriod::where('is_active', true)->first();

        $rows = collect();
        if ($period) {
            $subjects = Subject::with('career')
                ->where('is_active', true)
                ->get();

            $totals = ClassSession::select('subject_id')
                ->selectRaw('SUM(hours) as executed_hours')
                ->selectRaw('COUNT(*) as sessions_count')
                ->where('academic_period_id', $period->id)
                ->where('status', '!=', 'cancelada')
                ->groupBy('subject_id')
                ->get()
                ->keyBy('subject_id');

            $rows = $subjects
                ->map(function (Subject $subject) use ($totals) {
                    $summary = $totals->get($subject->id);

                    $executedHours = (float) ($summary?->executed_hours ?? 0);
                    $plannedHours = (float) $subject->hours;
                    $percentage = $plannedHours > 0 ? round(($executedHours / $plannedHours) * 100, 2) : 0;

                    return [
                        'subject' => $subject,
                        'sessions_count' => (int) ($summary?->sessions_count ?? 0),
                        'executed_hours' => $executedHours,
                        'planned_hours' => $plannedHours,
                        'percentage' => $percentage,
                    ];
                })
                ->sortByDesc('percentage');
        }

        $periods = AcademicPeriod::all();

        return Inertia::render('Execution/ClassSessions/Coverage', [
            'rows' => $rows,
            'period' => $period,
            'periods' => $periods,
        ]);
    }
}
