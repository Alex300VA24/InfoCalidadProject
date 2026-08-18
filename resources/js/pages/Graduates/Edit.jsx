import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function GraduatesEdit({ graduate, students, workStatuses }) {
    const { data, setData, put, processing, errors } = useForm({
        student_id: graduate.student_id,
        work_status: graduate.work_status,
        graduation_date: graduate.graduation_date ? String(graduate.graduation_date).slice(0, 10) : '',
        employer: graduate.employer ?? '',
        job_position: graduate.job_position ?? '',
        monthly_income: graduate.monthly_income ?? '',
        employment_relationship: graduate.employment_relationship ?? '',
        survey_date: graduate.survey_date ? String(graduate.survey_date).slice(0, 10) : '',
        observations: graduate.observations ?? '',
    })

    const submit = (e) => {
        e.preventDefault()
        put(`/graduates/${graduate.id}`)
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="flex justify-between items-end mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Editar Egresado</h2>
                        <p className="text-slate-500">{graduate.student?.user?.name ?? graduate.student?.codigo}</p>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Estudiante / Egresado</label>
                                    <select value={data.student_id} onChange={(e) => setData('student_id', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Seleccione estudiante</option>
                                        {students.map((student) => (
                                            <option key={student.id} value={student.id}>{student.codigo} - {student.user?.name}</option>
                                        ))}
                                    </select>
                                    {errors.student_id && <p className="mt-1 text-sm text-red-600">{errors.student_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Situación Laboral</label>
                                    <select value={data.work_status} onChange={(e) => setData('work_status', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        {Object.entries(workStatuses).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.work_status && <p className="mt-1 text-sm text-red-600">{errors.work_status}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Egreso</label>
                                    <input type="date" value={data.graduation_date} onChange={(e) => setData('graduation_date', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.graduation_date && <p className="mt-1 text-sm text-red-600">{errors.graduation_date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Empleador</label>
                                    <input type="text" value={data.employer} onChange={(e) => setData('employer', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.employer && <p className="mt-1 text-sm text-red-600">{errors.employer}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Cargo</label>
                                    <input type="text" value={data.job_position} onChange={(e) => setData('job_position', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.job_position && <p className="mt-1 text-sm text-red-600">{errors.job_position}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Ingreso Mensual (S/)</label>
                                    <input type="number" value={data.monthly_income} onChange={(e) => setData('monthly_income', e.target.value)} min="0" step="0.01" className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.monthly_income && <p className="mt-1 text-sm text-red-600">{errors.monthly_income}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Vínculo Laboral</label>
                                    <input type="text" value={data.employment_relationship} onChange={(e) => setData('employment_relationship', e.target.value)} placeholder="Ej. Planilla, Locación, Tercero" className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.employment_relationship && <p className="mt-1 text-sm text-red-600">{errors.employment_relationship}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Encuesta</label>
                                    <input type="date" value={data.survey_date} onChange={(e) => setData('survey_date', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.survey_date && <p className="mt-1 text-sm text-red-600">{errors.survey_date}</p>}
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
                                    {processing ? 'Guardando...' : 'Guardar Cambios'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

GraduatesEdit.layout = (page) => <AppLayout>{page}</AppLayout>
