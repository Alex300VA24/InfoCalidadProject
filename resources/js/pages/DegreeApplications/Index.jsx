import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function DegreeApplicationsIndex({ applications, types, statuses, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/degrees/applications', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/degrees/applications', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.type || filters.status)

    const statusColors = {
        aprobado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        otorgado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        observado: 'text-red-700 bg-red-100 border-red-200',
    }

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Grados y Títulos</h2>
                        <p className="text-slate-500">Expedientes de grado de bachiller y título profesional.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a href="/degrees/applications/create" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                            <span className="material-symbols-outlined text-lg">note_add</span>
                            Nuevo Expediente
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                    <div className="p-4">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <select value={filters.type ?? ''} onChange={(e) => applyFilter('type', e.target.value)} aria-label="Filtrar por tipo" className="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los tipos</option>
                                {Object.entries(types).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
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
                                    <th className="px-6 py-4">Expediente</th>
                                    <th className="px-6 py-4">Estudiante</th>
                                    <th className="px-6 py-4">Tipo</th>
                                    <th className="px-6 py-4">Solicitud</th>
                                    <th className="px-6 py-4">Resolución</th>
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {applications.data.length > 0 ? applications.data.map((application) => (
                                    <tr key={application.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4 font-bold text-navy">{application.code}</td>
                                        <td className="px-6 py-4">
                                            <span className="font-semibold">{application.student?.user?.name ?? application.student?.codigo}</span>
                                            <span className="block text-xs text-slate-400">{application.student?.codigo}</span>
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{application.type_label}</td>
                                        <td className="px-6 py-4 text-slate-500">{formatDate(application.application_date)}</td>
                                        <td className="px-6 py-4 text-slate-500">{application.resolution_number ?? '—'}</td>
                                        <td className="px-6 py-4">
                                            <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[application.status] || 'text-amber-700 bg-amber-100 border-amber-200'}`}>
                                                {application.status_label}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <a href={`/degrees/applications/${application.id}`} title="Ver detalle" aria-label="Ver detalle del expediente" className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                                <span className="material-symbols-outlined text-lg">visibility</span>
                                            </a>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan={7} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">No hay expedientes registrados</p>
                                            <p className="text-xs mt-1">Registra el primer expediente de grado</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={applications.links} />
                </div>
            </div>
        </div>
    )
}

DegreeApplicationsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
