import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function ResearchProjectsIndex({ projects, periods, statuses, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/research', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/research', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.status)

    const statusColors = {
        aprobado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        finalizado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        en_desarrollo: 'text-blue-700 bg-blue-100 border-blue-200',
        rechazado: 'text-red-700 bg-red-100 border-red-200',
        borrador: 'text-amber-700 bg-amber-100 border-amber-200',
    }

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Investigación</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Proyectos de Investigación</h2>
                    <p className="text-slate-500">Seguimiento de proyectos, líneas de investigación y producción académica.</p>
                </div>
                <a href="/research/create" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span className="material-symbols-outlined text-lg">science</span>
                    Nuevo Proyecto
                </a>
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
                                <th className="px-6 py-4">Proyecto</th>
                                <th className="px-6 py-4">Estudiante</th>
                                <th className="px-6 py-4">Área</th>
                                <th className="px-6 py-4">Asesor</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Estado</th>
                                <th className="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {projects.data.length > 0 ? projects.data.map((project) => (
                                <tr key={project.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <span className="font-semibold">{project.title}</span>
                                        <span className="block text-xs text-slate-400">Inicio: {formatDate(project.start_date)}</span>
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className="text-slate-600">{project.student?.user?.name ?? '—'}</span>
                                        <span className="block text-xs text-slate-400">{project.student?.codigo}</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{project.area ?? '—'}</td>
                                    <td className="px-6 py-4 text-slate-500">{project.advisor?.name ?? '—'}</td>
                                    <td className="px-6 py-4 text-slate-500">{project.academic_period?.name ?? '—'}</td>
                                    <td className="px-6 py-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[project.status] || 'text-slate-700 bg-slate-100 border-slate-200'}`}>
                                            {project.status_label}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right space-x-1">
                                        <a href={`/research/${project.id}/download`} title="Descargar documento" aria-label="Descargar documento del proyecto" className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span className="material-symbols-outlined text-lg">download</span>
                                        </a>
                                        <a href={`/research/${project.id}`} title="Ver detalle" aria-label="Ver detalle del proyecto" className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                            <span className="material-symbols-outlined text-lg">visibility</span>
                                        </a>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={7} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay proyectos registrados</p>
                                        <p className="text-xs mt-1">Registra el primer proyecto de investigación</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={projects.links} />
            </div>
        </div>
    )
}

ResearchProjectsIndex.layout = (page) => <AppLayout>{page}</AppLayout>