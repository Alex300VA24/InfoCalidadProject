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
        <x-inertia::app />
    </body>
</html>
