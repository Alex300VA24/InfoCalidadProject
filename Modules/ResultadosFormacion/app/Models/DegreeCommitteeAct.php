<?php

namespace Modules\ResultadosFormacion\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DegreeCommitteeAct extends Model
{
    use HasFactory;

    public const ACT_TYPES = [
        'sustentacion' => 'Sustentación de tesis',
        'suficiencia' => 'Examen de suficiencia',
    ];

    public const RESULTS = [
        'aprobado' => 'Aprobado',
        'desaprobado' => 'Desaprobado',
    ];

    protected $table = 'app_resultados_formacion.degree_committee_acts';

    protected $fillable = [
        'degree_application_id', 'act_type', 'session_date',
        'result', 'score', 'pdf_path',
    ];

    protected function casts(): array
    {
        return [
            'session_date' => 'date',
            'score' => 'decimal:2',
        ];
    }

    public function degreeApplication(): BelongsTo
    {
        return $this->belongsTo(DegreeApplication::class);
    }

    public function actTypeLabel(): string
    {
        return self::ACT_TYPES[$this->act_type] ?? $this->act_type;
    }

    public function resultLabel(): string
    {
        return self::RESULTS[$this->result] ?? $this->result;
    }

    public function getActTypeLabelAttribute(): string
    {
        return $this->actTypeLabel();
    }

    public function getResultLabelAttribute(): string
    {
        return $this->resultLabel();
    }
}
