<?php

namespace Modules\GestionIngreso\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\Student;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['code', 'student_id', 'academic_period_id', 'career_id', 'status', 'enrolled_at', 'ficha_path'];

    protected function casts(): array
    {
        return ['enrolled_at' => 'datetime'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function academicPeriod(): BelongsTo
    {
        return $this->belongsTo(AcademicPeriod::class);
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(EnrollmentSubject::class);
    }

    public function paymentOrders(): HasMany
    {
        return $this->hasMany(PaymentOrder::class);
    }
}
