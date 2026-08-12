<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Mobility;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreMobilityApplicationRequest;
use Modules\EnsenanzaAprendizaje\Models\MobilityApplication;

class MobilityController extends Controller
{
    public function index(Request $request)
    {
        $query = MobilityApplication::with(['student.user', 'academicPeriod']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest('application_date')->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $types = MobilityApplication::TYPES;
        $statuses = MobilityApplication::STATUSES;

        return Inertia::render('Mobility/Index', [
            'applications' => $applications,
            'periods' => $periods,
            'types' => $types,
            'statuses' => $statuses,
            'filters' => $request->only(['academic_period_id', 'type', 'status']),
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $students = Student::with('user')->where('estado', 'activo')->orderBy('codigo')->limit(100)->get();
        $types = MobilityApplication::TYPES;
        $statuses = MobilityApplication::STATUSES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return Inertia::render('Mobility/Create', [
            'periods' => $periods,
            'students' => $students,
            'types' => $types,
            'statuses' => $statuses,
            'defaultPeriod' => $defaultPeriod,
        ]);
    }

    public function store(StoreMobilityApplicationRequest $request)
    {
        MobilityApplication::create($request->validated());

        return redirect()->route('mobility.index')
            ->with('success', 'Solicitud de movilidad registrada correctamente.');
    }

    public function show(MobilityApplication $mobilityApplication)
    {
        $mobilityApplication->load(['student.user', 'academicPeriod']);

        return Inertia::render('Mobility/Show', [
            'mobilityApplication' => $mobilityApplication,
        ]);
    }

    public function updateStatus(Request $request, MobilityApplication $mobilityApplication)
    {
        $request->validate([
            'status' => ['required', 'in:en_evaluacion,aprobada,en_curso,finalizada,rechazada'],
        ]);

        $mobilityApplication->update(['status' => $request->status]);

        return back()->with('success', 'Estado de la solicitud actualizado.');
    }
}
