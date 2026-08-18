import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function EnrollmentPadron({ rows, period, periods }) {
    const groups = Array.isArray(rows) ? [] : Object.entries(rows)

    const changePeriod = (value) => {
        router.get(
            '/enrollment/padron-virtual',
            { academic_period_id: value },
            { preserveState: true, preserveScroll: true, replace: true }
        )
    }

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Padrón Virtual
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Listado Oficial de Matriculados</h2>
                    <p className="text-slate-500">Periodo {period?.name ?? 'sin definir'}</p>
                </div>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4 flex flex-wrap items-center justify-between gap-4">
                    <select
                        value={period?.id ?? ''}
                        onChange={(e) => changePeriod(e.target.value)}
                        className="w-full max-w-sm rounded-lg border-slate-200 text-sm"
                        aria-label="Filtrar por periodo"
                    >
                        {periods.map((p) => (
                            <option key={p.id} value={p.id}>
                                {p.name}
                            </option>
                        ))}
                    </select>
                    <button
                        type="button"
                        onClick={() => window.print()}
                        className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-bold hover:bg-[#343d96] transition-colors flex items-center gap-2"
                    >
                        <span className="material-symbols-outlined text-sm">print</span>
                        Imprimir
                    </button>
                </div>
            </div>

            {groups.map(([subjectName, items]) => (
                <div key={subjectName} className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="px-6 py-4 border-b border-slate-100 bg-slate-50">
                        <h3 className="text-lg font-bold text-navy">{subjectName}</h3>
                        <p className="text-xs text-slate-400">{items.length} matriculados</p>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th className="px-6 py-3">N°</th>
                                    <th className="px-6 py-3">Código</th>
                                    <th className="px-6 py-3">Estudiante</th>
                                    <th className="px-6 py-3">Carrera</th>
                                    <th className="px-6 py-3">Matrícula</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {items.map((row, index) => (
                                    <tr key={row.id}>
                                        <td className="px-6 py-3">{index + 1}</td>
                                        <td className="px-6 py-3">{row.student?.codigo}</td>
                                        <td className="px-6 py-3 font-semibold text-navy">
                                            {row.student?.user?.name ?? `Sin usuario (${row.student?.codigo ?? ''})`}
                                        </td>
                                        <td className="px-6 py-3 text-slate-500">{row.career?.code}</td>
                                        <td className="px-6 py-3 text-slate-500">{row.code}</td>
                                    </tr>
                                ))}
                            </tbody>
                        </table>
                    </div>
                </div>
            ))}

            {groups.length === 0 && (
                <div className="bg-white border-2 border-dashed border-slate-200 rounded-xl p-10 text-center">
                    <p className="text-sm font-bold text-slate-600">No hay matriculados para este periodo</p>
                    <p className="text-xs text-slate-400 mt-1">Registra matrículas para generar el padrón virtual</p>
                </div>
            )}
        </div>
    )
}

EnrollmentPadron.layout = (page) => <AppLayout>{page}</AppLayout>
