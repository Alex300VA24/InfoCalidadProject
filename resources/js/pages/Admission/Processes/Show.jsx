import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

const STATUS_STYLES = {
    abierto: 'text-emerald-700 bg-emerald-100 border-emerald-200',
    cerrado: 'text-slate-600 bg-slate-100 border-slate-200',
    borrador: 'text-amber-700 bg-amber-100 border-amber-200',
    activo: 'text-emerald-700 bg-emerald-100 border-emerald-200',
}

const APPLICANT_STATUS_STYLES = {
    ingresante: 'text-emerald-700 bg-emerald-100 border-emerald-200',
    no_ingresante: 'text-red-700 bg-red-100 border-red-200',
    postulante: 'text-amber-700 bg-amber-100 border-amber-200',
}

const APPLICANT_STATUS_LABELS = {
    ingresante: 'Ingresante',
    no_ingresante: 'No ingresante',
    postulante: 'Postulante',
}

const fullName = (a) => [a.paterno, a.materno, a.nombres].filter(Boolean).join(' ').trim()

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

const statusLabel = (status) => {
    const labels = {
        abierto: 'Abierto',
        cerrado: 'Cerrado',
        borrador: 'Borrador',
        activo: 'Activo',
    }
    return labels[status] ?? status
}

export default function AdmissionProcessesShow({ process }) {
    const totalApplicants = Number(process.total_applicants) ?? 0
    const ingresantes = ingresantesCount(process)
    const coverage = coveragePercentage(process)

    const handleFinalize = (e) => {
        e.preventDefault()
        router.post(`/admission/processes/${process.id}/finalize`, {}, { preserveScroll: true })
    }

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Cuadro de Vacantes · F1
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">{process.name}</h2>
                        <p className="text-slate-500">
                            {process.career?.name} · {process.academic_period?.name}
                        </p>
                    </div>
                    <div className="flex flex-wrap gap-3">
                        {process.status !== 'cerrado' && (
                            <form onSubmit={handleFinalize} className="inline">
                                <button
                                    type="submit"
                                    className="px-4 py-2 bg-navy text-white rounded-lg text-sm font-semibold hover:bg-[#343d96] transition-colors"
                                >
                                    {process.status === 'borrador' ? 'Abrir Convocatoria' : 'Cerrar Convocatoria'}
                                </button>
                            </form>
                        )}
                        <a
                            href="/admission/applicants/create"
                            className="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all"
                        >
                            + Registrar Postulante
                        </a>
                        <a
                            href={`/admission/processes/${process.id}/edit`}
                            className="px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors"
                        >
                            Editar
                        </a>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                    <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                        <div className="text-3xl font-bold text-navy">{process.vacancies}</div>
                        <div className="text-sm text-slate-500 font-medium mt-1">Vacantes</div>
                    </div>
                    <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                        <div className="text-3xl font-bold text-navy">{totalApplicants}</div>
                        <div className="text-sm text-slate-500 font-medium mt-1">Postulantes</div>
                    </div>
                    <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                        <div className="text-3xl font-bold text-emerald-600">{ingresantes}</div>
                        <div className="text-sm text-slate-500 font-medium mt-1">Ingresantes</div>
                    </div>
                    <div className="bg-white border border-slate-200 rounded-xl p-6 shadow-sm">
                        <div className="text-3xl font-bold text-navy">{coverage}%</div>
                        <div className="text-sm text-slate-500 font-medium mt-1">Cobertura de vacantes</div>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 className="text-xl font-semibold text-navy">Postulantes</h3>
                        <span className="text-xs bg-navy/10 text-navy px-3 py-1 rounded-full">{process.modality}</span>
                    </div>
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th className="px-6 py-4">DNI</th>
                                    <th className="px-6 py-4">Apellidos y Nombres</th>
                                    <th className="px-6 py-4">Puntaje</th>
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {process.applicants.map((applicant) => (
                                    <tr key={applicant.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4">{applicant.dni}</td>
                                        <td className="px-6 py-4 font-semibold text-navy">{fullName(applicant)}</td>
                                        <td className="px-6 py-4">{applicant.score ?? '—'}</td>
                                        <td className="px-6 py-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-xs font-bold border ${
                                                    APPLICANT_STATUS_STYLES[applicant.status] ??
                                                    'text-slate-600 bg-slate-100 border-slate-200'
                                                }`}
                                            >
                                                {APPLICANT_STATUS_LABELS[applicant.status] ?? applicant.status}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <a
                                                href={`/admission/applicants/${applicant.id}`}
                                                className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy"
                                            >
                                                <span className="material-symbols-outlined text-lg">visibility</span>
                                            </a>
                                        </td>
                                    </tr>
                                ))}
                                {process.applicants.length === 0 && (
                                    <tr>
                                        <td colSpan={5} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">Aún no hay postulantes</p>
                                            <p className="text-xs mt-1">Registra el primer postulante de esta convocatoria</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    )
}

AdmissionProcessesShow.layout = (page) => <AppLayout>{page}</AppLayout>
