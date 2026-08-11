import { usePage, router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'

function diffForHumans(value) {
    if (!value) return ''
    const d = new Date(value)
    if (Number.isNaN(d.getTime())) return String(value)
    const now = Date.now()
    const seconds = Math.max(1, Math.floor((now - d.getTime()) / 1000))
    if (seconds < 60) return `hace ${seconds}s`
    const minutes = Math.floor(seconds / 60)
    if (minutes < 60) return `hace ${minutes}min`
    const hours = Math.floor(minutes / 60)
    if (hours < 24) return `hace ${hours}h`
    const days = Math.floor(hours / 24)
    if (days < 30) return `hace ${days}d`
    const months = Math.floor(days / 30)
    if (months < 12) return `hace ${months}mes`
    return `hace ${Math.floor(months / 12)}año`
}

const STATUS_COLORS = {
    pending: {
        cardBorder: 'border-amber-300',
        cardHeader: 'bg-amber-500 text-white',
        badge: 'bg-amber-500/10 text-amber-700 border-amber-500/20',
    },
    in_review: {
        cardBorder: 'border-blue-300',
        cardHeader: 'bg-blue-500 text-white',
        badge: 'bg-blue-500/10 text-blue-700 border-blue-500/20',
    },
    completed: {
        cardBorder: 'border-emerald-300',
        cardHeader: 'bg-emerald-500 text-white',
        badge: 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
    },
    rejected: {
        cardBorder: 'border-red-300',
        cardHeader: 'bg-red-500 text-white',
        badge: 'bg-red-500/10 text-red-700 border-red-500/20',
    },
}

const STATUS_LABELS = {
    pending: 'PENDIENTE',
    in_review: 'EN REVISIÓN',
    completed: 'COMPLETADO',
    rejected: 'RECHAZADO',
}

const TYPE_LABELS = {
    bibliographic: 'BIBLIOGRÁFICO',
    hemerographic: 'HEMEROGRÁFICO',
    equipment: 'EQUIPAMIENTO',
}

const TYPE_COLORS = {
    bibliographic: 'text-sky-700 bg-sky-50 border-sky-500/20',
    hemerographic: 'text-purple-700 bg-purple-50 border-purple-500/20',
    equipment: 'text-orange-700 bg-orange-50 border-orange-500/20',
}

const TYPE_FILTER_OPTIONS = [
    ['', 'Todos los tipos'],
    ['bibliographic', 'Bibliográfico'],
    ['hemerographic', 'Hemerográfico'],
    ['equipment', 'Equipamiento'],
]

const STATUS_FILTER_OPTIONS = [
    ['', 'Todos los estados'],
    ['pending', 'Pendiente'],
    ['in_review', 'En revisión'],
    ['completed', 'Completado'],
    ['rejected', 'Rechazado'],
]

const STATUS_ICONS = {
    pending: 'pending_actions',
    in_review: 'rate_review',
    completed: 'task_alt',
    rejected: 'block',
}

export default function ResourcesIndex({ resourceRequests, periods, filters }) {
    const { can } = usePage().props

    const applyFilter = (key, value) => {
        const params = { ...filters }
        if (value === '' || value == null) {
            delete params[key]
        } else {
            params[key] = value
        }
        router.get('/resources', params, { preserveState: true, preserveScroll: true, replace: true })
    }

    const clearFilters = () => {
        router.get('/resources', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.status || filters.request_type || filters.academic_period_id)

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Solicitudes Académicas
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Recursos & Equipamiento</h2>
                        <p className="text-slate-500">Gestiona las solicitudes de materiales y equipamiento educativo.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a
                            href="/resources/create"
                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all"
                        >
                            <span className="material-symbols-outlined text-lg">add_circle</span>
                            Nueva Solicitud
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6 p-4">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <select
                            value={filters.academic_period_id ?? ''}
                            onChange={(e) => applyFilter('academic_period_id', e.target.value)}
                            aria-label="Filtrar por periodo"
                            className="w-full rounded-lg border-slate-200 text-sm"
                        >
                            <option value="">Todos los periodos</option>
                            {periods.map((p) => (
                                <option key={p.id} value={p.id}>{p.name}</option>
                            ))}
                        </select>
                        <select
                            value={filters.request_type ?? ''}
                            onChange={(e) => applyFilter('request_type', e.target.value)}
                            aria-label="Filtrar por tipo"
                            className="w-full rounded-lg border-slate-200 text-sm"
                        >
                            {TYPE_FILTER_OPTIONS.map(([value, label]) => (
                                <option key={value} value={value}>{label}</option>
                            ))}
                        </select>
                        <select
                            value={filters.status ?? ''}
                            onChange={(e) => applyFilter('status', e.target.value)}
                            aria-label="Filtrar por estado"
                            className="w-full rounded-lg border-slate-200 text-sm"
                        >
                            {STATUS_FILTER_OPTIONS.map(([value, label]) => (
                                <option key={value} value={value}>{label}</option>
                            ))}
                        </select>
                        <div className="flex items-center gap-2">
                            {hasFilters && (
                                <button
                                    type="button"
                                    onClick={clearFilters}
                                    className="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors"
                                    title="Quitar filtros"
                                >
                                    <span className="material-symbols-outlined text-lg">filter_alt_off</span>
                                    Limpiar
                                </button>
                            )}
                            <p className="text-xs text-slate-400">Filtros automáticos</p>
                        </div>
                    </div>
                </div>

                {resourceRequests.data.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        {resourceRequests.data.map((resource) => {
                            const palette = STATUS_COLORS[resource.status] || STATUS_COLORS.pending
                            const statusLabel = STATUS_LABELS[resource.status] || resource.status
                            const typeLabel = TYPE_LABELS[resource.request_type] || resource.request_type
                            const typePalette = TYPE_COLORS[resource.request_type] || ''
                            const icon = STATUS_ICONS[resource.status] || 'request_quote'
                            const docCount = resource.documents?.length ?? 0
                            const attCount = resource.attachments?.length ?? 0
                            const initial = (resource.applicant?.name ?? '?').charAt(0).toUpperCase()
                            return (
                                <div
                                    key={resource.id}
                                    className={`bg-white rounded-xl border-2 ${palette.cardBorder} overflow-hidden hover:shadow-xl transition-all flex flex-col h-full`}
                                >
                                    <div className={`h-24 p-4 flex flex-col justify-between ${palette.cardHeader}`}>
                                        <div className="flex items-center justify-between">
                                            <span className={`self-start text-[9px] font-black px-2 py-0.5 rounded border ${palette.badge}`}>
                                                {statusLabel}
                                            </span>
                                            <span className={`text-[9px] font-black px-2 py-0.5 rounded border ${typePalette}`}>
                                                {typeLabel}
                                            </span>
                                        </div>
                                        <p className="text-[10px] font-mono font-black opacity-80 tracking-wide">
                                            {resource.code}
                                        </p>
                                    </div>
                                    <div className="p-4 flex-1 flex flex-col">
                                        <h3 className="text-base font-bold text-navy line-clamp-2 min-h-[3rem]">
                                            {resource.title}
                                        </h3>
                                        <p className="text-xs text-slate-500 mt-2 line-clamp-3">
                                            {resource.description || 'Sin descripción'}
                                        </p>
                                        <div className="flex items-center gap-3 mt-4 mb-4">
                                            <div className="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-navy border border-slate-300">
                                                {initial}
                                            </div>
                                            <div>
                                                <p className="text-xs font-bold text-navy">
                                                    {resource.applicant?.name}
                                                </p>
                                                <p className="text-[9px] text-slate-400 uppercase font-bold">
                                                    {resource.academicPeriod?.name}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="flex gap-3 text-[11px] text-slate-500 mb-4">
                                            <span className="flex items-center gap-1">
                                                <span className="material-symbols-outlined text-sm">description</span>
                                                {docCount} documentos
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <span className="material-symbols-outlined text-sm">attachment</span>
                                                {attCount} anexos
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <span className="material-symbols-outlined text-sm">schedule</span>
                                                {diffForHumans(resource.created_at)}
                                            </span>
                                        </div>
                                        <div className="mt-auto pt-4 border-t border-slate-200/70 flex justify-between items-center">
                                            <span className="material-symbols-outlined text-slate-400 text-lg">
                                                {icon}
                                            </span>
                                            <a
                                                href={`/resources/${resource.id}`}
                                                className="inline-flex items-center gap-1 text-navy font-bold text-sm hover:underline underline-offset-4"
                                            >
                                                Revisar
                                                <span className="material-symbols-outlined text-lg">arrow_forward</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            )
                        })}
                    </div>
                ) : (
                    <div className="border-2 border-dashed border-slate-200 rounded-xl p-6 flex flex-col items-center justify-center text-center gap-4">
                        <div className="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-slate-400">
                            <span className="material-symbols-outlined text-3xl">folder_off</span>
                        </div>
                        <div>
                            <p className="text-sm font-bold text-slate-600">No hay solicitudes de recursos</p>
                            <p className="text-xs text-slate-400">Crea la primera solicitud</p>
                        </div>
                    </div>
                )}

                <div className="mt-6">
                    <Pagination links={resourceRequests.links} />
                </div>
            </div>
        </div>
    )
}

ResourcesIndex.layout = (page) => <AppLayout>{page}</AppLayout>
