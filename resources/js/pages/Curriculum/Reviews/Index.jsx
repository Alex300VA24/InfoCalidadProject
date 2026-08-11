import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'

const REVIEW_STATUS_STYLES = {
    completed: 'text-emerald-700 bg-emerald-100 border-emerald-200',
    draft: 'text-amber-700 bg-amber-100 border-amber-200',
}

const REVIEW_STATUS_LABELS = {
    completed: 'Completado',
    draft: 'Borrador',
}

export default function CurriculumReviewsIndex({ reviews }) {
    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Gestión Curricular
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Listas de Cotejo</h2>
                        <p className="text-slate-500">Revisiones curriculares y acciones derivadas.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a
                            href="/curriculum/reviews/create"
                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-gray-800 text-white rounded-md text-xs font-semibold uppercase tracking-widest hover:bg-gray-700"
                        >
                            <span className="material-symbols-outlined text-base">checklist</span>
                            Nueva Revisión
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th className="px-6 py-4">Carrera</th>
                                    <th className="px-6 py-4">Periodo</th>
                                    <th className="px-6 py-4">Plantilla</th>
                                    <th className="px-6 py-4">Acción</th>
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {reviews.data.map((review) => (
                                    <tr key={review.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4 text-slate-700">{review.career?.name}</td>
                                        <td className="px-6 py-4 text-slate-700">{review.academicPeriod?.name}</td>
                                        <td className="px-6 py-4 text-slate-700">{review.checklistTemplate?.code}</td>
                                        <td className="px-6 py-4 text-slate-700">{review.actionType?.name ?? '—'}</td>
                                        <td className="px-6 py-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-xs font-bold border ${
                                                    REVIEW_STATUS_STYLES[review.status] ?? 'text-slate-600 bg-slate-100 border-slate-200'
                                                }`}
                                            >
                                                {REVIEW_STATUS_LABELS[review.status] ?? review.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <a
                                                href={`/curriculum/reviews/${review.id}`}
                                                className="inline-flex items-center gap-1 text-navy hover:text-[#343d96] mr-2"
                                                title="Ver revisión"
                                            >
                                                <span className="material-symbols-outlined text-lg">visibility</span>
                                                <span className="hidden sm:inline text-sm font-semibold">Ver</span>
                                            </a>
                                            {review.status === 'draft' && (
                                                <a
                                                    href={`/curriculum/reviews/${review.id}/evaluate`}
                                                    className="inline-flex items-center gap-1 text-emerald-600 hover:text-emerald-700"
                                                    title="Evaluar revisión"
                                                >
                                                    <span className="material-symbols-outlined text-lg">task_alt</span>
                                                    <span className="hidden sm:inline text-sm font-semibold">Evaluar</span>
                                                </a>
                                            )}
                                        </td>
                                    </tr>
                                ))}
                                {reviews.data.length === 0 && (
                                    <tr>
                                        <td colSpan={6} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">No hay revisiones registradas</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={reviews.links} />
                </div>
            </div>
        </div>
    )
}

CurriculumReviewsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
