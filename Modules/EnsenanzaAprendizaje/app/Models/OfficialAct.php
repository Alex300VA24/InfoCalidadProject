<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Subject;
use Modules\Core\Models\User;

class OfficialAct extends Model
{
    use HasFactory;

    public const STATUSES = [
        'borrador' => 'Borrador',
        'cerrado' => 'Cerrado',
    ];

    protected $table = 'official_acts';

    protected $fillable = [
        'subject_id', 'teacher_id', 'academic_period_id',
        'pdf_path', 'status', 'closed_at', 'closed_by',
    ];

    protected function casts(): array
    {
        return ['closed_at' => 'datetime'];
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function closedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function isClosed(): bool
    {
        return $this->status === 'cerrado';
    }
}
