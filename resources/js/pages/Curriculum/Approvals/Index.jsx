import { useState } from 'react'
import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'
import NativeModal, { prefetchModalPage } from '../../../components/Modal/NativeModal'

const decisionStyle = (report) => {
    if (!report.approval) {
        return 'text-amber-700 bg-amber-100 border-amber-200'
    }
    return report.approval.decision === 'approved'
        ? 'text-emerald-700 bg-emerald-100 border-emerald-200'
        : 'text-red-700 bg-red-100 border-red-200'
}

const decisionLabel = (report) => {
    if (!report.approval) return 'Pendiente'
    return report.approval.decision === 'approved' ? 'Aprobado' : 'Observado'
}

export default function CurriculumApprovalsIndex({ reports }) {
    const [modal, setModal] = useState(null)
    const closeModal = () => setModal(null)

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Aprobaciones
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Aprobaciones de Informes Técnicos</h2>
                    <p className="text-slate-500">Dictamen final del Director de Escuela.</p>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th className="px-6 py-4">Carrera</th>
                                    <th className="px-6 py-4">Periodo</th>
                                    <th className="px-6 py-4">Acción</th>
                                    <th className="px-6 py-4">Preparado por</th>
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {reports.data.map((report) => (
                                    <tr key={report.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4 text-slate-700">
                                            {report.curriculumReview?.career?.name ?? '—'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {report.curriculumReview?.academicPeriod?.name ?? '—'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">
                                            {report.curriculumReview?.actionType?.name ?? '—'}
                                        </td>
                                        <td className="px-6 py-4 text-slate-700">{report.preparer?.name ?? '—'}</td>
                                        <td className="px-6 py-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-xs font-bold border ${decisionStyle(
                                                    report,
                                                )}`}
                                            >
                                                {decisionLabel(report)}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <button
                                                type="button"
                                                onClick={() => setModal({ href: `/curriculum/approvals/${report.id}/review`, title: `${report.approval ? 'Informe' : 'Revisar informe'} #${report.id}` })}
                                                onMouseEnter={() => prefetchModalPage(`/curriculum/approvals/${report.id}/review`)}
                                                onFocus={() => prefetchModalPage(`/curriculum/approvals/${report.id}/review`)}
                                                className="inline-flex items-center gap-1 text-navy hover:text-[#343d96]"
                                                title={report.approval ? 'Ver informe' : 'Revisar informe'}
                                            >
                                                <span className="material-symbols-outlined text-lg">
                                                    {report.approval ? 'visibility' : 'rate_review'}
                                                </span>
                                                <span className="text-sm font-semibold hidden sm:inline">
                                                    {report.approval ? 'Ver' : 'Revisar'}
                                                </span>
                                            </button>
                                        </td>
                                    </tr>
                                ))}
                                {reports.data.length === 0 && (
                                    <tr>
                                        <td colSpan={6} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">
                                                No hay informes pendientes de aprobación
                                            </p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={reports.links} />
                </div>
            </div>
            <NativeModal
                open={Boolean(modal)}
                href={modal?.href ?? ''}
                title={modal?.title ?? ''}
                onClose={closeModal}
                exitPaths={['/curriculum/approvals']}
            />
        </div>
    )
}

CurriculumApprovalsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
