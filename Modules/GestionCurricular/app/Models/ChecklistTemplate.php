<?php

namespace Modules\GestionCurricular\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChecklistTemplate extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'name', 'version', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function criteria(): HasMany
    {
        return $this->hasMany(ChecklistCriterion::class);
    }

    public function curriculumReviews(): HasMany
    {
        return $this->hasMany(CurriculumReview::class);
    }
}
