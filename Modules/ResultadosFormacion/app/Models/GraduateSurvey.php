<?php

namespace Modules\ResultadosFormacion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GraduateSurvey extends Model
{
    use HasFactory;

    protected $table = 'graduate_surveys';

    protected $fillable = [
        'graduate_id', 'period', 'survey_date', 'employed',
        'job_related_to_career', 'competency_level_score', 'income', 'observations',
    ];

    protected function casts(): array
    {
        return [
            'survey_date' => 'date',
            'employed' => 'boolean',
            'job_related_to_career' => 'boolean',
            'competency_level_score' => 'decimal:2',
            'income' => 'decimal:2',
        ];
    }

    public function graduate(): BelongsTo
    {
        return $this->belongsTo(Graduate::class);
    }
}
