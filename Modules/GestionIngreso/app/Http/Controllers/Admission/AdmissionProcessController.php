<?php

namespace Modules\GestionIngreso\Http\Controllers\Admission;

use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\GestionIngreso\Http\Requests\StoreAdmissionProcessRequest;
use Modules\GestionIngreso\Models\AdmissionProcess;

class AdmissionProcessController extends Controller
{
    public function index()
    {
        $processes = AdmissionProcess::with(['academicPeriod', 'career'])
            ->withCount(['applicants as ingresantes' => fn ($q) => $q->where('status', 'ingresante')])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Admission/Processes/Index', [
            'processes' => $processes,
        ]);
    }

    public function create()
    {
        $periods = AcademicPeriod::all();
        $careers = Career::where('is_active', true)->orderBy('code')->get();
        $defaultCareer = Career::resolveDefault(request()->user());

        return Inertia::render('Admission/Processes/Create', [
            'periods' => $periods,
            'careers' => $careers,
            'defaultCareer' => $defaultCareer,
        ]);
    }

    public function store(StoreAdmissionProcessRequest $request)
    {
        AdmissionProcess::create($request->validated());

        return redirect()->route('admission.processes.index')
            ->with('success', 'Convocatoria de admisión creada correctamente.');
    }

    public function show(AdmissionProcess $process)
    {
        $process->load(['academicPeriod', 'career', 'applicants.career']);
        $process->loadCount([
            'applicants as total_applicants',
            'applicants as ingresantes' => fn ($q) => $q->where('status', 'ingresante'),
        ]);

        return Inertia::render('Admission/Processes/Show', [
            'process' => $process,
        ]);
    }

    public function edit(AdmissionProcess $process)
    {
        $periods = AcademicPeriod::all();
        $careers = Career::where('is_active', true)->orderBy('code')->get();

        return Inertia::render('Admission/Processes/Edit', [
            'process' => $process,
            'periods' => $periods,
            'careers' => $careers,
        ]);
    }

    public function update(StoreAdmissionProcessRequest $request, AdmissionProcess $process)
    {
        $process->update($request->validated());

        return redirect()->route('admission.processes.show', $process)
            ->with('success', 'Convocatoria de admisión actualizada correctamente.');
    }

    public function destroy(AdmissionProcess $process)
    {
        if ($process->applicants()->exists()) {
            return back()->with('error', 'No se puede eliminar una convocatoria con postulantes registrados.');
        }

        $process->delete();

        return redirect()->route('admission.processes.index')
            ->with('success', 'Convocatoria eliminada.');
    }

    public function finalize(AdmissionProcess $process)
    {
        if ($process->status === 'borrador') {
            $process->update(['status' => 'abierto']);

            return back()->with('success', 'Convocatoria abierta correctamente.');
        }

        if ($process->status === 'abierto') {
            $process->update(['status' => 'cerrado']);

            return back()->with('success', 'Convocatoria cerrada correctamente.');
        }

        return back()->with('error', 'La convocatoria ya se encuentra cerrada.');
    }
}
