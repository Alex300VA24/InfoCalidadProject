import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function CertificatesCreate({ students, types }) {
    const { data, setData, post, processing, errors } = useForm({
        student_id: '',
        type: '',
        concept: '',
        issued_at: new Date().toISOString().slice(0, 10),
        issued_by: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/degrees/certificates')
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="flex justify-between items-end mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Emitir Certificado</h2>
                        <p className="text-slate-500">Emite un certificado de estudios o constancia para un estudiante.</p>
                    </div>
                </div>

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
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Tipo</label>
                                    <select value={data.type} onChange={(e) => setData('type', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Seleccione tipo</option>
                                        {Object.entries(types).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.type && <p className="mt-1 text-sm text-red-600">{errors.type}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Concepto / Detalle</label>
                                    <textarea value={data.concept} onChange={(e) => setData('concept', e.target.value)} rows="3" required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.concept && <p className="mt-1 text-sm text-red-600">{errors.concept}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Emisión</label>
                                    <input type="date" value={data.issued_at} onChange={(e) => setData('issued_at', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.issued_at && <p className="mt-1 text-sm text-red-600">{errors.issued_at}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Emitido por</label>
                                    <input type="text" value={data.issued_by} onChange={(e) => setData('issued_by', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.issued_by && <p className="mt-1 text-sm text-red-600">{errors.issued_by}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/degrees/certificates" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Emitiendo...' : 'Emitir Certificado'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

CertificatesCreate.layout = (page) => <AppLayout>{page}</AppLayout>
