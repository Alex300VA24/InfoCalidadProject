<?php

namespace Modules\ResultadosFormacion\Http\Controllers\Graduate;

use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\ResultadosFormacion\Http\Requests\StoreGraduateSurveyRequest;
use Modules\ResultadosFormacion\Models\Graduate;
use Modules\ResultadosFormacion\Models\GraduateSurvey;

class GraduateSurveyController extends Controller
{
    public function create(Graduate $graduate)
    {
        $graduate->load(['student.user', 'surveys']);

        return Inertia::render('GraduateSurveys/Create', [
            'graduate' => $graduate,
        ]);
    }

    public function store(StoreGraduateSurveyRequest $request, Graduate $graduate)
    {
        GraduateSurvey::create($request->validated() + [
            'graduate_id' => $graduate->id,
        ]);

        return redirect()->route('graduates.show', $graduate)
            ->with('success', 'Encuesta de seguimiento registrada correctamente.');
    }
}
