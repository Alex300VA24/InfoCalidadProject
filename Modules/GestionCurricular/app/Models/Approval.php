<?php

namespace Modules\GestionCurricular\Models;

use Modules\Core\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Approval extends Model
{
    protected $table = 'app_gestion_curricular.approvals';

    use HasFactory;

    protected $fillable = ['technical_report_id', 'approver_id', 'decision', 'comments', 'approved_at'];

    protected function casts(): array
    {
        return ['approved_at' => 'datetime'];
    }

    public function technicalReport(): BelongsTo
    {
        return $this->belongsTo(TechnicalReport::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approver_id');
    }
}
