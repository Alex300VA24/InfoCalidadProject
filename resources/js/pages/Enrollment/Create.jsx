import { useEffect, useState } from 'react'
import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function EnrollmentCreate({ students, periods, careers, defaultCareer }) {
    const activePeriod = periods.find((period) => period.is_active) ?? periods[0]

    const { data, setData, post, processing, errors } = useForm({
        student_id: '',
        academic_period_id: activePeriod?.id ?? '',
        career_id: defaultCareer?.id ?? '',
        matricula_fee: 0,
        subjects: [],
    })

    const [subjects, setSubjects] = useState([])
    const [loadingSubjects, setLoadingSubjects] = useState(false)
    const [subjectsError, setSubjectsError] = useState(null)

    useEffect(() => {
        const careerId = defaultCareer?.id
        if (!careerId) return undefined

        let cancelled = false
        setLoadingSubjects(true)
        setSubjectsError(null)

        fetch(`/enrollment/subjects?career_id=${careerId}`)
            .then((res) => res.json())
            .then((result) => {
                if (!cancelled) setSubjects(result)
            })
            .catch(() => {
                if (!cancelled) setSubjectsError('No se pudieron cargar las asignaturas.')
            })
            .finally(() => {
                if (!cancelled) setLoadingSubjects(false)
            })

        return () => {
            cancelled = true
        }
        // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [])

    const loadSubjects = (careerId) => {
        if (!careerId) {
            setSubjects([])
            return
        }

        setLoadingSubjects(true)
        setSubjectsError(null)

        fetch(`/enrollment/subjects?career_id=${careerId}`)
            .then((res) => res.json())
            .then(setSubjects)
            .catch(() => setSubjectsError('No se pudieron cargar las asignaturas.'))
            .finally(() => setLoadingSubjects(false))
    }

    const toggleSubject = (subjectId) => {
        setData(
            'subjects',
            data.subjects.includes(subjectId)
                ? data.subjects.filter((id) => id !== subjectId)
                : [...data.subjects, subjectId]
        )
    }

    const submit = (e) => {
        e.preventDefault()
        post('/enrollment')
    }

    return (
        <div className="page-enter">
            <div className="mb-6">
                <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                    Matrícula
                </span>
                <h2 className="text-3xl font-bold text-navy mt-2">Nueva Matrícula</h2>
                <p className="text-slate-500">Registra una nueva matrícula y emite la orden de pago.</p>
            </div>

            <div className="max-w-4xl">
                <div className="bg-white overflow-hidden shadow-sm rounded-lg">
                    <div className="p-6">
                        <form onSubmit={submit}>
                            <div className="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label htmlFor="student_id" className="block text-sm font-medium text-gray-700">
                                        Estudiante
                                    </label>
                                    <select
                                        id="student_id"
                                        value={data.student_id}
                                        onChange={(e) => setData('student_id', e.target.value)}
                                        required
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200"
                                    >
                                        <option value="">Seleccione estudiante</option>
                                        {students.map((student) => (
                                            <option key={student.id} value={student.id}>
                                                {student.codigo} - {student.user?.name ?? 'Sin usuario'}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.student_id && (
                                        <p className="mt-1 text-xs text-red-600">{errors.student_id}</p>
                                    )}
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
                                        onChange={(e) => {
                                            setData('career_id', e.target.value)
                                            loadSubjects(e.target.value)
                                        }}
                                        required
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                    >
                                        {careers.map((career) => (
                                            <option key={career.id} value={career.id}>
                                                {career.name}
                                            </option>
                                        ))}
                                    </select>
                                    {errors.career_id && (
                                        <p className="mt-1 text-xs text-red-600">{errors.career_id}</p>
                                    )}
                                </div>
                                <div>
                                    <label htmlFor="matricula_fee" className="block text-sm font-medium text-gray-700">
                                        Derecho de matrícula (S/)
                                    </label>
                                    <input
                                        id="matricula_fee"
                                        type="number"
                                        value={data.matricula_fee}
                                        onChange={(e) => setData('matricula_fee', e.target.value)}
                                        min="0"
                                        step="0.01"
                                        className="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300"
                                    />
                                    {errors.matricula_fee && (
                                        <p className="mt-1 text-xs text-red-600">{errors.matricula_fee}</p>
                                    )}
                                </div>
                            </div>

                            <div className="mb-4">
                                <label className="block text-sm font-medium text-gray-700 mb-2">
                                    Asignaturas a matricular
                                </label>
                                <div className="grid grid-cols-1 md:grid-cols-2 gap-2 border rounded-md border-gray-300 p-4 max-h-72 overflow-y-auto">
                                    {loadingSubjects && (
                                        <p className="text-sm text-slate-400 col-span-full">Cargando asignaturas...</p>
                                    )}
                                    {!loadingSubjects && subjectsError && (
                                        <p className="text-sm text-red-500 col-span-full">{subjectsError}</p>
                                    )}
                                    {!loadingSubjects && !subjectsError && subjects.length === 0 && (
                                        <p className="text-sm text-slate-400 col-span-full">
                                            {data.career_id
                                                ? 'No hay asignaturas para esta carrera.'
                                                : 'Seleccione la carrera para cargar las asignaturas.'}
                                        </p>
                                    )}
                                    {subjects.map((subject) => (
                                        <label
                                            key={subject.id}
                                            className="flex items-center gap-2 p-2 rounded hover:bg-slate-50 cursor-pointer"
                                        >
                                            <input
                                                type="checkbox"
                                                checked={data.subjects.includes(subject.id)}
                                                onChange={() => toggleSubject(subject.id)}
                                                className="rounded border-slate-300 text-navy focus:ring-navy"
                                            />
                                            <span className="text-sm">
                                                {subject.code} - {subject.name}
                                            </span>
                                        </label>
                                    ))}
                                </div>
                                {errors.subjects && (
                                    <p className="mt-1 text-xs text-red-600">{errors.subjects}</p>
                                )}
                            </div>

                            <div className="flex justify-end">
                                <a
                                    href="/enrollment"
                                    className="inline-flex items-center px-4 py-2 bg-gray-200 rounded-md font-semibold text-xs text-gray-700 mr-2"
                                >
                                    Cancelar
                                </a>
                                <button
                                    type="submit"
                                    disabled={processing}
                                    className="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 disabled:opacity-50"
                                >
                                    {processing ? 'Registrando...' : 'Registrar Matrícula'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    )
}

EnrollmentCreate.layout = (page) => <AppLayout>{page}</AppLayout>
