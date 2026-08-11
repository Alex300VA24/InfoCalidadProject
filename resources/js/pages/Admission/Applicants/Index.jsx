import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'

const STATUS_STYLES = {
    ingresante: 'text-emerald-700 bg-emerald-100 border-emerald-200',
    no_ingresante: 'text-red-700 bg-red-100 border-red-200',
    postulante: 'text-amber-700 bg-amber-100 border-amber-200',
}

const STATUS_OPTIONS = [
    ['', 'Todos los estados'],
    ['postulante', 'Postulante'],
    ['ingresante', 'Ingresante'],
    ['no_ingresante', 'No ingresante'],
]

const fullName = (a) => [a.paterno, a.materno, a.nombres].filter(Boolean).join(' ').trim()

export default function ApplicantsIndex({ applicants, processes, careers, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/admission/applicants', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/admission/applicants', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.admission_process_id || filters.career_id || filters.status)

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Admisión
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Postulantes</h2>
                        <p className="text-slate-500">Registro y resultados de postulantes por convocatoria.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a
                            href="/admission/applicants/create"
                            className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all"
                        >
                            <span className="material-symbols-outlined text-lg">person_add</span>
                            Registrar Postulante
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                    <div className="p-4">
                        <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                            <div>
                                <select
                                    value={filters.admission_process_id ?? ''}
                                    onChange={(e) => applyFilter('admission_process_id', e.target.value)}
                                    aria-label="Filtrar por convocatoria"
                                    className="w-full rounded-lg border-slate-200 text-sm"
                                >
                                    <option value="">Todas las convocatorias</option>
                                    {processes.map((process) => (
                                        <option key={process.id} value={process.id}>
                                            {process.name}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <select
                                    value={filters.career_id ?? ''}
                                    onChange={(e) => applyFilter('career_id', e.target.value)}
                                    aria-label="Filtrar por carrera"
                                    className="w-full rounded-lg border-slate-200 text-sm"
                                >
                                    <option value="">Todas las carreras</option>
                                    {careers.map((career) => (
                                        <option key={career.id} value={career.id}>
                                            {career.name}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <div>
                                <select
                                    value={filters.status ?? ''}
                                    onChange={(e) => applyFilter('status', e.target.value)}
                                    aria-label="Filtrar por estado"
                                    className="w-full rounded-lg border-slate-200 text-sm"
                                >
                                    {STATUS_OPTIONS.map(([value, label]) => (
                                        <option key={value} value={value}>
                                            {label}
                                        </option>
                                    ))}
                                </select>
                            </div>
                            <div className="flex items-center gap-2">
                                {hasFilters && (
                                    <button
                                        type="button"
                                        onClick={clearFilters}
                                        className="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors"
                                        title="Quitar filtros"
                                    >
                                        <span className="material-symbols-outlined text-lg">filter_alt_off</span>
                                        Limpiar
                                    </button>
                                )}
                                <p className="text-xs text-slate-400">Los filtros se aplican automáticamente</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                    <div className="overflow-x-auto">
                        <table className="w-full text-left">
                            <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                <tr>
                                    <th className="px-6 py-4">DNI</th>
                                    <th className="px-6 py-4">Apellidos y Nombres</th>
                                    <th className="px-6 py-4">Convocatoria</th>
                                    <th className="px-6 py-4">Carrera</th>
                                    <th className="px-6 py-4">Puntaje</th>
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {applicants.data.map((applicant) => (
                                    <tr key={applicant.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4">{applicant.dni}</td>
                                        <td className="px-6 py-4 font-semibold text-navy">{fullName(applicant)}</td>
                                        <td className="px-6 py-4 text-slate-500">{applicant.admission_process?.name}</td>
                                        <td className="px-6 py-4 text-slate-500">{applicant.career?.code}</td>
                                        <td className="px-6 py-4">{applicant.score ?? '—'}</td>
                                        <td className="px-6 py-4">
                                            <span
                                                className={`px-3 py-1 rounded-full text-xs font-bold border ${
                                                    STATUS_STYLES[applicant.status] ??
                                                    'text-slate-600 bg-slate-100 border-slate-200'
                                                }`}
                                            >
                                                {applicant.status === 'ingresante'
                                                    ? 'Ingresante'
                                                    : applicant.status === 'no_ingresante'
                                                      ? 'No ingresante'
                                                      : 'Postulante'}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right">
                                            <a
                                                href={`/admission/applicants/${applicant.id}`}
                                                title="Ver detalle"
                                                aria-label={`Ver detalle del postulante ${applicant.dni}`}
                                                className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy"
                                            >
                                                <span className="material-symbols-outlined text-lg">visibility</span>
                                            </a>
                                        </td>
                                    </tr>
                                ))}
                                {applicants.data.length === 0 && (
                                    <tr>
                                        <td colSpan={7} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">No hay postulantes</p>
                                            <p className="text-xs mt-1">Registra el primer postulante</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={applicants.links} />
                </div>
            </div>
        </div>
    )
}

ApplicantsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
