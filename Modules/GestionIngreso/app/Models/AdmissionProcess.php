<?php

namespace Modules\GestionIngreso\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;

class AdmissionProcess extends Model
{
    protected $table = 'app_gestion_ingreso.admission_processes';

    use HasFactory;

    public const STATUSES = [
        'borrador' => 'Borrador',
        'abierto' => 'Abierto',
        'cerrado' => 'Cerrado',
    ];

    protected $fillable = ['name', 'academic_period_id', 'career_id', 'vacancies', 'modality', 'start_date', 'end_date', 'status'];

    protected function casts(): array
    {
        return [
            'vacancies' => 'integer',
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function applicants(): HasMany
    {
        return $this->hasMany(Applicant::class);
    }

    public function ingresantesCount(): int
    {
        return $this->applicants()->where('status', 'ingresante')->count();
    }

    public function coveragePercentage(): float
    {
        if ($this->vacancies <= 0) {
            return 0;
        }

        return round(($this->ingresantesCount() / $this->vacancies) * 100, 2);
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
