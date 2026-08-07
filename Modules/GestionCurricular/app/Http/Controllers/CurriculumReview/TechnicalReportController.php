<?php

namespace Modules\GestionCurricular\Http\Controllers\CurriculumReview;

use Modules\Core\Http\Controllers\Controller;
use Modules\GestionCurricular\Http\Requests\StoreTechnicalReportRequest;
use Modules\GestionCurricular\Models\CurriculumReview;
use Modules\GestionCurricular\Models\TechnicalReport;
use Illuminate\Http\Request;

class TechnicalReportController extends Controller
{
    public function create(CurriculumReview $review)
    {
        $review->load(['checklistTemplate.criteria', 'evaluations.criterion', 'actionType', 'academicPeriod', 'career']);

        return view('curriculum.reports.create', compact('review'));
    }

    public function store(StoreTechnicalReportRequest $request, CurriculumReview $review)
    {
        $report = TechnicalReport::create([
            'curriculum_review_id' => $review->id,
            'preparer_id' => $request->user()->id,
            'content' => $request->content,
            'status' => 'draft',
        ]);

        return redirect()->route('curriculum.reports.show', $report)
            ->with('success', 'Informe Técnico creado correctamente.');
    }

    public function show(TechnicalReport $report)
    {
        $report->load(['curriculumReview.checklistTemplate.criteria', 'curriculumReview.evaluations.criterion', 'curriculumReview.actionType', 'curriculumReview.academicPeriod', 'curriculumReview.career', 'preparer']);

        return view('curriculum.reports.show', compact('report'));
    }

    public function edit(TechnicalReport $report)
    {
        $report->load(['curriculumReview.checklistTemplate.criteria', 'curriculumReview.evaluations.criterion', 'curriculumReview.actionType']);

        return view('curriculum.reports.edit', compact('report'));
    }

    public function update(Request $request, TechnicalReport $report)
    {
        $request->validate(['content' => 'required|string']);

        $report->update(['content' => $request->content]);

        return redirect()->route('curriculum.reports.show', $report)
            ->with('success', 'Informe Técnico actualizado correctamente.');
    }

    public function finalize(TechnicalReport $report)
    {
        $report->update(['status' => 'finalized']);

        return redirect()->route('curriculum.reports.show', $report)
            ->with('success', 'Informe Técnico finalizado y enviado para aprobación del Director de Escuela.');
    }

    public function pdf(TechnicalReport $report)
    {
        $report->load(['curriculumReview.checklistTemplate.criteria', 'curriculumReview.evaluations.criterion', 'curriculumReview.actionType', 'curriculumReview.academicPeriod', 'curriculumReview.career', 'preparer']);

        $pdf = app('dompdf.wrapper')->loadView('curriculum.reports.pdf', compact('report'));

        return $pdf->download("informe_tecnico_{$report->id}.pdf");
    }
}
