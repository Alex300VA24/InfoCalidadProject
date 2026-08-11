<?php

namespace Modules\GestionCurricular\Http\Controllers\CurriculumReview;

use Modules\Core\Http\Controllers\Controller;
use Modules\GestionCurricular\Http\Requests\StoreCurriculumReviewRequest;
use Modules\GestionCurricular\Http\Requests\CompleteReviewRequest;
use Modules\GestionCurricular\Models\CurriculumReview;
use Modules\GestionCurricular\Models\ChecklistTemplate;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\GestionCurricular\Models\ActionType;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    public function index()
    {
        $reviews = CurriculumReview::with(['checklistTemplate', 'academicPeriod', 'career', 'actionType', 'reviewer'])
            ->latest()
            ->paginate(10);

        return view('curriculum.reviews.index', compact('reviews'));
    }

    public function create()
    {
        $templates = ChecklistTemplate::where('is_active', true)->get();
        $periods = AcademicPeriod::all();
        $careers = Career::where('is_active', true)->orderBy('code')->get();
        $defaultCareer = Career::resolveDefault(request()->user());

        return view('curriculum.reviews.create', compact('templates', 'periods', 'careers', 'defaultCareer'));
    }

    public function store(StoreCurriculumReviewRequest $request)
    {
        $defaultCareer = Career::resolveDefault($request->user());

        $review = CurriculumReview::create([
            'checklist_template_id' => $request->checklist_template_id,
            'academic_period_id' => $request->academic_period_id,
            'career_id' => $request->career_id ?? $defaultCareer->id,
            'reviewer_id' => $request->user()->id,
            'status' => 'draft',
        ]);

        return redirect()->route('curriculum.reviews.evaluate', $review)
            ->with('success', 'Revisión curricular iniciada correctamente.');
    }

    public function evaluate(CurriculumReview $review)
    {
        $review->load(['checklistTemplate.criteria', 'evaluations']);

        $actionTypes = ActionType::all();

        return view('curriculum.reviews.evaluate', compact('review', 'actionTypes'));
    }

    public function saveEvaluation(Request $request, CurriculumReview $review)
    {
        $request->validate([
            'scores' => 'required|array',
            'scores.*' => 'required|integer|min:0|max:5',
            'observations' => 'nullable|array',
            'observations.*' => 'nullable|string|max:500',
        ]);

        $rows = [];
        foreach ($request->scores as $criterionId => $score) {
            $rows[] = [
                'curriculum_review_id' => $review->id,
                'criterion_id' => $criterionId,
                'score' => $score,
                'observations' => $request->observations[$criterionId] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $review->evaluations()->upsert(
            $rows,
            ['curriculum_review_id', 'criterion_id'],
            ['score', 'observations', 'updated_at']
        );

        return redirect()->route('curriculum.reviews.evaluate', $review)
            ->with('success', 'Evaluación guardada correctamente.');
    }

    public function complete(CompleteReviewRequest $request, CurriculumReview $review)
    {
        $review->update([
            'action_type_id' => $request->action_type_id,
            'status' => 'completed',
            'observations' => $request->observations,
        ]);

        return redirect()->route('curriculum.reviews.index')
            ->with('success', 'Revisión curricular completada. Ahora puede generar el Informe Técnico.');
    }

    public function show(CurriculumReview $review)
    {
        $review->load(['checklistTemplate.criteria', 'evaluations.criterion', 'actionType', 'academicPeriod', 'career', 'reviewer', 'technicalReport']);

        return view('curriculum.reviews.show', compact('review'));
    }
}
