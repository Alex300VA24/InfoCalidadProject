import { usePage } from '@inertiajs/react'
import AcademicHealthBadge from '../../components/Dashboard/AcademicHealthBadge'
import AdminStatsRow from '../../components/Dashboard/AdminStatsRow'
import DashboardHeader from '../../components/Dashboard/DashboardHeader'
import HorizontalBarChart from '../../components/Dashboard/HorizontalBarChart'
import KpiCard from '../../components/Dashboard/KpiCard'
import ModuleCard from '../../components/Dashboard/ModuleCard'
import QualityPanel from '../../components/Dashboard/QualityPanel'
import AppLayout from '../../layouts/AppLayout'
import { deriveAcademicHealth } from '../../utils/dashboardHealth'

const MODULES = [
    { gate: 'presidente-cotejo', href: '/curriculum/reviews', variant: 'dash-module--violet', icon: 'fact_check', title: 'Revisión curricular', description: 'Instrumentos de cotejo, informes técnicos y aprobaciones.', tag: 'GESTIÓN CURRICULAR' },
    { gate: 'syllabi', href: '/syllabi', variant: 'dash-module--blue', icon: 'menu_book', title: 'Repositorio de sílabos', description: 'Carga, visado y descarga de sílabos por periodo.', tag: 'DOCUMENTACIÓN ACADÉMICA' },
    { gate: 'coordinador-admision', href: '/admission/processes', variant: 'dash-module--cyan', icon: 'groups', title: 'Admisión', description: 'Procesos de admisión y registro de postulantes.', tag: 'GESTIÓN DE INGRESO' },
    { gate: 'personal-matricula', href: '/enrollment', variant: 'dash-module--orange', icon: 'how_to_reg', title: 'Matrícula', description: 'Matrículas, fichas, padrones y órdenes de pago.', tag: 'PERIODO ACTIVO' },
    { gate: 'evaluations', href: '/evaluations', variant: 'dash-module--gold', icon: 'target', title: 'Evaluaciones', description: 'Registro y seguimiento de evaluaciones estudiantiles.', tag: 'ENSEÑANZA–APRENDIZAJE' },
    { gate: 'degrees', href: '/degrees/certificates', variant: 'dash-module--green', icon: 'workspace_premium', title: 'Grados y títulos', description: 'Certificados y solicitudes de grados y títulos.', tag: 'RESULTADOS DE FORMACIÓN' },
]

function formatDate() {
    const now = new Date()
    const day = now.toLocaleDateString('es-ES', { weekday: 'long' }).toUpperCase()
    const date = now.toLocaleDateString('es-ES', { day: 'numeric', month: 'long' })
    return { day, date }
}

export default function DashboardIndex({ activePeriod, stats, kpis }) {
    const { auth, can } = usePage().props
    const { day, date } = formatDate()
    const hour = new Date().getHours()
    const greeting = hour < 12 ? 'Buenos días' : hour < 19 ? 'Buenas tardes' : 'Buenas noches'
    const firstName = (auth?.user?.name ?? '').split(' ')[0]
    const modules = MODULES.filter((module) => can[module.gate])
    const health = deriveAcademicHealth({ activePeriod, kpis })

    const modalidadData = Object.entries(kpis?.ingresantesPorModalidad ?? {})
        .map(([modalidad, total]) => ({ label: modalidad.charAt(0).toUpperCase() + modalidad.slice(1), value: total }))
        .sort((a, b) => Number(b.value) - Number(a.value))
    const carreraData = Object.entries(kpis?.matriculadosPorCarrera ?? {})
        .map(([carrera, total]) => ({ label: carrera, value: total }))
        .sort((a, b) => Number(b.value) - Number(a.value))

    return (
        <div className="dash-dashboard">
            <DashboardHeader firstName={firstName} greeting={greeting} dateLabel={`${day}, ${date}`} roleLabel={auth?.user?.role} activePeriod={activePeriod} />
            <AcademicHealthBadge {...health} />

            <section className="dash-kpi-grid" aria-label="Indicadores estratégicos">
                <KpiCard icon="donut_large" label="Cobertura de vacantes" value={kpis?.cobertura ?? 0} unit="%" context="Vacantes cubiertas por ingresantes" />
                <KpiCard icon="how_to_reg" label="Tasa de matrícula" value={kpis?.tasaMatricula ?? 0} unit="%" context="Ingresantes con matrícula registrada" />
                <KpiCard icon="school" label="Matriculados" value={kpis?.matriculados ?? 0} context="Estudiantes del periodo activo" />
                <KpiCard icon="verified" label="Logro de competencias" value={kpis?.competenciaPromedio ?? 0} unit="/20" context="Promedio reportado por egresados" />
            </section>

            <QualityPanel kpis={kpis} />

            <section className="dash-charts" aria-label="Distribución académica">
                <article className="dash-chart-panel">
                    <div className="dash-section-head"><div><h2>Ingresantes por modalidad</h2><p>Distribución del proceso de admisión.</p></div><span className="material-symbols-outlined" aria-hidden="true">groups</span></div>
                    <HorizontalBarChart data={modalidadData} ariaLabel="Ingresantes por modalidad" emptyMessage="Todavía no hay ingresantes registrados por modalidad." />
                </article>
                <article className="dash-chart-panel">
                    <div className="dash-section-head"><div><h2>Matriculados por carrera</h2><p>Concentración de matrícula por programa.</p></div><span className="material-symbols-outlined" aria-hidden="true">school</span></div>
                    <HorizontalBarChart data={carreraData} ariaLabel="Matriculados por carrera" emptyMessage="Todavía no hay matrículas registradas por carrera." />
                </article>
            </section>

            <section className="dash-access" id="modulos" aria-labelledby="modules-title">
                <div className="dash-section-head"><div><h2 id="modules-title">Accesos directos</h2><p>Herramientas disponibles según tu rol institucional.</p></div></div>
                <div className="dash-modules">{modules.map((module) => <ModuleCard key={module.href} {...module} />)}</div>
            </section>

            <AdminStatsRow stats={stats} />
        </div>
    )
}

DashboardIndex.layout = (page) => <AppLayout>{page}</AppLayout>
