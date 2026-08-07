<?php

namespace Modules\Core\Providers;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\Facades\Gate;
use Nwidart\Modules\Support\ModuleServiceProvider;

class CoreServiceProvider extends ModuleServiceProvider
{
    /**
     * The name of the module.
     */
    protected string $name = 'Core';

    /**
     * The lowercase version of the module name.
     */
    protected string $nameLower = 'core';

    /**
     * Command classes to register.
     *
     * @var string[]
     */
    // protected array $commands = [];

    /**
     * Provider classes to register.
     *
     * @var string[]
     */
    protected array $providers = [
        EventServiceProvider::class,
        RouteServiceProvider::class,
    ];

    /**
     * Define module schedules.
     *
     * @param  $schedule
     */
    // protected function configureSchedules(Schedule $schedule): void
    // {
    //     $schedule->command('inspire')->hourly();
    // }

    public function boot(): void
    {
        parent::boot();

        Gate::define('presidente-cotejo', fn ($user) => $user->hasRole('presidente_cotejo'));
        Gate::define('director-escuela', fn ($user) => $user->hasRole('director_escuela'));
        Gate::define('secretaria', fn ($user) => $user->hasRole('secretaria'));
        Gate::define('docente', fn ($user) => $user->hasRole('docente'));
        Gate::define('estudiante', fn ($user) => $user->hasRole('estudiante'));
        Gate::define('coordinador-admision', fn ($user) => $user->hasRole('coordinador_admision'));
        Gate::define('personal-matricula', fn ($user) => $user->hasRole('personal_matricula'));
        Gate::define('tutor-academico', fn ($user) => $user->hasRole('tutor_academico'));
        Gate::define('relaciones-internacionales', fn ($user) => $user->hasRole('relaciones_internacionales'));
        Gate::define('unidad-grados-titulos', fn ($user) => $user->hasRole('unidad_grados_titulos'));
        Gate::define('seguimiento-egresado', fn ($user) => $user->hasRole('seguimiento_egresado'));

        Gate::define('syllabi', fn ($user) => $user->hasRole('secretaria') || $user->hasRole('director_escuela') || $user->hasRole('docente'));

        Gate::define('resources', fn ($user) => $user->hasRole('secretaria') || $user->hasRole('docente') || $user->hasRole('director_escuela'));

        Gate::define('evaluations', fn ($user) => $user->hasRole('docente') || $user->hasRole('secretaria') || $user->hasRole('director_escuela'));

        Gate::define('execution', fn ($user) => $user->hasRole('docente') || $user->hasRole('secretaria') || $user->hasRole('director_escuela'));

        Gate::define('tutoring', fn ($user) => $user->hasRole('tutor_academico') || $user->hasRole('secretaria') || $user->hasRole('director_escuela'));

        Gate::define('mobility', fn ($user) => $user->hasRole('relaciones_internacionales') || $user->hasRole('secretaria') || $user->hasRole('director_escuela'));

        Gate::define('research', fn ($user) => $user->hasRole('docente') || $user->hasRole('secretaria') || $user->hasRole('director_escuela'));

        Gate::define('degrees', fn ($user) => $user->hasRole('unidad_grados_titulos') || $user->hasRole('secretaria') || $user->hasRole('director_escuela'));

        Gate::define('graduates', fn ($user) => $user->hasRole('seguimiento_egresado') || $user->hasRole('secretaria') || $user->hasRole('director_escuela'));
    }
}
