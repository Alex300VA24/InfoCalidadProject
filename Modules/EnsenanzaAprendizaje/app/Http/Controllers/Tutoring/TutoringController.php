<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Tutoring;

use Illuminate\Http\Request;
use Inertia\Inertia;
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

        $tutorings = $query->latest('tutoring_date')->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $statuses = AcademicTutoring::STATUSES;

        return Inertia::render('Tutoring/Index', [
            'tutorings' => $tutorings,
            'periods' => $periods,
            'statuses' => $statuses,
            'filters' => $request->only(['academic_period_id', 'status']),
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $students = Student::with('user')->where('estado', 'activo')->orderBy('codigo')->limit(100)->get();
        $tutors = User::withRole('tutor_academico')->orderBy('name')->limit(100)->get(['id', 'name']);
        $types = AcademicTutoring::TYPES;
        $statuses = AcademicTutoring::STATUSES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return Inertia::render('Tutoring/Create', [
            'periods' => $periods,
            'students' => $students,
            'tutors' => $tutors,
            'types' => $types,
            'statuses' => $statuses,
            'defaultPeriod' => $defaultPeriod,
        ]);
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

        return Inertia::render('Tutoring/Show', [
            'academicTutoring' => $academicTutoring,
        ]);
    }

    public function complete(AcademicTutoring $academicTutoring)
    {
        $academicTutoring->update(['status' => 'atendida']);

        return back()->with('success', 'Tutoría marcada como atendida.');
    }
}
