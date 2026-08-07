<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;

class StudentEvaluation extends Model
{
    use HasFactory;

    public const TYPES = [
        'practica_1' => 'Práctica 1',
        'practica_2' => 'Práctica 2',
        'practica_3' => 'Práctica 3',
        'examen_parcial' => 'Examen Parcial',
        'examen_final' => 'Examen Final',
        'extraordinario' => 'Examen Extraordinario',
    ];

    protected $fillable = [
        'student_id', 'subject_id', 'academic_period_id',
        'evaluation_type', 'score', 'evaluation_date', 'observations', 'registered_by',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
            'evaluation_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function registrar(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->evaluation_type] ?? $this->evaluation_type;
    }
}
