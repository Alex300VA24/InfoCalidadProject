import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function ResearchProjectsCreate({ periods, students, advisors, statuses, defaultPeriod }) {
    const { data, setData, post, processing, errors } = useForm({
        student_id: '',
        academic_period_id: defaultPeriod?.id ?? '',
        advisor_id: '',
        title: '',
        description: '',
        area: '',
        score: '',
        start_date: '',
        end_date: '',
        status: 'borrador',
        document: null,
    })

    const submit = (e) => {
        e.preventDefault()
        post('/research', {
            forceFormData: true,
        })
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
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Título del Proyecto</label>
                                    <input type="text" value={data.title} onChange={(e) => setData('title', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.title && <p className="mt-1 text-sm text-red-600">{errors.title}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Asesor</label>
                                    <select value={data.advisor_id} onChange={(e) => setData('advisor_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Sin asesor asignado</option>
                                        {advisors.map((advisor) => (
                                            <option key={advisor.id} value={advisor.id}>{advisor.name}</option>
                                        ))}
                                    </select>
                                    {errors.advisor_id && <p className="mt-1 text-sm text-red-600">{errors.advisor_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Área de Investigación</label>
                                    <input type="text" value={data.area} onChange={(e) => setData('area', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.area && <p className="mt-1 text-sm text-red-600">{errors.area}</p>}
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
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Nota (0-20)</label>
                                    <input type="number" step="0.01" min="0" max="20" value={data.score} onChange={(e) => setData('score', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.score && <p className="mt-1 text-sm text-red-600">{errors.score}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Inicio</label>
                                    <input type="date" value={data.start_date} onChange={(e) => setData('start_date', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.start_date && <p className="mt-1 text-sm text-red-600">{errors.start_date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Fin</label>
                                    <input type="date" value={data.end_date} onChange={(e) => setData('end_date', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.end_date && <p className="mt-1 text-sm text-red-600">{errors.end_date}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                                    <textarea rows="3" value={data.description} onChange={(e) => setData('description', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.description && <p className="mt-1 text-sm text-red-600">{errors.description}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Documento (PDF, DOC, DOCX)</label>
                                    <input
                                        type="file"
                                        accept=".pdf,.doc,.docx"
                                        onChange={(e) => setData('document', e.target.files[0] ?? null)}
                                        className="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-navy file:text-white hover:file:bg-navy/80"
                                    />
                                    {data.document && (
                                        <p className="mt-1 text-xs text-slate-500">{data.document.name} ({(data.document.size / 1024).toFixed(1)} KB)</p>
                                    )}
                                    {errors.document && <p className="mt-1 text-sm text-red-600">{errors.document}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/research" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Proyecto'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

ResearchProjectsCreate.layout = (page) => <AppLayout>{page}</AppLayout>