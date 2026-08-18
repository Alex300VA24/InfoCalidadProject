import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

export default function ExecutionsCreate({ periods, subjects, teachers, syllabi, statuses, defaultPeriod }) {
    const { data, setData, post, processing, errors } = useForm({
        subject_id: '',
        academic_period_id: defaultPeriod?.id ?? '',
        teacher_id: '',
        syllabus_id: '',
        progress_pct: '0',
        status: 'en_ejecucion',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/execution/executions')
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
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
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Docente</label>
                                    <select value={data.teacher_id} onChange={(e) => setData('teacher_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Seleccione docente</option>
                                        {teachers.map((teacher) => (
                                            <option key={teacher.id} value={teacher.id}>{teacher.name}</option>
                                        ))}
                                    </select>
                                    {errors.teacher_id && <p className="mt-1 text-sm text-red-600">{errors.teacher_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Sílabo</label>
                                    <select value={data.syllabus_id} onChange={(e) => setData('syllabus_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Sin sílabo</option>
                                        {syllabi.map((syllabus) => (
                                            <option key={syllabus.id} value={syllabus.id}>{syllabus.subject?.code} (v{syllabus.version})</option>
                                        ))}
                                    </select>
                                    {errors.syllabus_id && <p className="mt-1 text-sm text-red-600">{errors.syllabus_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Avance (%)</label>
                                    <input type="number" value={data.progress_pct} onChange={(e) => setData('progress_pct', e.target.value)} min="0" max="100" step="0.01" required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.progress_pct && <p className="mt-1 text-sm text-red-600">{errors.progress_pct}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Estado</label>
                                    <select value={data.status} onChange={(e) => setData('status', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        {Object.entries(statuses).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.status && <p className="mt-1 text-sm text-red-600">{errors.status}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/execution/executions" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Ejecución'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

ExecutionsCreate.layout = (page) => <AppLayout>{page}</AppLayout>
