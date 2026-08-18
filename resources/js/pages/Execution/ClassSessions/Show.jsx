import AppLayout from '../../../layouts/AppLayout'

export default function ClassSessionsShow({ classSession }) {
    const statusColors = {
        realizada: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        planificada: 'text-blue-700 bg-blue-100 border-blue-200',
        reprogramada: 'text-amber-700 bg-amber-100 border-amber-200',
        cancelada: 'text-red-700 bg-red-100 border-red-200',
    }

    return (
        <div className="page-enter">
            <div className="max-w-3xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 className="text-base font-bold text-navy">{classSession.topic}</h3>
                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[classSession.status] || 'text-slate-700 bg-slate-100 border-slate-200'}`}>
                            {classSession.status_label}
                        </span>
                    </div>
                    <dl className="divide-y divide-slate-100 text-sm">
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Asignatura</dt>
                            <dd className="font-semibold">{classSession.subject?.code} - {classSession.subject?.name}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Carrera</dt>
                            <dd className="font-semibold">{classSession.subject?.career?.name}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Periodo</dt>
                            <dd className="font-semibold">{classSession.academic_period?.name}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Docente</dt>
                            <dd className="font-semibold">{classSession.teacher?.name ?? '—'}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Fecha</dt>
                            <dd className="font-semibold">{classSession.session_date}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Horas</dt>
                            <dd className="font-semibold">{classSession.hours}</dd>
                        </div>
                        {classSession.notes && (
                            <div className="flex flex-wrap gap-x-4 gap-y-1 px-6 py-3">
                                <dt className="text-slate-500">Observaciones</dt>
                                <dd className="font-semibold max-w-full sm:max-w-md text-left sm:text-right min-w-0">{classSession.notes}</dd>
                            </div>
                        )}
                    </dl>
                </div>

                <div className="flex justify-end">
                    <a href="/execution" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
                </div>
            </div>
        </div>
    )
}

ClassSessionsShow.layout = (page) => <AppLayout>{page}</AppLayout>
