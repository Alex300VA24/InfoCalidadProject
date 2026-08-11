<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\User;

class TeacherPerformanceEvaluation extends Model
{
    use HasFactory;

    public const SOURCES = [
        'encuesta_estudiante' => 'Encuesta al estudiante',
        'coordinacion' => 'Coordinación',
        'director' => 'Director de escuela',
        'autoevaluacion' => 'Autoevaluación',
    ];

    protected $table = 'app_ensenanza_aprendizaje.teacher_performance_evaluations';

    protected $fillable = [
        'teacher_id', 'academic_period_id', 'score', 'source',
        'observations', 'evaluated_at',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'evaluated_at' => 'date',
        ];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function sourceLabel(): string
    {
        return self::SOURCES[$this->source] ?? $this->source;
    }

    public function getSourceLabelAttribute(): string
    {
        return $this->sourceLabel();
    }
}
