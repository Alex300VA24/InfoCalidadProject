<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Tutoring;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreAcademicTutoringRequest;
use Modules\EnsenanzaAprendizaje\Models\AcademicTutoring;

class TutoringController extends Controller
{
    public function index(Request $request)
    {
        $query = AcademicTutoring::with(['student.user', 'academicPeriod', 'tutor']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tutorings = $query->latest('tutoring_date')->paginate(15);
        $periods = AcademicPeriod::all();

        return view('tutoring.index', compact('tutorings', 'periods'));
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $students = Student::with('user')->where('estado', 'activo')->orderBy('codigo')->get();
        $tutors = User::withRole('tutor_academico')->orderBy('name')->get();
        $types = AcademicTutoring::TYPES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return view('tutoring.create', compact('periods', 'students', 'tutors', 'types', 'defaultPeriod'));
    }

    public function store(StoreAcademicTutoringRequest $request)
    {
        $data = $request->validated();
        $data['tutor_id'] = $request->filled('tutor_id') ? $request->tutor_id : $request->user()->id;

        AcademicTutoring::create($data);

        return redirect()->route('tutoring.index')
            ->with('success', 'Tutoría registrada correctamente.');
    }

    public function show(AcademicTutoring $academicTutoring)
    {
        $academicTutoring->load(['student.user', 'academicPeriod', 'tutor']);

        return view('tutoring.show', compact('academicTutoring'));
    }

    public function complete(AcademicTutoring $academicTutoring)
    {
        $academicTutoring->update(['status' => 'atendida']);

        return back()->with('success', 'Tutoría marcada como atendida.');
    }
}
