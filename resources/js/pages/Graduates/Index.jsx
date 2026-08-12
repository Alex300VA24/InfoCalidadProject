import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

const formatCurrency = (value) => {
    if (!value && value !== 0) return '—'
    return 'S/ ' + Number(value).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })
}

export default function GraduatesIndex({ graduates, workStatuses, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/graduates', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/graduates', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.work_status)

    const statusColors = {
        empleado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        independiente: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        desempleado: 'text-red-700 bg-red-100 border-red-200',
    }

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Seguimiento de Egresados</h2>
                        <p className="text-slate-500">Encuestas de inserción laboral y seguimiento de egresados.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a href="/graduates/stats" className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                            <span className="material-symbols-outlined text-lg">monitoring</span>
                            Estadísticas
                        </a>
                        <a href="/graduates/create" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                            <span className="material-symbols-outlined text-lg">person_add</span>
                            Registrar Egresado
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                    <div className="p-4">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <select value={filters.work_status ?? ''} onChange={(e) => applyFilter('work_status', e.target.value)} aria-label="Filtrar por situación laboral" className="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todas las situaciones</option>
                                {Object.entries(workStatuses).map(([value, label]) => (
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
                                    <th className="px-6 py-4">Egresado</th>
                                    <th className="px-6 py-4">Situación Laboral</th>
                                    <th className="px-6 py-4">Empleador</th>
                                    <th className="px-6 py-4">Cargo</th>
                                    <th className="px-6 py-4">Ingreso Mensual</th>
                                    <th className="px-6 py-4">Encuesta</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {graduates.data.length > 0 ? graduates.data.map((graduate) => (
                                    <tr key={graduate.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4">
                                            <span className="font-semibold">{graduate.student?.user?.name ?? graduate.student?.codigo}</span>
                                            <span className="block text-xs text-slate-400">{graduate.student?.codigo}</span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[graduate.work_status] || 'text-amber-700 bg-amber-100 border-amber-200'}`}>
                                                {graduate.work_status_label}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{graduate.employer ?? '—'}</td>
                                        <td className="px-6 py-4 text-slate-500">{graduate.job_position ?? '—'}</td>
                                        <td className="px-6 py-4 text-slate-500">{formatCurrency(graduate.monthly_income)}</td>
                                        <td className="px-6 py-4 text-slate-500">{formatDate(graduate.survey_date)}</td>
                                        <td className="px-6 py-4 text-right">
                                            <a href={`/graduates/${graduate.id}`} title="Ver detalle" aria-label="Ver detalle del egresado" className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                                <span className="material-symbols-outlined text-lg">visibility</span>
                                            </a>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan={7} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">No hay egresados registrados</p>
                                            <p className="text-xs mt-1">Registra el primer egresado</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={graduates.links} />
                </div>
            </div>
        </div>
    )
}

GraduatesIndex.layout = (page) => <AppLayout>{page}</AppLayout>
