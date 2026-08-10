<x-app-layout>
    <div class="nexo-dashboard page-enter">
        <section class="nexo-welcome">
            <div class="reveal-item delay-50">
                <span class="nexo-eyebrow"><span class="material-symbols-outlined">bolt</span> CENTRO DE CONTROL ACADÉMICO</span>
                <h1>Buenos días, {{ explode(' ', Auth::user()->name)[0] }}.</h1>
                <p>Todo lo importante de la universidad, organizado para actuar sin perder el contexto.</p>
            </div>
            <div class="nexo-date reveal-item delay-150">
                <span class="material-symbols-outlined">calendar_month</span>
                <div><small>{{ mb_strtoupper(now()->translatedFormat('l')) }}</small><strong>{{ now()->translatedFormat('d \d\e F') }}</strong></div>
            </div>
        </section>

        <section class="nexo-signal reveal-item delay-250">
            <div class="nexo-signal__status">
                <i></i>
                <div><small>ESTADO GENERAL</small><strong>El periodo marcha según lo planificado</strong></div>
            </div>
            @foreach(array_slice($stats, 0, 2, true) as $label => $value)
                <div class="nexo-signal__metric"><b>{{ $value }}</b><div><strong>{{ $label }}</strong><small>Información actualizada</small></div></div>
            @endforeach
            <a href="#modulos">Ver resumen <span class="material-symbols-outlined">arrow_forward</span></a>
        </section>

        @if($activePeriod)
            <div class="nexo-period reveal-item delay-300">
                <span class="material-symbols-outlined">auto_awesome</span>
                <span>Periodo académico activo</span>
                <strong>{{ $activePeriod->name }}</strong>
            </div>
        @endif

        <div class="nexo-section-heading reveal-item delay-350" id="modulos">
            <div><span>ACCESOS DIRECTOS</span><h2>Tu universidad, en un solo lugar</h2></div>
        </div>

        <section class="nexo-modules">
            @can('presidente-cotejo')
                <a href="{{ route('curriculum.reviews.index') }}" class="nexo-module nexo-module--violet reveal-item delay-400">
                    <span class="nexo-module__shine"></span>
                    <span class="nexo-module__icon material-symbols-outlined">fact_check</span><span class="nexo-module__arrow material-symbols-outlined">arrow_forward</span>
                    <h3>Revisión curricular</h3><p>Instrumentos de cotejo, informes técnicos y aprobaciones.</p><small>GESTIÓN CURRICULAR</small>
                </a>
            @endcan

            @can('syllabi')
                <a href="{{ route('syllabi.index') }}" class="nexo-module nexo-module--blue reveal-item delay-450">
                    <span class="nexo-module__shine"></span>
                    <span class="nexo-module__icon material-symbols-outlined">menu_book</span><span class="nexo-module__arrow material-symbols-outlined">arrow_forward</span>
                    <h3>Repositorio de sílabos</h3><p>Carga, visado y descarga de sílabos por periodo.</p><small>DOCUMENTACIÓN ACADÉMICA</small>
                </a>
            @endcan

            @can('coordinador-admision')
                <a href="{{ route('admission.processes.index') }}" class="nexo-module nexo-module--cyan reveal-item delay-500">
                    <span class="nexo-module__shine"></span>
                    <span class="nexo-module__icon material-symbols-outlined">groups</span><span class="nexo-module__arrow material-symbols-outlined">arrow_forward</span>
                    <h3>Admisión</h3><p>Procesos de admisión y registro de postulantes.</p><small>GESTIÓN DE INGRESO</small>
                </a>
            @endcan

            @can('personal-matricula')
                <a href="{{ route('enrollment.index') }}" class="nexo-module nexo-module--orange reveal-item delay-550">
                    <span class="nexo-module__shine"></span>
                    <span class="nexo-module__icon material-symbols-outlined">how_to_reg</span><span class="nexo-module__arrow material-symbols-outlined">arrow_forward</span>
                    <h3>Matrícula</h3><p>Matrículas, fichas, padrones y órdenes de pago.</p><small>PERIODO ACTIVO</small>
                </a>
            @endcan

            @can('evaluations')
                <a href="{{ route('evaluations.index') }}" class="nexo-module nexo-module--gold reveal-item delay-600">
                    <span class="nexo-module__shine"></span>
                    <span class="nexo-module__icon material-symbols-outlined">target</span><span class="nexo-module__arrow material-symbols-outlined">arrow_forward</span>
                    <h3>Evaluaciones</h3><p>Registro y seguimiento de evaluaciones estudiantiles.</p><small>ENSEÑANZA–APRENDIZAJE</small>
                </a>
            @endcan

            @can('degrees')
                <a href="{{ route('degree.certificates.index') }}" class="nexo-module nexo-module--green reveal-item delay-650">
                    <span class="nexo-module__shine"></span>
                    <span class="nexo-module__icon material-symbols-outlined">workspace_premium</span><span class="nexo-module__arrow material-symbols-outlined">arrow_forward</span>
                    <h3>Grados y títulos</h3><p>Certificados y solicitudes de grados y títulos.</p><small>RESULTADOS DE FORMACIÓN</small>
                </a>
            @endcan
        </section>

        <section class="nexo-lower-grid">
            <article class="nexo-panel reveal-item delay-550">
                <header><div><span>INDICADORES</span><h2>Resumen institucional</h2></div><span class="material-symbols-outlined">monitoring</span></header>
                <div class="nexo-stat-grid">
                    @forelse($stats as $label => $value)
                        <div><small>{{ $label }}</small><strong>{{ $value }}</strong></div>
                    @empty
                        <p>No hay indicadores disponibles para tu perfil.</p>
                    @endforelse
                </div>
                <div class="nexo-stat-grid nexo-stat-grid--kpis">
                    <div><small>Vacantes ofrecidas</small><strong>{{ $kpis['totalVacantes'] }}</strong></div>
                    <div><small>Ingresantes</small><strong>{{ $kpis['ingresantes'] }}</strong></div>
                    <div><small>Cobertura de vacantes</small><strong>{{ $kpis['cobertura'] }}%</strong></div>
                    <div><small>Matriculados</small><strong>{{ $kpis['matriculados'] }}</strong></div>
                    <div><small>Tasa de matrícula</small><strong>{{ $kpis['tasaMatricula'] }}%</strong></div>
                    <div><small>Encuestas de egresados</small><strong>{{ $kpis['encuestas'] }}</strong></div>
                    <div><small>Inserción laboral</small><strong>{{ $kpis['insercionLaboral'] }}%</strong></div>
                    <div><small>Logro de competencias</small><strong>{{ $kpis['competenciaPromedio'] }}/20</strong></div>
                </div>
            </article>

            <article class="nexo-panel nexo-quality reveal-item delay-650">
                <header><div><span>RESULTADOS DE FORMACIÓN</span><h2>Calidad académica</h2></div><span class="material-symbols-outlined">verified</span></header>
                <div class="nexo-quality__body">
                    <div class="nexo-ring" style="background:conic-gradient(var(--nexo-accent, #2188f3) 0 {{ $kpis['insercionLaboral'] }}%,#e8eef2 {{ $kpis['insercionLaboral'] }}%)"><span>{{ $kpis['insercionLaboral'] }}<small>%</small></span></div>
                    <div><strong>Inserción laboral</strong><small>Egresados empleados según encuestas</small></div>
                </div>
                <div class="nexo-progress"><div><span>Cobertura de vacantes</span><b>{{ $kpis['cobertura'] }}%</b></div><i><em data-width="{{ min($kpis['cobertura'], 100) }}%" style="width:0%"></em></i></div>
                <div class="nexo-progress"><div><span>Tasa de matrícula</span><b>{{ $kpis['tasaMatricula'] }}%</b></div><i><em data-width="{{ min($kpis['tasaMatricula'], 100) }}%" style="width:0%"></em></i></div>
                <div class="nexo-progress"><div><span>Logro de competencias</span><b>{{ $kpis['competenciaPromedio'] }}/20</b></div><i><em data-width="{{ min(($kpis['competenciaPromedio'] / 20) * 100, 100) }}%" style="width:0%"></em></i></div>
            </article>
        </section>

        @if($kpis['ingresantesPorModalidad']->isNotEmpty() || $kpis['matriculadosPorCarrera']->isNotEmpty())
            <section class="nexo-lower-grid nexo-lower-grid--breakdown">
                <article class="nexo-panel reveal-item delay-700">
                    <header><div><span>ADMISIÓN</span><h2>Ingresantes por modalidad</h2></div><span class="material-symbols-outlined">groups</span></header>
                    <div class="nexo-breakdown">
                        @foreach($kpis['ingresantesPorModalidad'] as $modalidad => $total)
                            <div class="nexo-breakdown__row"><span>{{ ucfirst($modalidad) }}</span><b>{{ $total }}</b></div>
                        @endforeach
                    </div>
                </article>
                <article class="nexo-panel reveal-item delay-750">
                    <header><div><span>MATRÍCULA</span><h2>Matriculados por carrera</h2></div><span class="material-symbols-outlined">how_to_reg</span></header>
                    <div class="nexo-breakdown">
                        @foreach($kpis['matriculadosPorCarrera'] as $carrera => $total)
                            <div class="nexo-breakdown__row"><span>{{ $carrera }}</span><b>{{ $total }}</b></div>
                        @endforeach
                    </div>
                </article>
            </section>
        @endif
    </div>

    @push('scripts')
    <script data-turbo-eval="true">
        window.__onReady(function () {
            var bars = document.querySelectorAll('.nexo-progress em[data-width]');
            if (bars.length) {
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () {
                        bars.forEach(function (em) {
                            em.style.width = em.getAttribute('data-width');
                        });
                    });
                });
            }
        });
    </script>
    @endpush
</x-app-layout>
