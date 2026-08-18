<aside
    class="app-sidebar group/sidebar"
    :class="{ 'is-open': sidebarOpen }"
    aria-label="Barra lateral"
>
    <div class="sidebar-glow sidebar-glow--top"></div>
    <div class="sidebar-glow sidebar-glow--bottom"></div>

    <div class="sidebar-inner relative w-full h-full overflow-hidden">
        <div class="shrink-0 px-4 pb-5 pt-5">
            <a href="{{ route('dashboard') }}" class="sidebar-brand group">
                <div class="nexo-brand-mark" aria-hidden="true">N</div>

                <span class="min-w-0">
                    <span class="flex items-center gap-2">
                        <strong>NEXO</strong>
                        <span class="sidebar-brand__point"></span>
                    </span>

                    <small>Universidad Nacional de Trujillo</small>
                </span>
            </a>
        </div>

        <div class="mx-4 mb-5 shrink-0 rounded-2xl border border-white/[0.08] bg-white/[0.045] p-3.5 backdrop-blur-sm">
            <div class="flex items-center gap-3">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-gradient-to-br from-blue-500 to-cyan-400 text-sm font-black text-white shadow-lg shadow-blue-950/30">
                    {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                </div>

                <div class="min-w-0">
                    <p class="truncate text-sm font-bold text-white">
                        {{ Auth::user()->name }}
                    </p>

                    <p class="mt-0.5 truncate text-[10px] font-medium text-slate-400">
                        {{ Auth::user()->roleLabel() }}
                    </p>
                </div>
            </div>
        </div>

        <nav class="sidebar-nav flex-1 overflow-y-auto px-3" aria-label="Navegación principal">
            <p class="sidebar-section-label">Principal</p>

            <a
                href="{{ route('dashboard') }}"
                class="sidebar-link {{ request()->routeIs('dashboard') ? 'is-active' : '' }}"
            >
                <span class="sidebar-link__icon">
                    <span class="material-symbols-outlined">dashboard</span>
                </span>

                <span class="sidebar-link__text">Inicio</span>

                @if(request()->routeIs('dashboard'))
                    <span class="sidebar-link__indicator"></span>
                @endif
            </a>

            @canany(['presidente-cotejo', 'director-escuela', 'syllabi', 'resources'])
                <p class="sidebar-section-label mt-6">Gestión Curricular</p>

                @can('presidente-cotejo')
                    <a
                        href="{{ route('curriculum.reviews.index') }}"
                        class="sidebar-link {{ request()->routeIs('curriculum.reviews.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">fact_check</span>
                        </span>

                        <span class="sidebar-link__text">Revisión curricular</span>
                    </a>
                @endcan

                @can('director-escuela')
                    <a
                        href="{{ route('curriculum.approvals.index') }}"
                        class="sidebar-link {{ request()->routeIs('curriculum.approvals.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">verified</span>
                        </span>

                        <span class="sidebar-link__text">Aprobaciones</span>
                    </a>
                @endcan

                @can('syllabi')
                    <a
                        href="{{ route('syllabi.index') }}"
                        class="sidebar-link {{ request()->routeIs('syllabi.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">folder_shared</span>
                        </span>

                        <span class="sidebar-link__text">Repositorio de sílabos</span>
                    </a>
                @endcan

                @can('resources')
                    <a
                        href="{{ route('resources.index') }}"
                        class="sidebar-link {{ request()->routeIs('resources.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">inventory_2</span>
                        </span>

                        <span class="sidebar-link__text">Solicitudes de recursos</span>
                    </a>
                @endcan
            @endcanany

            @canany(['coordinador-admision', 'personal-matricula'])
                <p class="sidebar-section-label mt-6">Gestión del Ingreso</p>

                @can('coordinador-admision')
                    <a
                        href="{{ route('admission.processes.index') }}"
                        class="sidebar-link {{ request()->routeIs('admission.processes.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">assignment</span>
                        </span>

                        <span class="sidebar-link__text">Procesos de admisión</span>
                    </a>

                    <a
                        href="{{ route('admission.applicants.index') }}"
                        class="sidebar-link {{ request()->routeIs('admission.applicants.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">groups</span>
                        </span>

                        <span class="sidebar-link__text">Postulantes</span>
                    </a>
                @endcan

                @can('personal-matricula')
                    <a
                        href="{{ route('enrollment.index') }}"
                        class="sidebar-link {{ request()->routeIs('enrollment.*') && ! request()->routeIs('enrollment.reports.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">how_to_reg</span>
                        </span>

                        <span class="sidebar-link__text">Matrículas</span>
                    </a>

                    <a
                        href="{{ route('enrollment.padron') }}"
                        class="sidebar-link {{ request()->routeIs('enrollment.padron') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">list_alt</span>
                        </span>

                        <span class="sidebar-link__text">Padrón virtual</span>
                    </a>

                    <a
                        href="{{ route('enrollment.reports.egresados') }}"
                        class="sidebar-link {{ request()->routeIs('enrollment.reports.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">summarize</span>
                        </span>

                        <span class="sidebar-link__text">Reportes</span>
                    </a>
                @endcan
            @endcanany

            @canany(['evaluations', 'execution', 'tutoring', 'mobility', 'research'])
                <p class="sidebar-section-label mt-6">Enseñanza y Aprendizaje</p>

                @can('evaluations')
                    <a
                        href="{{ route('evaluations.index') }}"
                        class="sidebar-link {{ request()->routeIs('evaluations.*') && ! request()->routeIs('evaluations.actas.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">task_alt</span>
                        </span>

                        <span class="sidebar-link__text">Evaluaciones</span>
                    </a>

                    <a
                        href="{{ route('evaluations.actas') }}"
                        class="sidebar-link {{ request()->routeIs('evaluations.actas.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">fact_check</span>
                        </span>

                        <span class="sidebar-link__text">Actas oficiales</span>
                    </a>
                @endcan

                @can('execution')
                    <a
                        href="{{ route('execution.index') }}"
                        class="sidebar-link {{ request()->routeIs('execution.index') || request()->routeIs('execution.create') || request()->routeIs('execution.show') || request()->routeIs('execution.coverage') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">calendar_view_month</span>
                        </span>

                        <span class="sidebar-link__text">Sesiones de clase</span>
                    </a>

                    <a
                        href="{{ route('execution.loads.index') }}"
                        class="sidebar-link {{ request()->routeIs('execution.loads.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">assignments</span>
                        </span>

                        <span class="sidebar-link__text">Cargas académicas</span>
                    </a>

                    <a
                        href="{{ route('execution.socializations.index') }}"
                        class="sidebar-link {{ request()->routeIs('execution.socializations.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">campaign</span>
                        </span>

                        <span class="sidebar-link__text">Socialización de sílabos</span>
                    </a>

                    <a
                        href="{{ route('execution.executions.index') }}"
                        class="sidebar-link {{ request()->routeIs('execution.executions.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">progress_activity</span>
                        </span>

                        <span class="sidebar-link__text">Ejecución de asignaturas</span>
                    </a>

                    <a
                        href="{{ route('execution.performance.index') }}"
                        class="sidebar-link {{ request()->routeIs('execution.performance.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">insights</span>
                        </span>

                        <span class="sidebar-link__text">Desempeño docente</span>
                    </a>
                @endcan

                @can('tutoring')
                    <a
                        href="{{ route('tutoring.index') }}"
                        class="sidebar-link {{ request()->routeIs('tutoring.*') && ! request()->routeIs('tutoring.remedial.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">support_agent</span>
                        </span>

                        <span class="sidebar-link__text">Tutoría académica</span>
                    </a>

                    <a
                        href="{{ route('tutoring.remedial.index') }}"
                        class="sidebar-link {{ request()->routeIs('tutoring.remedial.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">healing</span>
                        </span>

                        <span class="sidebar-link__text">Nivelación y recuperación</span>
                    </a>
                @endcan

                @can('mobility')
                    <a
                        href="{{ route('mobility.index') }}"
                        class="sidebar-link {{ request()->routeIs('mobility.index') || request()->routeIs('mobility.create') || request()->routeIs('mobility.show') || request()->routeIs('mobility.status') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">public</span>
                        </span>

                        <span class="sidebar-link__text">Movilidad y becas</span>
                    </a>

                    <a
                        href="{{ route('mobility.agreements.index') }}"
                        class="sidebar-link {{ request()->routeIs('mobility.agreements.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">handshake</span>
                        </span>

                        <span class="sidebar-link__text">Convenios</span>
                    </a>
                @endcan

                @can('research')
                    <a
                        href="{{ route('research.index') }}"
                        class="sidebar-link {{ request()->routeIs('research.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">science</span>
                        </span>

                        <span class="sidebar-link__text">Investigación</span>
                    </a>
                @endcan
            @endcanany

            @canany(['degrees', 'graduates'])
                <p class="sidebar-section-label mt-6">Resultados de la Formación</p>

                @can('degrees')
                    <a
                        href="{{ route('degree.certificates.index') }}"
                        class="sidebar-link {{ request()->routeIs('degree.certificates.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">workspace_premium</span>
                        </span>

                        <span class="sidebar-link__text">Certificados</span>
                    </a>

                    <a
                        href="{{ route('degree.applications.index') }}"
                        class="sidebar-link {{ request()->routeIs('degree.applications.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">school</span>
                        </span>

                        <span class="sidebar-link__text">Grados y títulos</span>
                    </a>
                @endcan

                @can('graduates')
                    <a
                        href="{{ route('graduates.index') }}"
                        class="sidebar-link {{ request()->routeIs('graduates.*') ? 'is-active' : '' }}"
                    >
                        <span class="sidebar-link__icon">
                            <span class="material-symbols-outlined">track_changes</span>
                        </span>

                        <span class="sidebar-link__text">Seguimiento de egresados</span>
                    </a>
                @endcan
            @endcanany
        </nav>

        <div class="sidebar-footer relative shrink-0 border-t border-white/[0.07] p-3">
            <a
                href="{{ route('profile.edit') }}"
                class="sidebar-link {{ request()->routeIs('profile.edit') ? 'is-active' : '' }}"
            >
                <span class="sidebar-link__icon">
                    <span class="material-symbols-outlined">settings</span>
                </span>

                <span class="sidebar-link__text">Configuración</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit" class="sidebar-link sidebar-link--logout w-full">
                    <span class="sidebar-link__icon">
                        <span class="material-symbols-outlined">logout</span>
                    </span>

                    <span class="sidebar-link__text">Cerrar sesión</span>
                </button>
            </form>
        </div>
    </div>
</aside>
