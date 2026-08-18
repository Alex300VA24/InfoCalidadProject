<?php

namespace Modules\ResultadosFormacion\Http\Controllers\Degree;

use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\ResultadosFormacion\Http\Requests\StoreDegreeCommitteeActRequest;
use Modules\ResultadosFormacion\Models\DegreeApplication;
use Modules\ResultadosFormacion\Models\DegreeCommitteeAct;

class CommitteeActController extends Controller
{
    public function index(DegreeApplication $degreeApplication)
    {
        $degreeApplication->load(['student.user']);

        $acts = DegreeCommitteeAct::where('degree_application_id', $degreeApplication->id)
            ->latest('session_date')
            ->paginate(10);

        return Inertia::render('CommitteeActs/Index', [
            'degreeApplication' => $degreeApplication,
            'acts' => $acts,
        ]);
    }

    public function create(DegreeApplication $degreeApplication)
    {
        $degreeApplication->load(['student.user']);
        $actTypes = DegreeCommitteeAct::ACT_TYPES;
        $results = DegreeCommitteeAct::RESULTS;

        return Inertia::render('CommitteeActs/Create', [
            'degreeApplication' => $degreeApplication,
            'actTypes' => $actTypes,
            'results' => $results,
        ]);
    }

    public function store(StoreDegreeCommitteeActRequest $request, DegreeApplication $degreeApplication)
    {
        DegreeCommitteeAct::create($request->validated() + [
            'degree_application_id' => $degreeApplication->id,
        ]);

        return redirect()->route('degree.applications.acts.index', $degreeApplication)
            ->with('success', 'Acta de grado registrada correctamente.');
    }
}
