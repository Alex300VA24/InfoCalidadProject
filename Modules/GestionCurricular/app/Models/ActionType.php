<?php

namespace Modules\GestionCurricular\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ActionType extends Model
{
    protected $table = 'app_gestion_curricular.action_types';

    use HasFactory;

    protected $fillable = ['name', 'slug', 'description'];

    public function curriculumReviews(): HasMany
    {
        return $this->hasMany(CurriculumReview::class);
    }
}
