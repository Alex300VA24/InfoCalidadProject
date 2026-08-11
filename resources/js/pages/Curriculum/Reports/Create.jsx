import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

export default function CurriculumReportsCreate({ review }) {
    const { data, setData, post, processing, errors } = useForm({
        content: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post(`/curriculum/reviews/${review.id}/reports`)
    }

    return (
        <div className="page-enter">
            <div className="max-w-5xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Informe Técnico
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Generar Informe Técnico</h2>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="p-6">
                        <h3 className="font-semibold text-navy mb-2">Resumen de la Revisión</h3>
                        <p className="text-sm text-slate-600">
                            Carrera: <strong className="text-navy">{review.career?.name}</strong> | Periodo:{' '}
                            <strong className="text-navy">{review.academicPeriod?.name}</strong> | Acción:{' '}
                            <strong className="text-navy">{review.actionType?.name}</strong>
                        </p>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="mb-4">
                                <label htmlFor="content" className="block text-sm font-medium text-gray-700 mb-2">
                                    Contenido del Informe Técnico
                                </label>
                                <p className="text-xs text-slate-500 mb-2">
                                    Incluya observaciones de la lista de cotejo, justificación de la acción curricular
                                    y recomendaciones.
                                </p>
                                <textarea
                                    id="content"
                                    rows={20}
                                    value={data.content}
                                    onChange={(e) => setData('content', e.target.value)}
                                    required
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 font-mono text-sm"
                                />
                                {errors.content && <p className="mt-1 text-xs text-red-600">{errors.content}</p>}
                            </div>
                            <div className="flex justify-end">
                                <a
                                    href={`/curriculum/reviews/${review.id}`}
                                    className="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2"
                                >
                                    Cancelar
                                </a>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-500 disabled:opacity-50"
                                >
                                    {processing ? 'Generando...' : 'Generar Informe'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

CurriculumReportsCreate.layout = (page) => <AppLayout>{page}</AppLayout>
