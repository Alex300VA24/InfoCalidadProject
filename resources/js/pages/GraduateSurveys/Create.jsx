import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function GraduateSurveysCreate({ graduate }) {
    const { data, setData, post, processing, errors } = useForm({
        period: '',
        survey_date: new Date().toISOString().slice(0, 10),
        employed: 0,
        job_related_to_career: '',
        competency_level_score: '',
        income: '',
        observations: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post(`/graduates/${graduate.id}/surveys`)
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="flex justify-between items-end mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Encuesta de Seguimiento</h2>
                        <p className="text-slate-500">{graduate.student?.user?.name ?? graduate.student?.codigo}</p>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Periodo</label>
                                    <input type="text" value={data.period} onChange={(e) => setData('period', e.target.value)} placeholder="Ej. 2026-II" required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.period && <p className="mt-1 text-sm text-red-600">{errors.period}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de encuesta</label>
                                    <input type="date" value={data.survey_date} onChange={(e) => setData('survey_date', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.survey_date && <p className="mt-1 text-sm text-red-600">{errors.survey_date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">¿Se encuentra empleado?</label>
                                    <select value={data.employed} onChange={(e) => setData('employed', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="0">No</option>
                                        <option value="1">Sí</option>
                                    </select>
                                    {errors.employed && <p className="mt-1 text-sm text-red-600">{errors.employed}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">¿El empleo se relaciona con la carrera?</label>
                                    <select value={data.job_related_to_career} onChange={(e) => setData('job_related_to_career', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Sin especificar</option>
                                        <option value="0">No</option>
                                        <option value="1">Sí</option>
                                    </select>
                                    {errors.job_related_to_career && <p className="mt-1 text-sm text-red-600">{errors.job_related_to_career}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Nivel de logro de competencias (0 - 20)</label>
                                    <input type="number" value={data.competency_level_score} onChange={(e) => setData('competency_level_score', e.target.value)} min="0" max="20" step="0.01" className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.competency_level_score && <p className="mt-1 text-sm text-red-600">{errors.competency_level_score}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Ingreso mensual (S/)</label>
                                    <input type="number" value={data.income} onChange={(e) => setData('income', e.target.value)} min="0" step="0.01" className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.income && <p className="mt-1 text-sm text-red-600">{errors.income}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
                                    <textarea value={data.observations} onChange={(e) => setData('observations', e.target.value)} rows="3" className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.observations && <p className="mt-1 text-sm text-red-600">{errors.observations}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href={`/graduates/${graduate.id}`} className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Encuesta'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

GraduateSurveysCreate.layout = (page) => <AppLayout>{page}</AppLayout>
