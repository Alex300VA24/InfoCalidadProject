<?php

namespace Modules\GestionCurricular\Http\Controllers\Syllabus;

use Modules\Core\Http\Controllers\Controller;
use Modules\GestionCurricular\Http\Requests\StoreSyllabusRequest;
use Modules\GestionCurricular\Models\Syllabus;
use Modules\Core\Models\Subject;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class SyllabusController extends Controller
{
    public function index(Request $request)
    {
        $query = Syllabus::with(['career', 'subject', 'academicPeriod', 'teacher']);

        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }
        if ($request->filled('is_visado')) {
            $query->where('is_visado', $request->is_visado === 'yes');
        }

        $syllabi = $query->latest()->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all(['id', 'name']);
        $teachers = User::withRole('docente')->orderBy('name')->get(['id', 'name']);
        $careers = Career::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name']);
        $defaultCareer = Career::resolveDefault($request->user());
        $filters = $request->only([
            'career_id', 'subject_id', 'academic_period_id', 'teacher_id', 'is_visado',
        ]);

        return Inertia::render('Syllabi/Index', compact('syllabi', 'periods', 'teachers', 'careers', 'filters'));
    }

    public function create(Request $request)
    {
        $periods = AcademicPeriod::all(['id', 'name']);
        $teachers = User::withRole('docente')->orderBy('name')->get(['id', 'name']);
        $careers = Career::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name']);
        $defaultCareer = Career::resolveDefault($request->user());
        $defaultCareerId = $request->filled('career_id')
            ? $request->career_id
            : ($defaultCareer?->id);
        $subjects = Subject::where('career_id', $defaultCareerId)
            ->where('is_active', true)
            ->get(['id', 'code', 'name']);

        return Inertia::render('Syllabi/Create', compact('periods', 'teachers', 'careers', 'defaultCareerId', 'subjects'));
    }

    public function store(StoreSyllabusRequest $request)
    {
        $file = $request->file('file');
        $period = AcademicPeriod::find($request->academic_period_id);
        $path = $file->store("syllabi/{$period->name}", 'public');
        $defaultCareer = Career::resolveDefault($request->user());

        Syllabus::create([
            'career_id' => $request->career_id ?? $defaultCareer->id,
            'subject_id' => $request->subject_id,
            'academic_period_id' => $request->academic_period_id,
            'teacher_id' => $request->teacher_id,
            'filename' => $file->getClientOriginalName(),
            'file_path' => $path,
            'file_size' => $file->getSize(),
            'mime_type' => $file->getMimeType(),
        ]);

        return redirect()->route('syllabi.index')
            ->with('success', 'Sílabo subido correctamente.');
    }

    public function show(Syllabus $syllabus)
    {
        $syllabus->load(['career', 'subject', 'academicPeriod', 'teacher', 'visas.visor']);

        return Inertia::render('Syllabi/Show', compact('syllabus'));
    }

    public function download(Syllabus $syllabus)
    {
        if (!Storage::disk('public')->exists($syllabus->file_path)) {
            return back()->with('error', 'El archivo no se encuentra disponible.');
        }

        return Storage::disk('public')->download($syllabus->file_path, $syllabus->filename);
    }

    public function visa(Syllabus $syllabus)
    {
        $syllabus->update([
            'is_visado' => true,
            'visado_at' => now(),
        ]);

        $syllabus->visas()->create([
            'visor_id' => request()->user()->id,
            'status' => 'visado',
            'visado_at' => now(),
        ]);

        return back()->with('success', 'Sílabo visado correctamente.');
    }

    public function getSubjects()
    {
        $defaultCareer = Career::resolveDefault(request()->user());

        if (request()->filled('career_id')) {
            $defaultCareer = Career::find(request('career_id')) ?? $defaultCareer;
        }

        $subjects = Subject::where('career_id', $defaultCareer?->id)
            ->where('is_active', true)
            ->get(['id', 'code', 'name']);

        return response()->json($subjects);
    }
}
