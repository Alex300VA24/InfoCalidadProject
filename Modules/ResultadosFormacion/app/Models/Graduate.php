<?php

namespace Modules\ResultadosFormacion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\Student;

class Graduate extends Model
{
    use HasFactory;

    public const WORK_STATUSES = [
        'no_especificado' => 'No especificado',
        'empleado' => 'Empleado',
        'independiente' => 'Trabajador independiente',
        'estudiando' => 'Estudiando posgrado',
        'desempleado' => 'En búsqueda de empleo',
    ];

    protected $fillable = [
        'student_id', 'graduation_date', 'work_status', 'employer',
        'job_position', 'monthly_income', 'survey_date',
        'employment_relationship', 'observations',
    ];

    protected function casts(): array
    {
        return [
            'graduation_date' => 'date',
            'survey_date' => 'date',
            'monthly_income' => 'decimal:2',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function surveys(): HasMany
    {
        return $this->hasMany(GraduateSurvey::class);
    }

    public function workStatusLabel(): string
    {
        return self::WORK_STATUSES[$this->work_status] ?? $this->work_status;
    }
}
