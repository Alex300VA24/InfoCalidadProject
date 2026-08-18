import AppLayout from '../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return '—'
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

const capitalize = (value) => {
    if (!value) return '—'
    return value.charAt(0).toUpperCase() + value.slice(1)
}

export default function CertificatesShow({ certificate }) {
    return (
        <div className="page-enter">
            <div className="max-w-3xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">Resultados de la Formación</span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Detalle de Certificado</h2>
                        <p className="text-slate-500">{certificate.code} — {certificate.type_label}</p>
                    </div>
                    <div className="flex gap-3">
                        <a href={`/degrees/certificates/${certificate.id}/download`} data-turbo="false" className="px-4 py-2 bg-accent text-ink font-black rounded shadow-md text-sm hover:brightness-95 transition-all">Descargar PDF</a>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mb-6">
                    <div className="px-6 py-4 border-b border-slate-100 flex justify-between items-center">
                        <h3 className="text-base font-bold text-navy">{certificate.type_label}</h3>
                        <span className="px-3 py-1 rounded-full text-xs font-bold border text-emerald-700 bg-emerald-100 border-emerald-200">
                            {capitalize(certificate.status)}
                        </span>
                    </div>
                    <dl className="divide-y divide-slate-100 text-sm">
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Código</dt>
                            <dd className="font-semibold text-navy">{certificate.code}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Estudiante</dt>
                            <dd className="font-semibold">{certificate.student?.user?.name ?? certificate.student?.codigo}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Código del estudiante</dt>
                            <dd className="font-semibold">{certificate.student?.codigo}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Fecha de emisión</dt>
                            <dd className="font-semibold">{formatDate(certificate.issued_at)}</dd>
                        </div>
                        <div className="flex justify-between px-6 py-3">
                            <dt className="text-slate-500">Emitido por</dt>
                            <dd className="font-semibold">{certificate.issued_by}</dd>
                        </div>
                        <div className="px-6 py-3">
                            <dt className="text-slate-500 mb-1">Concepto</dt>
                            <dd className="font-semibold">{certificate.concept}</dd>
                        </div>
                    </dl>
                </div>

                <div className="flex justify-end">
                    <a href="/degrees/certificates" className="px-4 py-2 border border-slate-300 text-slate-600 rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors">Volver al listado</a>
                </div>
            </div>
        </div>
    )
}

CertificatesShow.layout = (page) => <AppLayout>{page}</AppLayout>
