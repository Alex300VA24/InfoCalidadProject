<?php

namespace Modules\ResultadosFormacion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\Student;

class Certificate extends Model
{
    use HasFactory;

    public const TYPES = [
        'estudios' => 'Certificado de Estudios',
        'practicas' => 'Certificado de Prácticas Pre-Profesionales',
        'constancia_egresado' => 'Constancia de Egresado',
        'constancia_matricula' => 'Constancia de Matrícula',
    ];

    protected $fillable = [
        'student_id', 'code', 'type', 'concept',
        'issued_at', 'issued_by', 'pdf_path', 'status',
    ];

    protected function casts(): array
    {
        return ['issued_at' => 'date'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }
}
