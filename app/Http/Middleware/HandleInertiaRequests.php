<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        $user = $request->user();

        $abilities = [
            'presidente-cotejo', 'director-escuela', 'secretaria', 'docente', 'estudiante',
            'coordinador-admision', 'personal-matricula', 'tutor-academico',
            'relaciones-internacionales', 'unidad-grados-titulos', 'seguimiento-egresado',
            'syllabi', 'resources', 'evaluations', 'execution', 'tutoring', 'mobility',
            'research', 'degrees', 'graduates',
        ];

        return [
            ...parent::share($request),
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => method_exists($user, 'roleLabel') ? $user->roleLabel() : null,
                    'text_scale' => $user->text_scale ?? null,
                    'view_scale' => $user->view_scale ?? null,
                ] : null,
            ],
            'can' => collect($abilities)
                ->mapWithKeys(fn (string $ability) => [$ability => (bool) $user?->can($ability)])
                ->all(),
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
                'warning' => fn () => $request->session()->get('warning'),
                'info' => fn () => $request->session()->get('status'),
            ],
        ];
    }
}
