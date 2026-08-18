import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function CommitteeActsCreate({ degreeApplication, actTypes, results }) {
    const { data, setData, post, processing, errors } = useForm({
        act_type: 'sustentacion',
        session_date: new Date().toISOString().slice(0, 10),
        result: '',
        score: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post(`/degrees/applications/${degreeApplication.id}/acts`)
    }

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="flex justify-between items-end mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Registrar Acta de Grado</h2>
                        <p className="text-slate-500">{degreeApplication.code} — {degreeApplication.student?.user?.name ?? degreeApplication.student?.codigo}</p>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Tipo de acta</label>
                                    <select value={data.act_type} onChange={(e) => setData('act_type', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        {Object.entries(actTypes).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.act_type && <p className="mt-1 text-sm text-red-600">{errors.act_type}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Fecha de sesión</label>
                                    <input type="date" value={data.session_date} onChange={(e) => setData('session_date', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.session_date && <p className="mt-1 text-sm text-red-600">{errors.session_date}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Resultado</label>
                                    <select value={data.result} onChange={(e) => setData('result', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                                        <option value="">Pendiente</option>
                                        {Object.entries(results).map(([key, label]) => (
                                            <option key={key} value={key}>{label}</option>
                                        ))}
                                    </select>
                                    {errors.result && <p className="mt-1 text-sm text-red-600">{errors.result}</p>}
                                </div>
                                <div>
                                    <label className="block text-sm font-medium text-slate-700 mb-1">Nota (0 - 20)</label>
                                    <input type="number" value={data.score} onChange={(e) => setData('score', e.target.value)} min="0" max="20" step="0.01" className="w-full rounded-lg border-slate-200 text-sm" />
                                    {errors.score && <p className="mt-1 text-sm text-red-600">{errors.score}</p>}
                                </div>
                            </div>

                            <div className="flex justify-end">
                                <a href={`/degrees/applications/${degreeApplication.id}/acts`} className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">Cancelar</a>
                                <button type="submit" disabled={processing} className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Registrando...' : 'Registrar Acta'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

CommitteeActsCreate.layout = (page) => <AppLayout>{page}</AppLayout>
