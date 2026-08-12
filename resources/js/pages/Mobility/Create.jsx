import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function MobilityCreate({ periods, students, types, statuses, defaultPeriod }) {
    const { data, setData, post, processing, errors } = useForm({
        student_id: '',
        academic_period_id: defaultPeriod?.id ?? '',
        type: 'movilidad_nacional',
        application_date: new Date().toISOString().slice(0, 10),
        destination_institution: '',
        program_name: '',
        scholarship_name: '',
        status: 'en_evaluacion',
        start_date: '',
        end_date: '',
        notes: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/mobility')
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
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                                    <select value={data.type} onChange={(e) => setData('type', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        {Object.entries(types).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.type && <p className="mt-1 text-sm text-red-600">{errors.type}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Solicitud</label>
                                    <input type="date" value={data.application_date} onChange={(e) => setData('application_date', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.application_date && <p className="mt-1 text-sm text-red-600">{errors.application_date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Institución de Destino</label>
                                    <input type="text" value={data.destination_institution} onChange={(e) => setData('destination_institution', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.destination_institution && <p className="mt-1 text-sm text-red-600">{errors.destination_institution}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Programa</label>
                                    <input type="text" value={data.program_name} onChange={(e) => setData('program_name', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.program_name && <p className="mt-1 text-sm text-red-600">{errors.program_name}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Nombre de la Beca</label>
                                    <input type="text" value={data.scholarship_name} onChange={(e) => setData('scholarship_name', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.scholarship_name && <p className="mt-1 text-sm text-red-600">{errors.scholarship_name}</p>}
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
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
                                    <textarea rows="3" value={data.notes} onChange={(e) => setData('notes', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.notes && <p className="mt-1 text-sm text-red-600">{errors.notes}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/mobility" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Solicitud'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

MobilityCreate.layout = (page) => <AppLayout>{page}</AppLayout>