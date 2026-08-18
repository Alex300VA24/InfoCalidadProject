import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

const STATUS_STYLES = {
    ingresante: 'text-emerald-700 bg-emerald-100 border-emerald-200',
    no_ingresante: 'text-red-700 bg-red-100 border-red-200',
    postulante: 'text-amber-700 bg-amber-100 border-amber-200',
}

const STATUS_LABELS = {
    ingresante: 'Ingresante',
    no_ingresante: 'No ingresante',
    postulante: 'Pendiente',
}

const fullName = (a) => [a.paterno, a.materno, a.nombres].filter(Boolean).join(' ').trim()

function ResultForm({ applicant }) {
    const { data, setData, post, processing, errors } = useForm({
        score: applicant.score ?? '',
        status: applicant.status ?? 'postulante',
    })

    const submit = (e) => {
        e.preventDefault()
        post(`/admission/applicants/${applicant.id}/result`, {
            preserveScroll: true,
        })
    }

    return (
        <form onSubmit={submit}>
            <div className="mb-4">
                <label htmlFor="score" className="block text-sm font-medium text-gray-700">
                    Puntaje (0 - 100)
                </label>
                <input
                    id="score"
                    type="number"
                    value={data.score}
                    onChange={(e) => setData('score', e.target.value)}
                    min="0"
                    max="100"
                    step="0.01"
                    required
                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                />
                {errors.score && <p className="mt-1 text-xs text-red-600">{errors.score}</p>}
            </div>
            <div className="mb-4">
                <label htmlFor="status" className="block text-sm font-medium text-gray-700">
                    Decisión
                </label>
                <select
                    id="status"
                    value={data.status}
                    onChange={(e) => setData('status', e.target.value)}
                    required
                    className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                >
                    <option value="ingresante">Ingresante</option>
                    <option value="no_ingresante">No ingresante</option>
                    <option value="postulante">Pendiente</option>
                </select>
                {errors.status && <p className="mt-1 text-xs text-red-600">{errors.status}</p>}
            </div>
            <p className="text-xs text-slate-400 mb-4">
                Al marcar "Ingresante" se genera automáticamente la constancia de ingreso y se habilita la cuenta del estudiante.
            </p>
            <button
                type="submit"
                disabled={processing}
                className="w-full px-4 py-2 bg-navy text-white rounded-lg text-sm font-bold hover:bg-[#343d96] transition-colors disabled:opacity-50"
            >
                {processing ? 'Guardando...' : 'Guardar Resultado'}
            </button>
        </form>
    )
}

export default function ApplicantsShow({ applicant }) {
    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="flex flex-wrap justify-between items-end gap-3 mb-6">
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Postulante
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">{fullName(applicant)}</h2>
                        <p className="text-slate-500">
                            DNI {applicant.dni} · {applicant.admission_process?.name}
                        </p>
                    </div>
                </div>

                <div className="grid grid-cols-1 lg:grid-cols-12 gap-6">
                    <div className="lg:col-span-7">
                        <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                            <div className="px-6 py-4 border-b border-slate-100">
                                <h3 className="text-xl font-semibold text-navy">Datos del Postulante</h3>
                            </div>
                            <div className="p-6 grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                                <div>
                                    <span className="text-slate-400 block text-xs uppercase font-bold">Apellidos</span>
                                    <span className="font-semibold text-navy">
                                        {applicant.paterno} {applicant.materno}
                                    </span>
                                </div>
                                <div>
                                    <span className="text-slate-400 block text-xs uppercase font-bold">Nombres</span>
                                    <span className="font-semibold text-navy">{applicant.nombres}</span>
                                </div>
                                <div>
                                    <span className="text-slate-400 block text-xs uppercase font-bold">DNI</span>
                                    <span className="font-semibold text-navy">{applicant.dni}</span>
                                </div>
                                <div>
                                    <span className="text-slate-400 block text-xs uppercase font-bold">Carrera</span>
                                    <span className="font-semibold text-navy">{applicant.career?.name}</span>
                                </div>
                                <div>
                                    <span className="text-slate-400 block text-xs uppercase font-bold">Correo</span>
                                    <span className="font-semibold text-navy">{applicant.email ?? '—'}</span>
                                </div>
                                <div>
                                    <span className="text-slate-400 block text-xs uppercase font-bold">Teléfono</span>
                                    <span className="font-semibold text-navy">{applicant.telefono ?? '—'}</span>
                                </div>
                                <div>
                                    <span className="text-slate-400 block text-xs uppercase font-bold">Puntaje</span>
                                    <span className="font-semibold text-navy">{applicant.score ?? 'Sin registrar'}</span>
                                </div>
                                <div>
                                    <span className="text-slate-400 block text-xs uppercase font-bold">Estado</span>
                                    <span
                                        className={`px-3 py-1 rounded-full text-xs font-bold border inline-block mt-1 ${
                                            STATUS_STYLES[applicant.status] ?? 'text-slate-600 bg-slate-100 border-slate-200'
                                        }`}
                                    >
                                        {STATUS_LABELS[applicant.status] ?? applicant.status}
                                    </span>
                                </div>
                            </div>
                        </div>

                        {applicant.status === 'ingresante' && applicant.constancia_path && (
                            <div className="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mt-6 flex items-center justify-between">
                                <div className="flex items-center gap-3">
                                    <span className="material-symbols-outlined text-emerald-600">verified</span>
                                    <div>
                                        <p className="text-sm font-bold text-emerald-800">
                                            Constancia de ingreso (F-DAD-PG-017) disponible
                                        </p>
                                        <p className="text-xs text-emerald-600">
                                            El estudiante ya fue habilitado con acceso al sistema.
                                        </p>
                                    </div>
                                </div>
                                <a
                                    href={`/admission/applicants/${applicant.id}/constancia`}
                                    className="px-4 py-2 bg-emerald-700 text-white rounded-lg text-sm font-bold hover:bg-emerald-600 transition-colors"
                                >
                                    Descargar PDF
                                </a>
                            </div>
                        )}
                    </div>

                    <div className="lg:col-span-5">
                        <div className="bg-white border border-slate-200 rounded-xl shadow-sm overflow-hidden">
                            <div className="px-6 py-4 border-b border-slate-100">
                                <h3 className="text-xl font-semibold text-navy">Registrar Resultado</h3>
                            </div>
                            <div className="p-6">
                                <ResultForm applicant={applicant} />
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

ApplicantsShow.layout = (page) => <AppLayout>{page}</AppLayout>
