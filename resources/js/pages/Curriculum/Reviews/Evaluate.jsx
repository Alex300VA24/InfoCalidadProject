import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

function buildEvaluationInitials(review) {
    const scores = {}
    const observations = {}
    const evalMap = new Map()
    review.evaluations.forEach((e) => evalMap.set(e.criterion_id, e))
    review.checklistTemplate.criteria.forEach((c) => {
        const ev = evalMap.get(c.id)
        scores[c.id] = ev?.score ?? 0
        observations[c.id] = ev?.observations ?? ''
    })
    return { scores, observations }
}

export default function CurriculumReviewsEvaluate({ review, actionTypes }) {
    const initials = buildEvaluationInitials(review)

    const evalForm = useForm({
        scores: initials.scores,
        observations: initials.observations,
    })

    const completeForm = useForm({
        action_type_id: actionTypes[0]?.id ?? '',
        observations: '',
    })

    const hasEvaluations = review.evaluations && review.evaluations.length > 0

    const submitEval = (e) => {
        e.preventDefault()
        evalForm.post(`/curriculum/reviews/${review.id}/evaluate`, { preserveScroll: true })
    }

    const submitComplete = (e) => {
        e.preventDefault()
        completeForm.post(`/curriculum/reviews/${review.id}/complete`, { preserveScroll: true })
    }

    return (
        <div className="page-enter">
            <div className="max-w-5xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Lista de Cotejo
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Evaluar Revisión</h2>
                    <p className="text-slate-500">
                        Carrera: <strong className="text-navy">{review.career?.name}</strong> | Periodo:{' '}
                        <strong className="text-navy">{review.academicPeriod?.name}</strong>
                    </p>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="p-6">
                        <h3 className="text-lg font-semibold text-navy mb-4">
                            {review.checklistTemplate.code} - {review.checklistTemplate.name}
                        </h3>
                        <form onSubmit={submitEval}>
                            <div className="overflow-x-auto border border-slate-200 rounded-lg">
                                <table className="w-full text-left">
                                    <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500">
                                        <tr>
                                            <th className="px-4 py-3 w-20">Código</th>
                                            <th className="px-4 py-3">Descripción</th>
                                            <th className="px-4 py-3 text-center w-28">Puntaje (0-5)</th>
                                            <th className="px-4 py-3">Observaciones</th>
                                        </tr>
                                    </thead>
                                    <tbody className="text-sm divide-y divide-slate-100">
                                        {review.checklistTemplate.criteria.map((criterion) => (
                                            <tr key={criterion.id}>
                                                <td className="px-4 py-3 font-bold text-slate-700">{criterion.code}</td>
                                                <td className="px-4 py-3 text-slate-700">{criterion.description}</td>
                                                <td className="px-4 py-3 text-center">
                                                    <input
                                                        type="number"
                                                        min="0"
                                                        max="5"
                                                        step="1"
                                                        required
                                                        value={evalForm.data.scores[criterion.id] ?? 0}
                                                        onChange={(e) =>
                                                            evalForm.setData('scores', {
                                                                ...evalForm.data.scores,
                                                                [criterion.id]: Number(e.target.value),
                                                            })
                                                        }
                                                        className="w-16 text-center rounded-md border-slate-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                                        aria-label={`Puntaje criterio ${criterion.code}`}
                                                    />
                                                </td>
                                                <td className="px-4 py-3">
                                                    <input
                                                        type="text"
                                                        value={evalForm.data.observations[criterion.id] ?? ''}
                                                        onChange={(e) =>
                                                            evalForm.setData('observations', {
                                                                ...evalForm.data.observations,
                                                                [criterion.id]: e.target.value,
                                                            })
                                                        }
                                                        className="w-full rounded-md border-slate-300 shadow-sm focus:border-indigo-300 text-sm"
                                                        placeholder="(opcional)"
                                                    />
                                                </td>
                                            </tr>
                                        ))}
                                    </tbody>
                                </table>
                            </div>
                            <div className="flex justify-end mt-4">
                                <button
                                    type="submit"
                                    disabled={evalForm.processing}
                                    className="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-500 disabled:opacity-50"
                                >
                                    {evalForm.processing ? 'Guardando...' : 'Guardar Evaluación'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="p-6">
                        <h3 className="text-lg font-semibold text-navy mb-4">
                            Completar Revisión - Tipo de Acción Curricular
                        </h3>
                        {!hasEvaluations && (
                            <div className="mb-4 text-xs text-amber-700 bg-amber-50 border border-amber-200 rounded p-3">
                                Primero guarda al menos una evaluación para habilitar la opción de completar la revisión.
                            </div>
                        )}
                        <form onSubmit={submitComplete} disabled={!hasEvaluations}>
                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Tipo de Acción Curricular
                                </label>
                                <div className="space-y-2">
                                    {actionTypes.map((action) => (
                                        <label
                                            key={action.id}
                                            className="flex items-start gap-3 p-3 rounded-lg border border-slate-200 hover:bg-slate-50 cursor-pointer"
                                        >
                                            <input
                                                type="radio"
                                                name="action_type_id"
                                                value={action.id}
                                                checked={Number(completeForm.data.action_type_id) === Number(action.id)}
                                                onChange={(e) =>
                                                    completeForm.setData('action_type_id', Number(e.target.value))
                                                }
                                                disabled={!hasEvaluations}
                                                required
                                                className="mt-1"
                                            />
                                            <div>
                                                <span className="font-semibold text-navy">{action.name}</span>
                                                {action.description && (
                                                    <p className="text-xs text-slate-500 mt-0.5">{action.description}</p>
                                                )}
                                            </div>
                                        </label>
                                    ))}
                                </div>
                                {completeForm.errors.action_type_id && (
                                    <p className="mt-1 text-xs text-red-600">{completeForm.errors.action_type_id}</p>
                                )}
                            </div>
                            <div className="mb-4">
                                <label htmlFor="observations-complete" className="block text-sm font-medium text-gray-700">
                                    Observaciones Generales
                                </label>
                                <textarea
                                    id="observations-complete"
                                    rows={3}
                                    value={completeForm.data.observations}
                                    onChange={(e) => completeForm.setData('observations', e.target.value)}
                                    disabled={!hasEvaluations}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 disabled:bg-slate-50"
                                />
                                {completeForm.errors.observations && (
                                    <p className="mt-1 text-xs text-red-600">{completeForm.errors.observations}</p>
                                )}
                            </div>
                            <div className="flex justify-end">
                                <button
                                    type="submit"
                                    disabled={completeForm.processing || !hasEvaluations}
                                    className="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 disabled:opacity-50"
                                >
                                    {completeForm.processing ? 'Completando...' : 'Completar Revisión'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

CurriculumReviewsEvaluate.layout = (page) => <AppLayout>{page}</AppLayout>
