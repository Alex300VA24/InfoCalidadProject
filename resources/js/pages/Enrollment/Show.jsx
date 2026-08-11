import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

const formatDate = (value) => {
    if (!value) return ''
    return new Date(value).toLocaleDateString('es-PE', { day: '2-digit', month: '2-digit', year: 'numeric' })
}

const formatAmount = (value) => Number(value ?? 0).toFixed(2)

function RegisterPaymentForm({ payment }) {
    const { data, setData, post, processing, errors } = useForm({ receipt_number: '' })

    const submit = (e) => {
        e.preventDefault()
        post(`/enrollment/payments/${payment.id}/register`, {
            preserveScroll: true,
        })
    }

    return (
        <form onSubmit={submit} className="mt-3 flex flex-col sm:flex-row gap-2">
            <input
                type="text"
                name="receipt_number"
                value={data.receipt_number}
                onChange={(e) => setData('receipt_number', e.target.value)}
                required
                placeholder="N° de recibo"
                className="flex-1 rounded-lg border-slate-200 text-sm"
            />
            <button
                type="submit"
                disabled={processing}
                className="px-3 py-2 bg-emerald-600 text-white rounded-lg text-xs font-bold hover:bg-emerald-700 transition-colors disabled:opacity-50"
            >
                {processing ? 'Registrando...' : 'Registrar Pago'}
            </button>
            {errors.receipt_number && <p className="text-xs text-red-600 w-full">{errors.receipt_number}</p>}
        </form>
    )
}

export default function EnrollmentShow({ enrollment }) {
    const student = enrollment.student
    const statusLabel = enrollment.status.charAt(0).toUpperCase() + enrollment.status.slice(1)

    return (
        <div className="page-enter">
            <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                <div>
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Matrícula · Ficha F3
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">{enrollment.code}</h2>
                    <p className="text-slate-500">
                        {student?.user?.name ?? `Sin usuario (${student?.codigo ?? ''})`} · {enrollment.academic_period?.name}
                    </p>
                </div>
                <div className="flex flex-wrap gap-3">
                    <a
                        href={`/enrollment/${enrollment.id}/ficha`}
                        className="px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors flex items-center gap-2"
                    >
                        <span className="material-symbols-outlined text-sm">download</span>
                        Ficha de Matrícula
                    </a>
                    <a
                        href={`/enrollment/${enrollment.id}/orden-pago`}
                        className="px-4 py-2 border border-slate-300 text-navy rounded-lg text-sm font-bold hover:bg-slate-50 transition-colors flex items-center gap-2"
                    >
                        <span className="material-symbols-outlined text-sm">receipt_long</span>
                        Orden de Pago
                    </a>
                </div>
            </div>

            <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                <div className="lg:col-span-7">
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b border-slate-100">
                            <h3 className="text-xl font-semibold text-navy">Datos de la Matrícula</h3>
                        </div>
                        <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Estudiante</span>
                                <span className="font-semibold text-navy">
                                    {student?.user?.name ?? `Sin usuario (${student?.codigo ?? ''})`}
                                </span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Código</span>
                                <span className="font-semibold text-navy">{student?.codigo}</span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Periodo</span>
                                <span className="font-semibold text-navy">{enrollment.academic_period?.name}</span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Carrera</span>
                                <span className="font-semibold text-navy">{enrollment.career?.name}</span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Fecha de matrícula</span>
                                <span className="font-semibold text-navy">{formatDate(enrollment.enrolled_at)}</span>
                            </div>
                            <div>
                                <span className="text-slate-400 block text-xs uppercase font-bold">Estado</span>
                                <span className="px-3 py-1 rounded-full text-xs font-bold border inline-block mt-1 text-emerald-700 bg-emerald-100 border-emerald-200">
                                    {statusLabel}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden mt-6">
                        <div className="px-6 py-4 border-b border-slate-100">
                            <h3 className="text-xl font-semibold text-navy">Asignaturas Matriculadas</h3>
                        </div>
                        <div className="overflow-x-auto">
                            <table className="w-full text-left">
                                <thead className="bg-slate-100 text-xs font-bold uppercase text-slate-500 tracking-wider">
                                    <tr>
                                        <th className="px-6 py-4">Código</th>
                                        <th className="px-6 py-4">Asignatura</th>
                                        <th className="px-6 py-4">Créditos</th>
                                        <th className="px-6 py-4">Condición</th>
                                    </tr>
                                </thead>
                                <tbody className="text-sm divide-y divide-slate-100">
                                    {enrollment.subjects.map((es) => (
                                        <tr key={es.id}>
                                            <td className="px-6 py-4">{es.subject?.code}</td>
                                            <td className="px-6 py-4 font-semibold text-navy">{es.subject?.name}</td>
                                            <td className="px-6 py-4">{es.subject?.credits}</td>
                                            <td className="px-6 py-4 text-slate-500">
                                                {es.status.charAt(0).toUpperCase() + es.status.slice(1)}
                                            </td>
                                        </tr>
                                    ))}
                                    {enrollment.subjects.length === 0 && (
                                        <tr>
                                            <td colSpan={4} className="px-6 py-6 text-center text-slate-400">
                                                Sin asignaturas
                                            </td>
                                        </tr>
                                    )}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div className="lg:col-span-5">
                    <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                        <div className="px-6 py-4 border-b border-slate-100">
                            <h3 className="text-xl font-semibold text-navy">Órdenes de Pago</h3>
                        </div>
                        <div className="divide-y divide-slate-100">
                            {enrollment.payment_orders.map((payment) => (
                                <div key={payment.id} className="p-4">
                                    <div className="flex items-center justify-between gap-3">
                                        <div>
                                            <p className="text-sm font-bold text-navy">{payment.concept}</p>
                                            <p className="text-xs text-slate-400">S/ {formatAmount(payment.amount)}</p>
                                            {payment.receipt_number && (
                                                <p className="text-xs text-slate-400">Recibo N° {payment.receipt_number}</p>
                                            )}
                                        </div>
                                        <div className="flex items-center gap-2 shrink-0">
                                            <span
                                                className={`px-3 py-1 rounded-full text-xs font-bold border ${
                                                    payment.status === 'pagado'
                                                        ? 'text-emerald-700 bg-emerald-100 border-emerald-200'
                                                        : 'text-amber-700 bg-amber-100 border-amber-200'
                                                }`}
                                            >
                                                {payment.status.charAt(0).toUpperCase() + payment.status.slice(1)}
                                            </span>
                                            {payment.pdf_path && (
                                                <a
                                                    href={`/enrollment/${enrollment.id}/orden-pago`}
                                                    className="p-1.5 hover:bg-slate-100 rounded text-navy"
                                                    title="Descargar orden de pago"
                                                >
                                                    <span className="material-symbols-outlined text-lg">download</span>
                                                </a>
                                            )}
                                        </div>
                                    </div>

                                    {payment.status !== 'pagado' && <RegisterPaymentForm payment={payment} />}
                                </div>
                            ))}
                            {enrollment.payment_orders.length === 0 && (
                                <p className="p-6 text-sm text-slate-400 text-center">Sin órdenes de pago</p>
                            )}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

EnrollmentShow.layout = (page) => <AppLayout>{page}</AppLayout>
