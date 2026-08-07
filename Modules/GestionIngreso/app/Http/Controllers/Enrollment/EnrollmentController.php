<?php

namespace Modules\GestionIngreso\Http\Controllers\Enrollment;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\GestionIngreso\Http\Requests\StoreEnrollmentRequest;
use Modules\GestionIngreso\Models\Enrollment;
use Modules\GestionIngreso\Models\EnrollmentSubject;
use Modules\GestionIngreso\Models\PaymentOrder;

class EnrollmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Enrollment::with(['student.user', 'academicPeriod', 'career', 'subjects.subject']);

        if ($request->filled('academic_period_id')) {
            $query->where('academic_period_id', $request->academic_period_id);
        }
        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->latest()->paginate(15);
        $periods = AcademicPeriod::all();
        $careers = Career::where('is_active', true)->orderBy('code')->get();

        return view('enrollment.index', compact('enrollments', 'periods', 'careers'));
    }

    public function create()
    {
        $students = Student::with('user')
            ->where('estado', 'activo')
            ->orderBy('codigo')
            ->get();
        $periods = AcademicPeriod::all();
        $careers = Career::where('is_active', true)->orderBy('code')->get();
        $defaultCareer = Career::resolveDefault(request()->user());

        return view('enrollment.create', compact('students', 'periods', 'careers', 'defaultCareer'));
    }

    public function store(StoreEnrollmentRequest $request)
    {
        $student = Student::with('user')->find($request->student_id);

        $issues = $this->checkRequirements($student);

        if (! empty($issues)) {
            return back()->withInput()->with('error', implode(' ', $issues));
        }

        return DB::transaction(function () use ($request, $student) {
            $code = 'MAT-'.date('Y').'-'.Str::padLeft(Enrollment::max('id') + 1, 4, '0');

            $enrollment = Enrollment::create([
                'code' => $code,
                'student_id' => $student->id,
                'academic_period_id' => $request->academic_period_id,
                'career_id' => $request->career_id,
                'status' => 'matriculado',
                'enrolled_at' => now(),
            ]);

            foreach ($request->subjects as $subjectId) {
                EnrollmentSubject::create([
                    'enrollment_id' => $enrollment->id,
                    'subject_id' => $subjectId,
                    'status' => 'regular',
                ]);
            }

            $payment = PaymentOrder::create([
                'student_id' => $student->id,
                'enrollment_id' => $enrollment->id,
                'concept' => 'Matrícula '.$enrollment->academicPeriod->name,
                'amount' => $request->matricula_fee ?? 0,
                'status' => 'pendiente',
            ]);

            $this->generateDocuments($enrollment, $payment);

            return redirect()->route('enrollment.show', $enrollment)
                ->with('success', 'Matrícula registrada correctamente.');
        });
    }

    public function subjects(Request $request)
    {
        $careerId = $request->filled('career_id') ? $request->career_id : Career::resolveDefault($request->user())?->id;

        $subjects = Subject::where('career_id', $careerId)
            ->where('is_active', true)
            ->get(['id', 'code', 'name']);

        return response()->json($subjects);
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student.user', 'academicPeriod', 'career', 'subjects.subject', 'paymentOrders']);

        return view('enrollment.show', compact('enrollment'));
    }

    public function ficha(Enrollment $enrollment)
    {
        if (! $enrollment->ficha_path || ! Storage::disk('public')->exists($enrollment->ficha_path)) {
            $this->generateFicha($enrollment);
        }

        return Storage::disk('public')->download($enrollment->ficha_path, "ficha_matricula_{$enrollment->code}.pdf");
    }

    public function ordenPago(Enrollment $enrollment)
    {
        $payment = $enrollment->paymentOrders()->first();

        if (! $payment || ! $payment->pdf_path || ! Storage::disk('public')->exists($payment->pdf_path)) {
            return back()->with('error', 'La orden de pago aún no está disponible.');
        }

        return Storage::disk('public')->download($payment->pdf_path, "orden_pago_{$enrollment->code}.pdf");
    }

    public function padron(Request $request)
    {
        $period = $request->filled('academic_period_id')
            ? AcademicPeriod::find($request->academic_period_id)
            : AcademicPeriod::where('is_active', true)->first();

        $rows = EnrollmentSubject::with(['enrollment.student.user', 'enrollment.career', 'subject'])
            ->whereHas('enrollment', fn ($q) => $q->where('academic_period_id', $period?->id))
            ->whereHas('enrollment', fn ($q) => $q->where('status', 'matriculado'))
            ->get()
            ->groupBy(fn ($row) => $row->subject?->name ?? 'Sin asignatura');

        $periods = AcademicPeriod::all();

        return view('enrollment.padron', compact('rows', 'period', 'periods'));
    }

    public function registerPayment(Request $request, PaymentOrder $paymentOrder)
    {
        $request->validate([
            'receipt_number' => ['required', 'string', 'max:100'],
        ]);

        $paymentOrder->update([
            'status' => 'pagado',
            'receipt_number' => $request->receipt_number,
        ]);

        return back()->with('success', 'Pago registrado correctamente.');
    }

    private function checkRequirements(Student $student): array
    {
        $issues = [];

        if ($student->estado !== 'activo') {
            $issues[] = 'El estudiante no se encuentra activo.';
        }

        if (PaymentOrder::where('student_id', $student->id)->where('status', 'pendiente')->exists()) {
            $issues[] = 'El estudiante tiene órdenes de pago pendientes.';
        }

        if (Schema::hasTable('app_ensenanza_aprendizaje.academic_tutoring')) {
            $pendingTutoring = DB::table('app_ensenanza_aprendizaje.academic_tutoring')
                ->where('student_id', $student->id)
                ->where('status', 'pendiente')
                ->exists();

            if ($pendingTutoring) {
                $issues[] = 'El estudiante tiene tutorías académicas pendientes.';
            }
        }

        if (Schema::hasTable('app_ensenanza_aprendizaje.remedial_programs')) {
            $pendingRemedial = DB::table('app_ensenanza_aprendizaje.remedial_programs')
                ->where('student_id', $student->id)
                ->where('status', 'pendiente')
                ->exists();

            if ($pendingRemedial) {
                $issues[] = 'El estudiante tiene programas de nivelación pendientes.';
            }
        }

        return $issues;
    }

    private function generateDocuments(Enrollment $enrollment, PaymentOrder $payment): void
    {
        $this->generateFicha($enrollment);
        $this->generateOrdenPago($payment);
    }

    private function generateFicha(Enrollment $enrollment): void
    {
        $enrollment->load(['student.user', 'academicPeriod', 'career', 'subjects.subject']);
        $pdf = app('dompdf.wrapper')->loadView('enrollment.pdf.ficha', compact('enrollment'));
        $path = "enrollments/{$enrollment->code}/ficha.pdf";

        Storage::disk('public')->put($path, $pdf->output());
        $enrollment->update(['ficha_path' => $path]);
    }

    private function generateOrdenPago(PaymentOrder $payment): void
    {
        $payment->load(['student.user', 'enrollment']);
        $pdf = app('dompdf.wrapper')->loadView('enrollment.pdf.orden_pago', compact('payment'));
        $path = "enrollments/{$payment->enrollment->code}/orden_pago.pdf";

        Storage::disk('public')->put($path, $pdf->output());
        $payment->update(['pdf_path' => $path]);
    }
}
