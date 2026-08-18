<?php

namespace Modules\EnsenanzaAprendizaje\Http\Controllers\Evaluation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\EnsenanzaAprendizaje\Http\Requests\StoreStudentEvaluationRequest;
use Modules\EnsenanzaAprendizaje\Models\OfficialAct;
use Modules\EnsenanzaAprendizaje\Models\StudentEvaluation;

class EvaluationController extends Controller
{
    public function index(Request $request)
    {
        $query = StudentEvaluation::with(['student.user', 'subject', 'academicPeriod']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $evaluations = $query->latest('evaluation_date')->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $students = Student::with('user')->orderBy('codigo')->limit(100)->get();

        return Inertia::render('Evaluations/Index', [
            'evaluations' => $evaluations,
            'periods' => $periods,
            'subjects' => $subjects,
            'students' => $students,
            'filters' => $request->only(['academic_period_id', 'subject_id', 'student_id']),
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $students = Student::with('user')->where('estado', 'activo')->orderBy('codigo')->limit(100)->get();
        $types = StudentEvaluation::TYPES;
        $defaultPeriod = AcademicPeriod::where('is_active', true)->first() ?? $periods->first();

        return Inertia::render('Evaluations/Create', [
            'periods' => $periods,
            'subjects' => $subjects,
            'students' => $students,
            'types' => $types,
            'defaultPeriod' => $defaultPeriod,
        ]);
    }

    public function store(StoreStudentEvaluationRequest $request)
    {
        StudentEvaluation::create($request->validated() + [
            'registered_by' => $request->user()->id,
        ]);

        return redirect()->route('evaluations.index')
            ->with('success', 'Evaluación registrada correctamente.');
    }

    public function show(StudentEvaluation $evaluation)
    {
        $evaluation->load(['student.user', 'subject.career', 'academicPeriod', 'registrar']);

        return Inertia::render('Evaluations/Show', [
            'evaluation' => $evaluation,
        ]);
    }

    public function record(Request $request)
    {
        $period = $request->filled('academic_period_id')
            ? AcademicPeriod::find($request->academic_period_id)
            : AcademicPeriod::where('is_active', true)->first();

        $subject = $request->filled('subject_id') ? Subject::find($request->subject_id) : null;

        $rows = collect();
        if ($period && $subject) {
            $students = Student::with('user')
                ->whereIn('id', StudentEvaluation::where('academic_period_id', $period->id)
                    ->where('subject_id', $subject->id)
                    ->select('student_id')
                    ->distinct())
                ->orderBy('codigo')
                ->get();

            $evaluationsByStudent = $this->evaluationsFor($period, $subject, $students->pluck('id'));

            $rows = $students->map(function (Student $student) use ($evaluationsByStudent) {
                $evaluations = $evaluationsByStudent->get($student->id) ?? collect();

                $weights = ['practica_1' => 10, 'practica_2' => 10, 'practica_3' => 10, 'examen_parcial' => 30, 'examen_final' => 40];
                $weighted = 0;
                $totalWeight = 0;
                foreach ($weights as $type => $weight) {
                    if ($evaluations->has($type)) {
                        $weighted += (float) $evaluations->get($type)->score * $weight;
                        $totalWeight += $weight;
                    }
                }

                return [
                    'student' => $student,
                    'evaluations' => $evaluations,
                    'final' => $totalWeight > 0 ? round($weighted / $totalWeight, 2) : null,
                ];
            });
        }

        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();

        return Inertia::render('Evaluations/Record', [
            'rows' => $rows,
            'period' => $period,
            'subject' => $subject,
            'periods' => $periods,
            'subjects' => $subjects,
        ]);
    }

    public function actaPdf(Request $request)
    {
        $period = AcademicPeriod::findOrFail($request->academic_period_id);
        $subject = Subject::findOrFail($request->subject_id);

        $rows = $this->actaRows($period, $subject);

        $pdf = app('dompdf.wrapper')->loadView('evaluation.pdf.acta', compact('rows', 'period', 'subject'));

        return $pdf->stream("acta_{$subject->code}_{$period->name}.pdf");
    }

    public function actas(Request $request)
    {
        $query = OfficialAct::with(['subject', 'academicPeriod', 'teacher']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('subject_id')) {
            $query->where('subject_id', $request->subject_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $acts = $query->latest()->paginate(15)->withQueryString();
        $periods = AcademicPeriod::all();
        $subjects = Subject::where('is_active', true)->orderBy('code')->get();
        $statuses = OfficialAct::STATUSES;

        return Inertia::render('Evaluations/Actas', [
            'acts' => $acts,
            'periods' => $periods,
            'subjects' => $subjects,
            'statuses' => $statuses,
            'filters' => $request->only(['academic_period_id', 'subject_id', 'status']),
        ]);
    }

    public function generarActa(Request $request)
    {
        $request->validate([
            'academic_period_id' => ['required', 'exists:academic_periods,id'],
            'subject_id' => ['required', 'exists:subjects,id'],
        ]);

        $period = AcademicPeriod::findOrFail($request->academic_period_id);
        $subject = Subject::findOrFail($request->subject_id);

        $rows = $this->actaRows($period, $subject);

        $pdf = app('dompdf.wrapper')->loadView('evaluation.pdf.acta', compact('rows', 'period', 'subject'));

        $filename = 'acta_'.$subject->code.'_'.str_replace(['/', ' '], '-', $period->name).'_'.now()->format('Ymd_His').'.pdf';
        $path = 'actas/'.$filename;
        Storage::disk('local')->put($path, $pdf->output());

        $act = OfficialAct::updateOrCreate(
            [
                'subject_id' => $subject->id,
                'academic_period_id' => $period->id,
                'teacher_id' => $request->user()->id,
            ],
            [
                'pdf_path' => $path,
                'status' => 'borrador',
            ]
        );

        return redirect()->route('evaluations.actas')
            ->with('success', "Acta del curso {$subject->code} generada y guardada correctamente.");
    }

    public function cerrarActa(Request $request, OfficialAct $officialAct)
    {
        $officialAct->update([
            'status' => 'cerrado',
            'closed_at' => now(),
            'closed_by' => $request->user()->id,
        ]);

        return back()->with('success', 'Acta cerrada correctamente.');
    }

    public function downloadAct(OfficialAct $officialAct)
    {
        if ($officialAct->pdf_path && Storage::disk('local')->exists($officialAct->pdf_path)) {
            return Storage::disk('local')->download($officialAct->pdf_path, basename($officialAct->pdf_path));
        }

        return back()->with('error', 'El archivo del acta no se encuentra.');
    }

    private function actaRows(AcademicPeriod $period, Subject $subject)
    {
        $students = Student::with('user')
            ->whereIn('id', StudentEvaluation::where('academic_period_id', $period->id)
                ->where('subject_id', $subject->id)
                ->select('student_id')
                ->distinct())
            ->orderBy('codigo')
            ->get();

        $evaluationsByStudent = $this->evaluationsFor($period, $subject, $students->pluck('id'));

        return $students->map(function (Student $student) use ($evaluationsByStudent) {
            $evaluations = $evaluationsByStudent->get($student->id) ?? collect();

            $weights = ['practica_1' => 10, 'practica_2' => 10, 'practica_3' => 10, 'examen_parcial' => 30, 'examen_final' => 40];
            $weighted = 0;
            $totalWeight = 0;
            foreach ($weights as $type => $weight) {
                if ($evaluations->has($type)) {
                    $weighted += (float) $evaluations->get($type)->score * $weight;
                    $totalWeight += $weight;
                }
            }

            return [
                'student' => [
                    'id' => $student->id,
                    'codigo' => $student->codigo,
                    'full_name' => $student->fullName(),
                ],
                'p1' => $evaluations->get('practica_1')?->score,
                'p2' => $evaluations->get('practica_2')?->score,
                'p3' => $evaluations->get('practica_3')?->score,
                'parcial' => $evaluations->get('examen_parcial')?->score,
                'final' => $evaluations->get('examen_final')?->score,
                'promedio' => $totalWeight > 0 ? round($weighted / $totalWeight, 2) : null,
            ];
        });
    }

    private function evaluationsFor(AcademicPeriod $period, Subject $subject, $studentIds): \Illuminate\Support\Collection
    {
        return StudentEvaluation::whereIn('student_id', $studentIds)
            ->where('subject_id', $subject->id)
            ->where('academic_period_id', $period->id)
            ->get()
            ->groupBy('student_id')
            ->map(fn ($items) => $items->keyBy('evaluation_type'));
    }
}
