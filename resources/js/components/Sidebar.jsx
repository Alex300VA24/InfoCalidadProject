import { Link, usePage } from '@inertiajs/react'

const SECTIONS = [
    {
        label: 'Principal',
        items: [
            {
                label: 'Inicio',
                icon: 'dashboard',
                href: '/dashboard',
                match: ['/dashboard'],
                indicator: true,
            },
        ],
    },
    {
        label: 'Gestión Curricular',
        items: [
            {
                label: 'Revisión curricular',
                icon: 'fact_check',
                href: '/curriculum/reviews',
                gate: 'presidente-cotejo',
                match: ['/curriculum/reviews'],
            },
            {
                label: 'Aprobaciones',
                icon: 'verified',
                href: '/curriculum/approvals',
                gate: 'director-escuela',
                match: ['/curriculum/approvals'],
            },
            {
                label: 'Repositorio de sílabos',
                icon: 'folder_shared',
                href: '/syllabi',
                gate: 'syllabi',
                match: ['/syllabi'],
            },
            {
                label: 'Solicitudes de recursos',
                icon: 'inventory_2',
                href: '/resources',
                gate: 'resources',
                match: ['/resources'],
            },
        ],
    },
    {
        label: 'Gestión del Ingreso',
        items: [
            {
                label: 'Procesos de admisión',
                icon: 'assignment',
                href: '/admission/processes',
                gate: 'coordinador-admision',
                match: ['/admission/processes'],
            },
            {
                label: 'Postulantes',
                icon: 'groups',
                href: '/admission/applicants',
                gate: 'coordinador-admision',
                match: ['/admission/applicants'],
            },
            {
                label: 'Matrículas',
                icon: 'how_to_reg',
                href: '/enrollment',
                gate: 'personal-matricula',
                match: ['/enrollment'],
                exclude: ['/enrollment/reports', '/enrollment/padron-virtual'],
            },
            {
                label: 'Padrón virtual',
                icon: 'list_alt',
                href: '/enrollment/padron-virtual',
                gate: 'personal-matricula',
                match: ['/enrollment/padron-virtual'],
            },
            {
                label: 'Reportes',
                icon: 'summarize',
                href: '/enrollment/reports/egresados',
                gate: 'personal-matricula',
                match: ['/enrollment/reports'],
            },
        ],
    },
    {
        label: 'Enseñanza y Aprendizaje',
        items: [
            {
                label: 'Evaluaciones',
                icon: 'task_alt',
                href: '/evaluations',
                gate: 'evaluations',
                match: ['/evaluations'],
                exclude: ['/evaluations/actas'],
            },
            {
                label: 'Actas oficiales',
                icon: 'fact_check',
                href: '/evaluations/actas',
                gate: 'evaluations',
                match: ['/evaluations/actas'],
            },
            {
                label: 'Sesiones de clase',
                icon: 'calendar_view_month',
                href: '/execution',
                gate: 'execution',
                match: ['/execution'],
            },
            {
                label: 'Cargas académicas',
                icon: 'assignments',
                href: '/execution/loads',
                gate: 'execution',
                match: ['/execution/loads'],
            },
            {
                label: 'Socialización de sílabos',
                icon: 'campaign',
                href: '/execution/socializations',
                gate: 'execution',
                match: ['/execution/socializations'],
            },
            {
                label: 'Ejecución de asignaturas',
                icon: 'progress_activity',
                href: '/execution/executions',
                gate: 'execution',
                match: ['/execution/executions'],
            },
            {
                label: 'Desempeño docente',
                icon: 'insights',
                href: '/execution/performance',
                gate: 'execution',
                match: ['/execution/performance'],
            },
            {
                label: 'Tutoría académica',
                icon: 'support_agent',
                href: '/tutoring',
                gate: 'tutoring',
                match: ['/tutoring'],
                exclude: ['/tutoring/remedial'],
            },
            {
                label: 'Nivelación y recuperación',
                icon: 'healing',
                href: '/tutoring/remedial',
                gate: 'tutoring',
                match: ['/tutoring/remedial'],
            },
            {
                label: 'Movilidad y becas',
                icon: 'public',
                href: '/mobility',
                gate: 'mobility',
                match: ['/mobility'],
            },
            {
                label: 'Convenios',
                icon: 'handshake',
                href: '/mobility/agreements',
                gate: 'mobility',
                match: ['/mobility/agreements'],
            },
            {
                label: 'Investigación',
                icon: 'science',
                href: '/research',
                gate: 'research',
                match: ['/research'],
            },
        ],
    },
    {
        label: 'Resultados de la Formación',
        items: [
            {
                label: 'Certificados',
                icon: 'workspace_premium',
                href: '/degrees/certificates',
                gate: 'degrees',
                match: ['/degrees/certificates'],
            },
            {
                label: 'Grados y títulos',
                icon: 'school',
                href: '/degrees/applications',
                gate: 'degrees',
                match: ['/degrees/applications'],
            },
            {
                label: 'Seguimiento de egresados',
                icon: 'track_changes',
                href: '/graduates',
                gate: 'graduates',
                match: ['/graduates'],
            },
        ],
    },
]

export default function Sidebar({ collapsed, open, onOpenChange }) {
    const { props, url } = usePage()
    const can = (ability) => Boolean(props.can?.[ability])

    const path = url.split('?')[0]

    const isActive = (match, exclude = []) => {
        if (exclude.some((prefix) => path.startsWith(prefix))) return false
        return match.some((prefix) => {
            if (prefix.endsWith('*')) return path.startsWith(prefix.slice(0, -1))
            return path === prefix
        })
    }

    const sections = SECTIONS.map((section) => ({
        ...section,
        items: section.items.filter((item) => !item.gate || can(item.gate)),
    })).filter((section) => section.items.length > 0)

    return (
        <aside
            className={`app-sidebar group/sidebar ${open ? 'is-open' : ''} ${collapsed ? 'is-collapsed' : ''}`}
            aria-label="Barra lateral"
        >
            <div className="sidebar-glow sidebar-glow--top"></div>
            <div className="sidebar-glow sidebar-glow--bottom"></div>

            <div className="sidebar-inner relative w-full h-full overflow-hidden">
                <div className="sidebar-brand-wrap shrink-0 px-4 pb-5 pt-5">
                    <Link href="/dashboard" className="sidebar-brand group">
                        <img
                            src="/static/img/logo_informatica.png"
                            alt="Universidad Nacional de Trujillo"
                            className="relative h-11 w-11 rounded-2xl object-cover shadow-2xl transition duration-300 group-hover:-translate-y-1 group-hover:rotate-[-2deg]"
                        />

                        <span className="min-w-0">
                            <span className="flex items-center gap-2">
                                <strong>UNT</strong>
                                <span className="sidebar-brand__point"></span>
                            </span>

                            <small>Ing. Informática</small>
                        </span>
                    </Link>
                </div>

                <nav className="sidebar-nav flex-1 overflow-y-auto px-3" aria-label="Navegación principal">
                    {sections.map((section, index) => (
                        <div key={section.label}>
                            <p className={`sidebar-section-label ${index > 0 ? 'mt-6' : ''}`}>
                                {section.label}
                            </p>

                            {section.items.map((item) => {
                                const active = isActive(item.match, item.exclude)

                                return (
                                    <Link
                                        key={item.href}
                                        href={item.href}
                                        className={`sidebar-link ${active ? 'is-active' : ''}`}
                                        onClick={() => onOpenChange(false)}
                                    >
                                        <span className="sidebar-link__icon">
                                            <span className="material-symbols-outlined">{item.icon}</span>
                                        </span>

                                        <span className="sidebar-link__text">{item.label}</span>

                                        {item.indicator && active && (
                                            <span className="sidebar-link__indicator"></span>
                                        )}
                                    </Link>
                                )
                            })}
                        </div>
                    ))}
                </nav>

                <div className="sidebar-footer relative shrink-0 border-t border-white/[0.07] p-3">
                    <Link
                        href="/logout"
                        method="post"
                        as="button"
                        type="button"
                        className="sidebar-link sidebar-link--logout w-full"
                    >
                        <span className="sidebar-link__icon">
                            <span className="material-symbols-outlined">logout</span>
                        </span>

                        <span className="sidebar-link__text">Cerrar sesión</span>
                    </Link>
                </div>
            </div>
        </aside>
    )
}
