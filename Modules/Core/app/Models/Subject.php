<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subject extends Model
{
    use HasFactory;

    protected $table = 'core.subjects';

    protected $fillable = ['career_id', 'code', 'name', 'credits', 'hours', 'type', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function career(): BelongsTo
    {
        return $this->belongsTo(Career::class);
    }
}
