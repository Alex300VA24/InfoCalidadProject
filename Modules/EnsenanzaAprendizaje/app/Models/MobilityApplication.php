<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;

class MobilityApplication extends Model
{
    protected $table = 'app_ensenanza_aprendizaje.mobility_applications';

    use HasFactory;

    public const TYPES = [
        'movilidad_nacional' => 'Movilidad Nacional',
        'movilidad_internacional' => 'Movilidad Internacional',
        'beca_institucional' => 'Beca Institucional',
        'beca_externa' => 'Beca Externa',
    ];

    public const STATUSES = [
        'en_evaluacion' => 'En Evaluación',
        'aprobada' => 'Aprobada',
        'en_curso' => 'En Curso',
        'finalizada' => 'Finalizada',
        'rechazada' => 'Rechazada',
    ];

    protected $fillable = [
        'student_id', 'academic_period_id', 'type', 'destination_institution',
        'program_name', 'scholarship_name', 'agreement_id', 'application_date',
        'start_date', 'end_date', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function agreement(): BelongsTo
    {
        return $this->belongsTo(Agreement::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
