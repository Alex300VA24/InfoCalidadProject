import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

export default function ClassSessionsCoverage({ rows, period, periods }) {
    const applyFilter = (e) => {
        const periodId = e.target.value
        const url = periodId ? `/execution/coverage?academic_period_id=${periodId}` : '/execution/coverage'
        router.get(url, {}, {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Avance de Ejecución</h2>
                    <p className="text-slate-500">Progreso de horas ejecutadas versus planificadas por asignatura.</p>
                </div>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="max-w-xs">
                        <label className="block text-sm font-medium text-slate-700 mb-2">Periodo Académico</label>
                        <select onChange={applyFilter} value={period?.id ?? ''} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Seleccione periodo</option>
                            {periods.map((p) => (
                                <option key={p.id} value={p.id}>{p.name}</option>
                            ))}
                        </select>
                    </div>
                </div>
            </div>

            {period && rows.length > 0 ? (
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th className="px-6 py-4">Asignatura</th>
                                    <th className="px-6 py-4">Carrera</th>
                                    <th className="px-6 py-4">Sesiones</th>
                                    <th className="px-6 py-4">Horas Ejecutadas</th>
                                    <th className="px-6 py-4">Horas Planificadas</th>
                                    <th className="px-6 py-4">Progreso</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {rows.map((row) => (
                                    <tr key={row.subject.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4">
                                            <span className="font-semibold text-navy">{row.subject?.code}</span>
                                            <span className="block text-xs text-slate-400">{row.subject?.name}</span>
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{row.subject?.career?.name}</td>
                                        <td className="px-6 py-4 text-slate-500">{row.sessions_count}</td>
                                        <td className="px-6 py-4 text-slate-500">{Number(row.executed_hours).toFixed(2)}</td>
                                        <td className="px-6 py-4 text-slate-500">{Number(row.planned_hours).toFixed(2)}</td>
                                        <td className="px-6 py-4">
                                            <div className="flex items-center gap-3">
                                                <div className="w-32 h-2 rounded-full bg-slate-200 overflow-hidden">
                                                    <div className={`h-full rounded-full ${Number(row.percentage) >= 100 ? 'bg-emerald-500' : 'bg-accent'}`} style={{ width: `${Math.min(100, Number(row.percentage) || 0)}%` }} />
                                                </div>
                                                <span className="text-xs font-bold text-slate-600">{Number(row.percentage).toFixed(2)}%</span>
                                            </div>
                                        </td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            ) : (
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-12">
                    <div className="text-center text-slate-400">
                        <p className="text-sm font-bold text-slate-600">Selecciona un periodo para ver el avance de ejecución</p>
                    </div>
                </div>
            )}
        </div>
    )
}

ClassSessionsCoverage.layout = (page) => <AppLayout>{page}</AppLayout>
