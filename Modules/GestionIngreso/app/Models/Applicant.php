<?php

namespace Modules\GestionIngreso\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\Career;

class Applicant extends Model
{
    use HasFactory;

    protected $fillable = [
        'admission_process_id', 'dni', 'paterno', 'materno', 'nombres',
        'email', 'telefono', 'career_id', 'score', 'status', 'constancia_path',
    ];

    protected function casts(): array
    {
        return ['score' => 'decimal:2'];
    }

    public function admissionProcess(): BelongsTo
    {
        return $this->belongsTo(AdmissionProcess::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function fullName(): string
    {
        return trim("{$this->paterno} {$this->materno} {$this->nombres}");
    }
}
