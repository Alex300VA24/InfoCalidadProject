<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title inertia>{{ config('app.name', 'Gestión Académica') }}</title>

        <link rel="stylesheet" href="/css/fonts.css">
        <link rel="stylesheet" href="/css/material-symbols.css">

        @viteReactRefresh
        @vite([
            'resources/css/app.css',
            'resources/js/app.jsx',
        ])

        <x-inertia::head />
    </head>
    <body class="font-sans antialiased app-body">
        @if (request()->is('dashboard'))
            @php($dashboardKpis = $page['props']['kpis'] ?? [])
            <noscript>
                <section aria-label="Resumen académico">
                    <h1>Centro de Control Académico Institucional</h1>
                    <dl>
                        <dt>Vacantes ofrecidas</dt><dd>{{ $dashboardKpis['totalVacantes'] ?? 0 }}</dd>
                        <dt>Inserción laboral</dt><dd>{{ $dashboardKpis['insercionLaboral'] ?? 0 }}%</dd>
                        <dt>Logro de competencias</dt><dd>{{ $dashboardKpis['competenciaPromedio'] ?? 0 }}/20</dd>
                        <dt>Cobertura de vacantes</dt><dd>{{ $dashboardKpis['cobertura'] ?? 0 }}%</dd>
                    </dl>
                    @foreach (($dashboardKpis['ingresantesPorModalidad'] ?? []) as $modalidad => $total)
                        <p>{{ ucfirst($modalidad) }}: {{ $total }}</p>
                    @endforeach
                </section>
            </noscript>
        @endif
        <x-inertia::app />
    </body>
</html>
