<?php

namespace Modules\GestionCurricular\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChecklistCriterion extends Model
{
    use HasFactory;

    protected $table = 'checklist_criteria';

    protected $fillable = ['checklist_template_id', 'code', 'description', 'max_score', 'weight', 'order'];

    public function template(): BelongsTo
    {
        return $this->belongsTo(ChecklistTemplate::class, 'checklist_template_id');
    }

    public function evaluations()
    {
        return $this->hasMany(ReviewEvaluation::class, 'criterion_id');
    }
}
