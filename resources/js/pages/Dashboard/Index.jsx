import { useEffect, useRef } from 'react'
import { Link, usePage } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

const MODULES = [
    {
        gate: 'presidente-cotejo',
        href: '/curriculum/reviews',
        variant: 'nexo-module--violet',
        icon: 'fact_check',
        title: 'Revisión curricular',
        description: 'Instrumentos de cotejo, informes técnicos y aprobaciones.',
        tag: 'GESTIÓN CURRICULAR',
        delay: 'delay-400',
    },
    {
        gate: 'syllabi',
        href: '/syllabi',
        variant: 'nexo-module--blue',
        icon: 'menu_book',
        title: 'Repositorio de sílabos',
        description: 'Carga, visado y descarga de sílabos por periodo.',
        tag: 'DOCUMENTACIÓN ACADÉMICA',
        delay: 'delay-450',
    },
    {
        gate: 'coordinador-admision',
        href: '/admission/processes',
        variant: 'nexo-module--cyan',
        icon: 'groups',
        title: 'Admisión',
        description: 'Procesos de admisión y registro de postulantes.',
        tag: 'GESTIÓN DE INGRESO',
        delay: 'delay-500',
    },
    {
        gate: 'personal-matricula',
        href: '/enrollment',
        variant: 'nexo-module--orange',
        icon: 'how_to_reg',
        title: 'Matrícula',
        description: 'Matrículas, fichas, padrones y órdenes de pago.',
        tag: 'PERIODO ACTIVO',
        delay: 'delay-550',
    },
    {
        gate: 'evaluations',
        href: '/evaluations',
        variant: 'nexo-module--gold',
        icon: 'target',
        title: 'Evaluaciones',
        description: 'Registro y seguimiento de evaluaciones estudiantiles.',
        tag: 'ENSEÑANZA–APRENDIZAJE',
        delay: 'delay-600',
    },
    {
        gate: 'degrees',
        href: '/degrees/certificates',
        variant: 'nexo-module--green',
        icon: 'workspace_premium',
        title: 'Grados y títulos',
        description: 'Certificados y solicitudes de grados y títulos.',
        tag: 'RESULTADOS DE FORMACIÓN',
        delay: 'delay-650',
    },
]

const KPI_LABELS = [
    ['Vacantes ofrecidas', 'totalVacantes'],
    ['Ingresantes', 'ingresantes'],
    ['Cobertura de vacantes', 'cobertura', '%'],
    ['Matriculados', 'matriculados'],
    ['Tasa de matrícula', 'tasaMatricula', '%'],
    ['Encuestas de egresados', 'encuestas'],
    ['Inserción laboral', 'insercionLaboral', '%'],
    ['Logro de competencias', 'competenciaPromedio', '/20'],
]

function formatDate() {
    const now = new Date()
    const day = now.toLocaleDateString('es-ES', { weekday: 'long' }).toUpperCase()
    const date = now.toLocaleDateString('es-ES', { day: 'numeric', month: 'long' })
    return { day, date }
}

export default function DashboardIndex({ activePeriod, stats, kpis }) {
    const { auth, can } = usePage().props
    const progressRef = useRef(null)

    const { day, date } = formatDate()

    useEffect(() => {
        const bars = progressRef.current?.querySelectorAll?.('.nexo-progress em[data-width]')
        if (!bars?.length) return

        let raf = requestAnimationFrame(function animate() {
            requestAnimationFrame(function fill() {
                bars.forEach((em) => {
                    em.style.width = em.getAttribute('data-width')
                })
            })
        })

        return () => cancelAnimationFrame(raf)
    }, [])

    const firstName = (auth?.user?.name ?? '').split(' ')[0]
    const modules = MODULES.filter((module) => can[module.gate])

    const ingresantesPorModalidad = kpis?.ingresantesPorModalidad ?? {}
    const matriculadosPorCarrera = kpis?.matriculadosPorCarrera ?? {}
    const hasBreakdown =
        Object.keys(ingresantesPorModalidad).length > 0 ||
        Object.keys(matriculadosPorCarrera).length > 0

    const cobertura = Math.min(Number(kpis?.cobertura) || 0, 100)
    const tasaMatricula = Math.min(Number(kpis?.tasaMatricula) || 0, 100)
    const competenciaPromedio = Math.min((Number(kpis?.competenciaPromedio) || 0) / 20 * 100, 100)

    return (
        <div className="nexo-dashboard page-enter">
            <section className="nexo-welcome">
                <div className="reveal-item delay-50">
                    <span className="nexo-eyebrow">
                        <span className="material-symbols-outlined">bolt</span> CENTRO DE CONTROL ACADÉMICO
                    </span>
                    <h1>Buenos días, {firstName}.</h1>
                    <p>Todo lo importante de la universidad, organizado para actuar sin perder el contexto.</p>
                </div>
                <div className="nexo-date reveal-item delay-150">
                    <span className="material-symbols-outlined">calendar_month</span>
                    <div>
                        <small>{day}</small>
                        <strong>{date}</strong>
                    </div>
                </div>
            </section>

            <section className="nexo-signal reveal-item delay-250">
                <div className="nexo-signal__status">
                    <i></i>
                    <div>
                        <small>ESTADO GENERAL</small>
                        <strong>El periodo marcha según lo planificado</strong>
                    </div>
                </div>
                {Object.entries(stats ?? {})
                    .slice(0, 2)
                    .map(([label, value]) => (
                        <div className="nexo-signal__metric" key={label}>
                            <b>{value}</b>
                            <div>
                                <strong>{label}</strong>
                                <small>Información actualizada</small>
                            </div>
                        </div>
                    ))}
                <a href="#modulos">
                    Ver resumen <span className="material-symbols-outlined">arrow_forward</span>
                </a>
            </section>

            {activePeriod && (
                <div className="nexo-period reveal-item delay-300">
                    <span className="material-symbols-outlined">auto_awesome</span>
                    <span>Periodo académico activo</span>
                    <strong>{activePeriod.name}</strong>
                </div>
            )}

            <div className="nexo-section-heading reveal-item delay-350" id="modulos">
                <div>
                    <span>ACCESOS DIRECTOS</span>
                    <h2>Tu universidad, en un solo lugar</h2>
                </div>
            </div>

            <section className="nexo-modules">
                {modules.map((module) => (
                    <Link
                        key={module.href}
                        href={module.href}
                        className={`nexo-module ${module.variant} reveal-item ${module.delay}`}
                    >
                        <span className="nexo-module__shine"></span>
                        <span className="nexo-module__icon material-symbols-outlined">{module.icon}</span>
                        <span className="nexo-module__arrow material-symbols-outlined">arrow_forward</span>
                        <h3>{module.title}</h3>
                        <p>{module.description}</p>
                        <small>{module.tag}</small>
                    </Link>
                ))}
            </section>

            <section className="nexo-lower-grid">
                <article className="nexo-panel reveal-item delay-550">
                    <header>
                        <div>
                            <span>INDICADORES</span>
                            <h2>Resumen institucional</h2>
                        </div>
                        <span className="material-symbols-outlined">monitoring</span>
                    </header>
                    <div className="nexo-stat-grid">
                        {Object.entries(stats ?? {}).map(([label, value]) => (
                            <div key={label}>
                                <small>{label}</small>
                                <strong>{value}</strong>
                            </div>
                        ))}
                    </div>
                    <div className="nexo-stat-grid nexo-stat-grid--kpis">
                        {KPI_LABELS.map(([label, key, suffix]) => (
                            <div key={key}>
                                <small>{label}</small>
                                <strong>{kpis?.[key] ?? 0}{suffix}</strong>
                            </div>
                        ))}
                    </div>
                </article>

                <article className="nexo-panel nexo-quality reveal-item delay-650">
                    <header>
                        <div>
                            <span>RESULTADOS DE FORMACIÓN</span>
                            <h2>Calidad académica</h2>
                        </div>
                        <span className="material-symbols-outlined">verified</span>
                    </header>
                    <div className="nexo-quality__body">
                        <div
                            className="nexo-ring"
                            style={{
                                background: `conic-gradient(var(--nexo-accent, #2188f3) 0 ${Number(kpis?.insercionLaboral) || 0}%, #e8eef2 ${Number(kpis?.insercionLaboral) || 0}%)`,
                            }}
                        >
                            <span>
                                {Number(kpis?.insercionLaboral) || 0}
                                <small>%</small>
                            </span>
                        </div>
                        <div>
                            <strong>Inserción laboral</strong>
                            <small>Egresados empleados según encuestas</small>
                        </div>
                    </div>
                    <div className="nexo-progress">
                        <div>
                            <span>Cobertura de vacantes</span>
                            <b>{kpis?.cobertura ?? 0}%</b>
                        </div>
                        <i>
                            <em data-width={`${cobertura}%`} style={{ width: '0%' }}></em>
                        </i>
                    </div>
                    <div className="nexo-progress">
                        <div>
                            <span>Tasa de matrícula</span>
                            <b>{kpis?.tasaMatricula ?? 0}%</b>
                        </div>
                        <i>
                            <em data-width={`${tasaMatricula}%`} style={{ width: '0%' }}></em>
                        </i>
                    </div>
                    <div className="nexo-progress">
                        <div>
                            <span>Logro de competencias</span>
                            <b>{kpis?.competenciaPromedio ?? 0}/20</b>
                        </div>
                        <i>
                            <em data-width={`${competenciaPromedio}%`} style={{ width: '0%' }}></em>
                        </i>
                    </div>
                </article>
            </section>

            {hasBreakdown && (
                <section className="nexo-lower-grid nexo-lower-grid--breakdown">
                    {Object.keys(ingresantesPorModalidad).length > 0 && (
                        <article className="nexo-panel reveal-item delay-700">
                            <header>
                                <div>
                                    <span>ADMISIÓN</span>
                                    <h2>Ingresantes por modalidad</h2>
                                </div>
                                <span className="material-symbols-outlined">groups</span>
                            </header>
                            <div className="nexo-breakdown">
                                {Object.entries(ingresantesPorModalidad).map(([modalidad, total]) => (
                                    <div className="nexo-breakdown__row" key={modalidad}>
                                        <span>{modalidad.charAt(0).toUpperCase() + modalidad.slice(1)}</span>
                                        <b>{total}</b>
                                    </div>
                                ))}
                            </div>
                        </article>
                    )}

                    {Object.keys(matriculadosPorCarrera).length > 0 && (
                        <article className="nexo-panel reveal-item delay-750">
                            <header>
                                <div>
                                    <span>MATRÍCULA</span>
                                    <h2>Matriculados por carrera</h2>
                                </div>
                                <span className="material-symbols-outlined">how_to_reg</span>
                            </header>
                            <div className="nexo-breakdown">
                                {Object.entries(matriculadosPorCarrera).map(([carrera, total]) => (
                                    <div className="nexo-breakdown__row" key={carrera}>
                                        <span>{carrera}</span>
                                        <b>{total}</b>
                                    </div>
                                ))}
                            </div>
                        </article>
                    )}
                </section>
            )}
        </div>
    )
}

DashboardIndex.layout = (page) => <AppLayout>{page}</AppLayout>
