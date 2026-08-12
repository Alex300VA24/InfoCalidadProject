import AppLayout from '../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function ResearchProjectsShow({ researchProject }) {
    const project = researchProject

    const statusColors = {
        aprobado: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        finalizado: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        en_desarrollo: 'bg-blue-100 text-blue-700 border-blue-200',
        rechazado: 'bg-red-100 text-red-700 border-red-200',
        borrador: 'bg-amber-100 text-amber-700 border-amber-200',
    }

    const details = [
        { label: 'Área de Investigación', value: project.area ?? '—' },
        { label: 'Asesor', value: project.advisor?.name ?? '—' },
        { label: 'Periodo Académico', value: project.academic_period?.name ?? '—' },
        { label: 'Fecha de Inicio', value: formatDate(project.start_date) },
        { label: 'Fecha de Fin', value: formatDate(project.end_date) },
        { label: 'Nota', value: project.score != null ? project.score : '—' },
    ]

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Investigación</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Proyecto de Investigación</h2>
                    <p className="text-slate-500">{project.title}</p>
                </div>
                <div className="flex flex-wrap gap-2">
                    {project.document_path && (
                        <a href={`/research/${project.id}/download`} className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                            <span className="material-symbols-outlined text-lg">download</span>
                            Descargar Documento
                        </a>
                    )}
                    <a href="/research" className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                        <span className="material-symbols-outlined text-lg">arrow_back</span>
                        Volver
                    </a>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div className="lg:col-span-2">
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 className="font-bold text-navy">Información del Proyecto</h3>
                            <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[project.status] || 'bg-slate-100 text-slate-700 border-slate-200'}`}>
                                {project.status_label}
                            </span>
                        </div>
                        <div className="p-6 grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                            {details.map((detail) => (
                                <div key={detail.label}>
                                    <dt className="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">{detail.label}</dt>
                                    <dd className="text-sm text-slate-800">{detail.value}</dd>
                                </div>
                            ))}
                        </div>
                        {project.description && (
                            <div className="px-6 py-4 border-t border-slate-100">
                                <dt className="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Descripción</dt>
                                <dd className="text-sm text-slate-800 whitespace-pre-line">{project.description}</dd>
                            </div>
                        )}
                    </div>
                </div>

                <div className="space-y-6">
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                        <h3 className="font-bold text-navy mb-4 flex items-center gap-2">
                            <span className="material-symbols-outlined text-lg text-accent">person</span>
                            Estudiante
                        </h3>
                        <p className="text-sm font-semibold">{project.student?.user?.name ?? '—'}</p>
                        <p className="text-xs text-slate-400 mt-1">Código: {project.student?.codigo ?? '—'}</p>
                        <p className="text-xs text-slate-400">Estado: {project.student?.estado ?? '—'}</p>
                    </div>
                </div>
            </div>
        </div>
    )
}

ResearchProjectsShow.layout = (page) => <AppLayout>{page}</AppLayout>