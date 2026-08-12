<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Tutoring;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreRemedialProgramRequest;
use Modules\EnsenanzaAprendizaje\Models\RemedialProgram;

class RemedialProgramController extends Controller
{
    public function index(Request $request)
    {
        $query = RemedialProgram::with(['student.user', 'academicPeriod', 'subject']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $programs = $query->latest()->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $statuses = RemedialProgram::STATUSES;

        return Inertia::render('RemedialPrograms/Index', [
            'programs' => $programs,
            'periods' => $periods,
            'statuses' => $statuses,
            'filters' => $request->only(['academic_period_id', 'status']),
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $students = Student::with('user')->where('estado', 'activo')->orderBy('codigo')->limit(100)->get();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $statuses = RemedialProgram::STATUSES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return Inertia::render('RemedialPrograms/Create', [
            'periods' => $periods,
            'students' => $students,
            'subjects' => $subjects,
            'statuses' => $statuses,
            'defaultPeriod' => $defaultPeriod,
        ]);
    }

    public function store(StoreRemedialProgramRequest $request)
    {
        RemedialProgram::create($request->validated());

        return redirect()->route('tutoring.remedial.index')
            ->with('success', 'Programa de nivelación registrado correctamente.');
    }

    public function updateStatus(Request $request, RemedialProgram $remedialProgram)
    {
        $request->validate([
            'status' => ['required', 'in:pendiente,en_curso,completado,reprobado'],
        ]);

        $remedialProgram->update(['status' => $request->status]);

        return back()->with('success', 'Estado del programa actualizado.');
    }
}
