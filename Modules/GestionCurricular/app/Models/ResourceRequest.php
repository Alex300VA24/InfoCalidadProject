<?php

namespace Modules\GestionCurricular\Models;

use Modules\Core\Models\User;

use Modules\Core\Models\AcademicPeriod;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResourceRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'academic_period_id', 'applicant_id', 'title',
        'description', 'request_type', 'status'
    ];

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function applicant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'applicant_id');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(ResourceDocument::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ResourceAttachment::class);
    }
}
