import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'

export default function ClassSessionsIndex({ sessions, periods, subjects, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/execution', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/execution', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.subject_id || filters.status)

    const statusColors = {
        realizada: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        planificada: 'text-blue-700 bg-blue-100 border-blue-200',
        reprogramada: 'text-amber-700 bg-amber-100 border-amber-200',
        cancelada: 'text-red-700 bg-red-100 border-red-200',
    }

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Sesiones de Clase</h2>
                    <p className="text-slate-500">Registro de sesiones ejecutadas por asignatura y periodo.</p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <a href="/execution/coverage" className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                        <span className="material-symbols-outlined text-lg">progress_activity</span>
                        Avance de Ejecución
                    </a>
                    <a href="/execution/create" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                        <span className="material-symbols-outlined text-lg">event_available</span>
                        Registrar Sesión
                    </a>
                </div>
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
                            <option value="realizada">Realizada</option>
                            <option value="planificada">Planificada</option>
                            <option value="reprogramada">Reprogramada</option>
                            <option value="cancelada">Cancelada</option>
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
                                <th className="px-6 py-4">Tema</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Docente</th>
                                <th className="px-6 py-4">Horas</th>
                                <th className="px-6 py-4">Fecha</th>
                                <th className="px-6 py-4">Estado</th>
                                <th className="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {sessions.data.length > 0 ? sessions.data.map((session) => (
                                <tr key={session.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <span className="font-semibold text-navy">{session.subject?.code}</span>
                                        <span className="block text-xs text-slate-400">{session.subject?.name}</span>
                                    </td>
                                    <td className="px-6 py-4 font-semibold max-w-xs truncate">{session.topic}</td>
                                    <td className="px-6 py-4 text-slate-500">{session.academic_period?.name}</td>
                                    <td className="px-6 py-4 text-slate-500">{session.teacher?.name ?? '—'}</td>
                                    <td className="px-6 py-4 text-slate-500">{session.hours}</td>
                                    <td className="px-6 py-4 text-slate-500">{session.session_date}</td>
                                    <td className="px-6 py-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[session.status] || 'text-slate-700 bg-slate-100 border-slate-200'}`}>
                                            {session.status_label}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <a href={`/execution/${session.id}`} title="Ver detalle" className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span className="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={8} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay sesiones registradas</p>
                                        <p className="text-xs mt-1">Registra la primera sesión de clase</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={sessions.links} />
            </div>
        </div>
    )
}

ClassSessionsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
