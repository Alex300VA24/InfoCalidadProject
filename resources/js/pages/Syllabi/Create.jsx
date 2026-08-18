import { useEffect, useState } from 'react'
import { useForm } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

export default function SyllabiCreate({
    periods = [],
    teachers = [],
    careers = [],
    defaultCareerId = '',
    subjects: initialSubjects = [],
}) {
    const { data, setData, post, processing, errors } = useForm({
        career_id: defaultCareerId ? String(defaultCareerId) : '',
        subject_id: '',
        academic_period_id: '',
        teacher_id: '',
        version: '1.0',
        file: null,
    })

    const [subjects, setSubjects] = useState(initialSubjects)
    const [loadingSubjects, setLoadingSubjects] = useState(false)

    useEffect(() => {
        const careerId = data.career_id

        setData('subject_id', '')

        if (!careerId) {
            setSubjects([])
            return
        }

        const controller = new AbortController()

        async function loadSubjects() {
            setLoadingSubjects(true)

            try {
                const response = await fetch(
                    `/syllabi/subjects?career_id=${encodeURIComponent(careerId)}`,
                    {
                        headers: {
                            Accept: 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                        },
                        signal: controller.signal,
                    }
                )

                if (!response.ok) {
                    throw new Error(
                        `Error ${response.status} al cargar asignaturas`
                    )
                }

                const result = await response.json()

                console.log('Respuesta de asignaturas:', result)

                const subjectList = Array.isArray(result)
                    ? result
                    : Array.isArray(result.subjects)
                        ? result.subjects
                        : Array.isArray(result.data)
                            ? result.data
                            : []

                setSubjects(subjectList)
            } catch (error) {
                if (error.name !== 'AbortError') {
                    console.error('Error cargando asignaturas:', error)
                    setSubjects([])
                }
            } finally {
                if (!controller.signal.aborted) {
                    setLoadingSubjects(false)
                }
            }
        }

        loadSubjects()

        return () => controller.abort()
    }, [data.career_id])

    const onFileChange = (e) => {
        const file = e.target.files?.[0]
        if (file) setData('file', file)
    }

    const handleSubmit = (e) => {
        e.preventDefault()
        post('/syllabi', { preserveScroll: true })
    }

    const canSubmit = Boolean(data.career_id && data.subject_id && data.academic_period_id && data.teacher_id && data.file)

    return (
        <div className="page-enter">
            <div className="max-w-4xl mx-auto px-5 sm:px-8">
                <div className="flex items-center gap-3 mb-6">
                    <a href="/syllabi" className="text-slate-500 hover:text-navy" aria-label="Volver a sílabos">
                        <span className="material-symbols-outlined text-2xl">arrow_back</span>
                    </a>
                    <div>
                        <span className="text-[10px] font-bold text-navy bg-navy/10 px-2 py-0.5 rounded-sm uppercase tracking-widest">
                            Registro de Sílabos
                        </span>
                        <h2 className="text-3xl font-bold text-navy mt-2">Subir Nuevo Sílabo</h2>
                        <p className="text-slate-500">Registra el sílabo con la información requerida.</p>
                    </div>
                </div>

                <div className="bg-white border border-slate-200 rounded-xl shadow-sm p-6">
                    <form onSubmit={handleSubmit} noValidate encType="multipart/form-data">
                        <div className="grid grid-cols-1 md:grid-cols-2 gap-5">
                            <div>
                                <label className="block text-sm font-bold text-ink mb-1.5">Carrera <span className="text-red-500">*</span></label>
                                <select
                                    value={data.career_id}
                                    onChange={(e) => setData('career_id', e.target.value)}
                                    className={`w-full rounded-lg ${errors.career_id ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                    required
                                >
                                    <option value="">Selecciona una carrera</option>
                                    {careers.map((c) => (
                                        <option key={c.id} value={c.id}>{c.name}</option>
                                    ))}
                                </select>
                                {errors.career_id && <p className="mt-1 text-xs text-red-500">{errors.career_id}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-1.5">Asignatura <span className="text-red-500">*</span></label>
                                <select
                                    value={data.subject_id}
                                    onChange={(e) => setData('subject_id', e.target.value)}
                                    className={`w-full rounded-lg ${
                                        errors.subject_id
                                            ? 'border-red-400 focus:ring-red-200'
                                            : 'border-slate-200'
                                    }`}
                                    disabled={!data.career_id || loadingSubjects}
                                    required
                                >
                                    <option value="">
                                        {!data.career_id
                                            ? 'Primero selecciona una carrera'
                                            : loadingSubjects
                                                ? 'Cargando asignaturas...'
                                                : subjects.length === 0
                                                    ? 'No hay asignaturas disponibles'
                                                    : 'Selecciona una asignatura'}
                                    </option>

                                    {subjects.map((subject) => (
                                        <option key={subject.id} value={String(subject.id)}>
                                            {subject.code} — {subject.name}
                                        </option>
                                    ))}
                                </select>
                                {errors.subject_id && <p className="mt-1 text-xs text-red-500">{errors.subject_id}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-1.5">Periodo Académico <span className="text-red-500">*</span></label>
                                <select
                                    value={data.academic_period_id}
                                    onChange={(e) => setData('academic_period_id', e.target.value)}
                                    className={`w-full rounded-lg ${errors.academic_period_id ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                    required
                                >
                                    <option value="">Selecciona un periodo</option>
                                    {periods.map((p) => (
                                        <option key={p.id} value={p.id}>{p.name}</option>
                                    ))}
                                </select>
                                {errors.academic_period_id && <p className="mt-1 text-xs text-red-500">{errors.academic_period_id}</p>}
                            </div>

                            <div>
                                <label className="block text-sm font-bold text-ink mb-1.5">Docente Responsable <span className="text-red-500">*</span></label>
                                <select
                                    value={data.teacher_id}
                                    onChange={(e) => setData('teacher_id', e.target.value)}
                                    className={`w-full rounded-lg ${errors.teacher_id ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                    required
                                >
                                    <option value="">Selecciona un docente</option>
                                    {teachers.map((t) => (
                                        <option key={t.id} value={t.id}>{t.name}</option>
                                    ))}
                                </select>
                                {errors.teacher_id && <p className="mt-1 text-xs text-red-500">{errors.teacher_id}</p>}
                            </div>

                            <div className="md:col-span-2">
                                <label className="block text-sm font-bold text-ink mb-1.5">Versión del Sílabo</label>
                                <input
                                    type="text"
                                    value={data.version}
                                    onChange={(e) => setData('version', e.target.value)}
                                    className={`w-full rounded-lg ${errors.version ? 'border-red-400 focus:ring-red-200' : 'border-slate-200'}`}
                                    placeholder="Ej. 1.0"
                                />
                                {errors.version && <p className="mt-1 text-xs text-red-500">{errors.version}</p>}
                            </div>

                            <div className="md:col-span-2">
                                <label className="block text-sm font-bold text-ink mb-1.5">Archivo PDF del Sílabo <span className="text-red-500">*</span></label>
                                <div className="border-2 border-dashed border-slate-300 rounded-xl p-5 hover:border-accent hover:bg-accent/5 transition-colors">
                                    <input
                                        type="file"
                                        accept=".pdf,application/pdf"
                                        onChange={onFileChange}
                                        className="block w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-bold file:bg-accent file:text-ink hover:file:bg-accent/80"
                                    />
                                    <p className="text-xs text-slate-400 mt-2">
                                        Formato PDF, tamaño máximo 20 MB
                                    </p>
                                    {data.file && (
                                        <div className="mt-3 flex items-center gap-2 text-xs text-navy font-bold bg-navy/5 p-2 rounded border border-navy/10">
                                            <span className="material-symbols-outlined text-sm">picture_as_pdf</span>
                                            <span className="truncate">{data.file.name}</span>
                                            <span className="text-slate-400 ml-auto">
                                                {data.file.size ? `${(data.file.size / 1024).toFixed(1)} KB` : ''}
                                            </span>
                                        </div>
                                    )}
                                </div>
                                {errors.file && <p className="mt-1 text-xs text-red-500">{errors.file}</p>}
                            </div>
                        </div>

                        <div className="mt-8 flex justify-between border-t border-slate-200 pt-6">
                            <a
                                href="/syllabi" className="px-4 py-2 text-sm font-semibold text-slate-500 hover:text-slate-700">
                                Cancelar
                            </a>
                            <button
                                type="submit"
                                disabled={!canSubmit || processing}
                                className="inline-flex items-center gap-2 px-6 py-2 bg-navy text-white font-black rounded shadow-md disabled:opacity-50 disabled:cursor-not-allowed hover:bg-navy/90 transition-colors transition-colors text-sm"
                            >
                                {processing ? (
                                    <span className="material-symbols-outlined animate-spin text-sm">progress_activity</span>
                                ) : (
                                    <span className="material-symbols-outlined text-lg">upload_file</span>
                                )}
                                Guardar Sílabo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    )
}

SyllabiCreate.layout = (page) => <AppLayout>{page}</AppLayout>
