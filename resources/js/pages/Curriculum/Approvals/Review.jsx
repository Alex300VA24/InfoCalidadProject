import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

export default function CurriculumApprovalsReview({ report }) {
    const { data, setData, post, processing, errors } = useForm({
        decision: '',
        comments: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post(`/curriculum/approvals/${report.id}/approve`, { preserveScroll: true })
    }

    const hasApproval = Boolean(report.approval)

    return (
        <div className="page-enter">
            <div className="max-w-5xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Aprobación
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Revisar Informe Técnico #{report.id}</h2>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="p-6">
                        <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 text-sm mb-6">
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
                                <span className="text-slate-400 block text-xs uppercase font-bold">Preparado por</span>
                                <span className="font-semibold text-navy">{report.preparer?.name ?? '—'}</span>
                            </div>
                        </div>

                        <div className="prose max-w-none border border-slate-200 rounded-lg p-6 whitespace-pre-wrap text-sm leading-7 mb-6">
                            {report.content}
                        </div>

                        {report.curriculumReview?.evaluations?.length > 0 && (
                            <>
                                <h3 className="font-semibold text-navy mb-3">Evaluación de Lista de Cotejo</h3>
                                <div className="overflow-x-auto border border-slate-200 rounded-lg mb-6">
                                    <table className="w-full text-left">
                                        <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500">
                                            <tr>
                                                <th className="px-4 py-3">Criterio</th>
                                                <th className="px-4 py-3 text-center w-20">Puntaje</th>
                                                <th className="px-4 py-3">Observaciones</th>
                                            </tr>
                                        </thead>
                                        <tbody className="text-sm divide-y divide-slate-100">
                                            {report.curriculumReview.evaluations.map((evaluation) => (
                                                <tr key={evaluation.id}>
                                                    <td className="px-4 py-3">
                                                        <span className="font-bold text-slate-700">
                                                            {evaluation.criterion?.code}
                                                        </span>{' '}
                                                        <span className="text-slate-600">
                                                            {evaluation.criterion?.description}
                                                        </span>
                                                    </td>
                                                    <td className="px-4 py-3 text-center font-bold text-navy">
                                                        {evaluation.score}/5
                                                    </td>
                                                    <td className="px-4 py-3 text-slate-600">
                                                        {evaluation.observations ?? '—'}
                                                    </td>
                                                </tr>
                                            ))}
                                        </tbody>
                                    </table>
                                </div>
                            </>
                        )}

                        {!hasApproval ? (
                            <div className="border-t border-slate-200 pt-6">
                                <h3 className="font-semibold text-navy mb-4">Emitir Dictamen</h3>
                                <form onSubmit={submit}>
                                    <div className="mb-4">
                                        <label className="block text-sm font-medium text-gray-700 mb-2">Decisión</label>
                                        <div className="flex flex-wrap gap-6">
                                            <label className="inline-flex items-center cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="decision"
                                                    value="approved"
                                                    checked={data.decision === 'approved'}
                                                    onChange={(e) => setData('decision', e.target.value)}
                                                    required
                                                    className="mr-2"
                                                />
                                                <span className="text-emerald-700 font-semibold">Aprobar</span>
                                            </label>
                                            <label className="inline-flex items-center cursor-pointer">
                                                <input
                                                    type="radio"
                                                    name="decision"
                                                    value="observed"
                                                    checked={data.decision === 'observed'}
                                                    onChange={(e) => setData('decision', e.target.value)}
                                                    required
                                                    className="mr-2"
                                                />
                                                <span className="text-red-700 font-semibold">
                                                    Observar / Rechazar
                                                </span>
                                            </label>
                                        </div>
                                        {errors.decision && (
                                            <p className="mt-1 text-xs text-red-600">{errors.decision}</p>
                                        )}
                                    </div>
                                    <div className="mb-4">
                                        <label htmlFor="comments" className="block text-sm font-medium text-gray-700">
                                            Comentarios
                                        </label>
                                        <textarea
                                            id="comments"
                                            rows={4}
                                            value={data.comments}
                                            onChange={(e) => setData('comments', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                            placeholder="Ingrese sus comentarios o justificación..."
                                        />
                                        {errors.comments && (
                                            <p className="mt-1 text-xs text-red-600">{errors.comments}</p>
                                        )}
                                    </div>
                                    <div className="flex justify-end">
                                        <a
                                            href="/curriculum/approvals"
                                            className="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2"
                                        >
                                            Cancelar
                                        </a>
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 disabled:opacity-50"
                                        >
                                            {processing ? 'Emitiendo...' : 'Emitir Dictamen'}
                                        </button>
                                    </div>
                                </form>
                            </div>
                        ) : (
                            <div className="border-t border-slate-200 pt-6">
                                <h3 className="font-semibold text-navy mb-2">Dictamen Emitido</h3>
                                <p>
                                    Decisión:{' '}
                                    <strong
                                        className={
                                            report.approval.decision === 'approved'
                                                ? 'text-emerald-600'
                                                : 'text-red-600'
                                        }
                                    >
                                        {report.approval.decision === 'approved' ? 'APROBADO' : 'OBSERVADO'}
                                    </strong>
                                </p>
                                {report.approval.comments && (
                                    <p className="text-sm mt-1 text-slate-700">
                                        Comentarios: {report.approval.comments}
                                    </p>
                                )}
                            </div>
                        )}
                    </div>
                </div>
            </div>
        </div>
    )
}

CurriculumApprovalsReview.layout = (page) => <AppLayout>{page}</AppLayout>
