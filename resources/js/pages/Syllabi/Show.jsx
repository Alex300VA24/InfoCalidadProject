import { usePage, router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

function formatDateTime(value) {
    if (!value) return ''
    const d = new Date(value)
    if (Number.isNaN(d.getTime())) return String(value)
    return d.toLocaleString('es-PE', {
        dateStyle: 'long',
        timeStyle: 'short',
    })
}

function formatKb(bytes) {
    const n = Number(bytes) ?? 0
    if (n < 1024) return `${n} B`
    const kb = n / 1024
    if (kb < 1024) return `${kb.toFixed(1)} KB`
    return `${(kb / 1024).toFixed(1)} MB`
}

export default function SyllabiShow({ syllabus }) {
    const { can } = usePage().props
    const isVisado = Boolean(syllabus.is_visado)

    const handleVisa = (e) => {
        e.preventDefault()
        if (!isVisado && !confirm('¿Confirmar visado del sílabo? Esta acción es irreversible.')) return
        router.post(`/syllabi/${syllabus.id}/visa`, {}, { preserveScroll: true })
    }

    return (
        <div className="page-enter">
            <div className="max-w-6xl mx-auto px-5 sm:px-8">
                <div className="flex items-center gap-3 mb-6">
                    <a href="/syllabi" className="text-slate-500 hover:text-navy" aria-label="Volver a sílabos">
                        <span className="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <div className="flex-1 min-w-0">
                        <span className={`text-[10px] font-black px-2 py-0.5 rounded-sm uppercase tracking-widest ${
                            isVisado ? 'bg-emerald-500/10 text-emerald-700' : 'bg-amber-500/10 text-amber-700'
                        }`}>
                            VISA: {isVisado ? 'APROBADO' : 'PENDIENTE'}
                        </span>
                        <div className="flex items-start justify-between gap-3 mt-2">
                            <div className="min-w-0">
                                <p className="text-xs font-bold text-slate-400">
                                    {syllabus.subject?.code} — {syllabus.career?.code}
                                </p>
                                <h2 className="text-3xl font-bold text-navy truncate">
                                    {syllabus.subject?.name}
                                </h2>
                                <p className="text-slate-500 text-sm">Docente responsable: {syllabus.teacher?.name}</p>
                            </div>
                            <div className="flex flex-wrap gap-2">
                                <a
                                    href={`/syllabi/${syllabus.id}/download`}
                                    data-turbo="false"
                                    className="inline-flex items-center gap-1.5 px-4 py-2 bg-navy text-white font-black rounded shadow-md text-sm hover:bg-navy/90 transition-colors"
                                >
                                    <span className="material-symbols-outlined text-lg">download</span>
                                    Descargar PDF
                                </a>
                                {!isVisado && can.syllabi && (
                                    <form onSubmit={handleVisa} className="inline-flex">
                                        <button
                                            type="submit"
                                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-emerald-600 text-white font-black rounded shadow-md text-sm hover:bg-emerald-700 transition-colors"
                                        >
                                            <span className="material-symbols-outlined text-lg">verified_user</span>
                                            Visar Sílabo
                                        </button>
                                    </form>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div className="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                        <h3 className="text-lg font-bold text-ink border-b border-slate-200 pb-2 mb-4">
                            <span className="flex items-center gap-2">
                                <span className="material-symbols-outlined text-navy">info</span>
                                Datos del Sílabo
                            </span>
                        </h3>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Carrera</p>
                                <p className="text-sm font-bold text-navy mt-0.5">{syllabus.career?.name}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Periodo Académico</p>
                                <p className="text-sm font-bold text-navy mt-0.5">{syllabus.academicPeriod?.name}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Versión</p>
                                <p className="text-sm font-bold text-navy mt-0.5">{syllabus.version}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Tamaño</p>
                                <p className="text-sm font-bold text-navy mt-0.5">{formatKb(syllabus.file_size)}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Tipo MIME</p>
                                <p className="text-sm font-bold text-navy mt-0.5 break-all">{syllabus.mime_type}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Fecha de subida</p>
                                <p className="text-sm font-bold text-navy mt-0.5">{formatDateTime(syllabus.created_at)}</p>
                            </div>
                            <div className="sm:col-span-2">
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Archivo original</p>
                                <p className="text-sm font-bold text-navy mt-0.5 break-all">{syllabus.filename}</p>
                            </div>
                        </div>
                    </div>

                    <div className={`rounded-xl shadow-sm p-6 border ${
                        isVisado ? 'bg-emerald-50 border-emerald-200' : 'bg-amber-50 border-amber-200'
                    }`}>
                        <div className="flex flex-col items-center text-center gap-3">
                            <div className={`w-16 h-16 rounded-full flex items-center justify-center ${
                                isVisado ? 'bg-emerald-500 text-white' : 'bg-amber-500 text-white'
                            }`}>
                                <span className="material-symbols-outlined text-3xl">
                                    {isVisado ? 'verified' : 'pending_actions'}
                                </span>
                            </div>
                            <div>
                                <p className="text-lg font-bold text-navy">
                                    {isVisado ? 'Sílabo Visado' : 'Pendiente de Visación'}
                                </p>
                                {isVisado ? (
                                    <p className="text-sm text-slate-500">Visado el {formatDateTime(syllabus.visado_at)}</p>
                                ) : (
                                    <p className="text-sm text-slate-500">El sílabo requiere la validación correspondiente</p>
                                )}
                            </div>
                        </div>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                    <h3 className="text-lg font-bold text-ink border-b border-slate-200 pb-2 mb-4">
                        <span className="flex items-center gap-2">
                            <span className="material-symbols-outlined text-navy">history</span>
                            Historial de Visaciones
                        </span>
                    </h3>
                    {syllabus.visas?.length > 0 ? (
                        <div className="overflow-x-auto">
                            <table className="w-full text-sm">
                                <thead>
                                    <tr className="border-b border-slate-200">
                                        <th className="text-left py-3 px-4 font-bold text-slate-500 text-[10px] uppercase tracking-wider">Usuario</th>
                                        <th className="text-left py-3 px-4 font-bold text-slate-500 text-[10px] uppercase tracking-wider">Rol</th>
                                        <th className="text-left py-3 px-4 font-bold text-slate-500 text-[10px] uppercase tracking-wider">Comentario</th>
                                        <th className="text-left py-3 px-4 font-bold text-slate-500 text-[10px] uppercase tracking-wider">Fecha</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    {syllabus.visas.map((visa) => (
                                        <tr key={visa.id} className="border-b border-slate-100 hover:bg-slate-50">
                                            <td className="py-3 px-4 text-navy font-bold">{visa.visor?.name}</td>
                                            <td className="py-3 px-4 text-slate-500 capitalize">{visa.visor?.role?.name ?? '—'}</td>
                                            <td className="py-3 px-4 text-slate-500">{visa.comment || visa.status || '—'}</td>
                                            <td className="py-3 px-4 text-slate-500">{formatDateTime(visa.created_at)}</td>
                                        </tr>
                                    ))}
                                </tbody>
                            </table>
                        </div>
                    ) : (
                        <div className="text-center py-8 text-slate-400 text-sm">
                            <span className="material-symbols-outlined text-4xl block mb-2">history_toggle_off</span>
                            No existen registros de visación para este sílabo.
                        </div>
                    )}
                </div>
            </div>
        </div>
    )
}

SyllabiShow.layout = (page) => <AppLayout>{page}</AppLayout>
