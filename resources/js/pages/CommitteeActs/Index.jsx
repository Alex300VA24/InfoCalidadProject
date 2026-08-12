import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function CommitteeActsIndex({ degreeApplication, acts }) {
    return (
        <div className="page-enter">
            <div className="max-w-5xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Actas de Grado</h2>
                        <p className="text-slate-500">{degreeApplication.code} — {degreeApplication.student?.user?.name ?? degreeApplication.student?.codigo}</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a href={`/degrees/applications/${degreeApplication.id}/acts/create`} className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                            <span className="material-symbols-outlined text-lg">edit_note</span>
                            Registrar Acta
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th className="px-6 py-4">Tipo de acta</th>
                                    <th className="px-6 py-4">Fecha de sesión</th>
                                    <th className="px-6 py-4">Resultado</th>
                                    <th className="px-6 py-4">Nota</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {acts.data.length > 0 ? acts.data.map((act) => (
                                    <tr key={act.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4 font-semibold text-navy">{act.act_type_label}</td>
                                        <td className="px-6 py-4 text-slate-500">{formatDate(act.session_date)}</td>
                                        <td className="px-6 py-4">
                                            {act.result ? (
                                                <span className={`px-3 py-1 rounded-full text-xs font-bold border ${act.result === 'aprobado' ? 'text-emerald-700 bg-emerald-100 border-emerald-200' : 'text-red-700 bg-red-100 border-red-200'}`}>
                                                    {act.result_label}
                                                </span>
                                            ) : (
                                                <span className="text-slate-400">—</span>
                                            )}
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{act.score !== null ? `${Number(act.score).toFixed(2)} / 20` : '—'}</td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan={4} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">No hay actas registradas</p>
                                            <p className="text-xs mt-1">Registra la primera acta del expediente</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={acts.links} />
                </div>

                <div className="flex flex-wrap justify-end mt-6">
                    <a href={`/degrees/applications/${degreeApplication.id}`} className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                        <span className="material-symbols-outlined text-lg">arrow_back</span>
                        Volver al expediente
                    </a>
                </div>
            </div>
        </div>
    )
}

CommitteeActsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
