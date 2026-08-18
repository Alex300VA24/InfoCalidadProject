import AppLayout from '../../../layouts/AppLayout'

const STATUS_STYLES = {
    completed: 'text-emerald-700 bg-emerald-100 border-emerald-200',
    draft: 'text-amber-700 bg-amber-100 border-amber-200',
}

const STATUS_LABELS = {
    completed: 'Completado',
    draft: 'Borrador',
}

export default function CurriculumReviewsShow({ review }) {
    const averageScore =
        review.evaluations.length > 0
            ? (
                  review.evaluations.reduce((sum, e) => sum + (Number(e.score) ?? 0), 0) / review.evaluations.length
              ).toFixed(2)
            : null

    return (
        <div className="page-enter">
            <div className="max-w-5xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Detalle de Revisión
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">
                        {review.checklistTemplate?.code} — Revisión #{review.id}
                    </h2>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="p-6">
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6">
                            <div>
                                <span className="text-xs uppercase font-bold text-slate-400 block">Carrera</span>
                                <p className="font-semibold text-navy">{review.career?.name}</p>
                            </div>
                            <div>
                                <span className="text-xs uppercase font-bold text-slate-400 block">Periodo</span>
                                <p className="font-semibold text-navy">{review.academicPeriod?.name}</p>
                            </div>
                            <div>
                                <span className="text-xs uppercase font-bold text-slate-400 block">Plantilla</span>
                                <p className="font-semibold text-navy">{review.checklistTemplate?.code}</p>
                            </div>
                            <div>
                                <span className="text-xs uppercase font-bold text-slate-400 block">Acción Curricular</span>
                                <p className="font-semibold text-navy">{review.actionType?.name ?? '—'}</p>
                            </div>
                            <div>
                                <span className="text-xs uppercase font-bold text-slate-400 block">Revisor</span>
                                <p className="font-semibold text-navy">{review.reviewer?.name}</p>
                            </div>
                            <div>
                                <span className="text-xs uppercase font-bold text-slate-400 block">Estado</span>
                                <span
                                    className={`px-3 py-1 rounded-full text-xs font-bold border inline-block mt-1 ${
                                        STATUS_STYLES[review.status] ?? 'text-slate-600 bg-slate-100 border-slate-200'
                                    }`}
                                >
                                    {STATUS_LABELS[review.status] ?? review.status}
                                </span>
                            </div>
                        </div>

                        {review.evaluations.length > 0 && (
                            <>
                                <div className="flex justify-between items-baseline mb-3">
                                    <h3 className="font-semibold text-navy">Resultados de Evaluación</h3>
                                    {averageScore !== null && (
                                        <span className="text-sm text-slate-500 font-medium">
                                            Puntaje promedio:{' '}
                                            <span className="font-bold text-navy">{averageScore}/5</span>
                                        </span>
                                    )}
                                </div>
                                <div className="overflow-x-auto border border-slate-200 rounded-lg">
                                    <table className="w-full text-left">
                                        <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500">
                                            <tr>
                                                <th className="px-4 py-3">Criterio</th>
                                                <th className="px-4 py-3 w-24">Puntaje</th>
                                                <th className="px-4 py-3">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody className="text-sm divide-y divide-slate-100">
                                            {review.evaluations.map((evaluation) => (
                                                <tr key={evaluation.id}>
                                                    <td className="px-4 py-3">
                                                        <span className="font-bold text-slate-700">
                                                            {evaluation.criterion?.code}
                                                        </span>{' '}
                                                        <span className="text-slate-600">
                                                            {evaluation.criterion?.description}
                                                        </span>
                                                    </td>
                                                    <td className="px-4 py-3 font-bold text-navy">
                                                        {evaluation.score}
                                                        <span className="text-slate-400 text-xs font-normal">/5</span>
                                                    </td>
                                                    <td className="px-4 py-3 text-slate-600">
                                                        {evaluation.observations ?? '—'}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </>
                        )}

                        {review.observations && (
                            <div className="mt-6">
                                <span className="text-xs uppercase font-bold text-slate-400 block">
                                    Observaciones Generales
                                </span>
                                <p className="mt-1 text-slate-700 whitespace-pre-wrap">{review.observations}</p>
                            </div>
                        )}

                        {review.status === 'completed' && review.technicalReport ? (
                            <div className="mt-6 pt-4 border-t border-slate-200">
                                <a
                                    href={`/curriculum/reports/${review.technicalReport.id}`}
                                    className="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-500"
                                >
                                    Ver Informe Técnico
                                </a>
                            </div>
                        ) : review.status === 'completed' ? (
                            <div className="mt-6 pt-4 border-t border-slate-200">
                                <a
                                    href={`/curriculum/reviews/${review.id}/reports/create`}
                                    className="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md text-sm hover:bg-indigo-500"
                                >
                                    Generar Informe Técnico
                                </a>
                            </div>
                        ) : null}

                        <div className="mt-6">
                            <a href="/curriculum/reviews" className="text-sm text-slate-500 hover:text-navy">
                                ← Volver
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

CurriculumReviewsShow.layout = (page) => <AppLayout>{page}</AppLayout>
