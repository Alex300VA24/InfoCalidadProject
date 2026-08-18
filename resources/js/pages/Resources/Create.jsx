import { useMemo } from 'react'
import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

const TIPO_SOLICITUD = [
    ['bibliographic', 'Bibliográfico'],
    ['hemerographic', 'Hemerográfico'],
    ['equipment', 'Equipamiento'],
]

export default function ResourcesCreate({ periods }) {
    const { data, setData, post, processing, errors } = useForm({
        academic_period_id: '',
        title: '',
        description: '',
        request_type: 'bibliographic',
        documents: [],
        attachments: [],
    })

    const documentsFiles = useMemo(() => Array.from(data.documents), [data.documents])
    const attachmentsFiles = useMemo(() => Array.from(data.attachments), [data.attachments])

    const onDocumentsChange = (e) => {
        const list = e.target.files
        if (list?.length) setData('documents', Array.from(list))
    }

    const onAttachmentsChange = (e) => {
        const list = e.target.files
        if (list?.length) setData('attachments', Array.from(list))
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        const payload = new FormData()
        payload.append('academic_period_id', data.academic_period_id)
        payload.append('title', data.title)
        payload.append('description', data.description || '')
        payload.append('request_type', data.request_type)
        documentsFiles.forEach((f, i) => payload.append(`documents[${i}]`, f))
        attachmentsFiles.forEach((f, i) => payload.append(`attachments[${i}]`, f))

        post('/resources', { preserveScroll: true, data: Object.fromEntries(payload.entries()) })
    }

    const canSubmit = Boolean(
        data.academic_period_id &&
        data.title?.trim() &&
        data.request_type &&
        documentsFiles.length > 0,
    )

    const sumSize = (files) => files.reduce((acc, f) => acc + (f.size || 0), 0)
    const toKb = (n) => `${(n / 1024).toFixed(1)} KB`

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="flex items-center gap-3 mb-6">
                    <a href="/resources" className="text-slate-500 hover:text-navy" aria-label="Volver">
                        <span className="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Gestión Académica
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Registrar Solicitud de Recursos</h2>
                        <p className="text-slate-500">Completa la información y adjunta la documentación pertinente.</p>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                    <form onSubmit={handleSubmit} noValidate encType="multipart/form-data">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-5 mb-4">
                            <div>
                                <label className="block text-sm font-bold text-ink mb-1.5">Periodo Académico <span className="text-red-500">*</span></label>
                                <select
                                    value={data.academic_period_id}
                                    onChange={(e) => setData('academic_period_id', e.target.value)}
                                    className={`w-full rounded-lg ${errors.academic_period_id ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                    required
                                >
                                    <option value="">Selecciona un periodo</option>
                                    {periods.map((p) => (
                                        <option key={p.id} value={p.id}>{p.name}</option>
                                    ))}
                                </select>
                                {errors.academic_period_id && <p className="mt-1 text-xs text-red-500">{errors.academic_period_id}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-1.5">Tipo de Solicitud <span className="text-red-500">*</span></label>
                                <select
                                    value={data.request_type}
                                    onChange={(e) => setData('request_type', e.target.value)}
                                    className={`w-full rounded-lg ${errors.request_type ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                    required
                                >
                                    {TIPO_SOLICITUD.map(([value, label]) => (
                                        <option key={value} value={value}>{label}</option>
                                    ))}
                                </select>
                                {errors.request_type && <p className="mt-1 text-xs text-red-500">{errors.request_type}</p>}
                            </div>

                            <div className="md:col-span-2">
                                <label className="block text-sm font-bold text-ink mb-1.5">Título <span className="text-red-500">*</span></label>
                                <input
                                    type="text"
                                    value={data.title}
                                    onChange={(e) => setData('title', e.target.value)}
                                    placeholder="Describe brevemente el contenido o motivo de la solicitud"
                                    className={`w-full rounded-lg ${errors.title ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                    required
                                />
                                {errors.title && <p className="mt-1 text-xs text-red-500">{errors.title}</p>}
                            </div>

                            <div className="md:col-span-2">
                                <label className="block text-sm font-bold text-ink mb-1.5">Descripción</label>
                                <textarea
                                    value={data.description}
                                    onChange={(e) => setData('description', e.target.value)}
                                    rows={5}
                                    placeholder="Describe la información adicional de la solicitud"
                                    className={`w-full rounded-lg ${errors.description ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                />
                                {errors.description && <p className="mt-1 text-xs text-red-500">{errors.description}</p>}
                            </div>

                            <div className="md:col-span-2">
                                <label className="block text-sm font-bold text-ink mb-1.5">
                                    Documentos de Solicitud <span className="text-red-500">*</span>
                                    <span className="block text-[10px] text-slate-400 font-normal uppercase tracking-wider mt-0.5">
                                        Formularios oficiales, catálogos, cotizaciones
                                    </span>
                                </label>
                                <div className="border-2 border-dashed border-slate-300 rounded-xl p-5 hover:border-accent hover:bg-accent/5 transition-colors">
                                    <input
                                        type="file"
                                        multiple
                                        onChange={onDocumentsChange}
                                        className="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-accent file:text-ink hover:file:bg-accent/80"
                                    />
                                    {documentsFiles.length > 0 && (
                                        <ul className="mt-3 space-y-2 text-xs">
                                            {documentsFiles.map((f, i) => (
                                                <li key={i} className="flex items-center justify-between gap-2 bg-navy/5 border border-navy/10 rounded p-2">
                                                    <div className="flex items-center gap-2 min-w-0">
                                                        <span className="material-symbols-outlined text-navy text-sm text-sm">description</span>
                                                        <span className="font-bold text-navy truncate">{f.name}</span>
                                                    </div>
                                                    <span className="text-slate-400">{toKb(f.size || 0)}</span>
                                                </li>
                                            ))}
                                        </ul>
                                    )}
                                </div>
                                {errors.documents && <p className="mt-1 text-xs text-red-500">{errors.documents}</p>}
                            </div>

                            <div className="md:col-span-2">
                                <label className="block text-sm font-bold text-ink mb-1.5">
                                    Anexos y Evidencias
                                    <span className="block text-[10px] text-slate-400 font-normal uppercase tracking-wider mt-0.5">
                                        Materiales adicionales, imágenes, soporte documental
                                    </span>
                                </label>
                                <div className="border-2 border-dashed border-slate-300 rounded-xl p-5 hover:border-accent hover:bg-accent/5 transition-colors">
                                    <input
                                        type="file"
                                        multiple
                                        onChange={onAttachmentsChange}
                                        className="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-accent file:text-ink hover:file:bg-accent/80"
                                    />
                                    {attachmentsFiles.length > 0 && (
                                        <ul className="mt-3 space-y-2 text-xs">
                                            {attachmentsFiles.map((f, i) => (
                                                <li key={i} className="flex items-center justify-between gap-2 bg-navy/5 border border-navy/10 rounded p-2">
                                                    <div className="flex items-center gap-2 min-w-0">
                                                        <span className="material-symbols-outlined text-navy text-sm">attachment</span>
                                                        <span className="font-bold text-navy truncate">{f.name}</span>
                                                    </div>
                                                    <span className="text-slate-400">{toKb(f.size || 0)}</span>
                                                </li>
                                            ))}
                                        </ul>
                                    )}
                                </div>
                                {errors.attachments && <p className="mt-1 text-xs text-red-500">{errors.attachments}</p>}
                            </div>
                        </div>

                        {(documentsFiles.length > 0 || attachmentsFiles.length > 0) ? (
                            <div className="mt-6 bg-accent/5 border border-accent/20 rounded-lg p-4 text-sm">
                                <p className="font-bold text-navy mb-1">Resumen de carga</p>
                                <p className="text-slate-600 text-xs">
                                    {documentsFiles.length} doc(s) principal(es) ({toKb(sumSize(documentsFiles))})
                                    {attachmentsFiles.length > 0 ? (
                                        <> · {attachmentsFiles.length} anexo(s) ({toKb(sumSize(attachmentsFiles))})</>
                                    ) : ''}
                                </p>
                            </div>
                        ) : null}

                        <div className="mt-8 flex justify-between border-t border-slate-200 pt-6">
                            <a href="/resources" className="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700">
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                disabled={!canSubmit || processing}
                                className="inline-flex items-center gap-2 px-6 py-2 bg-navy text-white font-black rounded shadow-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-navy/90 transition-colors text-sm"
                            >
                                {processing ? (
                                    <span className="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                                ) : (
                                    <span className="material-symbols-outlined text-lg">send</span>
                                )}
                                Enviar Solicitud
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    )
}

ResourcesCreate.layout = (page) => <AppLayout>{page}</AppLayout>
