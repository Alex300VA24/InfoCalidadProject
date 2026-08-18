import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'
import ModalLink from '../../components/Modal/ModalLink'
import Breadcrumbs from '../../components/Breadcrumbs'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function AgreementsIndex({ agreements, types, statuses, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/mobility/agreements', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/mobility/agreements', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.type || filters.status)

    const statusColors = {
        vigente: 'text-emerald-700 bg-emerald-100 border-emerald-200',
        vencido: 'text-amber-700 bg-amber-100 border-amber-200',
        resuelto: 'text-slate-500 bg-slate-100 border-slate-200',
    }

    return (
        <div className="page-enter">
            <Breadcrumbs
                backHref="/mobility"
                items={[
                    { label: 'Movilidad y Becas', href: '/mobility' },
                    { label: 'Convenios' },
                ]}
            />

            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Movilidad Académica y Becas</span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Convenios de Movilidad</h2>
                    <p className="text-slate-500">Acuerdos nacionales e internacionales con instituciones aliadas.</p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <a href="/mobility" className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">
                        <span className="material-symbols-outlined text-lg">flight_takeoff</span>
                        Solicitudes
                    </a>
                    <ModalLink href="/mobility/agreements/create" title="Registrar convenio" context="Movilidad Académica" icon="handshake" returnPath="/mobility/agreements" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                        <span className="material-symbols-outlined text-lg">handshake</span>
                        Nuevo Convenio
                    </ModalLink>
                </div>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <select value={filters.type ?? ''} onChange={(e) => applyFilter('type', e.target.value)} aria-label="Filtrar por tipo" className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los tipos</option>
                            {Object.entries(types).map(([value, label]) => (
                                <option key={value} value={value}>{label}</option>
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
                                <th className="px-6 py-4">Convenio</th>
                                <th className="px-6 py-4">Institución</th>
                                <th className="px-6 py-4">Tipo</th>
                                <th className="px-6 py-4">Vigencia</th>
                                <th className="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {agreements.data.length > 0 ? agreements.data.map((agreement) => (
                                <tr key={agreement.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4">
                                        <span className="font-semibold">{agreement.name}</span>
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{agreement.institution}</td>
                                    <td className="px-6 py-4 text-slate-500">{agreement.type_label}</td>
                                    <td className="px-6 py-4 text-slate-500">
                                        {formatDate(agreement.start_date)} — {formatDate(agreement.end_date)}
                                    </td>
                                    <td className="px-6 py-4">
                                        <span className={`px-3 py-1 rounded-full text-xs font-bold border ${statusColors[agreement.status] || 'text-slate-700 bg-slate-100 border-slate-200'}`}>
                                            {agreement.status_label}
                                        </span>
                                    </td>
                                </tr>
                            )) : (
                                <tr>
                                    <td colSpan={5} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay convenios registrados</p>
                                        <p className="text-xs mt-1">Registra el primer convenio de movilidad</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={agreements.links} />
            </div>
        </div>
    )
}

AgreementsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
