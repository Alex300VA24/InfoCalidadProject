import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'

const STATUS_STYLES = {
    abierto: 'text-emerald-700 bg-emerald-100 border-emerald-200',
    cerrado: 'text-slate-600 bg-slate-100 border-slate-200',
    borrador: 'text-amber-700 bg-amber-100 border-amber-200',
    activo: 'text-emerald-700 bg-emerald-100 border-emerald-200',
}

const STATUS_LABELS = {
    abierto: 'Abierto',
    cerrado: 'Cerrado',
    borrador: 'Borrador',
    activo: 'Activo',
}

const ingresantesCount = (process) => {
    if (typeof process.ingresantes !== 'undefined') {
        return Number(process.ingresantes)
    }
    return 0
}

const coveragePercentage = (process) => {
    const vacancies = Number(process.vacancies) ?? 0
    if (vacancies <= 0) return 0
    return Math.round((ingresantesCount(process) / vacancies) * 10000) / 100
}

export default function AdmissionProcessesIndex({ processes }) {
    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Gestión del Ingreso
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Convocatorias de Admisión</h2>
                        <p className="text-slate-500">Administra las convocatorias, vacantes y resultados de postulantes.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a
                            href="/admission/processes/create"
                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all"
                        >
                            <span className="material-symbols-outlined text-lg">add</span>
                            Nueva Convocatoria
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th className="px-6 py-4">Convocatoria</th>
                                    <th className="px-6 py-4">Periodo</th>
                                    <th className="px-6 py-4">Carrera</th>
                                    <th className="px-6 py-4">Vacantes</th>
                                    <th className="px-6 py-4">Ingresantes</th>
                                    <th className="px-6 py-4">Cobertura</th>
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {processes.data.map((process) => {
                                    const ingresantes = ingresantesCount(process)
                                    const coverage = coveragePercentage(process)
                                    return (
                                        <tr key={process.id} className="hover:bg-slate-50 transition-colors">
                                            <td className="px-6 py-4">
                                                <a href={`/admission/processes/${process.id}`} className="font-bold text-navy hover:underline">
                                                    {process.name}
                                                </a>
                                                <div className="text-xs text-slate-400">{process.modality}</div>
                                            </td>
                                            <td className="px-6 py-4 text-slate-500">{process.academic_period?.name}</td>
                                            <td className="px-6 py-4 text-slate-500">{process.career?.code}</td>
                                            <td className="px-6 py-4">{process.vacancies}</td>
                                            <td className="px-6 py-4">{ingresantes}</td>
                                            <td className="px-6 py-4">
                                                <div className="flex items-center gap-2">
                                                    <div className="w-24 h-1.5 bg-slate-200 rounded-full overflow-hidden">
                                                        <div
                                                            className={`h-full ${coverage >= 100 ? 'bg-emerald-500' : 'bg-navy'}`}
                                                            style={{ width: `${Math.min(coverage, 100)}%` }}
                                                        ></div>
                                                    </div>
                                                    <span className="text-xs font-bold">{coverage}%</span>
                                                </div>
                                            </td>
                                            <td className="px-6 py-4">
                                                <span
                                                    className={`px-3 py-1 rounded-full text-xs font-bold border ${
                                                        STATUS_STYLES[process.status] ?? 'text-slate-600 bg-slate-100 border-slate-200'
                                                    }`}
                                                >
                                                    {STATUS_LABELS[process.status] ?? process.status}
                                                </span>
                                            </td>
                                            <td className="px-6 py-4 text-right">
                                                <a
                                                    href={`/admission/processes/${process.id}`}
                                                    className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy"
                                                    title="Ver"
                                                >
                                                    <span className="material-symbols-outlined text-lg">visibility</span>
                                                </a>
                                                <a
                                                    href={`/admission/processes/${process.id}/edit`}
                                                    className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy"
                                                    title="Editar"
                                                >
                                                    <span className="material-symbols-outlined text-lg">edit</span>
                                                </a>
                                            </td>
                                        </tr>
                                    )
                                })}
                                {processes.data.length === 0 && (
                                    <tr>
                                        <td colSpan={8} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">No hay convocatorias</p>
                                            <p className="text-xs mt-1">Crea la primera convocatoria de admisión</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={processes.links} />
                </div>
            </div>
        </div>
    )
}

AdmissionProcessesIndex.layout = (page) => <AppLayout>{page}</AppLayout>
