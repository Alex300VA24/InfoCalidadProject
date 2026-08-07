<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Student extends Model
{
    use HasFactory;

    protected $table = 'core.students';

    protected $fillable = ['user_id', 'codigo', 'ciclo', 'fecha_nacimiento', 'direccion', 'estado'];

    protected function casts(): array
    {
        return ['fecha_nacimiento' => 'date'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function fullName(): string
    {
        return $this->user?->name ?? "Sin usuario ({$this->codigo})";
    }
}
