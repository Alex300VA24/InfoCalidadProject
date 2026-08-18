import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

export default function SocializationsCreate({ syllabi, users }) {
    const { data, setData, post, processing, errors } = useForm({
        syllabus_id: '',
        date: new Date().toISOString().slice(0, 10),
        evidence_path: '',
        notes: '',
        registered_by: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/execution/socializations')
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Sílabo</label>
                                    <select value={data.syllabus_id} onChange={(e) => setData('syllabus_id', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Seleccione sílabo</option>
                                        {syllabi.map((syllabus) => (
                                            <option key={syllabus.id} value={syllabus.id}>{syllabus.subject?.code} - {syllabus.subject?.name} (v{syllabus.version})</option>
                                        ))}
                                    </select>
                                    {errors.syllabus_id && <p className="mt-1 text-sm text-red-600">{errors.syllabus_id}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha</label>
                                    <input type="date" value={data.date} onChange={(e) => setData('date', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.date && <p className="mt-1 text-sm text-red-600">{errors.date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Registrado por</label>
                                    <select value={data.registered_by} onChange={(e) => setData('registered_by', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Usuario actual</option>
                                        {users.map((user) => (
                                            <option key={user.id} value={user.id}>{user.name}</option>
                                        ))}
                                    </select>
                                    {errors.registered_by && <p className="mt-1 text-sm text-red-600">{errors.registered_by}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Evidencia</label>
                                    <input type="text" value={data.evidence_path} onChange={(e) => setData('evidence_path', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.evidence_path && <p className="mt-1 text-sm text-red-600">{errors.evidence_path}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Observaciones</label>
                                    <textarea rows="3" value={data.notes} onChange={(e) => setData('notes', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.notes && <p className="mt-1 text-sm text-red-600">{errors.notes}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/execution/socializations" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Socialización'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

SocializationsCreate.layout = (page) => <AppLayout>{page}</AppLayout>
