import AppLayout from '../../layouts/AppLayout'

const scoreBadge = (score) => {
    const value = Number(score)
    if (Number.isNaN(value)) return 'text-slate-600 bg-slate-100 border-slate-200'
    if (value >= 14) return 'text-emerald-700 bg-emerald-100 border-emerald-200'
    if (value >= 10) return 'text-amber-700 bg-amber-100 border-amber-200'
    return 'text-red-700 bg-red-100 border-red-200'
}

export default function EvaluationsShow({ evaluation }) {
    return (
        <div className="page-enter">
            <div className="max-w-3xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 className="text-base font-bold text-navy">{evaluation.type_label}</h3>
                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${scoreBadge(evaluation.score)}`}>{evaluation.score}</span>
                    </div>
                    <dl className="divide-y divide-slate-100 text-sm">
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Estudiante</dt>
                            <dd className="font-semibold text-navy">{evaluation.student?.user?.name ?? 'Sin usuario'}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Código</dt>
                            <dd className="font-semibold">{evaluation.student?.codigo}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Asignatura</dt>
                            <dd className="font-semibold">{evaluation.subject?.code} - {evaluation.subject?.name}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Periodo</dt>
                            <dd className="font-semibold">{evaluation.academic_period?.name}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Fecha</dt>
                            <dd className="font-semibold">{evaluation.evaluation_date}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Registrado por</dt>
                            <dd className="font-semibold">{evaluation.registrar?.name ?? '—'}</dd>
                        </div>
                        {evaluation.observations ? (
                            <div className="flex flex-wrap gap-x-4 gap-y-1 px-6 py-3">
                                <dt className="text-slate-500">Observaciones</dt>
                                <dd className="font-semibold max-w-full sm:max-w-md text-left sm:text-right min-w-0">{evaluation.observations}</dd>
                            </div>
                        ) : null}
                    </dl>
                </div>

                <div className="flex justify-end">
                    <a href="/evaluations" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
                </div>
            </div>
        </div>
    )
}

EvaluationsShow.layout = (page) => <AppLayout>{page}</AppLayout>
