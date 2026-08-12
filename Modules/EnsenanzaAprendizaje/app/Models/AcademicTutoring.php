<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;

class AcademicTutoring extends Model
{
    use HasFactory;

    public const TYPES = [
        'acompanamiento' => 'Acompañamiento',
        'nivelacion' => 'Nivelación de Competencias',
        'orientacion' => 'Orientación Vocacional',
    ];

    public const STATUSES = [
        'pendiente' => 'Pendiente',
        'atendida' => 'Atendida',
        'cancelada' => 'Cancelada',
    ];

    protected $table = 'app_ensenanza_aprendizaje.academic_tutoring';

    protected $fillable = [
        'student_id', 'academic_period_id', 'tutor_id',
        'tutoring_date', 'type', 'reason', 'outcome', 'status',
    ];

    protected function casts(): array
    {
        return ['tutoring_date' => 'date'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function tutor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tutor_id');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->typeLabel();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
