<?php

namespace Modules\GestionCurricular\Http\Controllers\CurriculumReview;

use Inertia\Inertia;
use Modules\Core\Http\Controllers\Controller;
use Modules\GestionCurricular\Http\Requests\ApproveReportRequest;
use Modules\GestionCurricular\Models\TechnicalReport;
use Modules\GestionCurricular\Models\Approval;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    public function index()
    {
        $reports = TechnicalReport::with(['curriculumReview.career', 'curriculumReview.academicPeriod', 'curriculumReview.actionType', 'preparer', 'approval'])
            ->where('status', 'finalized')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Curriculum/Approvals/Index', [
            'reports' => $reports,
        ]);
    }

    public function review(TechnicalReport $report)
    {
        $report->load(['curriculumReview.checklistTemplate.criteria', 'curriculumReview.evaluations.criterion', 'curriculumReview.actionType', 'curriculumReview.academicPeriod', 'curriculumReview.career', 'preparer', 'approval']);

        return Inertia::render('Curriculum/Approvals/Review', [
            'report' => $report,
        ]);
    }

    public function approve(ApproveReportRequest $request, TechnicalReport $report)
    {
        $report->approval()->create([
            'approver_id' => $request->user()->id,
            'decision' => $request->decision,
            'comments' => $request->comments,
            'approved_at' => now(),
        ]);

        $message = $request->decision === 'approved'
            ? 'Informe Técnico aprobado correctamente.'
            : 'Informe Técnico observado. Se ha notificado al Presidente de Cotejo.';

        return redirect()->route('curriculum.approvals.index')
            ->with('success', $message);
    }
}
