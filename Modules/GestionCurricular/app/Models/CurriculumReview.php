<?php

namespace Modules\GestionCurricular\Models;

use Modules\Core\Models\Career;

use Modules\Core\Models\User;

use Modules\Core\Models\AcademicPeriod;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CurriculumReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_template_id', 'academic_period_id', 'reviewer_id',
        'action_type_id', 'career_id', 'status', 'observations'
    ];

    public function checklistTemplate(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }

    public function actionType(): BelongsTo
    {
        return $this->belongsTo(ActionType::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function evaluations(): HasMany
    {
        return $this->hasMany(ReviewEvaluation::class);
    }

    public function technicalReport(): HasOne
    {
        return $this->hasOne(TechnicalReport::class);
    }
}
