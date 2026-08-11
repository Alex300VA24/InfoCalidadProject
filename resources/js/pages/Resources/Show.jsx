import { usePage, router } from '@inertiajs/react'
import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

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

const STATUS_COLORS = {
    pending: 'bg-amber-500/10 text-amber-700 border-amber-500/20',
    in_review: 'bg-blue-500/10 text-blue-700 border-blue-500/20',
    completed: 'bg-emerald-500/10 text-emerald-700 border-emerald-500/20',
    rejected: 'bg-red-500/10 text-red-700 border-red-500/20',
}

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

export default function ResourcesShow({ resourceRequest }) {
    const { can } = usePage().props
    const status = resourceRequest.status
    const statusLabel = STATUS_LABELS[status] || status
    const typeLabel = TYPE_LABELS[resourceRequest.request_type] || resourceRequest.request_type
    const isCompleted = status === 'completed'
    const canRespond = can.secretaria && !isCompleted

    const { data, setData, post, processing, errors } = useForm({
        document_number: '',
        subject: '',
        document: null,
    })

    const onFileChange = (e) => {
        const file = e.target.files?.[0]
        if (file) setData('document', file)
    }

    const handleResponse = (e) => {
        e.preventDefault()
        post(`/resources/${resourceRequest.id}/response`, { preserveScroll: true })
    }

    const initial = (resourceRequest.applicant?.name ?? '?').charAt(0).toUpperCase()

    return (
        <div className="page-enter">
            <div className="max-w-6xl mx-auto px-5 sm:px-8">
                <div className="flex items-center gap-3 mb-6">
                    <a href="/resources" className="text-slate-500 hover:text-navy" aria-label="Volver">
                        <span className="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <div className="flex-1 min-w-0">
                        <div className="flex flex-wrap items-center gap-2">
                            <span className={`text-[10px] font-black px-2 py-0.5 rounded-sm uppercase tracking-widest border ${STATUS_COLORS[status] || STATUS_COLORS.pending}`}>
                                {statusLabel}
                            </span>
                            <span className="text-[10px] font-black px-2 py-0.5 rounded-sm uppercase tracking-widest border border-navy/20 text-navy bg-navy/5">
                                {typeLabel}
                            </span>
                        </div>
                        <h2 className="text-3xl font-bold text-navy mt-2 truncate">{resourceRequest.title}</h2>
                        <p className="text-slate-500 text-sm font-mono">{resourceRequest.code}</p>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
                    <div className="lg:col-span-2 bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                        <h3 className="text-lg font-bold text-ink border-b border-slate-200 pb-2 mb-4">
                            <span className="flex items-center gap-2">
                                <span className="material-symbols-outlined text-navy">receipt_long</span>
                                Datos de la Solicitud
                            </span>
                        </h3>
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Solicitante</p>
                                <div className="flex items-center gap-3 mt-2">
                                    <div className="w-10 h-10 rounded-full bg-slate-200 flex items-center justify-center font-bold text-navy border border-slate-300">
                                        {initial}
                                    </div>
                                    <div>
                                        <p className="text-sm font-bold text-navy">{resourceRequest.applicant?.name}</p>
                                        <p className="text-xs text-slate-500">{resourceRequest.applicant?.email}</p>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Periodo</p>
                                <p className="text-sm font-bold text-navy mt-2">{resourceRequest.academicPeriod?.name}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Código</p>
                                <p className="text-sm font-mono text-navy mt-2">{resourceRequest.code}</p>
                            </div>
                            <div>
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Fecha de solicitud</p>
                                <p className="text-sm font-bold text-navy mt-2">{formatDateTime(resourceRequest.created_at)}</p>
                            </div>
                            <div className="sm:col-span-2">
                                <p className="text-[10px] font-black uppercase tracking-wider text-slate-400">Descripción</p>
                                <p className="text-sm text-navy mt-2 whitespace-pre-wrap">
                                    {resourceRequest.description || 'Sin descripción'}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="bg-slate-50 border border-slate-200 rounded-xl shadow-sm p-6">
                        <h3 className="text-lg font-bold text-ink mb-4 flex items-center gap-2">
                            <span className="material-symbols-outlined text-navy">info_outline</span>
                            Resumen
                        </h3>
                        <ul className="space-y-3 text-sm">
                            <li className="flex justify-between">
                                <span className="text-slate-500">Documentos:</span>
                                <span className="font-bold text-navy">{resourceRequest.documents?.length ?? 0}</span>
                            </li>
                            <li className="flex justify-between">
                                <span className="text-slate-500">Anexos:</span>
                                <span className="font-bold text-navy">{resourceRequest.attachments?.length ?? 0}</span>
                            </li>
                            <li className="flex justify-between border-t border-slate-200 pt-3">
                                <span className="text-slate-500">Estado:</span>
                                <span className={`font-black text-[11px] uppercase px-2 py-0.5 rounded border ${STATUS_COLORS[status] || STATUS_COLORS.pending}`}>
                                    {statusLabel}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                        <h3 className="text-lg font-bold text-ink border-b border-slate-200 pb-2 mb-4">
                            <span className="flex items-center gap-2">
                                <span className="material-symbols-outlined text-navy">description</span>
                                Documentos de la Solicitud
                            </span>
                        </h3>
                        {resourceRequest.documents?.length > 0 ? (
                            <ul className="divide-y divide-slate-100">
                                {resourceRequest.documents.map((doc) => (
                                    <li key={doc.id} className="py-3 flex items-center justify-between gap-3">
                                        <div className="min-w-0 flex-1">
                                            <p className="font-bold text-navy text-sm truncate">{doc.filename}</p>
                                            <p className="text-xs text-slate-400">{formatKb(doc.file_size)} · {doc.document_type || 'document'}</p>
                                        </div>
                                        <a
                                            href={`/resources/documents/${doc.id}/download`}
                                            data-turbo="false"
                                            className="inline-flex items-center gap-1 px-2 py-1 text-xs font-bold text-navy bg-navy/5 hover:bg-navy/10 rounded transition-colors"
                                            title="Descargar documento"
                                        >
                                            <span className="material-symbols-outlined text-lg">download</span>
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <div className="text-center py-8 text-slate-400 text-sm">
                                <span className="material-symbols-outlined text-4xl block mb-2">description_off</span>
                                No existen documentos.
                            </div>
                        )}
                    </div>

                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                        <h3 className="text-lg font-bold text-ink border-b border-slate-200 pb-2 mb-4">
                            <span className="flex items-center gap-2">
                                <span className="material-symbols-outlined text-navy">attachment</span>
                                Anexos y Evidencias
                            </span>
                        </h3>
                        {resourceRequest.attachments?.length > 0 ? (
                            <ul className="divide-y divide-slate-100">
                                {resourceRequest.attachments.map((att) => (
                                    <li key={att.id} className="py-3 flex items-center justify-between gap-3">
                                        <div className="min-w-0 flex-1">
                                            <p className="font-bold text-navy text-sm truncate">{att.filename}</p>
                                            <p className="text-xs text-slate-400">{formatKb(att.file_size)}</p>
                                        </div>
                                        <a
                                            href={`/resources/documents/${att.id}/download`}
                                            data-turbo="false"
                                            className="inline-flex items-center gap-1 px-2 py-1 text-xs font-bold text-navy bg-navy/5 hover:bg-navy/10 rounded transition-colors"
                                            title="Descargar anexo"
                                        >
                                            <span className="material-symbols-outlined text-lg">download</span>
                                        </a>
                                    </li>
                                ))}
                            </ul>
                        ) : (
                            <div className="text-center py-8 text-slate-400 text-sm">
                                <span className="material-symbols-outlined text-4xl block mb-2">attachment_off</span>
                                No se adjuntaron evidencias.
                            </div>
                        )}
                    </div>
                </div>

                {canRespond && (
                    <div className="bg-white border-2 border-accent/30 rounded-xl shadow-sm p-6 mb-6">
                        <div className="flex items-start gap-3 mb-4">
                            <div className="w-10 h-10 rounded-full bg-accent/20 text-navy flex items-center justify-center shrink-0">
                                <span className="material-symbols-outlined text-xl">reply</span>
                            </div>
                            <div>
                                <h3 className="text-lg font-bold text-ink">Responder Solicitud</h3>
                                <p className="text-sm text-slate-500">Adjunta el documento de respuesta oficial para marcar como completada la solicitud.</p>
                            </div>
                        </div>
                        <form onSubmit={handleResponse} noValidate encType="multipart/form-data">
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                                <div>
                                    <label className="block text-sm font-bold text-ink mb-1.5">
                                        N° Documento <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        value={data.document_number}
                                        onChange={(e) => setData('document_number', e.target.value)}
                                        placeholder="Ej. OGE-001-2024"
                                        className={`w-full rounded-lg ${errors.document_number ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                        required
                                    />
                                    {errors.document_number && <p className="mt-1 text-xs text-red-500">{errors.document_number}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-bold text-ink mb-1.5">
                                        Asunto <span className="text-red-500">*</span>
                                    </label>
                                    <input
                                        type="text"
                                        value={data.subject}
                                        onChange={(e) => setData('subject', e.target.value)}
                                        placeholder="Motivo del documento de respuesta"
                                        className={`w-full rounded-lg ${errors.subject ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                        required
                                    />
                                    {errors.subject && <p className="mt-1 text-xs text-red-500">{errors.subject}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-bold text-ink mb-1.5">
                                        Archivo PDF <span className="text-red-500">*</span>
                                    </label>
                                    <div className="border-2 border-dashed border-slate-300 rounded-xl p-5 hover:border-accent hover:bg-accent/5 transition-colors">
                                        <input
                                            type="file"
                                            accept=".pdf,application/pdf"
                                            onChange={onFileChange}
                                            className="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-accent file:text-ink hover:file:bg-accent/80"
                                        />
                                    </div>
                                    {errors.document && <p className="mt-1 text-xs text-red-500">{errors.document}</p>}
                                </div>
                            </div>

                            <div className="mt-6 flex justify-end gap-2">
                                <button
                                    type="submit"
                                    disabled={processing || !(data.document_number && data.subject && data.document)}
                                    className="inline-flex items-center gap-2 px-6 py-2 bg-navy text-white font-black rounded shadow-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-navy/90 transition-colors text-sm"
                                >
                                    {processing ? (
                                        <span className="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                                    ) : (
                                        <span className="material-symbols-outlined text-lg">send</span>
                                    )}
                                    Marcar Completado
                                </button>
                            </div>
                        </form>
                    </div>
                )}

                {isCompleted && (
                    <div className="bg-emerald-50 border-2 border-emerald-300 rounded-xl shadow-sm p-6">
                        <div className="flex items-center gap-3">
                            <div className="w-10 h-10 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0">
                                <span className="material-symbols-outlined text-xl">task_alt</span>
                            </div>
                            <div>
                                <h3 className="text-lg font-bold text-emerald-800">Solicitud Completada</h3>
                                <p className="text-sm text-emerald-700">
                                    La solicitud fue cerrada el {formatDateTime(resourceRequest.updated_at)}
                                </p>
                            </div>
                        </div>
                    </div>
                )}
            </div>
        </div>
    )
}

ResourcesShow.layout = (page) => <AppLayout>{page}</AppLayout>
