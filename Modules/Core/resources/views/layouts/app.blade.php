<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ trim($__env->yieldContent('title', config('app.name', 'Gestión Académica'))) }}</title>
        @include('partials.head')
        @auth
            <style>
                html { font-size: {{ Auth::user()->text_scale ?? 100 }}%; }
                .app-shell { zoom: {{ Auth::user()->view_scale ?? 100 }}%; }
            </style>
        @endauth
        @stack('styles')
    </head>
    <body class="font-sans antialiased app-body">
        <div
            class="app-shell"
            x-data="{ sidebarOpen: false, sidebarCollapsed: false }"
            :class="{ 'sidebar-collapsed': sidebarCollapsed }"
            x-init="$watch('sidebarOpen', open => {
                const root = document.documentElement;
                const isMobile = window.matchMedia('(max-width: 1023px)').matches;
                if (open && isMobile) {
                    root.classList.add('sidebar-open');
                } else {
                    root.classList.remove('sidebar-open');
                }
            })"
        >
            <div
                class="sidebar-backdrop"
                :class="{ 'is-visible': sidebarOpen }"
                @click="sidebarOpen = false"
                aria-hidden="true"
            ></div>

            @include('layouts.navigation')

            <button
                type="button"
                class="sidebar-toggle"
                :class="{ 'is-collapsed': sidebarCollapsed }"
                @click="sidebarCollapsed = !sidebarCollapsed"
                :aria-label="sidebarCollapsed ? 'Expandir menú lateral' : 'Contraer menú lateral'"
                :title="sidebarCollapsed ? 'Expandir menú lateral' : 'Contraer menú lateral'"
            >
                <span class="material-symbols-outlined" x-show="!sidebarCollapsed" aria-hidden="true">chevron_left</span>
                <span class="material-symbols-outlined" x-show="sidebarCollapsed" x-cloak aria-hidden="true">chevron_right</span>
            </button>

            <div class="app-main page-enter">
                @include('partials.topbar')
                @isset($header)
                    <header class="page-heading">
                        <div class="page-heading__inner">{{ $header }}</div>
                    </header>
                @endisset
                <main class="page-content">
                    {{ $slot }}
                </main>
            </div>
        </div>

        @stack('scripts')
    </body>
</html>
