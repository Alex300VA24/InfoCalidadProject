import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function TutoringIndex({ tutorings, periods, statuses, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/tutoring', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/tutoring', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.status)

    const statusColors = {
        atendida: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        pendiente: 'text-amber-700 bg-amber-100 border-amber-200',
        cancelada: 'text-red-700 bg-red-100 border-red-200',
    }

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Seguimiento al Desempeño</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Tutoría Académica</h2>
                    <p className="text-slate-500">Acompañamiento, nivelación de competencias y orientación estudiantil.</p>
                </div>
                <a href="/tutoring/create" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span className="material-symbols-outlined text-lg">support_agent</span>
                    Nueva Tutoría
                </a>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select value={filters.academic_period_id ?? ''} onChange={(e) => applyFilter('academic_period_id', e.target.value)} aria-label="Filtrar por periodo" className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los periodos</option>
                            {periods.map((period) => (
                                <option key={period.id} value={period.id}>{period.name}</option>
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
                                <th className="px-6 py-4">Estudiante</th>
                                <th className="px-6 py-4">Tipo</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Tutor</th>
                                <th className="px-6 py-4">Fecha</th>
                                <th className="px-6 py-4">Estado</th>
                                <th className="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {tutorings.data.length > 0 ? tutorings.data.map((tutoring) => (
                                <tr key={tutoring.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <span className="font-semibold">{tutoring.student?.user?.name ?? '—'}</span>
                                        <span className="block text-xs text-slate-400">{tutoring.student?.codigo}</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{tutoring.type_label}</td>
                                    <td className="px-6 py-4 text-slate-500">{tutoring.academic_period?.name}</td>
                                    <td className="px-6 py-4 text-slate-500">{tutoring.tutor?.name ?? '—'}</td>
                                    <td className="px-6 py-4 text-slate-500">{formatDate(tutoring.tutoring_date)}</td>
                                    <td className="px-6 py-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[tutoring.status] || 'text-slate-700 bg-slate-100 border-slate-200'}`}>
                                            {tutoring.status_label}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <a href={`/tutoring/${tutoring.id}`} title="Ver detalle" aria-label="Ver detalle de la tutoría" className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span className="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={7} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay tutorías registradas</p>
                                        <p className="text-xs mt-1">Registra la primera tutoría académica</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={tutorings.links} />
            </div>
        </div>
    )
}

TutoringIndex.layout = (page) => <AppLayout>{page}</AppLayout>
