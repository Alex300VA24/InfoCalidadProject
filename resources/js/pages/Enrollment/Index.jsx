import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'
import ModalLink from '../../components/Modal/ModalLink'

const STATUS_STYLES = {
    matriculado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
    observado: 'text-amber-700 bg-amber-100 border-amber-200',
    retirado: 'text-red-700 bg-red-100 border-red-200',
}

const STATUS_OPTIONS = [
    ['', 'Todos los estados'],
    ['matriculado', 'Matriculado'],
    ['observado', 'Observado'],
    ['retirado', 'Retirado'],
]

const studentName = (student) => student?.user?.name ?? `Sin usuario (${student?.codigo ?? ''})`

export default function EnrollmentIndex({ enrollments, periods, careers, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/enrollment', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/enrollment', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.career_id || filters.status)

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Matrícula
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Matrículas</h2>
                    <p className="text-slate-500">Registra matrículas, emite fichas, órdenes de pago y el padrón virtual.</p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <a
                        href="/enrollment/padron-virtual"
                        className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors"
                    >
                        <span className="material-symbols-outlined text-lg">groups</span>
                        Padrón Virtual
                    </a>
                    <ModalLink
                        href="/enrollment/create"
                        title="Registrar matrícula"
                        context="Gestión de Matrícula"
                        icon="how_to_reg"
                        returnPath="/enrollment"
                        className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all"
                    >
                        <span className="material-symbols-outlined text-lg">how_to_reg</span>
                        Nueva Matrícula
                    </ModalLink>
                </div>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <select
                                value={filters.academic_period_id ?? ''}
                                onChange={(e) => applyFilter('academic_period_id', e.target.value)}
                                aria-label="Filtrar por periodo"
                                className="w-full rounded-lg border-slate-200 text-sm"
                            >
                                <option value="">Todos los periodos</option>
                                {periods.map((period) => (
                                    <option key={period.id} value={period.id}>
                                        {period.name}
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
                                <th className="px-6 py-4">Código</th>
                                <th className="px-6 py-4">Estudiante</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Carrera</th>
                                <th className="px-6 py-4">Asignaturas</th>
                                <th className="px-6 py-4">Estado</th>
                                <th className="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {enrollments.data.map((enrollment) => (
                                <tr key={enrollment.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4 font-bold text-navy">{enrollment.code}</td>
                                    <td className="px-6 py-4">
                                        <span className="font-semibold">{studentName(enrollment.student)}</span>
                                        <span className="block text-xs text-slate-400">{enrollment.student?.codigo}</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{enrollment.academic_period?.name}</td>
                                    <td className="px-6 py-4 text-slate-500">{enrollment.career?.code}</td>
                                    <td className="px-6 py-4">{enrollment.subjects_count}</td>
                                    <td className="px-6 py-4">
                                        <span
                                            className={`px-3 py-1 rounded-full text-xs font-bold border ${
                                                STATUS_STYLES[enrollment.status] ?? 'text-slate-600 bg-slate-100 border-slate-200'
                                            }`}
                                        >
                                            {enrollment.status.charAt(0).toUpperCase() + enrollment.status.slice(1)}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <ModalLink
                                            href={`/enrollment/${enrollment.id}`}
                                            title="Detalle de matrícula"
                                            context="Gestión de Matrícula"
                                            icon="how_to_reg"
                                            returnPath="/enrollment"
                                            title="Ver detalle"
                                            aria-label={`Ver detalle de la matrícula ${enrollment.code}`}
                                            className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy"
                                        >
                                            <span className="material-symbols-outlined text-lg">visibility</span>
                                        </ModalLink>
                                    </td>
                                </tr>
                            ))}
                            {enrollments.data.length === 0 && (
                                <tr>
                                    <td colSpan={7} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay matrículas</p>
                                        <p className="text-xs mt-1">Registra la primera matrícula</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={enrollments.links} />
            </div>
        </div>
    )
}

EnrollmentIndex.layout = (page) => <AppLayout>{page}</AppLayout>
