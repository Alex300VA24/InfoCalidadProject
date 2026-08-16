<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Core\Support\CatalogCache;

class AcademicPeriod extends Model
{
    use HasFactory;

    protected $table = 'core.academic_periods';

    protected $fillable = ['name', 'start_date', 'end_date', 'is_active'];

    protected static function booted(): void
    {
        static::saved(fn () => CatalogCache::forget());
        static::deleted(fn () => CatalogCache::forget());
    }

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }
}
