<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;

class TeachingLoad extends Model
{
    use HasFactory;

    public const STATUSES = [
        'asignado' => 'Asignado',
        'confirmado' => 'Confirmado',
        'reemplazo' => 'Reemplazo',
    ];

    protected $table = 'app_ensenanza_aprendizaje.teaching_loads';

    protected $fillable = [
        'teacher_id', 'subject_id', 'academic_period_id', 'section', 'hours', 'status',
    ];

    protected function casts(): array
    {
        return ['hours' => 'decimal:2'];
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->statusLabel();
    }
}
