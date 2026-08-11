import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

export default function CurriculumReviewsCreate({ templates, periods, careers, defaultCareer }) {
    const activePeriod = periods.find((p) => p.is_active) ?? periods[0]

    const { data, setData, post, processing, errors } = useForm({
        checklist_template_id: templates[0]?.id ?? '',
        academic_period_id: activePeriod?.id ?? '',
        career_id: defaultCareer?.id ?? '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/curriculum/reviews')
    }

    return (
        <div className="page-enter">
            <div className="max-w-3xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Gestión Curricular
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Nueva Revisión Curricular</h2>
                    <p className="text-slate-500">Inicia una revisión curricular con una plantilla de lista de cotejo.</p>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="mb-4">
                                <label htmlFor="checklist_template_id" className="block text-sm font-medium text-gray-700">
                                    Plantilla de Lista de Cotejo
                                </label>
                                <select
                                    id="checklist_template_id"
                                    value={data.checklist_template_id}
                                    onChange={(e) => setData('checklist_template_id', e.target.value)}
                                    required
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                >
                                    {templates.map((t) => (
                                        <option key={t.id} value={t.id}>
                                            {t.code} - {t.name} ({t.version})
                                        </option>
                                    ))}
                                </select>
                                {errors.checklist_template_id && (
                                    <p className="mt-1 text-xs text-red-600">{errors.checklist_template_id}</p>
                                )}
                            </div>

                            <div className="mb-4">
                                <label htmlFor="career_id" className="block text-sm font-medium text-gray-700">
                                    Carrera
                                </label>
                                <select
                                    id="career_id"
                                    value={data.career_id}
                                    onChange={(e) => setData('career_id', e.target.value)}
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                >
                                    {careers.map((career) => (
                                        <option key={career.id} value={career.id}>
                                            {career.name}
                                        </option>
                                    ))}
                                </select>
                                {errors.career_id && <p className="mt-1 text-xs text-red-600">{errors.career_id}</p>}
                            </div>

                            <div className="mb-4">
                                <label htmlFor="academic_period_id" className="block text-sm font-medium text-gray-700">
                                    Periodo Académico
                                </label>
                                <select
                                    id="academic_period_id"
                                    value={data.academic_period_id}
                                    onChange={(e) => setData('academic_period_id', e.target.value)}
                                    required
                                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                >
                                    {periods.map((period) => (
                                        <option key={period.id} value={period.id}>
                                            {period.name}
                                        </option>
                                    ))}
                                </select>
                                {errors.academic_period_id && (
                                    <p className="mt-1 text-xs text-red-600">{errors.academic_period_id}</p>
                                )}
                            </div>

                            <div className="flex justify-end mt-6">
                                <a
                                    href="/curriculum/reviews"
                                    className="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2"
                                >
                                    Cancelar
                                </a>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 disabled:opacity-50"
                                >
                                    {processing ? 'Iniciando...' : 'Iniciar Revisión'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

CurriculumReviewsCreate.layout = (page) => <AppLayout>{page}</AppLayout>
