<div class="relative overflow-hidden rounded-[2rem] border border-slate-200/80 bg-white shadow-[0_30px_80px_-40px_rgba(8,38,70,0.45)]">
    {{-- Iluminación decorativa --}}
    <div class="pointer-events-none absolute -right-24 -top-24 h-56 w-56 rounded-full bg-blue-500/10 blur-3xl"></div>
    <div class="pointer-events-none absolute -bottom-24 -left-24 h-48 w-48 rounded-full bg-cyan-400/10 blur-3xl"></div>

    {{-- Línea superior --}}
    <div class="absolute left-0 right-0 top-0 h-1 bg-gradient-to-r from-blue-700 via-sky-500 to-cyan-400"></div>

    <div class="relative p-6 sm:p-8 lg:p-10">
        {{-- Encabezado --}}
        <div class="mb-8">
            <div class="mb-5 flex items-center gap-4">
                <div class="flex h-14 w-14 shrink-0 items-center justify-center rounded-2xl bg-blue-50 text-blue-700 shadow-sm ring-1 ring-blue-100">
                    <span class="material-symbols-outlined text-[28px]" style="font-variation-settings: 'FILL' 1, 'wght' 500;">
                        menu_book
                    </span>
                </div>

                <div>
                    <span class="inline-flex items-center gap-2 rounded-full border border-blue-100 bg-blue-50 px-2.5 py-1 text-[9px] font-extrabold uppercase tracking-[0.16em] text-blue-700">
                        <span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span>
                        Módulo activo
                    </span>

                    <p class="mt-2 text-xs font-semibold uppercase tracking-[0.14em] text-slate-400">
                        Gestión Curricular
                    </p>
                </div>
            </div>

            <h1 class="text-2xl font-black tracking-[-0.035em] text-slate-950 sm:text-3xl">
                Bienvenido nuevamente
            </h1>

            <p class="mt-3 max-w-lg text-sm leading-6 text-slate-500">
                Ingrese sus credenciales institucionales para acceder a la revisión curricular, gestión de sílabos, informes técnicos y aprobaciones.
            </p>
        </div>

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            {{-- Correo --}}
            <div>
                <label for="email" class="mb-2 block text-sm font-bold text-slate-700">
                    Correo electrónico
                </label>

                <div class="group relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-colors duration-300 group-focus-within:text-blue-600">
                        <span class="material-symbols-outlined text-[20px]">mail</span>
                    </span>

                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="usuario@unitru.edu.pe" class="block w-full rounded-xl border border-slate-200 bg-slate-50/80 py-3.5 pl-12 pr-4 text-sm font-medium text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-slate-300 hover:bg-white focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">
                </div>

                <x-input-error :messages="$errors->get('email')" class="mt-2 text-sm font-medium text-red-600" />
            </div>

            {{-- Contraseña --}}
            <div>
                <div class="mb-2 flex items-center justify-between gap-4">
                    <label for="password" class="block text-sm font-bold text-slate-700">Contraseña</label>

                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" class="rounded-md text-xs font-semibold text-blue-700 transition-colors duration-300 hover:text-blue-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                            ¿Olvidó su contraseña?
                        </a>
                    @endif
                </div>

                <div class="group relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400 transition-colors duration-300 group-focus-within:text-blue-600">
                        <span class="material-symbols-outlined text-[20px]">lock</span>
                    </span>

                    <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="Ingrese su contraseña" class="block w-full rounded-xl border border-slate-200 bg-slate-50/80 py-3.5 pl-12 pr-12 text-sm font-medium text-slate-900 outline-none transition-all duration-300 placeholder:text-slate-400 hover:border-slate-300 hover:bg-white focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10">

                    <button type="button" id="toggle-password" aria-label="Mostrar contraseña" aria-pressed="false" class="absolute inset-y-0 right-0 flex items-center px-4 text-slate-400 transition-colors duration-300 hover:text-blue-700 focus-visible:outline-none">
                        <span id="password-icon" class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>

                <x-input-error :messages="$errors->get('password')" class="mt-2 text-sm font-medium text-red-600" />
            </div>

            {{-- Recordar sesión --}}
            <div class="flex items-center justify-between gap-4 pt-1">
                <label for="remember_me" class="group inline-flex cursor-pointer items-center gap-3">
                    <input id="remember_me" type="checkbox" name="remember" class="h-4 w-4 rounded border-slate-300 text-blue-700 shadow-sm transition focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">

                    <span class="text-sm font-medium text-slate-600 transition-colors group-hover:text-slate-900">Recordar sesión</span>
                </label>

                <span class="hidden items-center gap-1.5 text-[11px] text-slate-400 sm:flex">
                    <span class="material-symbols-outlined text-[16px]">shield_lock</span>
                    Acceso protegido
                </span>
            </div>

            {{-- Botón --}}
            <button type="submit" class="group relative mt-2 flex w-full items-center justify-center overflow-hidden rounded-xl bg-gradient-to-r from-blue-800 via-blue-700 to-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-900/20 transition-all duration-300 hover:-translate-y-0.5 hover:shadow-xl hover:shadow-blue-800/30 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-blue-500/25 active:translate-y-0">
                <span class="absolute inset-0 -translate-x-full bg-gradient-to-r from-transparent via-white/20 to-transparent transition-transform duration-700 group-hover:translate-x-full"></span>

                <span class="relative flex items-center gap-2">
                    Ingresar al sistema

                    <span class="material-symbols-outlined text-[19px] transition-transform duration-300 group-hover:translate-x-1">arrow_forward</span>
                </span>
            </button>

            @if (Route::has('register'))
                <div class="border-t border-slate-100 pt-6 text-center">
                    <p class="text-sm text-slate-500">
                        ¿No tiene una cuenta?

                        <a href="{{ route('register') }}" class="ml-1 rounded-md font-bold text-blue-700 transition-colors hover:text-blue-900 hover:underline focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2">
                            Solicitar registro
                        </a>
                    </p>
                </div>
            @endif
        </form>
    </div>
</div>
