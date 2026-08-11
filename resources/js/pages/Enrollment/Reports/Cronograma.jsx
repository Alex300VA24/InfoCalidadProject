import AppLayout from '../../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return ''
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

export default function Cronograma({ periods, stats }) {
    return (
        <div className="page-enter">
            <div className="mb-6">
                <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                    Reportes · F1
                </span>
                <h2 className="text-3xl font-bold text-navy mt-2">Cronograma Académico</h2>
                <p className="text-slate-500">Periodos académicos y su actividad de matrícula.</p>
            </div>

            <div className="grid grid-cols-1 md:grid-cols-3 gap-6">
                {periods.map((period) => {
                    const stat = stats[period.id]
                    const total = stat?.total ?? 0
                    const careers = stat?.careers ?? 0
                    const width = Math.min(total * 8, 100)

                    return (
                        <div key={period.id} className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                            <div className={`p-4 ${period.is_active ? 'bg-navy text-white' : 'bg-slate-100 text-slate-600'}`}>
                                <div className="flex justify-between items-center">
                                    <h3 className="text-xl font-bold">{period.name}</h3>
                                    {period.is_active && (
                                        <span className="text-[9px] font-black px-2 py-0.5 rounded border bg-emerald-500/20 border-emerald-500/30 text-emerald-300">
                                            ACTIVO
                                        </span>
                                    )}
                                </div>
                                <p className="text-xs mt-1 opacity-70">
                                    {formatDate(period.start_date)} — {formatDate(period.end_date)}
                                </p>
                            </div>
                            <div className="p-4 space-y-3 text-sm">
                                <div className="flex justify-between">
                                    <span className="text-slate-500">Matrículas registradas</span>
                                    <span className="font-bold text-navy">{total}</span>
                                </div>
                                <div className="flex justify-between">
                                    <span className="text-slate-500">Carreras activas</span>
                                    <span className="font-bold text-navy">{careers}</span>
                                </div>
                                <div className="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div className="h-full bg-navy" style={{ width: `${width}%` }}></div>
                                </div>
                            </div>
                        </div>
                    )
                })}
                {periods.length === 0 && (
                    <div className="col-span-full text-center text-slate-400 py-10">
                        <p className="text-sm font-bold text-slate-600">No hay periodos académicos</p>
                    </div>
                )}
            </div>
        </div>
    )
}

Cronograma.layout = (page) => <AppLayout>{page}</AppLayout>
