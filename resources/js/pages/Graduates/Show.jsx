import AppLayout from '../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

const formatCurrency = (value) => {
    if (!value && value !== 0) return '—'
    return 'S/ ' + Number(value).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

export default function GraduatesShow({ graduate }) {
    const statusColors = {
        empleado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        independiente: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        desempleado: 'text-red-700 bg-red-100 border-red-200',
    }

    const boolLabel = (value) => {
        if (value === null || value === undefined) return '—'
        return value ? 'Sí' : 'No'
    }

    return (
        <div className="page-enter">
            <div className="max-w-3xl mx-auto px-5 sm:px-8">
                <div className="flex justify-between items-end mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Detalle de Egresado</h2>
                        <p className="text-slate-500">{graduate.student?.user?.name ?? graduate.student?.codigo}</p>
                    </div>
                    <div className="flex gap-3">
                        <a href={`/graduates/${graduate.id}/surveys/create`} className="px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">+ Encuesta</a>
                        <a href={`/graduates/${graduate.id}/edit`} className="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">Editar</a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 className="text-base font-bold text-navy">Inserción Laboral</h3>
                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[graduate.work_status] || 'text-amber-700 bg-amber-100 border-amber-200'}`}>
                            {graduate.work_status_label}
                        </span>
                    </div>
                    <dl className="divide-y divide-slate-100 text-sm">
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Egresado</dt>
                            <dd className="font-semibold text-navy">{graduate.student?.user?.name ?? graduate.student?.codigo}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Código</dt>
                            <dd className="font-semibold">{graduate.student?.codigo}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Fecha de egreso</dt>
                            <dd className="font-semibold">{formatDate(graduate.graduation_date)}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Empleador</dt>
                            <dd className="font-semibold">{graduate.employer ?? '—'}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Cargo</dt>
                            <dd className="font-semibold">{graduate.job_position ?? '—'}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Vínculo laboral</dt>
                            <dd className="font-semibold">{graduate.employment_relationship ?? '—'}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Ingreso mensual</dt>
                            <dd className="font-semibold">{formatCurrency(graduate.monthly_income)}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Fecha de encuesta</dt>
                            <dd className="font-semibold">{formatDate(graduate.survey_date)}</dd>
                        </div>
                        {graduate.observations && (
                            <div className="px-6 py-3">
                                <dt className="text-slate-500 mb-1">Observaciones</dt>
                                <dd className="font-semibold">{graduate.observations}</dd>
                            </div>
                        )}
                    </dl>
                </div>

                {graduate.surveys.length > 0 && (
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                        <div className="px-6 py-4 border-b border-slate-100">
                            <h3 className="text-base font-bold text-navy">Encuestas de Seguimiento</h3>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-left text-sm">
                                <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                    <tr>
                                        <th className="px-6 py-3">Periodo</th>
                                        <th className="px-6 py-3">Fecha</th>
                                        <th className="px-6 py-3">Empleado</th>
                                        <th className="px-6 py-3">Relación con la carrera</th>
                                        <th className="px-6 py-3">Nivel de competencias</th>
                                        <th className="px-6 py-3">Ingreso</th>
                                    </tr>
                                </thead>
                                <tbody className="divide-y divide-slate-100">
                                    {graduate.surveys.map((survey) => (
                                        <tr key={survey.id} className="hover:bg-slate-50 transition-colors">
                                            <td className="px-6 py-3 text-slate-500">{survey.period}</td>
                                            <td className="px-6 py-3 text-slate-500">{formatDate(survey.survey_date)}</td>
                                            <td className="px-6 py-3 text-slate-500">{boolLabel(survey.employed)}</td>
                                            <td className="px-6 py-3 text-slate-500">{boolLabel(survey.job_related_to_career)}</td>
                                            <td className="px-6 py-3 font-semibold text-navy">
                                                {survey.competency_level_score !== null && survey.competency_level_score !== undefined
                                                    ? Number(survey.competency_level_score).toFixed(2) + ' / 20'
                                                    : '—'}
                                            </td>
                                            <td className="px-6 py-3 text-slate-500">{formatCurrency(survey.income)}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    </div>
                )}

                <div className="flex justify-end">
                    <a href="/graduates" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
                </div>
            </div>
        </div>
    )
}

GraduatesShow.layout = (page) => <AppLayout>{page}</AppLayout>
