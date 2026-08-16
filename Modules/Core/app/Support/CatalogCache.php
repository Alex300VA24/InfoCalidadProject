<?php

namespace Modules\Core\Support;

use Illuminate\Support\Facades\Cache;
use Modules\Core\Models\AcademicPeriod;
use Modules\Core\Models\Career;
use Modules\Core\Models\User;

class CatalogCache
{
    private const TTL = 300;

    public static function periods(): array
    {
        return Cache::remember('catalog:periods', self::TTL, fn () => AcademicPeriod::all(['id', 'name', 'is_active'])->toArray());
    }

    public static function teachers(): array
    {
        return Cache::remember('catalog:teachers', self::TTL, fn () => User::withRole('docente')->orderBy('name')->get(['id', 'name'])->toArray());
    }

    public static function activeCareers(): array
    {
        return Cache::remember('catalog:careers:active', self::TTL, fn () => Career::where('is_active', true)->orderBy('code')->get(['id', 'code', 'name'])->toArray());
    }

    public static function forget(): void
    {
        Cache::forget('catalog:periods');
        Cache::forget('catalog:teachers');
        Cache::forget('catalog:careers:active');
    }
}
