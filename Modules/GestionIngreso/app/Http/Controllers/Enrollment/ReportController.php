<?php

namespace Modules\GestionIngreso\Http\Controllers\Enrollment;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\GestionIngreso\Models\Enrollment;

class ReportController extends Controller
{
    public function egresados(Request $request)
    {
        $students = Student::with('user')
            ->when($request->filled('career_id'), fn ($q) => $q->whereHas('user', fn ($u) => $u->where('career_id', $request->career_id)))
            ->orderBy('codigo')
            ->paginate(15)
            ->withQueryString();

        $counts = Enrollment::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->selectRaw('student_id, count(*) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id');

        return Inertia::render('Enrollment/Reports/Egresados', [
            'students' => $students,
            'counts' => $counts,
        ]);
    }

    public function cronograma()
    {
        $periods = AcademicPeriod::orderBy('name')->get();

        $stats = Enrollment::query()
            ->selectRaw('academic_period_id, count(*) as total, count(distinct career_id) as careers')
            ->groupBy('academic_period_id')
            ->get()
            ->keyBy('academic_period_id');

        return Inertia::render('Enrollment/Reports/Cronograma', [
            'periods' => $periods,
            'stats' => $stats,
        ]);
    }
}
