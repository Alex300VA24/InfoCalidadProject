import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function AgreementsCreate({ types, statuses }) {
    const { data, setData, post, processing, errors } = useForm({
        name: '',
        institution: '',
        type: 'nacional',
        description: '',
        start_date: '',
        end_date: '',
        status: 'vigente',
        document_path: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/mobility/agreements')
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Nombre del Convenio</label>
                                    <input type="text" value={data.name} onChange={(e) => setData('name', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.name && <p className="mt-1 text-sm text-red-600">{errors.name}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Institución</label>
                                    <input type="text" value={data.institution} onChange={(e) => setData('institution', e.target.value)} required className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.institution && <p className="mt-1 text-sm text-red-600">{errors.institution}</p>}
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
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Inicio</label>
                                    <input type="date" value={data.start_date} onChange={(e) => setData('start_date', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.start_date && <p className="mt-1 text-sm text-red-600">{errors.start_date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de Fin</label>
                                    <input type="date" value={data.end_date} onChange={(e) => setData('end_date', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.end_date && <p className="mt-1 text-sm text-red-600">{errors.end_date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">URL del Documento</label>
                                    <input type="text" value={data.document_path} onChange={(e) => setData('document_path', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" placeholder="http://..." />
                                    {errors.document_path && <p className="mt-1 text-sm text-red-600">{errors.document_path}</p>}
                                </div>
                                <div className="md:col-span-2">
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Descripción</label>
                                    <textarea rows="3" value={data.description} onChange={(e) => setData('description', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.description && <p className="mt-1 text-sm text-red-600">{errors.description}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href="/mobility/agreements" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Convenio'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

AgreementsCreate.layout = (page) => <AppLayout>{page}</AppLayout>