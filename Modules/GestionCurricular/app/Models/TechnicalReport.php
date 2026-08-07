<?php

namespace Modules\GestionCurricular\Models;

use Modules\Core\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class TechnicalReport extends Model
{
    use HasFactory;

    protected $fillable = ['curriculum_review_id', 'preparer_id', 'content', 'status'];

    public function curriculumReview(): BelongsTo
    {
        return $this->belongsTo(CurriculumReview::class);
    }

    public function preparer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'preparer_id');
    }

    public function approval(): HasOne
    {
        return $this->hasOne(Approval::class);
    }
}
