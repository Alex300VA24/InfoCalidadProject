<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Research;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreResearchProjectRequest;
use Modules\EnsenanzaAprendizaje\Models\ResearchProject;

class ResearchProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = ResearchProject::with(['student.user', 'academicPeriod', 'advisor']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $projects = $query->latest('created_at')->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $statuses = ResearchProject::STATUSES;

        return Inertia::render('ResearchProjects/Index', [
            'projects' => $projects,
            'periods' => $periods,
            'statuses' => $statuses,
            'filters' => $request->only(['academic_period_id', 'status']),
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $students = Student::with('user')->where('estado', 'activo')->orderBy('codigo')->limit(100)->get();
        $advisors = User::withRole('docente')->orderBy('name')->limit(100)->get(['id', 'name']);
        $statuses = ResearchProject::STATUSES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return Inertia::render('ResearchProjects/Create', [
            'periods' => $periods,
            'students' => $students,
            'advisors' => $advisors,
            'statuses' => $statuses,
            'defaultPeriod' => $defaultPeriod,
        ]);
    }

    public function store(StoreResearchProjectRequest $request)
    {
        $data = $request->validated();
        unset($data['document']);

        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('research_projects', 'public');
            $data['document_path'] = $path;
        }

        ResearchProject::create($data);

        return redirect()->route('research.index')
            ->with('success', 'Proyecto de investigación registrado correctamente.');
    }

    public function show(ResearchProject $researchProject)
    {
        $researchProject->load(['student.user', 'academicPeriod', 'advisor']);

        return Inertia::render('ResearchProjects/Show', [
            'researchProject' => $researchProject,
        ]);
    }

    public function updateStatus(Request $request, ResearchProject $researchProject)
    {
        $request->validate([
            'status' => ['required', 'in:borrador,en_desarrollo,finalizado,aprobado,rechazado'],
            'score' => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        $researchProject->update([
            'status' => $request->status,
            'score' => $request->filled('score') ? $request->score : $researchProject->score,
        ]);

        return back()->with('success', 'Estado del proyecto actualizado.');
    }

    public function download(ResearchProject $researchProject)
    {
        if (! $researchProject->document_path || ! Storage::disk('public')->exists($researchProject->document_path)) {
            return back()->with('error', 'El documento no se encuentra disponible.');
        }

        return Storage::disk('public')->download($researchProject->document_path);
    }
}
