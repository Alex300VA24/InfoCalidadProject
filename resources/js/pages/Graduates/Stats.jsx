import AppLayout from '../../layouts/AppLayout'

export default function GraduatesStats({ total, byStatus, averageIncome }) {
    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex justify-between items-end mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Estadísticas de Egresados</h2>
                        <p className="text-slate-500">Indicadores de inserción laboral de los egresados.</p>
                    </div>
                </div>

                <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div className="bg-white border border-slate-200 p-6 rounded-xl shadow-sm">
                        <div className="flex justify-between items-start mb-4">
                            <span className="material-symbols-outlined text-navy bg-navy/10 p-2 rounded-lg">badge</span>
                        </div>
                        <div className="text-3xl font-bold text-navy">{total}</div>
                        <div className="text-sm text-slate-500 font-medium mt-1">Egresados Registrados</div>
                    </div>

                    <div className="bg-white border border-slate-200 p-6 rounded-xl shadow-sm">
                        <div className="flex justify-between items-start mb-4">
                            <span className="material-symbols-outlined text-navy bg-navy/10 p-2 rounded-lg">payments</span>
                        </div>
                        <div className="text-3xl font-bold text-navy">S/ {Number(averageIncome).toLocaleString('es-PE', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</div>
                        <div className="text-sm text-slate-500 font-medium mt-1">Ingreso Mensual Promedio</div>
                    </div>

                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm">
                        <div className="px-6 py-4 border-b border-slate-100">
                            <h3 className="text-base font-bold text-navy">Situación Laboral</h3>
                        </div>
                        <div className="p-6 space-y-3">
                            {byStatus.length > 0 ? byStatus.map((data, index) => (
                                <div key={index} className="flex justify-between items-center">
                                    <span className="text-sm font-medium text-slate-700">{data.status}</span>
                                    <span className="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-navy/10 text-navy">
                                        {data.total}
                                    </span>
                                </div>
                            )) : (
                                <p className="text-sm text-slate-400 text-center py-4">Sin datos</p>
                            )}
                        </div>
                    </div>
                </div>

                <div className="flex justify-end">
                    <a href="/graduates" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
                </div>
            </div>
        </div>
    )
}

GraduatesStats.layout = (page) => <AppLayout>{page}</AppLayout>
