import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'

export default function PerformanceIndex({ evaluations, periods, teachers, sources, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/execution/performance', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/execution/performance', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.teacher_id || filters.source)

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Desempeño Docente</h2>
                    <p className="text-slate-500">Evaluaciones del desempeño docente por fuente y periodo.</p>
                </div>
                <a href="/execution/performance/create" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span className="material-symbols-outlined text-lg">fact_check</span>
                    Registrar Evaluación
                </a>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <select value={filters.academic_period_id ?? ''} onChange={(e) => applyFilter('academic_period_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los periodos</option>
                            {periods.map((period) => (
                                <option key={period.id} value={period.id}>{period.name}</option>
                            ))}
                        </select>
                        <select value={filters.teacher_id ?? ''} onChange={(e) => applyFilter('teacher_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los docentes</option>
                            {teachers.map((teacher) => (
                                <option key={teacher.id} value={teacher.id}>{teacher.name}</option>
                            ))}
                        </select>
                        <select value={filters.source ?? ''} onChange={(e) => applyFilter('source', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todas las fuentes</option>
                            {Object.entries(sources).map(([key, label]) => (
                                <option key={key} value={key}>{label}</option>
                            ))}
                        </select>
                        <div className="flex items-center gap-2">
                            {hasFilters && (
                                <button type="button" onClick={clearFilters} className="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
                                    <span className="material-symbols-outlined text-lg">filter_alt_off</span>
                                    Limpiar
                                </button>
                            )}
                            <p className="text-xs text-slate-400">Los filtros se aplican automáticamente</p>
                        </div>
                    </div>
                </div>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                            <tr>
                                <th className="px-6 py-4">Docente</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Fuente</th>
                                <th className="px-6 py-4">Fecha</th>
                                <th className="px-6 py-4">Nota</th>
                                <th className="px-6 py-4">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {evaluations.data.length > 0 ? evaluations.data.map((evaluation) => (
                                <tr key={evaluation.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4 font-semibold text-navy">{evaluation.teacher?.name ?? '—'}</td>
                                    <td className="px-6 py-4 text-slate-500">{evaluation.academic_period?.name}</td>
                                    <td className="px-6 py-4 text-slate-500">{evaluation.source_label}</td>
                                    <td className="px-6 py-4 text-slate-500">{evaluation.evaluated_at}</td>
                                    <td className="px-6 py-4">
                                        <span className="font-bold text-navy">{Number(evaluation.score).toFixed(2)}</span>
                                        <span className="text-xs text-slate-400">/ 20</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500 max-w-xs truncate">{evaluation.observations ?? '—'}</td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={6} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay evaluaciones de desempeño</p>
                                        <p className="text-xs mt-1">Registra la primera evaluación</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={evaluations.links} />
            </div>
        </div>
    )
}

PerformanceIndex.layout = (page) => <AppLayout>{page}</AppLayout>
