<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Execution;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreTeachingLoadRequest;
use Modules\EnsenanzaAprendizaje\Models\TeachingLoad;

class TeachingLoadController extends Controller
{
    public function index(Request $request)
    {
        $query = TeachingLoad::with(['teacher', 'subject', 'academicPeriod']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $loads = $query->latest()->paginate(15);
        $periods = AcademicPeriod::all();
        $teachers = User::withRole('docente')->orderBy('name')->get();

        return view('execution.loads', compact('loads', 'periods', 'teachers'));
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $teachers = User::withRole('docente')->orderBy('name')->get();
        $statuses = TeachingLoad::STATUSES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return view('execution.loads-create', compact('periods', 'subjects', 'teachers', 'statuses', 'defaultPeriod'));
    }

    public function store(StoreTeachingLoadRequest $request)
    {
        TeachingLoad::create($request->validated());

        return redirect()->route('execution.loads.index')
            ->with('success', 'Carga académica registrada correctamente.');
    }
}
