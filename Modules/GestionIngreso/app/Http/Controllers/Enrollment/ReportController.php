<?php

namespace Modules\GestionIngreso\Http\Controllers\Enrollment;

use Illuminate\Http\Request;
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
            ->paginate(15);

        $counts = Enrollment::query()
            ->whereIn('student_id', $students->pluck('id'))
            ->selectRaw('student_id, count(*) as total')
            ->groupBy('student_id')
            ->pluck('total', 'student_id');

        return view('enrollment.reports.egresados', compact('students', 'counts'));
    }

    public function cronograma()
    {
        $periods = AcademicPeriod::orderBy('name')->get();

        $stats = Enrollment::query()
            ->selectRaw('academic_period_id, count(*) as total, count(distinct career_id) as careers')
            ->groupBy('academic_period_id')
            ->get()
            ->keyBy('academic_period_id');

        return view('enrollment.reports.cronograma', compact('periods', 'stats'));
    }
}
