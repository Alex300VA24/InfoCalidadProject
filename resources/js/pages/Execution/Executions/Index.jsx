import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'
import ModalLink from '../../../components/Modal/ModalLink'

export default function ExecutionsIndex({ executions, periods, subjects, statuses, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/execution/executions', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/execution/executions', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.subject_id || filters.status)

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Ejecución de Asignaturas</h2>
                    <p className="text-slate-500">Avance porcentual de las asignaturas por periodo académico.</p>
                </div>
                <ModalLink href="/execution/executions/create" title="Registrar avance de ejecución" context="Ejecución del Plan Curricular" icon="monitoring" returnPath="/execution/executions" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span className="material-symbols-outlined text-lg">add</span>
                    Registrar Ejecución
                </ModalLink>
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
                        <select value={filters.subject_id ?? ''} onChange={(e) => applyFilter('subject_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todas las asignaturas</option>
                            {subjects.map((subject) => (
                                <option key={subject.id} value={subject.id}>{subject.code} - {subject.name}</option>
                            ))}
                        </select>
                        <select value={filters.status ?? ''} onChange={(e) => applyFilter('status', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los estados</option>
                            {Object.entries(statuses).map(([key, label]) => (
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
                                <th className="px-6 py-4">Asignatura</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Docente</th>
                                <th className="px-6 py-4">Avance</th>
                                <th className="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {executions.data.length > 0 ? executions.data.map((execution) => (
                                <tr key={execution.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <span className="font-semibold text-navy">{execution.subject?.code}</span>
                                        <span className="block text-xs text-slate-400">{execution.subject?.name}</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{execution.academic_period?.name}</td>
                                    <td className="px-6 py-4 text-slate-500">{execution.teacher?.name ?? '—'}</td>
                                    <td className="px-6 py-4">
                                        <div className="flex items-center gap-3">
                                            <div className="w-32 h-2 rounded-full bg-slate-200 overflow-hidden">
                                                <div className={`h-full rounded-full ${Number(execution.progress_pct) >= 100 ? 'bg-emerald-500' : 'bg-accent'}`} style={{ width: `${Math.min(100, Number(execution.progress_pct) || 0)}%` }} />
                                            </div>
                                            <span className="text-xs font-bold text-slate-600">{execution.progress_pct}%</span>
                                        </div>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${execution.status === 'cerrado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : 'text-blue-700 bg-blue-100 border-blue-200'}`}>
                                            {execution.status_label}
                                        </span>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={5} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay ejecuciones registradas</p>
                                        <p className="text-xs mt-1">Registra la primera ejecución de asignatura</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={executions.links} />
            </div>
        </div>
    )
}

ExecutionsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
