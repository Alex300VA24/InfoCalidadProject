import { router } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'
import Pagination from '../../components/Pagination'
import ModalLink from '../../components/Modal/ModalLink'

const scoreBadge = (score) => {
    const value = Number(score)
    if (Number.isNaN(value)) return 'text-slate-600 bg-slate-100 border-slate-200'
    if (value >= 14) return 'text-emerald-700 bg-emerald-100 border-emerald-200'
    if (value >= 10) return 'text-amber-700 bg-amber-100 border-amber-200'
    return 'text-red-700 bg-red-100 border-red-200'
}

export default function EvaluationsIndex({ evaluations, periods, subjects, students, filters }) {
    const query = (extra = {}) => {
        const params = { ...filters }
        Object.keys(extra).forEach((key) => {
            if (extra[key] === '' || extra[key] == null) delete params[key]
            else params[key] = extra[key]
        })
        return params
    }

    const applyFilter = (key, value) => {
        router.get('/evaluations', query({ [key]: value }), {
            preserveState: true,
            preserveScroll: true,
            replace: true,
        })
    }

    const clearFilters = () => {
        router.get('/evaluations', {}, { preserveState: true, preserveScroll: true, replace: true })
    }

    const hasFilters = Boolean(filters.academic_period_id || filters.subject_id || filters.student_id)

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Evaluación del Estudiante
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Evaluaciones</h2>
                    <p className="text-slate-500">Registro de notas de prácticas, parciales y finales por asignatura.</p>
                </div>
                <div className="flex flex-wrap gap-2">
                    <a
                        href="/evaluations/record"
                        className="inline-flex items-center gap-1.5 px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors"
                    >
                        <span className="material-symbols-outlined text-lg">edit_note</span>
                        Acta de Notas
                    </a>
                    <ModalLink
                        href="/evaluations/create"
                        title="Registrar evaluación"
                        context="Enseñanza y Aprendizaje"
                        icon="fact_check"
                        returnPath="/evaluations"
                        className="inline-flex items-center gap-1.5 px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all"
                    >
                        <span className="material-symbols-outlined text-lg">fact_check</span>
                        Registrar Evaluación
                    </ModalLink>
                </div>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm mb-6">
                <div className="p-4">
                    <div className="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <select value={filters.academic_period_id ?? ''} onChange={(e) => applyFilter('academic_period_id', e.target.value)} aria-label="Filtrar por periodo" className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los periodos</option>
                            {periods.map((period) => (
                                <option key={period.id} value={period.id}>{period.name}</option>
                            ))}
                        </select>
                        <select value={filters.subject_id ?? ''} onChange={(e) => applyFilter('subject_id', e.target.value)} aria-label="Filtrar por asignatura" className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todas las asignaturas</option>
                            {subjects.map((subject) => (
                                <option key={subject.id} value={subject.id}>{subject.code} - {subject.name}</option>
                            ))}
                        </select>
                        <select value={filters.student_id ?? ''} onChange={(e) => applyFilter('student_id', e.target.value)} aria-label="Filtrar por estudiante" className="w-full rounded-lg border-slate-200 text-sm">
                            <option value="">Todos los estudiantes</option>
                            {students.map((student) => (
                                <option key={student.id} value={student.id}>{student.codigo} - {student.user?.name ?? 'Sin usuario'}</option>
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
                                <th className="px-6 py-4">Asignatura</th>
                                <th className="px-6 py-4">Periodo</th>
                                <th className="px-6 py-4">Tipo</th>
                                <th className="px-6 py-4">Nota</th>
                                <th className="px-6 py-4">Fecha</th>
                                <th className="px-6 py-4 text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {evaluations.data.length > 0 ? (
                                evaluations.data.map((evaluation) => (
                                    <tr key={evaluation.id} className="hover:bg-slate-50 transition-colors">
                                        <td className="px-6 py-4">
                                            <span className="font-semibold">{evaluation.student?.user?.name ?? 'Sin usuario'}</span>
                                            <span className="block text-xs text-slate-400">{evaluation.student?.codigo}</span>
                                        </td>
                                        <td className="px-6 py-4">
                                            <span className="font-semibold text-navy">{evaluation.subject?.code}</span>
                                            <span className="block text-xs text-slate-400">{evaluation.subject?.name}</span>
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{evaluation.academic_period?.name}</td>
                                        <td className="px-6 py-4 text-slate-500">{evaluation.type_label}</td>
                                        <td className="px-6 py-4">
                                            <span className={`px-3 py-1 rounded-full text-xs font-bold border ${scoreBadge(evaluation.score)}`}>
                                                {evaluation.score}
                                            </span>
                                        </td>
                                        <td className="px-6 py-4 text-slate-500">{evaluation.evaluation_date}</td>
                                        <td className="px-6 py-4 text-right">
                                            <ModalLink href={`/evaluations/${evaluation.id}`} title="Detalle de evaluación" context="Enseñanza y Aprendizaje" icon="fact_check" returnPath="/evaluations" aria-label={`Ver detalle de la evaluación ${evaluation.id}`} className="inline-flex p-1.5 hover:bg-slate-100 rounded text-navy">
                                                <span className="material-symbols-outlined text-lg">visibility</span>
                                            </ModalLink>
                                        </td>
                                    </tr>
                                ))
                            ) : (
                                <tr>
                                    <td colSpan={7} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay evaluaciones registradas</p>
                                        <p className="text-xs mt-1">Registra la primera evaluación</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={evaluations.links} />
            </div>
        </div>
    )
}

EvaluationsIndex.layout = (page) => <AppLayout>{page}</AppLayout>
