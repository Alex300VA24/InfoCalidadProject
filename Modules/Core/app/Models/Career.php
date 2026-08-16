<?php

namespace Modules\Core\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Modules\Core\Support\CatalogCache;

class Career extends Model
{
    use HasFactory;

    protected $table = 'core.careers';

    protected $fillable = ['code', 'name', 'description', 'is_active', 'faculty_id'];

    protected static function booted(): void
    {
        static::saved(fn () => CatalogCache::forget());
        static::deleted(fn () => CatalogCache::forget());
    }

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public static function resolveDefault(?User $user = null): ?Career
    {
        if ($user?->career_id) {
            return $user->career;
        }

        if (request()->filled('career_id')) {
            return static::find(request('career_id'));
        }

        return static::where('is_active', true)->orderBy('code')->first();
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class);
    }
}
