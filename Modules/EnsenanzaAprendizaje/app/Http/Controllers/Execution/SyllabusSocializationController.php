<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Execution;

use Illuminate\Http\Request;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreSyllabusSocializationRequest;
use Modules\EnsenanzaAprendizaje\Models\SyllabusSocialization;
use Modules\GestionCurricular\Models\Syllabus;

class SyllabusSocializationController extends Controller
{
    public function index(Request $request)
    {
        $query = SyllabusSocialization::with(['syllabus.subject', 'syllabus.career', 'registeredBy']);

        if ($request->filled('career_id')) {
            $query->whereHas('syllabus', function ($q) use ($request) {
                $q->where('career_id', $request->career_id);
            });
        }
        if ($request->filled('subject_id')) {
            $query->whereHas('syllabus', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        $socializations = $query->latest('date')->paginate(15);
        $users = User::orderBy('name')->limit(100)->get(['id', 'name']);

        $subjects = Subject::whereIn('id', Syllabus::distinct()->pluck('subject_id'))
            ->orderBy('code')
            ->get(['id', 'code', 'name']);

        return view('execution.socializations', compact('socializations', 'users', 'subjects'));
    }

    public function create()
    {
        $syllabi = Syllabus::with(['subject', 'career'])->orderByDesc('version')->get();
        $users = User::withRole('docente')->orderBy('name')->limit(100)->get(['id', 'name']);

        return view('execution.socializations-create', compact('syllabi', 'users'));
    }

    public function store(StoreSyllabusSocializationRequest $request)
    {
        $data = $request->validated();
        $data['registered_by'] = $request->filled('registered_by') ? $request->registered_by : $request->user()->id;

        SyllabusSocialization::create($data);

        return redirect()->route('execution.socializations.index')
            ->with('success', 'Socialización de sílabo registrada correctamente.');
    }
}
