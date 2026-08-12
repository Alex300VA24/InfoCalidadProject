<?php

namespace Modules\ResultadosFormacion\Http\Controllers\Degree;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Student;
use Modules\ResultadosFormacion\Http\Requests\StoreCertificateRequest;
use Modules\ResultadosFormacion\Models\Certificate;

class CertificateController extends Controller
{
    public function index(Request $request)
    {
        $query = Certificate::with(['student.user']);

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        $certificates = $query->latest('issued_at')->paginate(15)->withQueryString();
        $types = Certificate::TYPES;
        $students = Student::with('user')->orderBy('codigo')->limit(100)->get();

        return Inertia::render('Certificates/Index', [
            'certificates' => $certificates,
            'types' => $types,
            'students' => $students,
            'filters' => $request->only(['type', 'student_id']),
        ]);
    }

    public function create()
    {
        $students = Student::with('user')->orderBy('codigo')->limit(100)->get();
        $types = Certificate::TYPES;

        return Inertia::render('Certificates/Create', [
            'students' => $students,
            'types' => $types,
        ]);
    }

    public function store(StoreCertificateRequest $request)
    {
        return DB::transaction(function () use ($request) {
            $code = 'CER-'.date('Y').'-'.Str::padLeft(Certificate::max('id') + 1, 5, '0');

            $certificate = Certificate::create($request->validated() + [
                'code' => $code,
                'status' => 'emitido',
                'issued_by' => $request->filled('issued_by') ? $request->issued_by : $request->user()->name,
            ]);

            $this->generatePdf($certificate);

            return redirect()->route('degree.certificates.show', $certificate)
                ->with('success', 'Certificado emitido correctamente.');
        });
    }

    public function show(Certificate $certificate)
    {
        $certificate->load(['student.user']);

        return Inertia::render('Certificates/Show', [
            'certificate' => $certificate,
        ]);
    }

    public function download(Certificate $certificate)
    {
        if (! $certificate->pdf_path || ! Storage::disk('public')->exists($certificate->pdf_path)) {
            $this->generatePdf($certificate);
        }

        return Storage::disk('public')->download($certificate->pdf_path, "{$certificate->code}.pdf");
    }

    private function generatePdf(Certificate $certificate): void
    {
        $certificate->load(['student.user']);
        $pdf = app('dompdf.wrapper')->loadView('degree.certificates.pdf', compact('certificate'));
        $path = "certificates/{$certificate->code}.pdf";

        Storage::disk('public')->put($path, $pdf->output());
        $certificate->update(['pdf_path' => $path]);
    }
}
