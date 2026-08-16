import { useState } from 'react'
import { usePage, router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'
import ConfirmModal from '../../components/Modal/ConfirmModal'
import NativeModal, { prefetchModalPage } from '../../components/Modal/NativeModal'

function formatKb(bytes) {
    const n = Number(bytes) ?? 0
    return n > 0 ? `${(n / 1024).toFixed(1)} KB` : '—'
}

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

const STATUS_FILTER_OPTIONS = [
    ['', 'Todos los estados'],
    ['yes', 'Visados'],
    ['no', 'No visados'],
]

export default function SyllabiIndex({ syllabi = { data: [], links: [] }, periods = [], teachers = [], careers = [], filters = {} }) {
    const { can } = usePage().props
    const [modal, setModal] = useState(null)
    const [visaTarget, setVisaTarget] = useState(null)
    const [visaProcessing, setVisaProcessing] = useState(false)

    const closeModal = () => setModal(null)

    const applyFilter = (key, value) => {
        const params = { ...filters }
        if (value === '' || value == null) {
            delete params[key]
        } else {
            params[key] = value
        }
        router.get('/syllabi', params, { preserveState: true, preserveScroll: true, replace: true })
    }

    const clearFilters = () => {
        router.get('/syllabi', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const handleVisa = () => {
        if (!visaTarget) return
        setVisaProcessing(true)
        router.post(`/syllabi/${visaTarget.id}/visa`, {}, {
            preserveScroll: true,
            onSuccess: () => setVisaTarget(null),
            onFinish: () => setVisaProcessing(false),
        })
    }

    const hasFilters = Boolean(
        filters.career_id || filters.academic_period_id || filters.teacher_id || filters.is_visado,
    )

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Repositorio Institucional
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Repositorio de Sílabos</h2>
                        <p className="text-slate-500">Gestiona y valida el contenido académico de las carreras.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <button
                            type="button"
                            onClick={() => setModal({ href: '/syllabi/create', title: 'Subir nuevo sílabo', size: 'wide' })}
                            onMouseEnter={() => prefetchModalPage('/syllabi/create')}
                            onFocus={() => prefetchModalPage('/syllabi/create')}
                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all"
                        >
                            <span className="material-symbols-outlined text-lg">upload_file</span>
                            Subir Sílabo
                        </button>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                    <div className="p-4">
                        <div className="grid grid-cols-1 md:grid-cols-5 gap-4">
                            <select
                                value={filters.career_id ?? ''}
                                onChange={(e) => applyFilter('career_id', e.target.value)}
                                aria-label="Filtrar por carrera"
                                className="w-full rounded-lg border-slate-200 text-sm"
                            >
                                <option value="">Todas las carreras</option>
                                {careers.map((career) => (
                                    <option key={career.id} value={career.id}>
                                        {career.name}
                                    </option>
                                ))}
                            </select>
                            <select
                                value={filters.academic_period_id ?? ''}
                                onChange={(e) => applyFilter('academic_period_id', e.target.value)}
                                aria-label="Filtrar por periodo"
                                className="w-full rounded-lg border-slate-200 text-sm"
                            >
                                <option value="">Todos los periodos</option>
                                {periods.map((period) => (
                                    <option key={period.id} value={period.id}>
                                        {period.name}
                                    </option>
                                ))}
                            </select>
                            <select
                                value={filters.teacher_id ?? ''}
                                onChange={(e) => applyFilter('teacher_id', e.target.value)}
                                aria-label="Filtrar por docente"
                                className="w-full rounded-lg border-slate-200 text-sm"
                            >
                                <option value="">Todos los docentes</option>
                                {teachers.map((t) => (
                                    <option key={t.id} value={t.id}>
                                        {t.name}
                                    </option>
                                ))}
                            </select>
                            <select
                                value={filters.is_visado ?? ''}
                                onChange={(e) => applyFilter('is_visado', e.target.value)}
                                aria-label="Filtrar por estado"
                                className="w-full rounded-lg border-slate-200 text-sm"
                            >
                                {STATUS_FILTER_OPTIONS.map(([value, label]) => (
                                    <option key={value} value={value}>
                                        {label}
                                    </option>
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
                                <p className="text-xs text-slate-400">Los filtros se aplican automáticamente</p>
                            </div>
                        </div>
                    </div>
                </div>

                {syllabi.data.length > 0 ? (
                    <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                        {syllabi.data.map((syllabus) => {
                            const isVisado = Boolean(syllabus.is_visado)
                            const initial = (syllabus.teacher?.name ?? '?').charAt(0).toUpperCase()
                            return (
                                <div
                                    key={syllabus.id}
                                    className="bg-white rounded-xl border border-slate-200 overflow-hidden hover:shadow-lg transition-all group cursor-pointer flex flex-col h-full"
                                >
                                    <div
                                        className={`h-32 p-4 flex flex-col justify-between ${
                                            isVisado ? 'bg-navy text-white' : 'bg-accent/10 text-navy'
                                        }`}
                                    >
                                        <span
                                            className={`self-start text-[9px] font-black px-2 py-0.5 rounded border ${
                                                isVisado
                                                    ? 'bg-emerald-500/10 border-emerald-500/20 text-emerald-300'
                                                    : 'bg-amber-500/10 border-amber-500/20 text-amber-600'
                                            }`}
                                        >
                                            VISA: {isVisado ? 'APROBADO' : 'PENDIENTE'}
                                        </span>
                                        <div>
                                            <p className="text-[9px] font-bold opacity-70">
                                                {syllabus.subject?.code}
                                            </p>
                                            <h3 className="text-lg font-bold line-clamp-1">
                                                {syllabus.subject?.name}
                                            </h3>
                                        </div>
                                    </div>
                                    <div className="p-4 flex-1 flex flex-col">
                                        <div className="flex items-center gap-3 mb-4">
                                            <div className="w-8 h-8 rounded-full bg-slate-200 flex items-center justify-center text-xs font-bold text-navy border border-slate-300">
                                                {initial}
                                            </div>
                                            <div>
                                                <p className="text-xs font-bold text-navy">
                                                    {syllabus.teacher?.name}
                                                </p>
                                                <p className="text-[9px] text-slate-400 uppercase font-bold">
                                                    {syllabus.career?.code}
                                                </p>
                                            </div>
                                        </div>
                                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs text-slate-500 mb-4">
                                            <span className="flex items-center gap-1">
                                                <span className="material-symbols-outlined text-sm">event</span>
                                                {syllabus.academicPeriod?.name}
                                            </span>
                                            <span className="flex items-center gap-1">
                                                <span className="material-symbols-outlined text-sm">description</span>
                                                {formatKb(syllabus.file_size)}
                                            </span>
                                        </div>
                                        <div className="mt-auto pt-4 border-t border-slate-200/70 flex justify-between items-center">
                                            <span className="text-[10px] text-slate-400 italic">
                                                {diffForHumans(syllabus.created_at)}
                                            </span>
                                            <div className="flex gap-1">
                                                <button
                                                    type="button"
                                                    onClick={() => setModal({ href: `/syllabi/${syllabus.id}`, title: `Sílabo ${syllabus.subject?.code ?? `#${syllabus.id}`}`, size: 'wide' })}
                                                    onMouseEnter={() => prefetchModalPage(`/syllabi/${syllabus.id}`)}
                                                    onFocus={() => prefetchModalPage(`/syllabi/${syllabus.id}`)}
                                                    title="Ver detalle"
                                                    aria-label={`Ver detalle del sílabo ${syllabus.id}`}
                                                    className="text-navy hover:bg-navy/5 p-1 rounded transition-colors"
                                                >
                                                    <span className="material-symbols-outlined text-lg">visibility</span>
                                                </button>
                                                <a
                                                    href={`/syllabi/${syllabus.id}/download`}
                                                    title="Descargar"
                                                    aria-label={`Descargar sílabo ${syllabus.id}`}
                                                    className="text-navy hover:bg-navy/5 p-1 rounded transition-colors"
                                                >
                                                    <span className="material-symbols-outlined text-lg">download</span>
                                                </a>
                                                {!isVisado && can.syllabi && (
                                                    <div className="inline">
                                                        <button
                                                            type="button"
                                                            onClick={() => setVisaTarget(syllabus)}
                                                            title="Marcar como visado"
                                                            aria-label={`Marcar sílabo ${syllabus.id} como visado`}
                                                            className="text-emerald-600 hover:bg-emerald-50 p-1 rounded transition-colors"
                                                        >
                                                            <span className="material-symbols-outlined text-lg">verified</span>
                                                        </button>
                                                    </div>
                                                )}
                                            </div>
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
                            <p className="text-sm font-bold text-slate-600">No hay sílabos</p>
                            <p className="text-xs text-slate-400">Sube el primer sílabo al repositorio</p>
                        </div>
                    </div>
                )}

                <div className="mt-6">
                    <Pagination links={syllabi.links} />
                </div>
            </div>
            <NativeModal
                open={Boolean(modal)}
                href={modal?.href ?? ''}
                title={modal?.title ?? ''}
                size={modal?.size}
                onClose={closeModal}
                exitPaths={['/syllabi']}
            />
            <ConfirmModal
                open={Boolean(visaTarget)}
                title="Confirmar visado"
                message={`Se registrará como visado el sílabo de ${visaTarget?.subject?.name ?? 'la asignatura seleccionada'}. Esta acción es irreversible.`}
                confirmLabel="Visar sílabo"
                tone="success"
                processing={visaProcessing}
                onConfirm={handleVisa}
                onCancel={() => setVisaTarget(null)}
            />
        </div>
    )
}

SyllabiIndex.layout = (page) => <AppLayout>{page}</AppLayout>
