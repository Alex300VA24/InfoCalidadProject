import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

export default function PerformanceCreate({ periods, teachers, sources, defaultPeriod }) {
    const { data, setData, post, processing, errors } = useForm({
        teacher_id: '',
        academic_period_id: defaultPeriod?.id ?? '',
        source: 'encuesta_estudiante',
        evaluated_at: new Date().toISOString().slice(0, 10),
        score: '',
        observations: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/execution/performance')
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Docente</label>
                                    <select value={data.teacher_id} onChange={(e) => setData('teacher_id', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Seleccione docente</option>
                                        {teachers.map((teacher) => (
                                            <option key={teacher.id} value={teacher.id}>{teacher.name}</option>
                                        ))}
                                    </select>
                                    {errors.teacher_id && <p className="mt-1 text-sm text-red-600">{errors.teacher_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Periodo Académico</label>
                                    <select value={data.academic_period_id} onChange={(e) => setData('academic_period_id', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        {periods.map((period) => (
                                            <option key={period.id} value={period.id}>{period.name}</option>
                                        ))}
                                    </select>
                                    {errors.academic_period_id && <p className="mt-1 text-sm text-red-600">{errors.academic_period_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fuente</label>
                                    <select value={data.source} onChange={(e) => setData('source', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        {Object.entries(sources).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.source && <p className="mt-1 text-sm text-red-600">{errors.source}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Evaluación</label>
                                    <input type="date" value={data.evaluated_at} onChange={(e) => setData('evaluated_at', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.evaluated_at && <p className="mt-1 text-sm text-red-600">{errors.evaluated_at}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Nota (0 - 20)</label>
                                    <input type="number" value={data.score} onChange={(e) => setData('score', e.target.value)} min="0" max="20" step="0.01" required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.score && <p className="mt-1 text-sm text-red-600">{errors.score}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
                                    <textarea rows="3" value={data.observations} onChange={(e) => setData('observations', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.observations && <p className="mt-1 text-sm text-red-600">{errors.observations}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/execution/performance" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Evaluación'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

PerformanceCreate.layout = (page) => <AppLayout>{page}</AppLayout>
