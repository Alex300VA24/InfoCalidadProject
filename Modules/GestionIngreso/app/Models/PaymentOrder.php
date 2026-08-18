<?php

namespace Modules\GestionIngreso\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\Student;

class PaymentOrder extends Model
{
    protected $table = 'app_gestion_ingreso.payment_orders';

    use HasFactory;

    protected $fillable = ['student_id', 'enrollment_id', 'concept', 'amount', 'status', 'receipt_number', 'pdf_path'];

    protected function casts(): array
    {
        return ['amount' => 'decimal:2'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function enrollment(): BelongsTo
    {
        return $this->belongsTo(Enrollment::class);
    }
}
