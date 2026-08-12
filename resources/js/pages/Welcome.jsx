import { Head, Link, usePage } from '@inertiajs/react'

const areas = [
    { name: 'Gestión Curricular', description: 'Organiza la revisión de sílabos, informes técnicos y procesos que sostienen la calidad del currículo.', icon: 'menu_book', tone: 'bg-blue-50 text-blue-700' },
    { name: 'Gestión del Ingreso', description: 'Acompaña la admisión, selección y registro de estudiantes desde el inicio de su trayectoria académica.', icon: 'person_add', tone: 'bg-cyan-50 text-cyan-700' },
    { name: 'Enseñanza y Aprendizaje', description: 'Articula el seguimiento educativo, las metodologías y el rendimiento para fortalecer la formación.', icon: 'school', tone: 'bg-violet-50 text-violet-700' },
    { name: 'Resultados de la Formación', description: 'Integra logros de aprendizaje, desempeño y evaluación para orientar decisiones de mejora continua.', icon: 'monitoring', tone: 'bg-amber-50 text-amber-700' },
]

const benefits = [
    ['hub', 'Visión integrada', 'Procesos académicos conectados en un mismo entorno institucional.'],
    ['fact_check', 'Seguimiento ordenado', 'Información estructurada para revisar avances y respaldar decisiones.'],
    ['groups', 'Trabajo coordinado', 'Un lenguaje común para docentes, estudiantes, responsables y evaluadores.'],
]

export default function Welcome() {
    const { auth } = usePage().props
    const accessHref = auth?.user ? '/dashboard' : '/login'
    const accessLabel = auth?.user ? 'Ir al dashboard' : 'Iniciar sesión'

    return (
        <div className="public-site min-h-screen overflow-x-hidden bg-canvas text-ink-950 antialiased">
            <Head title="Plataforma de Calidad Académica" />

            <header className="sticky top-0 z-50 border-b border-white/10 bg-ink-950/95 shadow-[0_12px_40px_-24px_rgba(8,26,35,.8)]">
                <nav className="mx-auto flex h-[72px] max-w-7xl items-center justify-between gap-5 px-5 sm:px-8" aria-label="Navegación principal">
                    <Link href="/" className="flex min-w-0 items-center gap-3 rounded-xl focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">
                        <img src="/static/img/logo_informatica.png" alt="Facultad de Ingeniería Informática" className="h-10 w-10 rounded-xl object-cover" />
                        <span className="min-w-0">
                            <strong className="block truncate text-sm font-extrabold tracking-wide text-white">UNT · Ingeniería Informática</strong>
                            <span className="block truncate text-[11px] text-slate-400">Plataforma de Calidad Académica</span>
                        </span>
                    </Link>
                    <div className="flex items-center gap-5">
                        <div className="hidden items-center gap-5 text-sm font-semibold text-slate-300 md:flex">
                            <a href="#plataforma" className="rounded-md hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">Plataforma</a>
                            <a href="#areas" className="rounded-md hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">Áreas</a>
                            <a href="#beneficios" className="rounded-md hover:text-white focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300">Beneficios</a>
                        </div>
                        <Link href={accessHref} aria-label={accessLabel} className="inline-flex min-h-11 items-center gap-2 rounded-xl bg-white px-3 py-2.5 text-sm font-extrabold text-brand-900 shadow-[0_10px_28px_-16px_rgba(255,255,255,.6)] transition hover:bg-brand-50 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-300 focus-visible:ring-offset-2 focus-visible:ring-offset-ink-950 sm:px-4">
                            <span className="material-symbols-outlined text-[19px]">login</span>
                            <span className="sm:hidden">Acceder</span><span className="hidden sm:inline">{accessLabel}</span>
                        </Link>
                    </div>
                </nav>
            </header>

            <main>
                <section id="plataforma" className="relative isolate overflow-hidden bg-ink-950 text-white">
                    <div className="absolute inset-0 -z-20 bg-[radial-gradient(circle_at_80%_20%,rgba(33,136,243,.2),transparent_34%),linear-gradient(135deg,#081a23_0%,#0a3047_55%,#071922_100%)]" />
                    <div className="absolute right-[8%] top-24 -z-10 hidden h-72 w-72 rounded-full border border-white/10 lg:block" />
                    <div className="mx-auto grid max-w-7xl items-center gap-14 px-5 py-20 sm:px-8 sm:py-28 lg:grid-cols-[1.15fr_.85fr] lg:py-32">
                        <div>
                            <h1 className="max-w-4xl text-balance text-4xl font-black leading-[1.06] tracking-[-0.035em] sm:text-6xl lg:text-[5rem]">
                                Calidad académica que conecta cada etapa de la formación
                            </h1>
                            <p className="mt-7 max-w-2xl text-base leading-8 text-slate-300 sm:text-lg">
                                Un entorno institucional para organizar, acompañar y fortalecer los procesos académicos de Ingeniería Informática en la Universidad Nacional de Trujillo.
                            </p>
                            <div className="mt-9 flex flex-wrap gap-3">
                                <Link href={accessHref} className="inline-flex min-h-12 items-center gap-2 rounded-xl bg-brand-500 px-6 py-3 text-sm font-extrabold text-white shadow-[0_18px_40px_-20px_rgba(33,136,243,.8)] transition hover:bg-brand-400 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-200">
                                    {accessLabel}<span className="material-symbols-outlined text-[19px]">arrow_forward</span>
                                </Link>
                                <a href="#areas" className="inline-flex min-h-12 items-center rounded-xl border border-white/20 px-6 py-3 text-sm font-bold text-white transition hover:bg-white/10 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-200">Conocer la plataforma</a>
                            </div>
                        </div>
                        <div className="relative mx-auto w-full max-w-md lg:justify-self-end">
                            <div className="rounded-2xl bg-white p-7 text-ink-950 shadow-[0_35px_90px_-38px_rgba(0,0,0,.75)] sm:p-9">
                                <span className="material-symbols-outlined text-[36px] text-brand-600">verified_user</span>
                                <h2 className="mt-5 text-2xl font-black tracking-[-0.025em]">Una plataforma, un propósito común</h2>
                                <p className="mt-4 text-sm leading-7 text-ink-600">Transformar información académica en seguimiento útil, coordinación y decisiones orientadas a la mejora continua.</p>
                                <div className="mt-7 border-t border-ink-100 pt-6 text-sm font-bold text-brand-800">Acceso seguro · Gestión integrada · Mejora continua</div>
                            </div>
                        </div>
                    </div>
                </section>

                <section id="areas" className="scroll-mt-20 py-20 sm:py-24">
                    <div className="mx-auto max-w-7xl px-5 sm:px-8">
                        <div className="max-w-3xl">
                            <h2 className="text-balance text-3xl font-black tracking-[-0.03em] text-ink-950 sm:text-5xl">Cuatro áreas que sostienen el ciclo académico</h2>
                            <p className="mt-5 max-w-2xl text-base leading-8 text-ink-600">Cada área aporta información y seguimiento a una visión compartida de calidad. Los módulos forman parte de un único sistema institucional.</p>
                        </div>
                        <div className="mt-12 grid gap-x-8 gap-y-10 md:grid-cols-2 xl:grid-cols-4">
                            {areas.map((area) => (
                                <article key={area.name} className="border-t border-ink-200 pt-6">
                                    <span className={`flex h-12 w-12 items-center justify-center rounded-xl ${area.tone}`} aria-hidden="true"><span className="material-symbols-outlined text-[25px]">{area.icon}</span></span>
                                    <h3 className="mt-6 text-xl font-extrabold tracking-[-0.02em] text-ink-950">{area.name}</h3>
                                    <p className="mt-3 text-sm leading-7 text-ink-600">{area.description}</p>
                                </article>
                            ))}
                        </div>
                    </div>
                </section>

                <section id="beneficios" className="scroll-mt-20 bg-white py-20 sm:py-24">
                    <div className="mx-auto grid max-w-7xl gap-12 px-5 sm:px-8 lg:grid-cols-[.8fr_1.2fr]">
                        <div>
                            <h2 className="text-balance text-3xl font-black tracking-[-0.03em] sm:text-5xl">Información que ayuda a comprender y actuar</h2>
                            <p className="mt-5 text-base leading-8 text-ink-600">La plataforma facilita el trabajo cotidiano de la comunidad académica y ofrece una lectura organizada de los procesos a responsables y evaluadores.</p>
                        </div>
                        <div className="divide-y divide-ink-100 border-y border-ink-100">
                            {benefits.map(([icon, title, copy]) => (
                                <div key={title} className="grid gap-4 py-7 sm:grid-cols-[52px_1fr]">
                                    <span className="material-symbols-outlined text-[28px] text-brand-600" aria-hidden="true">{icon}</span>
                                    <div><h3 className="text-lg font-extrabold">{title}</h3><p className="mt-2 text-sm leading-7 text-ink-600">{copy}</p></div>
                                </div>
                            ))}
                        </div>
                    </div>
                </section>

                <section className="bg-brand-950 py-20 text-white sm:py-24">
                    <div className="mx-auto max-w-7xl px-5 sm:px-8">
                        <h2 className="max-w-3xl text-balance text-3xl font-black tracking-[-0.03em] sm:text-5xl">Un ciclo continuo, no acciones aisladas</h2>
                        <div className="mt-12 grid gap-8 md:grid-cols-3">
                            {[
                                ['visibility', 'Observar', 'Reunir información relevante de los procesos académicos.'],
                                ['analytics', 'Comprender', 'Organizar hallazgos para reconocer avances y oportunidades.'],
                                ['autorenew', 'Mejorar', 'Convertir el seguimiento en acciones coordinadas y sostenibles.'],
                            ].map(([icon, title, copy]) => <div key={title} className="border-t border-brand-700 pt-6"><span className="material-symbols-outlined text-brand-300">{icon}</span><h3 className="mt-5 text-xl font-extrabold">{title}</h3><p className="mt-3 max-w-sm text-sm leading-7 text-brand-100">{copy}</p></div>)}
                        </div>
                    </div>
                </section>

                <section className="py-20 sm:py-24">
                    <div className="mx-auto max-w-5xl px-5 text-center sm:px-8">
                        <h2 className="text-balance text-3xl font-black tracking-[-0.03em] sm:text-5xl">Compromiso institucional con la mejora continua</h2>
                        <p className="mx-auto mt-6 max-w-3xl text-base leading-8 text-ink-600">Tecnología al servicio de una gestión académica responsable, organizada y enfocada en fortalecer la experiencia formativa.</p>
                        <Link href={accessHref} className="mt-9 inline-flex min-h-12 items-center gap-2 rounded-xl bg-brand-700 px-6 py-3 text-sm font-extrabold text-white shadow-[0_16px_35px_-20px_rgba(8,85,170,.8)] transition hover:bg-brand-600 focus-visible:outline-none focus-visible:ring-4 focus-visible:ring-brand-300/50">{accessLabel}<span className="material-symbols-outlined text-[19px]">login</span></Link>
                    </div>
                </section>
            </main>

            <footer className="border-t border-white/10 bg-ink-950 text-slate-400">
                <div className="mx-auto flex max-w-7xl flex-col gap-6 px-5 py-10 sm:px-8 md:flex-row md:items-center md:justify-between">
                    <div className="flex items-center gap-3"><img src="/static/img/logo_informatica.png" alt="" className="h-10 w-10 rounded-xl object-cover" /><div><strong className="text-sm text-white">Universidad Nacional de Trujillo</strong><p className="mt-1 text-xs">Ingeniería Informática</p></div></div>
                    <p className="text-xs">© {new Date().getFullYear()} Plataforma de Calidad Académica</p>
                </div>
            </footer>
        </div>
    )
}
