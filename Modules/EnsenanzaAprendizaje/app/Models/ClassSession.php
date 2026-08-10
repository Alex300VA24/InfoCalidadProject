<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;

class ClassSession extends Model
{
    protected $table = 'app_ensenanza_aprendizaje.class_sessions';

    use HasFactory;

    public const STATUSES = [
        'planificada' => 'Planificada',
        'realizada' => 'Realizada',
        'reprogramada' => 'Reprogramada',
        'cancelada' => 'Cancelada',
    ];

    protected $fillable = [
        'subject_id', 'academic_period_id', 'teacher_id',
        'topic', 'hours', 'session_date', 'notes', 'status',
    ];

    protected function casts(): array
    {
        return [
            'hours' => 'decimal:2',
            'session_date' => 'date',
        ];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
