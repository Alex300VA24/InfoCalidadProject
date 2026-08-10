<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;

class ResearchProject extends Model
{
    protected $table = 'app_ensenanza_aprendizaje.research_projects';

    use HasFactory;

    public const STATUSES = [
        'borrador' => 'Borrador',
        'en_desarrollo' => 'En Desarrollo',
        'finalizado' => 'Finalizado',
        'aprobado' => 'Aprobado',
        'rechazado' => 'Rechazado',
    ];

    protected $fillable = [
        'student_id', 'academic_period_id', 'advisor_id',
        'title', 'description', 'area', 'score',
        'start_date', 'end_date', 'status', 'document_path',
    ];

    protected function casts(): array
    {
        return [
            'score' => 'decimal:2',
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

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }
}
