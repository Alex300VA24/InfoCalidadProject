import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'
import ModalLink from '../../../components/Modal/ModalLink'

export default function LoadsIndex({ loads, periods, teachers, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/execution/loads', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/execution/loads', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.teacher_id)

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Cargas Académicas</h2>
                    <p className="text-slate-500">Registro de cargas asignadas por periodo y docente.</p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <ModalLink href="/execution/loads/create" title="Registrar carga académica" context="Ejecución del Plan Curricular" icon="assignment_ind" returnPath="/execution/loads" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                        <span className="material-symbols-outlined text-lg">add</span>
                        Registrar Carga
                    </ModalLink>
                </div>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select value={filters.academic_period_id ?? ''} onChange={(e) => applyFilter('academic_period_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los periodos</option>
                            {periods.map((period) => (
                                <option key={period.id} value={period.id}>{period.name}</option>
                            ))}
                        </select>
                        <select value={filters.teacher_id ?? ''} onChange={(e) => applyFilter('teacher_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los docentes</option>
                            {teachers.map((teacher) => (
                                <option key={teacher.id} value={teacher.id}>{teacher.name}</option>
                            ))}
                        </select>
                        <div className="flex items-center gap-2">
                            {hasFilters && (
                                <button type="button" onClick={clearFilters} className="inline-flex items-center gap-1.5 px-3 py-2 text-sm font-semibold text-slate-500 hover:text-navy transition-colors" title="Quitar filtros">
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
                                <th className="px-6 py-4">Docente</th>
                                <th className="px-6 py-4">Asignatura</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Sección</th>
                                <th className="px-6 py-4">Horas</th>
                                <th className="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {loads.data.length > 0 ? loads.data.map((load) => (
                                <tr key={load.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4 font-semibold text-navy">{load.teacher?.name ?? '—'}</td>
                                    <td className="px-6 py-4">
                                        <span className="font-semibold text-navy">{load.subject?.code}</span>
                                        <span className="block text-xs text-slate-400">{load.subject?.name}</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{load.academic_period?.name}</td>
                                    <td className="px-6 py-4 text-slate-500">{load.section ?? '—'}</td>
                                    <td className="px-6 py-4 text-slate-500">{load.hours}</td>
                                    <td className="px-6 py-4">
                                        <span className="px-3 py-1 rounded-full text-xs font-bold border text-blue-700 bg-blue-100 border-blue-200">{load.status_label}</span>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={6} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay cargas registradas</p>
                                        <p className="text-xs mt-1">Registra la primera carga académica</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={loads.links} />
            </div>
        </div>
    )
}

LoadsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
