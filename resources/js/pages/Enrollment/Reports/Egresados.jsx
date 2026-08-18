import AppLayout from '../../../layouts/AppLayout'
import Pagination from '../../../components/Pagination'

export default function Egresados({ students, counts }) {
    return (
        <div className="page-enter">
            <div className="mb-6">
                <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                    Reportes · F5
                </span>
                <h2 className="text-3xl font-bold text-navy mt-2">Reporte de Egresados</h2>
                <p className="text-slate-500">Listado de estudiantes con historial académico para el reporte de egresados.</p>
            </div>

            <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                <div className="overflow-x-auto">
                    <table className="w-full text-left">
                        <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                            <tr>
                                <th className="px-6 py-4">Código</th>
                                <th className="px-6 py-4">Estudiante</th>
                                <th className="px-6 py-4">Ciclo</th>
                                <th className="px-6 py-4">Matrículas</th>
                                <th className="px-6 py-4">Estado</th>
                            </tr>
                        </thead>
                        <tbody className="text-sm divide-y divide-slate-100">
                            {students.data.map((student) => (
                                <tr key={student.id} className="hover:bg-slate-50 transition-colors">
                                    <td className="px-6 py-4 font-bold text-navy">{student.codigo}</td>
                                    <td className="px-6 py-4 font-semibold">
                                        {student.user?.name ?? `Sin usuario (${student.codigo})`}
                                    </td>
                                    <td className="px-6 py-4 text-slate-500">{student.ciclo}</td>
                                    <td className="px-6 py-4 text-slate-500">{counts[student.id] ?? 0}</td>
                                    <td className="px-6 py-4">
                                        <span
                                            className={`px-3 py-1 rounded-full text-xs font-bold border ${
                                                student.estado === 'activo'
                                                    ? 'text-emerald-700 bg-emerald-100 border-emerald-200'
                                                    : 'text-slate-600 bg-slate-100 border-slate-200'
                                            }`}
                                        >
                                            {student.estado.charAt(0).toUpperCase() + student.estado.slice(1)}
                                        </span>
                                    </td>
                                </tr>
                            ))}
                            {students.data.length === 0 && (
                                <tr>
                                    <td colSpan={5} className="px-6 py-10 text-center text-slate-400">
                                        <p className="text-sm font-bold text-slate-600">No hay estudiantes registrados</p>
                                    </td>
                                </tr>
                            )}
                        </tbody>
                    </table>
                </div>
            </div>

            <div className="mt-6">
                <Pagination links={students.links} />
            </div>
        </div>
    )
}

Egresados.layout = (page) => <AppLayout>{page}</AppLayout>
