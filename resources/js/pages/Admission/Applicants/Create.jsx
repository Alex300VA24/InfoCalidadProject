import { useForm } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

export default function ApplicantsCreate({ processes, careers }) {
    const { data, setData, post, processing, errors } = useForm({
        admission_process_id: processes[0]?.id ?? '',
        dni: '',
        career_id: careers[0]?.id ?? '',
        paterno: '',
        materno: '',
        nombres: '',
        email: '',
        telefono: '',
    })

    const submit = (e) => {
        e.preventDefault()
        post('/admission/applicants')
    }

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Admisión
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Registrar Postulante</h2>
                    <p className="text-slate-500">Registra un nuevo postulante en una convocatoria de admisión.</p>
                </div>

                <div className="max-w-3xl mx-auto">
                    <div className="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div className="p-6">
                            <form onSubmit={submit}>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <div className="col-span-2">
                                        <label htmlFor="admission_process_id" className="block text-sm font-medium text-gray-700">
                                            Convocatoria
                                        </label>
                                        <select
                                            id="admission_process_id"
                                            value={data.admission_process_id}
                                            onChange={(e) => setData('admission_process_id', e.target.value)}
                                            required
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                        >
                                            {processes.map((process) => (
                                                <option key={process.id} value={process.id}>
                                                    {process.name} ({process.academic_period?.name})
                                                </option>
                                            ))}
                                        </select>
                                        {errors.admission_process_id && (
                                            <p className="mt-1 text-xs text-red-600">{errors.admission_process_id}</p>
                                        )}
                                    </div>
                                    <div>
                                        <label htmlFor="dni" className="block text-sm font-medium text-gray-700">
                                            DNI
                                        </label>
                                        <input
                                            id="dni"
                                            type="text"
                                            value={data.dni}
                                            onChange={(e) => setData('dni', e.target.value)}
                                            required
                                            maxLength={15}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.dni && <p className="mt-1 text-xs text-red-600">{errors.dni}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="career_id" className="block text-sm font-medium text-gray-700">
                                            Carrera
                                        </label>
                                        <select
                                            id="career_id"
                                            value={data.career_id}
                                            onChange={(e) => setData('career_id', e.target.value)}
                                            required
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        >
                                            {careers.map((career) => (
                                                <option key={career.id} value={career.id}>
                                                    {career.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.career_id && <p className="mt-1 text-xs text-red-600">{errors.career_id}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="paterno" className="block text-sm font-medium text-gray-700">
                                            Apellido Paterno
                                        </label>
                                        <input
                                            id="paterno"
                                            type="text"
                                            value={data.paterno}
                                            onChange={(e) => setData('paterno', e.target.value)}
                                            required
                                            maxLength={100}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.paterno && <p className="mt-1 text-xs text-red-600">{errors.paterno}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="materno" className="block text-sm font-medium text-gray-700">
                                            Apellido Materno
                                        </label>
                                        <input
                                            id="materno"
                                            type="text"
                                            value={data.materno}
                                            onChange={(e) => setData('materno', e.target.value)}
                                            maxLength={100}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.materno && <p className="mt-1 text-xs text-red-600">{errors.materno}</p>}
                                    </div>
                                    <div className="col-span-2">
                                        <label htmlFor="nombres" className="block text-sm font-medium text-gray-700">
                                            Nombres
                                        </label>
                                        <input
                                            id="nombres"
                                            type="text"
                                            value={data.nombres}
                                            onChange={(e) => setData('nombres', e.target.value)}
                                            required
                                            maxLength={100}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.nombres && <p className="mt-1 text-xs text-red-600">{errors.nombres}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                            Correo electrónico
                                        </label>
                                        <input
                                            id="email"
                                            type="email"
                                            value={data.email}
                                            onChange={(e) => setData('email', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.email && <p className="mt-1 text-xs text-red-600">{errors.email}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="telefono" className="block text-sm font-medium text-gray-700">
                                            Teléfono
                                        </label>
                                        <input
                                            id="telefono"
                                            type="text"
                                            value={data.telefono}
                                            onChange={(e) => setData('telefono', e.target.value)}
                                            maxLength={20}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.telefono && <p className="mt-1 text-xs text-red-600">{errors.telefono}</p>}
                                    </div>
                                </div>

                                <div className="flex justify-end">
                                    <a
                                        href="/admission/applicants"
                                        className="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2"
                                    >
                                        Cancelar
                                    </a>
                                    <button
                                        type="submit"
                                        disabled={processing}
                                        className="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 disabled:opacity-50"
                                    >
                                        {processing ? 'Registrando...' : 'Registrar'}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

ApplicantsCreate.layout = (page) => <AppLayout>{page}</AppLayout>
