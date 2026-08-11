<?php

namespace Modules\ResultadosFormacion\Http\Controllers\Graduate;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Student;
use Modules\ResultadosFormacion\Http\Requests\StoreGraduateRequest;
use Modules\ResultadosFormacion\Models\Graduate;

class GraduateController extends Controller
{
    public function index(Request $request)
    {
        $query = Graduate::with(['student.user']);

        if ($request->filled('work_status')) {
            $query->where('work_status', $request->work_status);
        }

        $graduates = $query->latest('created_at')->paginate(15);
        $workStatuses = Graduate::WORK_STATUSES;

        return view('graduates.index', compact('graduates', 'workStatuses'));
    }

    public function create()
    {
        $students = Student::with('user')->orderBy('codigo')->limit(100)->get();
        $workStatuses = Graduate::WORK_STATUSES;

        return view('graduates.create', compact('students', 'workStatuses'));
    }

    public function store(StoreGraduateRequest $request)
    {
        Graduate::updateOrCreate(
            ['student_id' => $request->student_id],
            $request->validated()
        );

        return redirect()->route('graduates.index')
            ->with('success', 'Registro de egresado guardado correctamente.');
    }

    public function show(Graduate $graduate)
    {
        $graduate->load(['student.user', 'surveys']);

        return view('graduates.show', compact('graduate'));
    }

    public function edit(Graduate $graduate)
    {
        $graduate->load(['student.user']);
        $students = Student::with('user')->orderBy('codigo')->limit(100)->get();
        $workStatuses = Graduate::WORK_STATUSES;

        return view('graduates.edit', compact('graduate', 'students', 'workStatuses'));
    }

    public function update(StoreGraduateRequest $request, Graduate $graduate)
    {
        $graduate->update($request->validated());

        return redirect()->route('graduates.show', $graduate)
            ->with('success', 'Registro de egresado actualizado correctamente.');
    }

    public function stats()
    {
        $total = Graduate::count();
        $byStatus = Graduate::select('work_status', DB::raw('count(*) as total'))
            ->groupBy('work_status')
            ->get()
            ->map(fn ($g) => [
                'status' => Graduate::WORK_STATUSES[$g->work_status] ?? $g->work_status,
                'total' => $g->total,
            ]);
        $averageIncome = (float) Graduate::whereNotNull('monthly_income')->avg('monthly_income');

        return view('graduates.stats', compact('total', 'byStatus', 'averageIncome'));
    }
}
