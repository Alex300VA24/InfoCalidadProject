import { useState } from 'react'
import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'
import ModalLink from '../../components/Modal/ModalLink'

export default function RemedialProgramsIndex({ programs, periods, statuses, filters }) {
    const [updatingId, setUpdatingId] = useState(null)

    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/tutoring/remedial', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/tutoring/remedial', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const updateStatus = (program, value) => {
        if (value === program.status || updatingId) return
        setUpdatingId(program.id)
        router.post(`/tutoring/remedial/${program.id}/status`, { status: value }, {
            preserveScroll: true,
            onFinish: () => setUpdatingId(null),
        })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.status)

    const statusColors = {
        completado: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        reprobado: 'text-red-700 bg-red-100 border-red-200',
        en_curso: 'text-blue-700 bg-blue-100 border-blue-200',
        pendiente: 'text-amber-700 bg-amber-100 border-amber-200',
    }

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Tutoría Académica</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Nivelación y Recuperación</h2>
                    <p className="text-slate-500">Programas de nivelación y recuperación académica.</p>
                </div>
                <ModalLink href="/tutoring/remedial/create" title="Registrar programa de nivelación" context="Tutoría Académica" icon="school" returnPath="/tutoring/remedial" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span className="material-symbols-outlined text-lg">add</span>
                    Registrar Programa
                </ModalLink>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select value={filters.academic_period_id ?? ''} onChange={(e) => applyFilter('academic_period_id', e.target.value)} aria-label="Filtrar por periodo" className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los periodos</option>
                            {periods.map((period) => (
                                <option key={period.id} value={period.id}>{period.name}</option>
                            ))}
                        </select>
                        <select value={filters.status ?? ''} onChange={(e) => applyFilter('status', e.target.value)} aria-label="Filtrar por estado" className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los estados</option>
                            {Object.entries(statuses).map(([value, label]) => (
                                <option key={value} value={value}>{label}</option>
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
                                <th className="px-6 py-4">Estudiante</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Asignatura</th>
                                <th className="px-6 py-4">Descripción</th>
                                <th className="px-6 py-4">Estado</th>
                                <th className="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {programs.data.length > 0 ? programs.data.map((program) => (
                                <tr key={program.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <span className="font-semibold text-navy">{program.student?.user?.name ?? '—'}</span>
                                        <span className="block text-xs text-slate-400">{program.student?.codigo}</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{program.academic_period?.name}</td>
                                    <td className="px-6 py-4 text-slate-500">{program.subject ? `${program.subject.code} - ${program.subject.name}` : '—'}</td>
                                    <td className="px-6 py-4 text-slate-500 max-w-xs truncate">{program.description ?? '—'}</td>
                                    <td className="px-6 py-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[program.status] || 'text-slate-700 bg-slate-100 border-slate-200'}`}>
                                            {program.status_label}
                                        </span>
                                    </td>
                                    <td className="px-6 py-4 text-right">
                                        <select value={program.status} onChange={(e) => updateStatus(program, e.target.value)} disabled={updatingId === program.id} aria-label="Cambiar estado" className="text-xs rounded-lg border-slate-200">
                                            {Object.entries(statuses).map(([value, label]) => (
                                                <option key={value} value={value}>{label}</option>
                                            ))}
                                        </select>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={6} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay programas de nivelación</p>
                                        <p className="text-xs mt-1">Registra el primer programa</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={programs.links} />
            </div>
        </div>
    )
}

RemedialProgramsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
