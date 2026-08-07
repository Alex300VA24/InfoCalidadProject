<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ trim($__env->yieldContent('title', config('app.name', 'Gestión Académica'))) }}</title>
        @include('partials.head')
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

        <script>
            (function () {
                try {
                    var links = document.querySelectorAll('a[href]:not([target="_blank"]):not([href^="#"]):not([href^="mailto:"]):not([href^="tel:"])');
                    links.forEach(function (a) {
                        a.addEventListener('click', function (e) {
                            var url = new URL(a.href, window.location.origin);
                            if (url.origin !== window.location.origin) return;
                            var main = document.querySelector('.app-main, .app-shell, .page-content, .nexo-dashboard');
                            if (!main) return;
                            var start = null;
                            var duration = 120;
                            requestAnimationFrame(function step(t) {
                                if (!start) start = t;
                                var p = Math.min((t - start) / duration, 1);
                                main.style.opacity = String(1 - p * 0.55);
                                main.style.transform = 'translateY(' + (p * 5) + 'px)';
                                if (p < 1) requestAnimationFrame(step);
                            });
                        });
                    });
                } catch (err) {}

                window.addEventListener('pageshow', function () {
                    var main = document.querySelector('.app-main, .app-shell, .page-content, .nexo-dashboard');
                    if (main) {
                        main.style.transition = 'opacity 260ms ease, transform 260ms ease';
                        requestAnimationFrame(function () {
                            main.style.opacity = '1';
                            main.style.transform = '';
                        });
                    }
                });

                document.addEventListener('DOMContentLoaded', function () {
                    var t = document.querySelector('.app-topbar');
                    if (t) {
                        var u = function () { t.classList.toggle('is-scrolled', window.scrollY > 8); };
                        u();
                        window.addEventListener('scroll', u, { passive: true });
                    }
                });
            })();
        </script>

        @stack('scripts')
    </body>
</html>
