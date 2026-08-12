<?php

namespace Modules\ResultadosFormacion\Http\Controllers\Degree;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;
use Modules\ResultadosFormacion\Http\Requests\StoreDegreeApplicationRequest;
use Modules\ResultadosFormacion\Models\DegreeApplication;

class DegreeApplicationController extends Controller
{
    public function index(Request $request)
    {
        $query = DegreeApplication::with(['student.user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $applications = $query->latest('application_date')->paginate(15)->withQueryString();
        $types = DegreeApplication::TYPES;
        $statuses = DegreeApplication::STATUSES;

        return Inertia::render('DegreeApplications/Index', [
            'applications' => $applications,
            'types' => $types,
            'statuses' => $statuses,
            'filters' => $request->only(['type', 'status']),
        ]);
    }

    public function create()
    {
        $students = Student::with('user')->where('estado', 'activo')->orderBy('codigo')->limit(100)->get();
        $teachers = User::withRole('docente')->orderBy('name')->limit(100)->get();
        $types = DegreeApplication::TYPES;

        return Inertia::render('DegreeApplications/Create', [
            'students' => $students,
            'teachers' => $teachers,
            'types' => $types,
        ]);
    }

    public function store(StoreDegreeApplicationRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $code = 'EXP-'.date('Y').'-'.Str::padLeft(DegreeApplication::max('id') + 1, 5, '0');

            DegreeApplication::create($request->validated() + [
                'code' => $code,
                'status' => 'en_tramite',
            ]);

            return redirect()->route('degree.applications.index')
                ->with('success', 'Expediente de grado registrado correctamente.');
        });
    }

    public function show(DegreeApplication $degreeApplication)
    {
        $degreeApplication->load(['student.user', 'advisor']);

        return Inertia::render('DegreeApplications/Show', [
            'degreeApplication' => $degreeApplication,
            'statuses' => DegreeApplication::STATUSES,
        ]);
    }

    public function updateStatus(Request $request, DegreeApplication $degreeApplication)
    {
        $request->validate([
            'status' => ['required', 'in:en_tramite,revisado,aprobado,otorgado,observado'],
            'resolution_number' => ['nullable', 'string', 'max:100'],
            'resolution_date' => ['nullable', 'date'],
        ]);

        $degreeApplication->update([
            'status' => $request->status,
            'resolution_number' => $request->filled('resolution_number') ? $request->resolution_number : $degreeApplication->resolution_number,
            'resolution_date' => $request->filled('resolution_date') ? $request->resolution_date : $degreeApplication->resolution_date,
        ]);

        return back()->with('success', 'Estado del expediente actualizado.');
    }
}
