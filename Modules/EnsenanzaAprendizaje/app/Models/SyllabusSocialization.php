<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Core\Models\User;
use Modules\GestionCurricular\Models\Syllabus;

class SyllabusSocialization extends Model
{
    use HasFactory;

    protected $table = 'syllabus_socializations';

    protected $fillable = [
        'syllabus_id', 'date', 'evidence_path', 'notes', 'registered_by',
    ];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function syllabus(): BelongsTo
    {
        return $this->belongsTo(Syllabus::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }
}
