<?php

namespace Modules\GestionCurricular\Models;

use Modules\Core\Models\User;

use Modules\Core\Models\AcademicPeriod;

use Modules\Core\Models\Subject;

use Modules\Core\Models\Career;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Syllabus extends Model
{
    use HasFactory;

    protected $table = 'syllabi';

    protected $fillable = [
        'career_id', 'subject_id', 'academic_period_id', 'teacher_id',
        'version', 'filename', 'file_path', 'file_size', 'mime_type',
        'is_visado', 'visado_at'
    ];

    protected function casts(): array
    {
        return [
            'is_visado' => 'boolean',
            'visado_at' => 'datetime',
            'file_size' => 'integer',
        ];
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function visas(): HasMany
    {
        return $this->hasMany(SyllabusVisa::class, 'syllabus_id');
    }
}
