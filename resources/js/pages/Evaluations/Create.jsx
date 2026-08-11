import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function EvaluationsCreate({ periods, subjects, students, types, defaultPeriod }) {
    const { data, setData, post, processing, errors } = useForm({
        student_id: '',
        subject_id: '',
        academic_period_id: defaultPeriod?.id ?? '',
        evaluation_type: '',
        score: '',
        evaluation_date: new Date().toISOString().slice(0, 10),
        observations: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/evaluations')
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Estudiante</label>
                                    <select value={data.student_id} onChange={(e) => setData('student_id', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Seleccione estudiante</option>
                                        {students.map((student) => (
                                            <option key={student.id} value={student.id}>{student.codigo} - {student.user?.name ?? 'Sin usuario'}</option>
                                        ))}
                                    </select>
                                    {errors.student_id && <p className="mt-1 text-sm text-red-600">{errors.student_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Asignatura</label>
                                    <select value={data.subject_id} onChange={(e) => setData('subject_id', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Seleccione asignatura</option>
                                        {subjects.map((subject) => (
                                            <option key={subject.id} value={subject.id}>{subject.code} - {subject.name}</option>
                                        ))}
                                    </select>
                                    {errors.subject_id && <p className="mt-1 text-sm text-red-600">{errors.subject_id}</p>}
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
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Tipo de Evaluación</label>
                                    <select value={data.evaluation_type} onChange={(e) => setData('evaluation_type', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Seleccione tipo</option>
                                        {Object.entries(types).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.evaluation_type && <p className="mt-1 text-sm text-red-600">{errors.evaluation_type}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Nota (0-20)</label>
                                    <input type="number" value={data.score} onChange={(e) => setData('score', e.target.value)} min="0" max="20" step="0.01" required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.score && <p className="mt-1 text-sm text-red-600">{errors.score}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Evaluación</label>
                                    <input type="date" value={data.evaluation_date} onChange={(e) => setData('evaluation_date', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.evaluation_date && <p className="mt-1 text-sm text-red-600">{errors.evaluation_date}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
                                    <textarea rows="3" value={data.observations} onChange={(e) => setData('observations', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.observations && <p className="mt-1 text-sm text-red-600">{errors.observations}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/evaluations" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
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

EvaluationsCreate.layout = (page) => <AppLayout>{page}</AppLayout>
