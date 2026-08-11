import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return '—'
    const d = new Date(value)
    if (Number.isNaN(d.getTime())) return String(value)
    return d.toLocaleDateString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
    })
}

const formatDateTime = (value) => {
    if (!value) return '—'
    const d = new Date(value)
    if (Number.isNaN(d.getTime())) return String(value)
    return d.toLocaleString('es-PE', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}

export default function CurriculumReportsShow({ report }) {
    const handleFinalize = (e) => {
        e.preventDefault()
        if (window.confirm('¿Finalizar y enviar para aprobación?')) {
            router.post(`/curriculum/reports/${report.id}/finalize`, {}, { preserveScroll: true })
        }
    }

    return (
        <div className="page-enter">
            <div className="max-w-5xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Informe Técnico
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Informe Técnico #{report.id}</h2>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        {report.status === 'draft' && (
                            <>
                                <a
                                    href={`/curriculum/reports/${report.id}/edit`}
                                    className="inline-flex items-center px-4 py-2 bg-amber-500 text-white rounded-md text-sm font-semibold hover:bg-amber-400"
                                >
                                    Editar
                                </a>
                                <form onSubmit={handleFinalize} className="inline">
                                    <button
                                        type="submit"
                                        className="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-md text-sm font-semibold hover:bg-green-500"
                                    >
                                        Finalizar y Enviar
                                    </button>
                                </form>
                            </>
                        )}
                        <a
                            href={`/curriculum/reports/${report.id}/pdf`}
                            className="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md text-sm font-semibold hover:bg-red-500"
                            data-turbo="false"
                        >
                            PDF
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="p-6">
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-6 text-sm">
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Carrera</span>
                                <span className="font-semibold text-navy">
                                    {report.curriculumReview?.career?.name ?? '—'}
                                </span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Periodo</span>
                                <span className="font-semibold text-navy">
                                    {report.curriculumReview?.academicPeriod?.name ?? '—'}
                                </span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Acción Curricular</span>
                                <span className="font-semibold text-navy">
                                    {report.curriculumReview?.actionType?.name ?? '—'}
                                </span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Estado</span>
                                <span
                                    className={`px-3 py-1 rounded-full text-xs font-bold border inline-block mt-1 ${
                                        report.status === 'finalized'
                                            ? 'text-emerald-700 bg-emerald-100 border-emerald-200'
                                            : 'text-amber-700 bg-amber-100 border-amber-200'
                                    }`}
                                >
                                    {report.status === 'finalized' ? 'Finalizado' : 'Borrador'}
                                </span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Preparado por</span>
                                <span className="font-semibold text-navy">{report.preparer?.name ?? '—'}</span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Fecha</span>
                                <span className="font-semibold text-navy">{formatDate(report.created_at)}</span>
                            </div>
                        </div>

                        <div className="prose max-w-none border border-slate-200 rounded-lg p-6 whitespace-pre-wrap text-sm leading-7">
                            {report.content}
                        </div>

                        {report.approval && (
                            <div className="mt-6 pt-6 border-t border-slate-200">
                                <h3 className="font-semibold text-navy mb-2">Dictamen del Director de Escuela</h3>
                                <p className="text-sm">
                                    Decisión:{' '}
                                    <span
                                        className={`font-bold ${
                                            report.approval.decision === 'approved'
                                                ? 'text-emerald-600'
                                                : 'text-red-600'
                                        }`}
                                    >
                                        {report.approval.decision === 'approved' ? 'APROBADO' : 'OBSERVADO'}
                                    </span>
                                </p>
                                {report.approval.comments && (
                                    <p className="text-sm mt-2 text-slate-700">
                                        Comentarios: {report.approval.comments}
                                    </p>
                                )}
                                <p className="text-sm text-slate-500 mt-1">
                                    Aprobado el: {formatDateTime(report.approval.approved_at)}
                                </p>
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    )
}

CurriculumReportsShow.layout = (page) => <AppLayout>{page}</AppLayout>
