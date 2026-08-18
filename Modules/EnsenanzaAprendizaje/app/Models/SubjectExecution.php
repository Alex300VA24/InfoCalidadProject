<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;
use Modules\GestionCurricular\Models\Syllabus;

class SubjectExecution extends Model
{
    use HasFactory;

    public const STATUSES = [
        'en_ejecucion' => 'En ejecución',
        'cerrado' => 'Cerrado',
    ];

    protected $table = 'app_ensenanza_aprendizaje.subject_executions';

    protected $fillable = [
        'subject_id', 'teacher_id', 'academic_period_id', 'syllabus_id',
        'progress_pct', 'status',
    ];

    protected function casts(): array
    {
        return ['progress_pct' => 'decimal:2'];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function syllabus(): BelongsTo
    {
        return $this->belongsTo(Syllabus::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->statusLabel();
    }

    public function isClosed(): bool
    {
        return $this->status === 'cerrado';
    }
}
