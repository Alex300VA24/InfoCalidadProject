import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

const capitalize = (value) => {
    if (!value) return '—'
    return value.charAt(0).toUpperCase() + value.slice(1)
}

export default function CertificatesIndex({ certificates, types, students, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/degrees/certificates', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/degrees/certificates', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.type || filters.student_id)

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Certificados</h2>
                        <p className="text-slate-500">Emisión de certificados de estudios, prácticas y constancias.</p>
                    </div>
                    <div className="flex flex-wrap gap-2">
                        <a href="/degrees/certificates/create" className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">
                            <span className="material-symbols-outlined text-lg">workspace_premium</span>
                            Emitir Certificado
                        </a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                    <div className="p-4">
                        <div className="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <select value={filters.type ?? ''} onChange={(e) => applyFilter('type', e.target.value)} aria-label="Filtrar por tipo" className="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los tipos</option>
                                {Object.entries(types).map(([value, label]) => (
                                    <option key={value} value={value}>{label}</option>
                                ))}
                            </select>
                            <select value={filters.student_id ?? ''} onChange={(e) => applyFilter('student_id', e.target.value)} aria-label="Filtrar por estudiante" className="w-full rounded-lg border-slate-200 text-sm">
                                <option value="">Todos los estudiantes</option>
                                {students.map((student) => (
                                    <option key={student.id} value={student.id}>{student.codigo} - {student.user?.name}</option>
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
                                    <th className="px-6 py-4">Código</th>
                                    <th className="px-6 py-4">Estudiante</th>
                                    <th className="px-6 py-4">Tipo</th>
                                    <th className="px-6 py-4">Concepto</th>
                                    <th className="px-6 py-4">Emisión</th>
                                    <th className="px-6 py-4">Estado</th>
                                    <th className="px-6 py-4 text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody className="text-sm divide-y divide-slate-100">
                                {certificates.data.length > 0 ? certificates.data.map((certificate) => (
                                    <tr key={certificate.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4 font-bold text-navy">{certificate.code}</td>
                                        <td className="px-6 py-4">
                                            <span className="font-semibold">{certificate.student?.user?.name ?? certificate.student?.codigo}</span>
                                            <span className="block text-xs text-slate-400">{certificate.student?.codigo}</span>
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{certificate.type_label}</td>
                                        <td className="px-6 py-4 max-w-xs truncate">{certificate.concept}</td>
                                        <td className="px-6 py-4 text-slate-500">{formatDate(certificate.issued_at)}</td>
                                        <td className="px-6 py-4">
                                            <span className="px-3 py-1 rounded-full text-xs font-bold border text-emerald-700 bg-emerald-100 border-emerald-200">
                                                {capitalize(certificate.status)}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-right whitespace-nowrap">
                                            <a href={`/degrees/certificates/${certificate.id}/download`} data-turbo="false" className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy" title="Descargar">
                                                <span className="material-symbols-outlined text-lg">download</span>
                                            </a>
                                            <a href={`/degrees/certificates/${certificate.id}`} title="Ver detalle" aria-label="Ver detalle del certificado" className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                                <span className="material-symbols-outlined text-lg">visibility</span>
                                            </a>
                                        </td>
                                    </tr>
                                )) : (
                                    <tr>
                                        <td colSpan={7} className="px-6 py-10 text-center text-slate-400">
                                            <p className="text-sm font-bold text-slate-600">No hay certificados emitidos</p>
                                            <p className="text-xs mt-1">Emite el primer certificado</p>
                                        </td>
                                    </tr>
                                )}
                            </tbody>
                        </table>
                    </div>
                </div>

                <div className="mt-6">
                    <Pagination links={certificates.links} />
                </div>
            </div>
        </div>
    )
}

CertificatesIndex.layout = (page) => <AppLayout>{page}</AppLayout>
