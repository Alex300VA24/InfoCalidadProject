<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;

class RemedialProgram extends Model
{
    use HasFactory;

    public const STATUSES = [
        'pendiente' => 'Pendiente',
        'en_curso' => 'En curso',
        'completado' => 'Completado',
        'reprobado' => 'Reprobado',
    ];

    protected $table = 'app_ensenanza_aprendizaje.remedial_programs';

    protected $fillable = [
        'student_id', 'academic_period_id', 'subject_id',
        'description', 'plan_path', 'status',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->statusLabel();
    }

    public function isPending(): bool
    {
        return $this->status === 'pendiente';
    }
}
