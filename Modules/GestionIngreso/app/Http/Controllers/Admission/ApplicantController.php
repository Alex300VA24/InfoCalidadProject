<?php

namespace Modules\GestionIngreso\Http\Controllers\Admission;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\Career;
use Modules\Core\Models\Role;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;
use Modules\GestionIngreso\Http\Requests\StoreApplicantRequest;
use Modules\GestionIngreso\Models\AdmissionProcess;
use Modules\GestionIngreso\Models\Applicant;

class ApplicantController extends Controller
{
    public function index(Request $request)
    {
        $query = Applicant::with(['admissionProcess', 'career']);

        if ($request->filled('admission_process_id')) {
            $query->where('admission_process_id', $request->admission_process_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('career_id')) {
            $query->where('career_id', $request->career_id);
        }

        $applicants = $query->latest()->paginate(15)->withQueryString();
        $processes = AdmissionProcess::latest()->get();
        $careers = Career::where('is_active', true)->orderBy('code')->get();

        return Inertia::render('Admission/Applicants/Index', [
            'applicants' => $applicants,
            'processes' => $processes,
            'careers' => $careers,
            'filters' => $request->only(['admission_process_id', 'career_id', 'status']),
        ]);
    }

    public function create()
    {
        $processes = AdmissionProcess::with('academicPeriod')
            ->where('status', '!=', 'cerrado')
            ->latest()
            ->get();
        $careers = Career::where('is_active', true)->orderBy('code')->get();

        return Inertia::render('Admission/Applicants/Create', [
            'processes' => $processes,
            'careers' => $careers,
        ]);
    }

    public function store(StoreApplicantRequest $request)
    {
        Applicant::create($request->validated());

        return redirect()->route('admission.applicants.index')
            ->with('success', 'Postulante registrado correctamente.');
    }

    public function show(Applicant $applicant)
    {
        $applicant->load(['admissionProcess.academicPeriod', 'admissionProcess.career', 'career']);

        return Inertia::render('Admission/Applicants/Show', [
            'applicant' => $applicant,
        ]);
    }

    public function saveResult(Request $request, Applicant $applicant)
    {
        $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'status' => 'required|in:ingresante,no_ingresante',
        ]);

        return DB::transaction(function () use ($request, $applicant) {
            $applicant->update([
                'score' => $request->score,
                'status' => $request->status,
            ]);

            if ($request->status === 'ingresante') {
                $this->convertToStudent($applicant);
                $this->storeConstancia($applicant);
            }

            return redirect()->route('admission.applicants.show', $applicant)
                ->with('success', 'Resultado registrado correctamente.');
        });
    }

    public function constancia(Applicant $applicant)
    {
        if ($applicant->status !== 'ingresante' || ! $applicant->constancia_path) {
            return back()->with('error', 'La constancia aún no está disponible.');
        }

        if (! Storage::disk('public')->exists($applicant->constancia_path)) {
            return back()->with('error', 'El archivo no se encuentra disponible.');
        }

        return Storage::disk('public')->download($applicant->constancia_path, "constancia_ingreso_{$applicant->dni}.pdf");
    }

    private function convertToStudent(Applicant $applicant): void
    {
        $user = User::where('dni', $applicant->dni)->first();

        if (! $user) {
            $email = $applicant->email ?? "{$applicant->dni}@ingresante.edu.pe";
            $roleId = Role::where('slug', 'estudiante')->value('id');

            $user = User::create([
                'name' => $applicant->fullName(),
                'email' => $email,
                'password' => Hash::make('password'),
                'role_id' => $roleId,
                'career_id' => $applicant->career_id,
                'dni' => $applicant->dni,
                'telefono' => $applicant->telefono,
            ]);
        }

        if (! Student::where('user_id', $user->id)->exists()) {
            Student::create([
                'user_id' => $user->id,
                'codigo' => $this->nextStudentCode(),
                'ciclo' => 'I',
                'estado' => 'activo',
            ]);
        }
    }

    private function storeConstancia(Applicant $applicant): void
    {
        $applicant->load(['admissionProcess.academicPeriod', 'admissionProcess.career', 'career']);

        $pdf = app('dompdf.wrapper')->loadView('admission.applicants.constancia', compact('applicant'));
        $path = "constancias/ingreso_{$applicant->id}.pdf";

        Storage::disk('public')->put($path, $pdf->output());
        $applicant->update(['constancia_path' => $path]);
    }

    private function nextStudentCode(): string
    {
        $year = date('Y');
        $last = Student::where('codigo', 'like', "{$year}-%")
            ->orderByDesc('codigo')
            ->value('codigo');

        $seq = $last ? (int) substr($last, strlen($year) + 1) + 1 : 1;

        return $year.'-'.str_pad($seq, 5, '0', STR_PAD_LEFT);
    }
}
