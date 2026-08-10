<?php

namespace App\Providers;

use App\Support\ConciseBlueprint;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Nwidart\Modules\Facades\Module;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(Blueprint::class, ConciseBlueprint::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        foreach (Module::allEnabled() as $module) {
            $viewsPath = module_path($module->getName(), 'resources/views');
            if (is_dir($viewsPath)) {
                View::addLocation($viewsPath);
            }

            $componentsPath = module_path($module->getName(), 'resources/views/components');
            if (is_dir($componentsPath)) {
                Blade::anonymousComponentPath($componentsPath);
            }
        }
    }
}
