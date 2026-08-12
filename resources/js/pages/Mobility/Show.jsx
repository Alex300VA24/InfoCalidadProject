import AppLayout from '../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function MobilityShow({ mobilityApplication }) {
    const app = mobilityApplication

    const statusColors = {
        aprobada: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        finalizada: 'bg-emerald-100 text-emerald-700 border-emerald-200',
        en_curso: 'bg-blue-100 text-blue-700 border-blue-200',
        rechazada: 'bg-red-100 text-red-700 border-red-200',
        en_evaluacion: 'bg-amber-100 text-amber-700 border-amber-200',
    }

    const details = [
        { label: 'Tipo', value: app.type_label },
        { label: 'Institución de Destino', value: app.destination_institution ?? '—' },
        { label: 'Programa', value: app.program_name ?? '—' },
        { label: 'Beca', value: app.scholarship_name ?? '—' },
        { label: 'Periodo Académico', value: app.academic_period?.name ?? '—' },
        { label: 'Fecha de Solicitud', value: formatDate(app.application_date) },
        { label: 'Fecha de Inicio', value: formatDate(app.start_date) },
        { label: 'Fecha de Fin', value: formatDate(app.end_date) },
    ]

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Movilidad Académica y Becas</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Solicitud de Movilidad</h2>
                    <p className="text-slate-500">{app.student?.user?.name}, {app.student?.codigo}</p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <a href="/mobility" className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                        <span className="material-symbols-outlined text-lg">arrow_back</span>
                        Volver
                    </a>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div className="lg:col-span-2">
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b border-slate-100 flex items-center justify-between">
                            <h3 className="font-bold text-navy">Información de la Solicitud</h3>
                            <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[app.status] || 'bg-slate-100 text-slate-700 border-slate-200'}`}>
                                {app.status_label}
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
                        {app.notes && (
                            <div className="px-6 py-4 border-t border-slate-100">
                                <dt className="text-[11px] font-bold uppercase tracking-wider text-slate-400 mb-1">Observaciones</dt>
                                <dd className="text-sm text-slate-800 whitespace-pre-line">{app.notes}</dd>
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
                        <p className="text-sm font-semibold">{app.student?.user?.name ?? '—'}</p>
                        <p className="text-xs text-slate-400 mt-1">Código: {app.student?.codigo ?? '—'}</p>
                        <p className="text-xs text-slate-400">Estado: {app.student?.estado ?? '—'}</p>
                    </div>
                </div>
            </div>
        </div>
    )
}

MobilityShow.layout = (page) => <AppLayout>{page}</AppLayout>