import { useForm, router } from '@inertiajs/react'
import AppLayout from '../../../layouts/AppLayout'

const MODALIDADES = ['Ordinario', 'Extraordinario', 'CEPUNT', 'Titulados', 'Primeros Puestos']

const STATUS_OPTIONS = [
    ['borrador', 'Borrador'],
    ['activo', 'Activo'],
    ['cerrado', 'Cerrado'],
]

const formatDate = (value) => {
    if (!value) return ''
    if (typeof value === 'string') return value.slice(0, 10)
    return new Date(value).toISOString().slice(0, 10)
}

export default function AdmissionProcessesEdit({ process, periods, careers }) {
    const { data, setData, put, processing, errors, delete: destroy } = useForm({
        name: process.name,
        academic_period_id: process.academic_period_id,
        career_id: process.career_id,
        vacancies: process.vacancies,
        modality: process.modality ?? 'Ordinario',
        start_date: formatDate(process.start_date),
        end_date: formatDate(process.end_date),
        status: process.status,
    })

    const submit = (e) => {
        e.preventDefault()
        put(`/admission/processes/${process.id}`)
    }

    const handleDelete = (e) => {
        e.preventDefault()
        if (window.confirm('¿Eliminar esta convocatoria?')) {
            destroy(`/admission/processes/${process.id}`, {
                onSuccess: () => {},
            })
        }
    }

    return (
        <div className="page-enter">
            <div className="max-w-7xl mx-auto px-5 sm:px-8">
                <div className="mb-6">
                    <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                        Gestión del Ingreso
                    </span>
                    <h2 className="text-3xl font-bold text-navy mt-2">Editar Convocatoria</h2>
                    <p className="text-slate-500">Modifica los datos de la convocatoria de admisión.</p>
                </div>

                <div className="max-w-3xl mx-auto">
                    <div className="bg-white overflow-hidden shadow-sm rounded-lg">
                        <div className="p-6">
                            <form onSubmit={submit}>
                                <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                    <div className="col-span-2">
                                        <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                                            Nombre de la convocatoria
                                        </label>
                                        <input
                                            id="name"
                                            type="text"
                                            value={data.name}
                                            onChange={(e) => setData('name', e.target.value)}
                                            required
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                        />
                                        {errors.name && <p className="mt-1 text-xs text-red-600">{errors.name}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="academic_period_id" className="block text-sm font-medium text-gray-700">
                                            Periodo Académico
                                        </label>
                                        <select
                                            id="academic_period_id"
                                            value={data.academic_period_id}
                                            onChange={(e) => setData('academic_period_id', e.target.value)}
                                            required
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        >
                                            {periods.map((period) => (
                                                <option key={period.id} value={period.id}>
                                                    {period.name}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.academic_period_id && (
                                            <p className="mt-1 text-xs text-red-600">{errors.academic_period_id}</p>
                                        )}
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
                                        <label htmlFor="vacancies" className="block text-sm font-medium text-gray-700">
                                            Vacantes
                                        </label>
                                        <input
                                            id="vacancies"
                                            type="number"
                                            value={data.vacancies}
                                            onChange={(e) => setData('vacancies', e.target.value)}
                                            min="0"
                                            required
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.vacancies && <p className="mt-1 text-xs text-red-600">{errors.vacancies}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="modality" className="block text-sm font-medium text-gray-700">
                                            Modalidad
                                        </label>
                                        <select
                                            id="modality"
                                            value={data.modality}
                                            onChange={(e) => setData('modality', e.target.value)}
                                            required
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        >
                                            {MODALIDADES.map((m) => (
                                                <option key={m} value={m}>
                                                    {m}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.modality && <p className="mt-1 text-xs text-red-600">{errors.modality}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="start_date" className="block text-sm font-medium text-gray-700">
                                            Inicio
                                        </label>
                                        <input
                                            id="start_date"
                                            type="date"
                                            value={formatDate(data.start_date)}
                                            onChange={(e) => setData('start_date', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.start_date && <p className="mt-1 text-xs text-red-600">{errors.start_date}</p>}
                                    </div>
                                    <div>
                                        <label htmlFor="end_date" className="block text-sm font-medium text-gray-700">
                                            Fin
                                        </label>
                                        <input
                                            id="end_date"
                                            type="date"
                                            value={formatDate(data.end_date)}
                                            onChange={(e) => setData('end_date', e.target.value)}
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        />
                                        {errors.end_date && <p className="mt-1 text-xs text-red-600">{errors.end_date}</p>}
                                    </div>
                                    <div className="col-span-2">
                                        <label htmlFor="status" className="block text-sm font-medium text-gray-700">
                                            Estado
                                        </label>
                                        <select
                                            id="status"
                                            value={data.status}
                                            onChange={(e) => setData('status', e.target.value)}
                                            required
                                            className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                        >
                                            {STATUS_OPTIONS.map(([value, label]) => (
                                                <option key={value} value={value}>
                                                    {label}
                                                </option>
                                            ))}
                                        </select>
                                        {errors.status && <p className="mt-1 text-xs text-red-600">{errors.status}</p>}
                                    </div>
                                </div>

                                <div className="flex justify-between">
                                    <button
                                        type="button"
                                        onClick={handleDelete}
                                        className="inline-flex items-center px-4 py-2 bg-red-600 text-white rounded-md font-semibold text-xs uppercase tracking-widest hover:bg-red-500"
                                    >
                                        Eliminar
                                    </button>
                                    <div>
                                        <a
                                            href="/admission/processes"
                                            className="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2"
                                        >
                                            Cancelar
                                        </a>
                                        <button
                                            type="submit"
                                            disabled={processing}
                                            className="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 disabled:opacity-50"
                                        >
                                            {processing ? 'Guardando...' : 'Guardar'}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    )
}

AdmissionProcessesEdit.layout = (page) => <AppLayout>{page}</AppLayout>
