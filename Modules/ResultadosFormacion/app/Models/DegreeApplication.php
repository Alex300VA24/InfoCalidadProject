<?php

namespace Modules\ResultadosFormacion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\Student;
use Modules\Core\Models\User;

class DegreeApplication extends Model
{
    protected $table = 'app_resultados_formacion.degree_applications';

    use HasFactory;

    public const TYPES = [
        'bachiller' => 'Grado de Bachiller',
        'titulo_ingeniero' => 'Título de Ingeniero',
        'titulo_licenciado' => 'Título de Licenciado',
    ];

    public const STATUSES = [
        'en_tramite' => 'En Trámite',
        'revisado' => 'Revisado',
        'aprobado' => 'Aprobado',
        'otorgado' => 'Otorgado',
        'observado' => 'Observado',
    ];

    protected $fillable = [
        'student_id', 'code', 'type', 'thesis_title', 'advisor_id',
        'application_date', 'resolution_date', 'resolution_number', 'status', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'application_date' => 'date',
            'resolution_date' => 'date',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'advisor_id');
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
