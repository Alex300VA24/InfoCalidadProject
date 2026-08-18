import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function DegreeApplicationsShow({ degreeApplication, statuses }) {
    const { data, setData, post, processing, errors } = useForm({
        status: degreeApplication.status,
        resolution_number: degreeApplication.resolution_number ?? '',
        resolution_date: degreeApplication.resolution_date ? String(degreeApplication.resolution_date).slice(0, 10) : '',
    })

    const submit = (e) => {
        e.preventDefault()
        post(`/degrees/applications/${degreeApplication.id}/status`)
    }

    const statusColors = {
        aprobado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        otorgado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        observado: 'text-red-700 bg-red-100 border-red-200',
    }

    return (
        <div className="page-enter">
            <div className="max-w-3xl mx-auto px-5 sm:px-8">
                <div className="flex justify-between items-end mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Detalle de Expediente</h2>
                        <p className="text-slate-500">{degreeApplication.code} — {degreeApplication.type_label}</p>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 className="text-base font-bold text-navy">{degreeApplication.type_label}</h3>
                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[degreeApplication.status] || 'text-amber-700 bg-amber-100 border-amber-200'}`}>
                            {degreeApplication.status_label}
                        </span>
                    </div>
                    <dl className="divide-y divide-slate-100 text-sm">
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Expediente</dt>
                            <dd className="font-semibold text-navy">{degreeApplication.code}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Estudiante</dt>
                            <dd className="font-semibold">{degreeApplication.student?.user?.name ?? degreeApplication.student?.codigo}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Código</dt>
                            <dd className="font-semibold">{degreeApplication.student?.codigo}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Fecha de solicitud</dt>
                            <dd className="font-semibold">{formatDate(degreeApplication.application_date)}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Asesor</dt>
                            <dd className="font-semibold">{degreeApplication.advisor?.name ?? '—'}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Resolución</dt>
                            <dd className="font-semibold">
                                {degreeApplication.resolution_number ?? '—'}
                                {degreeApplication.resolution_date ? ` (${formatDate(degreeApplication.resolution_date)})` : ''}
                            </dd>
                        </div>
                        {degreeApplication.thesis_title && (
                            <div className="px-6 py-3">
                                <dt className="text-slate-500 mb-1">Título de la tesis</dt>
                                <dd className="font-semibold">{degreeApplication.thesis_title}</dd>
                            </div>
                        )}
                        {degreeApplication.notes && (
                            <div className="px-6 py-3">
                                <dt className="text-slate-500 mb-1">Observaciones</dt>
                                <dd className="font-semibold">{degreeApplication.notes}</dd>
                            </div>
                        )}
                    </dl>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="px-6 py-4 border-b border-slate-100">
                        <h3 className="text-base font-bold text-navy">Actualizar Estado</h3>
                    </div>
                    <div className="p-6">
                        <form onSubmit={submit} className="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            <select value={data.status} onChange={(e) => setData('status', e.target.value)} className="rounded-lg border-slate-200 text-sm">
                                {Object.entries(statuses).map(([key, label]) => (
                                    <option key={key} value={key}>{label}</option>
                                ))}
                            </select>
                            <input type="text" value={data.resolution_number} onChange={(e) => setData('resolution_number', e.target.value)} placeholder="N° de resolución" className="rounded-lg border-slate-200 text-sm" />
                            <input type="date" value={data.resolution_date} onChange={(e) => setData('resolution_date', e.target.value)} className="rounded-lg border-slate-200 text-sm" />
                            <div className="flex items-end">
                                <button type="submit" disabled={processing} className="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors">
                                    {processing ? 'Actualizando...' : 'Actualizar'}
                                </button>
                            </div>
                        </form>
                        {errors.status && <p className="mt-1 text-sm text-red-600">{errors.status}</p>}
                        {errors.resolution_number && <p className="mt-1 text-sm text-red-600">{errors.resolution_number}</p>}
                        {errors.resolution_date && <p className="mt-1 text-sm text-red-600">{errors.resolution_date}</p>}
                    </div>
                </div>

                <div className="flex justify-end">
                    <a href={`/degrees/applications/${degreeApplication.id}/acts`} className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold mr-2 hover:bg-slate-50 transition-colors">
                        <span className="material-symbols-outlined text-base align-text-bottom mr-1">description</span>
                        Actas de grado
                    </a>
                    <a href="/degrees/applications" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
                </div>
            </div>
        </div>
    )
}

DegreeApplicationsShow.layout = (page) => <AppLayout>{page}</AppLayout>
