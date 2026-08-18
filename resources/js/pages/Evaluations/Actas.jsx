import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'

export default function EvaluationsActas({ acts, periods, subjects, statuses, filters }) {
    const applyFilter = (key, value) => {
        const params = { ...filters }
        if (value === '' || value == null) {
            delete params[key]
        } else {
            params[key] = value
        }
        router.get('/evaluations/actas', params, { preserveState: true, preserveScroll: true, replace: true })
    }

    const clearFilters = () => {
        router.get('/evaluations/actas', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.subject_id || filters.status)

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Evaluación del Estudiante
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Actas Oficiales</h2>
                    <p className="text-slate-500">Generación y gestión de actas oficiales de notas por asignatura y periodo.</p>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                    <div className="p-4">
                        <form className="grid grid-cols-1 md:grid-cols-12 gap-4 items-end">
                            <div className="md:col-span-5">
                                <label className="block text-sm font-medium text-slate-700 mb-1">Asignatura</label>
                                <select value={filters.subject_id ?? ''} onChange={(e) => applyFilter('subject_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Seleccione asignatura</option>
                                    {subjects.map((subject) => (
                                        <option key={subject.id} value={subject.id}>{subject.code} - {subject.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div className="md:col-span-4">
                                <label className="block text-sm font-medium text-slate-700 mb-1">Periodo Académico</label>
                                <select value={filters.academic_period_id ?? ''} onChange={(e) => applyFilter('academic_period_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                    <option value="">Todos los periodos</option>
                                    {periods.map((period) => (
                                        <option key={period.id} value={period.id}>{period.name}</option>
                                    ))}
                                </select>
                            </div>
                            <div className="md:col-span-3">
                                <button type="button" onClick={() => router.post('/evaluations/actas/generar', { subject_id: filters.subject_id, academic_period_id: filters.academic_period_id }, { preserveScroll: true })} className="w-full inline-flex items-center justify-center gap-1.5 px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    <span className="material-symbols-outlined text-lg">description</span>
                                    Generar Acta
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                    <div className="p-4">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <select value={filters.academic_period_id ?? ''} onChange={(e) => applyFilter('academic_period_id', e.target.value)} aria-label="Filtrar por periodo" className="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los periodos</option>
                                {periods.map((period) => (
                                    <option key={period.id} value={period.id}>{period.name}</option>
                                ))}
                            </select>
                            <select value={filters.subject_id ?? ''} onChange={(e) => applyFilter('subject_id', e.target.value)} aria-label="Filtrar por asignatura" className="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las asignaturas</option>
                                {subjects.map((subject) => (
                                    <option key={subject.id} value={subject.id}>{subject.code} - {subject.name}</option>
                                ))}
                            </select>
                            <select value={filters.status ?? ''} onChange={(e) => applyFilter('status', e.target.value)} aria-label="Filtrar por estado" className="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estados</option>
                                {Object.entries(statuses).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
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
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4">Cerrada el</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {acts.data.length > 0 ? acts.data.map((act) => (
                                    <tr key={act.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4">
                                            <span className="font-semibold text-navy">{act.subject?.code}</span>
                                            <span className="block text-xs text-slate-400">{act.subject?.name}</span>
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{act.academic_period?.name}</td>
                                        <td className="px-6 py-4 text-slate-500">{act.teacher?.name ?? '—'}</td>
                                        <td className="px-6 py-4">
                                            <span className={`px-3 py-1 rounded-full text-xs font-bold border ${act.status === 'cerrado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : 'text-amber-700 bg-amber-100 border-amber-200'}`}>
                                                {act.status_label}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{act.closed_at ?? '—'}</td>
                                        <td className="px-6 py-4 text-right whitespace-nowrap">
                                            <a href={`/evaluations/actas/${act.id}/descargar`} className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy" title="Descargar acta">
                                                <span className="material-symbols-outlined text-lg">download</span>
                                            </a>
                                            {act.status !== 'cerrado' ? (
                                                <button type="button" onClick={() => router.post(`/evaluations/actas/${act.id}/cerrar`, {}, { preserveScroll: true })} className="inline-flex p-1.5 hover:bg-emerald-50 rounded text-emerald-700" title="Cerrar acta">
                                                    <span className="material-symbols-outlined text-lg">lock</span>
                                                </button>
                                            ) : null}
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan={6} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">No hay actas generadas</p>
                                            <p className="text-xs mt-1">Genera la primera acta oficial desde el formulario superior</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={acts.links} />
                </div>
            </div>
        </div>
    )
}

EvaluationsActas.layout = (page) => <AppLayout>{page}</AppLayout>
