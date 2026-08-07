<?php

namespace Modules\EnsenanzaAprendizaje\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agreement extends Model
{
    use HasFactory;

    public const TYPES = [
        'nacional' => 'Convenio nacional',
        'internacional' => 'Convenio internacional',
    ];

    public const STATUSES = [
        'vigente' => 'Vigente',
        'vencido' => 'Vencido',
        'resuelto' => 'Resuelto',
    ];

    protected $table = 'agreements';

    protected $fillable = [
        'name', 'institution', 'type', 'description',
        'start_date', 'end_date', 'status', 'document_path',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function typeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function statusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function isVigente(): bool
    {
        return $this->status === 'vigente';
    }
}
