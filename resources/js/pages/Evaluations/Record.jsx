import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function EvaluationsRecord({ rows, period, subject, periods, subjects }) {
    const submit = (e) => {
        e.preventDefault()
        const formData = new FormData(e.currentTarget)
        const params = new URLSearchParams(formData)
        router.get(`/evaluations/record?${params.toString()}`, {}, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                    <div className="p-4">
                        <form onSubmit={submit} className="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <select name="academic_period_id" defaultValue={period?.id ?? ''} className="w-full rounded-lg border-slate-200 text-sm">
                                    {periods.map((p) => (
                                        <option key={p.id} value={p.id}>{p.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <select name="subject_id" defaultValue={subject?.id ?? ''} className="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Seleccione asignatura</option>
                                    {subjects.map((s) => (
                                        <option key={s.id} value={s.id}>{s.code} - {s.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <button type="submit" className="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">Generar Acta</button>
                            </div>
                            {period && subject ? (
                                <div className="flex justify-end">
                                    <a href={`/evaluations/acta-pdf?academic_period_id=${period.id}&subject_id=${subject.id}`} target="_blank" rel="noreferrer" className="w-full px-4 py-2 bg-accent text-ink font-black rounded-lg text-sm text-center hover:brightness-95 transition-all">Descargar PDF</a>
                                </div>
                            ) : null}
                        </form>
                    </div>
                </div>

                {period && subject ? (
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                            <h3 className="text-base font-bold text-navy">{subject.code} - {subject.name}</h3>
                            <span className="text-xs text-slate-400">Periodo {period.name}</span>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                    <tr>
                                        <th className="px-6 py-4">Código</th>
                                        <th className="px-6 py-4">Estudiante</th>
                                        <th className="px-6 py-4">P1</th>
                                        <th className="px-6 py-4">P2</th>
                                        <th className="px-6 py-4">P3</th>
                                        <th className="px-6 py-4">Parcial</th>
                                        <th className="px-6 py-4">Final</th>
                                        <th className="px-6 py-4">Promedio</th>
                                        <th className="px-6 py-4">Condición</th>
                                    </tr>
                                </thead>
                                <tbody className="text-sm divide-y divide-slate-100">
                                    {rows.length > 0 ? rows.map((row) => {
                                        const finalValue = Number(row.final)
                                        const condition = finalValue >= 14 ? 'Aprobado' : finalValue >= 10 ? 'En Riesgo' : 'Desaprobado'
                                        const badge = finalValue >= 14 ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : finalValue >= 10 ? 'text-amber-700 bg-amber-100 border-amber-200' : 'text-red-700 bg-red-100 border-red-200'
                                        return (
                                            <tr key={row.student.id} className="hover:bg-slate-50 transition-colors">
                                                <td className="px-6 py-4 font-bold text-navy">{row.student.codigo}</td>
                                                <td className="px-6 py-4 font-semibold">{row.student.full_name}</td>
                                                <td className="px-6 py-4">{row.evaluations?.practica_1?.score ?? '—'}</td>
                                                <td className="px-6 py-4">{row.evaluations?.practica_2?.score ?? '—'}</td>
                                                <td className="px-6 py-4">{row.evaluations?.practica_3?.score ?? '—'}</td>
                                                <td className="px-6 py-4">{row.evaluations?.examen_parcial?.score ?? '—'}</td>
                                                <td className="px-6 py-4">{row.evaluations?.examen_final?.score ?? '—'}</td>
                                                <td className="px-6 py-4 font-bold text-navy">{row.final ?? '—'}</td>
                                                <td className="px-6 py-4">
                                                    {row.final !== null ? (
                                                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${badge}`}>{condition}</span>
                                                    ) : (
                                                        <span className="text-slate-400 text-xs">Sin notas</span>
                                                    )}
                                                </td>
                                            </tr>
                                        )
                                    }) : (
                                        <tr>
                                            <td colSpan={9} className="px-6 py-10 text-center text-slate-400">
                                                <p className="text-sm font-bold text-slate-600">No hay estudiantes matriculados en esta asignatura</p>
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                ) : (
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-10 text-center">
                        <p className="text-sm font-bold text-slate-600">Seleccione asignatura y periodo</p>
                        <p className="text-xs text-slate-400 mt-1">Para generar el acta de notas del periodo.</p>
                    </div>
                )}
            </div>
        </div>
    )
}

EvaluationsRecord.layout = (page) => <AppLayout>{page}</AppLayout>
