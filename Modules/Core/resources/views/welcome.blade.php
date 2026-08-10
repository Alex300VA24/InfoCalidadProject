<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-turbo="false">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta
        name="description"
        content="Plataforma de Gestión de Calidad Académica de la Facultad de Ingeniería Informática."
    >

    <title>Plataforma de Calidad | Ingeniería Informática UNT</title>

    {{-- Fuentes --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200&display=swap"
        rel="stylesheet"
    >

    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff8ff',
                            100: '#dbefff',
                            200: '#b8dfff',
                            300: '#85c9ff',
                            400: '#4aabff',
                            500: '#2188f3',
                            600: '#096bd1',
                            700: '#0855aa',
                            800: '#0b478a',
                            900: '#0f3d72',
                            950: '#082646',
                        },
                        ink: {
                            50: '#f5f8fa',
                            100: '#e8eef2',
                            200: '#cedbe2',
                            300: '#a8bdc8',
                            400: '#7896a5',
                            500: '#587987',
                            600: '#45616e',
                            700: '#394f5a',
                            800: '#243943',
                            900: '#142832',
                            950: '#081a23',
                        },
                        gold: {
                            50: '#fffbea',
                            100: '#fff3c5',
                            200: '#ffe787',
                            300: '#ffd649',
                            400: '#fbc019',
                            500: '#dda006',
                            600: '#b87802',
                            700: '#925706',
                            800: '#78450b',
                            900: '#66390f',
                        },
                        canvas: '#f4f7fb',
                    },

                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },

                    boxShadow: {
                        soft: '0 20px 50px -30px rgba(15, 35, 48, 0.30)',
                        card: '0 24px 60px -36px rgba(8, 38, 70, 0.35)',
                        glow: '0 20px 50px -20px rgba(33, 136, 243, 0.45)',
                    },

                    animation: {
                        float: 'float 7s ease-in-out infinite',
                        floatSlow: 'floatSlow 10s ease-in-out infinite',
                        pulseSoft: 'pulseSoft 4s ease-in-out infinite',
                        fadeUp: 'fadeUp .8s cubic-bezier(.22,1,.36,1) both',
                        fadeIn: 'fadeIn .7s ease-out both',
                    },

                    keyframes: {
                        float: {
                            '0%, 100%': {
                                transform: 'translate3d(0, 0, 0)',
                            },
                            '50%': {
                                transform: 'translate3d(0, -14px, 0)',
                            },
                        },

                        floatSlow: {
                            '0%, 100%': {
                                transform: 'translate3d(0, 0, 0) scale(1)',
                            },
                            '50%': {
                                transform: 'translate3d(18px, -18px, 0) scale(1.05)',
                            },
                        },

                        pulseSoft: {
                            '0%, 100%': {
                                opacity: '.35',
                                transform: 'scale(1)',
                            },
                            '50%': {
                                opacity: '.65',
                                transform: 'scale(1.08)',
                            },
                        },

                        fadeUp: {
                            '0%': {
                                opacity: '0',
                                transform: 'translateY(24px)',
                            },
                            '100%': {
                                opacity: '1',
                                transform: 'translateY(0)',
                            },
                        },

                        fadeIn: {
                            '0%': {
                                opacity: '0',
                            },
                            '100%': {
                                opacity: '1',
                            },
                        },
                    },
                },
            },
        };
    </script>

    <style>
        :root {
            color-scheme: light;
        }

        html {
            scroll-behavior: smooth;
        }

        body {
            font-family: 'Inter', sans-serif;
            background:
                radial-gradient(circle at 10% 20%, rgba(33, 136, 243, 0.05), transparent 24rem),
                radial-gradient(circle at 90% 55%, rgba(14, 165, 233, 0.06), transparent 25rem),
                #f4f7fb;
        }

        ::selection {
            color: #ffffff;
            background: #096bd1;
        }

        .material-symbols-outlined {
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
        }

        .material-symbols-filled {
            font-variation-settings:
                'FILL' 1,
                'wght' 500,
                'GRAD' 0,
                'opsz' 24;
        }

        /* Textura sutil del hero */
        .hero-grid {
            background-image:
                linear-gradient(rgba(255, 255, 255, 0.035) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.035) 1px, transparent 1px);

            background-size: 48px 48px;

            mask-image: linear-gradient(
                to bottom,
                rgba(0, 0, 0, 0.95),
                rgba(0, 0, 0, 0.15)
            );
        }

        /* Luz que se desplaza por elementos */
        .shine-effect {
            position: relative;
            overflow: hidden;
            isolation: isolate;
        }

        .shine-effect::after {
            content: '';
            position: absolute;
            top: -80%;
            left: -130%;
            z-index: 3;

            width: 60%;
            height: 260%;

            background: linear-gradient(
                90deg,
                transparent,
                rgba(255, 255, 255, 0.28),
                transparent
            );

            transform: rotate(22deg);
            transition: left 850ms cubic-bezier(.22, 1, .36, 1);
            pointer-events: none;
        }

        .shine-effect:hover::after {
            left: 150%;
        }

        /* Tarjetas de módulos */
        .module-card {
            --module-color: 33, 136, 243;

            position: relative;
            isolation: isolate;
            overflow: hidden;

            transition:
                transform 420ms cubic-bezier(.22, 1, .36, 1),
                box-shadow 420ms cubic-bezier(.22, 1, .36, 1),
                border-color 420ms ease,
                background-color 420ms ease;
        }

        .module-card::before {
            content: '';
            position: absolute;
            top: -130px;
            right: -130px;
            z-index: -1;

            width: 240px;
            height: 240px;

            border-radius: 9999px;
            background: rgba(var(--module-color), 0.13);
            filter: blur(4px);

            transform: scale(.75);
            transition:
                transform 500ms cubic-bezier(.22, 1, .36, 1),
                opacity 500ms ease;
        }

        .module-card::after {
            content: '';
            position: absolute;
            inset: 0;
            z-index: -2;

            opacity: 0;

            background:
                radial-gradient(
                    circle at 85% 10%,
                    rgba(var(--module-color), .11),
                    transparent 38%
                );

            transition: opacity 450ms ease;
        }

        .module-card:hover {
            transform: translateY(-9px);
            border-color: rgba(var(--module-color), .38);

            box-shadow:
                0 30px 70px -35px rgba(8, 38, 70, .45),
                0 0 0 1px rgba(var(--module-color), .04);
        }

        .module-card:hover::before {
            opacity: 1;
            transform: scale(1.08);
        }

        .module-card:hover::after {
            opacity: 1;
        }

        .module-card:focus-visible {
            outline: 3px solid rgba(var(--module-color), .75);
            outline-offset: 5px;
        }

        .module-card:hover .module-icon {
            color: rgb(var(--module-color));
            background: rgba(var(--module-color), .13);
            transform: translateY(-3px) rotate(-3deg) scale(1.06);
            box-shadow: 0 15px 35px -18px rgba(var(--module-color), .8);
        }

        .module-card:hover .module-arrow {
            transform: translateX(5px);
        }

        .module-card:hover .module-link-text {
            color: rgb(var(--module-color));
        }

        .module-card:hover .module-line {
            width: 100%;
        }

        .module-icon {
            transition:
                transform 420ms cubic-bezier(.22, 1, .36, 1),
                color 350ms ease,
                background-color 350ms ease,
                box-shadow 350ms ease;
        }

        .module-arrow {
            transition: transform 350ms cubic-bezier(.22, 1, .36, 1);
        }

        .module-link-text {
            transition: color 300ms ease;
        }

        .module-line {
            width: 0;
            height: 2px;

            background: rgb(var(--module-color));

            transition: width 500ms cubic-bezier(.22, 1, .36, 1);
        }

        /* Animaciones escalonadas */
        .reveal-item {
            opacity: 0;
            animation: revealItem .75s cubic-bezier(.22, 1, .36, 1) forwards;
        }

        .delay-100 {
            animation-delay: 100ms;
        }

        .delay-200 {
            animation-delay: 200ms;
        }

        .delay-300 {
            animation-delay: 300ms;
        }

        .delay-400 {
            animation-delay: 400ms;
        }

        .delay-500 {
            animation-delay: 500ms;
        }

        .delay-600 {
            animation-delay: 600ms;
        }

        @keyframes revealItem {
            from {
                opacity: 0;
                transform: translateY(24px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Respeto a preferencias de accesibilidad */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                scroll-behavior: auto !important;
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }

            .reveal-item {
                opacity: 1;
            }
        }
    </style>
</head>

<body class="min-h-screen overflow-x-hidden bg-canvas text-ink-950 antialiased">

    {{-- Navbar --}}
    <header
        class="sticky top-0 z-50 border-b border-white/10 bg-ink-950/80 shadow-lg shadow-ink-950/5 backdrop-blur-xl"
    >
        <nav
            class="mx-auto flex h-[72px] max-w-7xl items-center justify-between px-5 sm:px-8"
            aria-label="Navegación principal"
        >
            <a
                href="/"
                class="group flex items-center gap-3 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-4 focus-visible:ring-offset-ink-950"
            >
                <div class="relative">
                    <div
                        class="absolute -inset-1 rounded-xl bg-gradient-to-br from-brand-400/50 to-cyan-300/20 opacity-0 blur-md transition-opacity duration-500 group-hover:opacity-100"
                    ></div>

                    <img
                        src="/static/img/logo_informatica.png"
                        alt="Logo de la Universidad Nacional de Trujillo"
                        class="relative h-10 w-10 rounded-xl border border-white/20 object-cover shadow-lg transition duration-300 group-hover:scale-105"
                    >
                </div>

                <div>
                    <div class="flex items-center gap-2">
                        <span class="text-sm font-extrabold tracking-wide text-white">
                            UNT
                        </span>

                        <span class="h-1 w-1 rounded-full bg-brand-300"></span>

                        <span class="text-[10px] font-semibold uppercase tracking-[0.16em] text-brand-200">
                            Ingeniería Informática
                        </span>
                    </div>

                    <span class="mt-0.5 block text-[10px] font-medium text-slate-400">
                        Plataforma de Calidad Académica
                    </span>
                </div>
            </a>

            <div class="hidden items-center gap-2 sm:flex">
                <div
                    class="flex items-center gap-2 rounded-full border border-emerald-400/20 bg-emerald-400/10 px-3 py-1.5"
                >
                    <span class="relative flex h-2 w-2">
                        <span
                            class="absolute inline-flex h-full w-full animate-ping rounded-full bg-emerald-400 opacity-60"
                        ></span>

                        <span class="relative inline-flex h-2 w-2 rounded-full bg-emerald-400"></span>
                    </span>

                    <span class="text-[11px] font-semibold text-emerald-200">
                        Servicios disponibles
                    </span>
                </div>
            </div>
        </nav>
    </header>

    <main>
        {{-- Hero --}}
        <section class="relative isolate overflow-hidden bg-ink-950">
            {{-- Fondo principal --}}
            <div
                class="absolute inset-0 -z-30 bg-gradient-to-br from-ink-950 via-[#0a3047] to-[#071922]"
            ></div>

            {{-- Cuadrícula decorativa --}}
            <div class="hero-grid absolute inset-0 -z-20"></div>

            {{-- Iluminación ambiental --}}
            <div
                class="absolute -right-32 -top-40 -z-10 h-[520px] w-[520px] animate-pulseSoft rounded-full bg-brand-500/20 blur-[110px]"
            ></div>

            <div
                class="absolute -bottom-52 -left-44 -z-10 h-[500px] w-[500px] animate-floatSlow rounded-full bg-cyan-400/10 blur-[120px]"
            ></div>

            <div
                class="absolute left-[45%] top-10 -z-10 h-64 w-64 animate-float rounded-full bg-blue-400/5 blur-[90px]"
            ></div>

            {{-- Decoraciones --}}
            <div
                class="absolute right-[8%] top-28 hidden h-40 w-40 rotate-12 rounded-[2rem] border border-white/5 bg-white/[0.025] backdrop-blur-sm lg:block"
            ></div>

            <div
                class="absolute right-[17%] top-52 hidden h-20 w-20 -rotate-12 rounded-3xl border border-brand-300/10 bg-brand-300/5 backdrop-blur-sm lg:block"
            ></div>

            <div class="relative mx-auto max-w-7xl px-5 py-20 sm:px-8 sm:py-28 lg:py-32">
                <div class="max-w-4xl">
                    <div class="reveal-item delay-100">
                        <span
                            class="inline-flex items-center gap-2 rounded-full border border-brand-300/20 bg-brand-300/10 px-3.5 py-2 text-[10px] font-bold uppercase tracking-[0.18em] text-brand-100 shadow-lg shadow-brand-950/20 backdrop-blur-md"
                        >
                            <span class="material-symbols-outlined text-[16px] text-brand-300">
                                verified
                            </span>

                            Plataforma de Calidad Académica
                        </span>
                    </div>

                    <h1
                        class="reveal-item delay-200 mt-7 max-w-4xl text-4xl font-black leading-[1.08] tracking-[-0.04em] text-white sm:text-5xl lg:text-7xl"
                    >
                        Gestión integral para una

                        <span
                            class="relative mt-1 inline-block bg-gradient-to-r from-brand-200 via-cyan-300 to-brand-300 bg-clip-text text-transparent"
                        >
                            educación de calidad

                            <span
                                class="absolute -bottom-2 left-0 h-[3px] w-2/3 rounded-full bg-gradient-to-r from-brand-400 to-transparent"
                            ></span>
                        </span>
                    </h1>

                    <p
                        class="reveal-item delay-300 mt-8 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg"
                    >
                        Un ecosistema digital diseñado para gestionar, supervisar y fortalecer
                        continuamente los procesos académicos de la Facultad de Ingeniería
                        Informática.
                    </p>

                    <div
                        class="reveal-item delay-400 mt-9 flex flex-wrap items-center gap-x-7 gap-y-4 text-sm text-slate-300"
                    >
                        <div class="flex items-center gap-2.5">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5"
                            >
                                <span class="material-symbols-outlined text-[17px] text-brand-300">
                                    shield_lock
                                </span>
                            </span>

                            <span class="font-medium">Acceso seguro</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5"
                            >
                                <span class="material-symbols-outlined text-[17px] text-brand-300">
                                    dashboard
                                </span>
                            </span>

                            <span class="font-medium">Módulos especializados</span>
                        </div>

                        <div class="flex items-center gap-2.5">
                            <span
                                class="flex h-8 w-8 items-center justify-center rounded-lg border border-white/10 bg-white/5"
                            >
                                <span class="material-symbols-outlined text-[17px] text-brand-300">
                                    monitoring
                                </span>
                            </span>

                            <span class="font-medium">Mejora continua</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Separador inferior --}}
            <div
                class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-brand-300/30 to-transparent"
            ></div>
        </section>

        {{-- Módulos --}}
        <section class="relative py-16 sm:py-20 lg:py-24">
            {{-- Elementos decorativos --}}
            <div
                class="pointer-events-none absolute left-0 top-10 h-72 w-72 rounded-full bg-brand-400/5 blur-3xl"
            ></div>

            <div
                class="pointer-events-none absolute bottom-10 right-0 h-72 w-72 rounded-full bg-cyan-400/5 blur-3xl"
            ></div>

            <div class="relative mx-auto max-w-7xl px-5 sm:px-8">
                {{-- Encabezado --}}
                <div class="reveal-item delay-200 mx-auto mb-12 max-w-2xl text-center sm:mb-14">
                    <span
                        class="inline-flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-brand-600"
                    >
                        <span class="h-px w-8 bg-brand-400"></span>
                        Acceso a la plataforma
                        <span class="h-px w-8 bg-brand-400"></span>
                    </span>

                    <h2
                        class="mt-4 text-3xl font-black tracking-[-0.035em] text-ink-950 sm:text-4xl"
                    >
                        Seleccione un módulo
                    </h2>

                    <p class="mt-4 text-sm leading-7 text-ink-500 sm:text-base">
                        Cada módulo cuenta con un entorno independiente y especializado
                        para sus respectivos procesos académicos.
                    </p>
                </div>

                {{-- Grid de módulos --}}
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 xl:grid-cols-4">

                    {{-- Gestión Curricular --}}
                    <a
                        href="http://127.0.0.1:8000/login"
                        class="module-card shine-effect reveal-item delay-300 flex min-h-[350px] flex-col rounded-3xl border border-brand-200/80 bg-white/90 p-6 shadow-card backdrop-blur-sm sm:p-7"
                        style="--module-color: 9, 107, 209;"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="module-icon flex h-14 w-14 items-center justify-center rounded-2xl bg-brand-50 text-brand-600"
                            >
                                <span class="material-symbols-outlined material-symbols-filled text-[28px]">
                                    menu_book
                                </span>
                            </div>

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[0.14em] text-emerald-700"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                                Activo
                            </span>
                        </div>

                        <div class="mt-7">
                            <span class="text-[11px] font-bold uppercase tracking-[0.18em] text-brand-600">
                                Módulo 01
                            </span>

                            <h3 class="mt-2 text-xl font-extrabold tracking-[-0.025em] text-ink-950">
                                Gestión Curricular
                            </h3>

                            <p class="mt-3 text-sm leading-6 text-ink-500">
                                Revisión de sílabos, informes técnicos, aprobaciones y
                                control de calidad curricular.
                            </p>
                        </div>

                        <div class="mt-auto pt-8">
                            <div class="mb-5 h-px overflow-hidden bg-ink-100">
                                <div class="module-line"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="module-link-text text-sm font-bold text-ink-800">
                                    
                                </span>

                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-700"
                                >
                                    <span class="module-arrow material-symbols-outlined text-[19px]">
                                        arrow_forward
                                    </span>
                                </span>
                            </div>
                        </div>
                    </a>

                    {{-- Gestión del Ingreso --}}
                    <a
                        href="http://127.0.0.1:8001/login"
                        class="module-card shine-effect reveal-item delay-400 flex min-h-[350px] flex-col rounded-3xl border border-ink-100 bg-white/90 p-6 shadow-card backdrop-blur-sm sm:p-7"
                        style="--module-color: 14, 165, 233;"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="module-icon flex h-14 w-14 items-center justify-center rounded-2xl bg-sky-50 text-sky-600"
                            >
                                <span class="material-symbols-outlined text-[28px]">
                                    person_add
                                </span>
                            </div>

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-sky-200 bg-sky-50 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[0.14em] text-sky-700"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-sky-500"></span>
                                Disponible
                            </span>
                        </div>

                        <div class="mt-7">
                            <span class="text-[11px] font-bold uppercase tracking-[0.18em] text-sky-600">
                                Módulo 02
                            </span>

                            <h3 class="mt-2 text-xl font-extrabold tracking-[-0.025em] text-ink-950">
                                Gestión del Ingreso
                            </h3>

                            <p class="mt-3 text-sm leading-6 text-ink-500">
                                Admisión, selección y registro de nuevos estudiantes
                                que ingresan a la institución.
                            </p>
                        </div>

                        <div class="mt-auto pt-8">
                            <div class="mb-5 h-px overflow-hidden bg-ink-100">
                                <div class="module-line"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="module-link-text text-sm font-bold text-ink-800">
                                    
                                </span>

                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-700"
                                >
                                    <span class="module-arrow material-symbols-outlined text-[19px]">
                                        arrow_forward
                                    </span>
                                </span>
                            </div>
                        </div>
                    </a>

                    {{-- Enseñanza y Aprendizaje --}}
                    <a
                        href="http://127.0.0.1:8002/login"
                        class="module-card shine-effect reveal-item delay-500 flex min-h-[350px] flex-col rounded-3xl border border-ink-100 bg-white/90 p-6 shadow-card backdrop-blur-sm sm:p-7"
                        style="--module-color: 124, 58, 237;"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="module-icon flex h-14 w-14 items-center justify-center rounded-2xl bg-violet-50 text-violet-600"
                            >
                                <span class="material-symbols-outlined text-[28px]">
                                    school
                                </span>
                            </div>

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-violet-200 bg-violet-50 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[0.14em] text-violet-700"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-violet-500"></span>
                                Disponible
                            </span>
                        </div>

                        <div class="mt-7">
                            <span class="text-[11px] font-bold uppercase tracking-[0.18em] text-violet-600">
                                Módulo 03
                            </span>

                            <h3 class="mt-2 text-xl font-extrabold tracking-[-0.025em] text-ink-950">
                                Enseñanza y Aprendizaje
                            </h3>

                            <p class="mt-3 text-sm leading-6 text-ink-500">
                                Seguimiento del proceso educativo, metodologías y
                                rendimiento académico de los estudiantes.
                            </p>
                        </div>

                        <div class="mt-auto pt-8">
                            <div class="mb-5 h-px overflow-hidden bg-ink-100">
                                <div class="module-line"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="module-link-text text-sm font-bold text-ink-800">
                                    
                                </span>

                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-700"
                                >
                                    <span class="module-arrow material-symbols-outlined text-[19px]">
                                        arrow_forward
                                    </span>
                                </span>
                            </div>
                        </div>
                    </a>

                    {{-- Resultados de la Formación --}}
                    <a
                        href="http://127.0.0.1:8003/login"
                        class="module-card shine-effect reveal-item delay-600 flex min-h-[350px] flex-col rounded-3xl border border-ink-100 bg-white/90 p-6 shadow-card backdrop-blur-sm sm:p-7"
                        style="--module-color: 217, 119, 6;"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div
                                class="module-icon flex h-14 w-14 items-center justify-center rounded-2xl bg-amber-50 text-amber-600"
                            >
                                <span class="material-symbols-outlined text-[28px]">
                                    monitoring
                                </span>
                            </div>

                            <span
                                class="inline-flex items-center gap-1.5 rounded-full border border-amber-200 bg-amber-50 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[0.14em] text-amber-700"
                            >
                                <span class="h-1.5 w-1.5 rounded-full bg-amber-500"></span>
                                Disponible
                            </span>
                        </div>

                        <div class="mt-7">
                            <span class="text-[11px] font-bold uppercase tracking-[0.18em] text-amber-600">
                                Módulo 04
                            </span>

                            <h3 class="mt-2 text-xl font-extrabold tracking-[-0.025em] text-ink-950">
                                Resultados de la Formación
                            </h3>

                            <p class="mt-3 text-sm leading-6 text-ink-500">
                                Indicadores de desempeño, logros de aprendizaje y
                                evaluación de la calidad formativa.
                            </p>
                        </div>

                        <div class="mt-auto pt-8">
                            <div class="mb-5 h-px overflow-hidden bg-ink-100">
                                <div class="module-line"></div>
                            </div>

                            <div class="flex items-center justify-between">
                                <span class="module-link-text text-sm font-bold text-ink-800">
                                    
                                </span>

                                <span
                                    class="flex h-9 w-9 items-center justify-center rounded-full bg-ink-50 text-ink-700"
                                >
                                    <span class="module-arrow material-symbols-outlined text-[19px]">
                                        arrow_forward
                                    </span>
                                </span>
                            </div>
                        </div>
                    </a>
                </div>

                {{-- Nota informativa --}}
                <div
                    class="reveal-item delay-600 mx-auto mt-12 flex max-w-3xl items-start gap-3 rounded-2xl border border-brand-100 bg-brand-50/60 p-4 backdrop-blur-sm sm:items-center"
                >
                    <span
                        class="material-symbols-outlined mt-0.5 text-[21px] text-brand-600 sm:mt-0"
                    >
                        info
                    </span>

                    <p class="text-xs leading-6 text-brand-900 sm:text-sm">
                        Utilice las credenciales correspondientes al módulo seleccionado.
                        Cada entorno administra sus propios permisos y perfiles de usuario.
                    </p>
                </div>
            </div>
        </section>
    </main>

    {{-- Footer --}}
    <footer class="relative overflow-hidden border-t border-white/5 bg-ink-950 text-slate-400">
        <div
            class="absolute left-1/2 top-0 h-px w-2/3 -translate-x-1/2 bg-gradient-to-r from-transparent via-brand-400/40 to-transparent"
        ></div>

        <div
            class="pointer-events-none absolute -bottom-40 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-brand-500/10 blur-[100px]"
        ></div>

        <div class="relative mx-auto max-w-7xl px-5 py-12 sm:px-8">
            <div
                class="flex flex-col items-center justify-between gap-8 text-center md:flex-row md:text-left"
            >
                <div class="flex items-center gap-4">
                    <div class="relative">
                        <div class="absolute -inset-1 rounded-xl bg-brand-400/20 blur-md"></div>

                        <img
                            src="/static/img/logo_informatica.png"
                            alt="Logo de la Universidad Nacional de Trujillo"
                            class="relative h-11 w-11 rounded-xl border border-white/10 object-cover"
                        >
                    </div>

                    <div>
                        <div class="flex items-center justify-center gap-2 md:justify-start">
                            <span class="font-extrabold text-white">
                                Universidad Nacional de Trujillo
                            </span>
                        </div>

                        <p class="mt-1 text-xs text-slate-500">
                            Facultad de Ingeniería Informática
                        </p>
                    </div>
                </div>

                <div class="md:text-right">
                    <p class="text-sm font-medium text-slate-300">
                        Plataforma de Gestión de Calidad Académica
                    </p>

                    <p class="mt-1 text-xs text-slate-500">
                        Tecnología para la mejora continua de la educación
                    </p>
                </div>
            </div>

            <div
                class="mt-9 flex flex-col items-center justify-between gap-3 border-t border-white/[0.07] pt-6 text-center text-[11px] text-slate-600 sm:flex-row sm:text-left"
            >
                <p>
                    © {{ date('Y') }} Universidad Nacional de Trujillo.
                    Todos los derechos reservados.
                </p>

                <div class="flex items-center gap-2">
                    <span class="material-symbols-outlined text-[15px]">
                        verified_user
                    </span>

                    <span>Sistema institucional</span>
                </div>
            </div>
        </div>
    </footer>

</body>
</html>