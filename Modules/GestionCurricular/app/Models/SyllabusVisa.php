<?php

namespace Modules\GestionCurricular\Models;

use Modules\Core\Models\User;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SyllabusVisa extends Model
{
    protected $table = 'app_gestion_curricular.syllabus_visas';

    use HasFactory;

    protected $fillable = ['syllabus_id', 'visor_id', 'status', 'observations', 'visado_at'];

    protected function casts(): array
    {
        return ['visado_at' => 'datetime'];
    }

    public function syllabus(): BelongsTo
    {
        return $this->belongsTo(Syllabus::class, 'syllabus_id');
    }

    public function visor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visor_id');
    }
}
