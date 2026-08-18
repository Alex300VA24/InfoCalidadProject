import { router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'
import ModalLink from '../../../components/Modal/ModalLink'

export default function SocializationsIndex({ socializations, users, subjects, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/execution/socializations', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/execution/socializations', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.career_id || filters.subject_id)

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Ejecución del Plan Curricular</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Socialización de Sílabos</h2>
                    <p className="text-slate-500">Registro de socialización de sílabos por asignatura y carrera.</p>
                </div>
                <ModalLink href="/execution/socializations/create" title="Registrar socialización de sílabo" context="Ejecución del Plan Curricular" icon="school" returnPath="/execution/socializations" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                    <span className="material-symbols-outlined text-lg">school</span>
                    Registrar Socialización
                </ModalLink>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <select value={filters.subject_id ?? ''} onChange={(e) => applyFilter('subject_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todas las asignaturas</option>
                            {subjects.map((subject) => (
                                <option key={subject.id} value={subject.id}>{subject.code} - {subject.name}</option>
                            ))}
                        </select>
                        <select value={filters.career_id ?? ''} onChange={(e) => applyFilter('career_id', e.target.value)} className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todas las carreras</option>
                            <option value="1">No disponible</option>
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
                                <th className="px-6 py-4">Sílabo</th>
                                <th className="px-6 py-4">Fecha</th>
                                <th className="px-6 py-4">Registrado por</th>
                                <th className="px-6 py-4">Observaciones</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {socializations.data.length > 0 ? socializations.data.map((socialization) => (
                                <tr key={socialization.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <span className="font-semibold text-navy">{socialization.syllabus?.subject?.code}</span>
                                        <span className="block text-xs text-slate-400">{socialization.syllabus?.subject?.name}</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{socialization.date}</td>
                                    <td className="px-6 py-4 text-slate-500">{socialization.registered_by?.name ?? '—'}</td>
                                    <td className="px-6 py-4 text-slate-500 max-w-xs truncate">{socialization.notes ?? '—'}</td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={4} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay socializaciones registradas</p>
                                        <p className="text-xs mt-1">Registra la primera socialización</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={socializations.links} />
            </div>
        </div>
    )
}

SocializationsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
