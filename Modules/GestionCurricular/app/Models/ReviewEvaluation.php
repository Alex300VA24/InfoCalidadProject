<?php

namespace Modules\GestionCurricular\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReviewEvaluation extends Model
{
    use HasFactory;

    protected $fillable = ['curriculum_review_id', 'criterion_id', 'score', 'observations'];

    public function curriculumReview(): BelongsTo
    {
        return $this->belongsTo(CurriculumReview::class);
    }

    public function criterion(): BelongsTo
    {
        return $this->belongsTo(ChecklistCriterion::class, 'criterion_id');
    }
}
