<?php

namespace Modules\ResultadosFormacion\Http\Controllers\Degree;

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

        return view('degree.acts.index', compact('degreeApplication', 'acts'));
    }

    public function create(DegreeApplication $degreeApplication)
    {
        $degreeApplication->load(['student.user']);
        $actTypes = DegreeCommitteeAct::ACT_TYPES;
        $results = DegreeCommitteeAct::RESULTS;

        return view('degree.acts.create', compact('degreeApplication', 'actTypes', 'results'));
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
