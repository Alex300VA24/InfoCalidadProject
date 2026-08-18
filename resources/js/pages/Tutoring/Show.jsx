import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function TutoringShow({ academicTutoring }) {
    const statusColors = {
        atendida: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        pendiente: 'text-amber-700 bg-amber-100 border-amber-200',
        cancelada: 'text-red-700 bg-red-100 border-red-200',
    }

    const complete = () => {
        router.post(`/tutoring/${academicTutoring.id}/complete`, {}, { preserveScroll: true })
    }

    return (
        <div className="page-enter">
            <div className="max-w-3xl mx-auto px-5 sm:px-8">
                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 className="text-base font-bold text-navy">{academicTutoring.type_label}</h3>
                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[academicTutoring.status] || 'text-slate-700 bg-slate-100 border-slate-200'}`}>
                            {academicTutoring.status_label}
                        </span>
                    </div>
                    <dl className="divide-y divide-slate-100 text-sm">
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Estudiante</dt>
                            <dd className="font-semibold text-navy">{academicTutoring.student?.user?.name ?? '—'}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Código</dt>
                            <dd className="font-semibold">{academicTutoring.student?.codigo}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Periodo</dt>
                            <dd className="font-semibold">{academicTutoring.academic_period?.name}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Tutor</dt>
                            <dd className="font-semibold">{academicTutoring.tutor?.name ?? '—'}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Fecha</dt>
                            <dd className="font-semibold">{formatDate(academicTutoring.tutoring_date)}</dd>
                        </div>
                        {academicTutoring.reason && (
                            <div className="px-6 py-3">
                                <dt className="text-slate-500 mb-1">Motivo</dt>
                                <dd className="font-semibold">{academicTutoring.reason}</dd>
                            </div>
                        )}
                        {academicTutoring.outcome && (
                            <div className="px-6 py-3">
                                <dt className="text-slate-500 mb-1">Resultado / Acuerdos</dt>
                                <dd className="font-semibold">{academicTutoring.outcome}</dd>
                            </div>
                        )}
                    </dl>
                </div>

                <div className="flex justify-between items-center">
                    <a href="/tutoring" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
                    {academicTutoring.status === 'pendiente' && (
                        <button type="button" onClick={complete} className="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-semibold hover:bg-emerald-700 transition-colors">Marcar Atendida</button>
                    )}
                </div>
            </div>
        </div>
    )
}

TutoringShow.layout = (page) => <AppLayout>{page}</AppLayout>
