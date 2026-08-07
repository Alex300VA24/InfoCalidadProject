<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta
        name="description"
        content="Acceso al módulo de Gestión Curricular de la Plataforma de Calidad Académica."
    >

    <title>{{ config('app.name', 'Gestión Académica') }}</title>

    @include('partials.head')
</head>

<body class="font-sans antialiased">
    <main class="relative min-h-screen overflow-hidden bg-[#071a27]">
        {{-- Fondo --}}
        <div class="absolute inset-0 bg-gradient-to-br from-[#071a27] via-[#0a3047] to-[#061822]"></div>

        {{-- Cuadrícula --}}
        <div
            class="pointer-events-none absolute inset-0 opacity-40"
            style="
                background-image:
                    linear-gradient(rgba(255,255,255,.035) 1px, transparent 1px),
                    linear-gradient(90deg, rgba(255,255,255,.035) 1px, transparent 1px);
                background-size: 48px 48px;
            "
        ></div>

        {{-- Iluminaciones --}}
        <div class="pointer-events-none absolute -right-40 -top-40 h-[500px] w-[500px] rounded-full bg-blue-500/20 blur-[120px]"></div>
        <div class="pointer-events-none absolute -bottom-44 -left-40 h-[500px] w-[500px] rounded-full bg-cyan-400/10 blur-[120px]"></div>

        {{-- Figuras --}}
        <div class="pointer-events-none absolute right-[10%] top-24 hidden h-40 w-40 rotate-12 rounded-[2rem] border border-white/5 bg-white/[0.025] backdrop-blur-sm lg:block"></div>

        <div class="relative flex min-h-screen items-center justify-center px-5 py-10 sm:px-8">
            <div class="w-full max-w-md">
                {{-- Marca --}}
                <header class="mb-7 text-center">
                    <a
                        href="/"
                        class="group inline-flex flex-col items-center rounded-2xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-300 focus-visible:ring-offset-4 focus-visible:ring-offset-[#071a27]"
                    >
                        <div class="relative">
                            <div class="absolute -inset-2 rounded-2xl bg-blue-400/25 opacity-50 blur-xl transition duration-500 group-hover:opacity-100"></div>

                            <img
                                src="/static/img/logo_informatica.png"
                                alt="Universidad Nacional de Trujillo"
                                class="relative h-16 w-16 rounded-2xl border border-white/15 object-cover shadow-2xl transition duration-300 group-hover:-translate-y-1 group-hover:rotate-[-2deg]"
                            >
                        </div>

                        <div class="mt-4">
                            <div class="flex items-center justify-center gap-2">
                                <strong class="text-lg font-black tracking-wide text-white">
                                    UNT
                                </strong>

                                <span class="h-1 w-1 rounded-full bg-sky-300"></span>

                                <span class="text-[10px] font-bold uppercase tracking-[0.16em] text-sky-200">
                                    Ingeniería Informática
                                </span>
                            </div>

                            <span class="mt-1 block text-xs font-medium text-slate-400">
                                Plataforma de Calidad Académica
                            </span>
                        </div>
                    </a>
                </header>

                {{-- Módulo --}}
                <div class="mb-6 text-center">
                    <span class="inline-flex items-center gap-2 rounded-full border border-blue-300/15 bg-blue-300/10 px-3 py-1.5 text-[9px] font-extrabold uppercase tracking-[0.16em] text-blue-100">
                        <span class="material-symbols-outlined text-[15px]">
                            menu_book
                        </span>

                        Gestión Curricular
                    </span>

                    <p class="mx-auto mt-3 max-w-sm text-sm leading-6 text-slate-400">
                        Revisión curricular, gestión de sílabos, informes técnicos y aprobaciones.
                    </p>
                </div>

                {{-- El login ya contiene su tarjeta --}}
                {{ $slot }}

                <footer class="mt-7 text-center">
                    <p class="text-[11px] leading-5 text-slate-500">
                        Plataforma de Calidad Académica
                    </p>

                    <p class="text-[10px] text-slate-600">
                        Facultad de Ingeniería Informática
                    </p>
                </footer>
            </div>
        </div>
    </main>
</body>
</html>