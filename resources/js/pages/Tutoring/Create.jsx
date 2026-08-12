import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function TutoringCreate({ periods, students, tutors, types, statuses, defaultPeriod }) {
    const { data, setData, post, processing, errors } = useForm({
        student_id: '',
        academic_period_id: defaultPeriod?.id ?? '',
        tutor_id: '',
        tutoring_date: new Date().toISOString().slice(0, 10),
        type: 'acompanamiento',
        status: 'pendiente',
        reason: '',
        outcome: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/tutoring')
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
                                            <option key={student.id} value={student.id}>{student.codigo} - {student.user?.name}</option>
                                        ))}
                                    </select>
                                    {errors.student_id && <p className="mt-1 text-sm text-red-600">{errors.student_id}</p>}
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
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Tutor</label>
                                    <select value={data.tutor_id} onChange={(e) => setData('tutor_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Tutor actual</option>
                                        {tutors.map((tutor) => (
                                            <option key={tutor.id} value={tutor.id}>{tutor.name}</option>
                                        ))}
                                    </select>
                                    {errors.tutor_id && <p className="mt-1 text-sm text-red-600">{errors.tutor_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de la Tutoría</label>
                                    <input type="date" value={data.tutoring_date} onChange={(e) => setData('tutoring_date', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.tutoring_date && <p className="mt-1 text-sm text-red-600">{errors.tutoring_date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                                    <select value={data.type} onChange={(e) => setData('type', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        {Object.entries(types).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.type && <p className="mt-1 text-sm text-red-600">{errors.type}</p>}
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
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Motivo</label>
                                    <textarea rows="3" value={data.reason} onChange={(e) => setData('reason', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.reason && <p className="mt-1 text-sm text-red-600">{errors.reason}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Resultado / Acuerdos</label>
                                    <textarea rows="3" value={data.outcome} onChange={(e) => setData('outcome', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.outcome && <p className="mt-1 text-sm text-red-600">{errors.outcome}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/tutoring" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Tutoría'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

TutoringCreate.layout = (page) => <AppLayout>{page}</AppLayout>
