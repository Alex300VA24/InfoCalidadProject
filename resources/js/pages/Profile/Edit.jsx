import { useEffect, useState } from 'react'
import { Head, router, useForm, usePage } from '@inertiajs/react'
import AppLayout from '../../layouts/AppLayout'

function SavedNotice({ show, text = 'Guardado.' }) {
    const [visible, setVisible] = useState(false)

    useEffect(() => {
        if (!show) {
            setVisible(false)
            return
        }

        setVisible(true)
        const timer = setTimeout(() => setVisible(false), 2000)
        return () => clearTimeout(timer)
    }, [show])

    if (!visible) {
        return null
    }

    return <p className="text-sm text-gray-600">{text}</p>
}

function InputError({ message }) {
    if (!message) {
        return null
    }

    return <p className="mt-2 text-sm font-medium text-red-600">{message}</p>
}

const inputClass =
    'mt-1 block w-full rounded-lg border-slate-200 bg-slate-50/80 text-sm font-medium text-slate-900 outline-none transition-all duration-300 hover:border-slate-300 hover:bg-white focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10'

const cardClass =
    'bg-white border border-slate-200 rounded-2xl shadow-[0_16px_45px_-35px_rgba(8,38,70,0.25)] p-6 sm:p-8'

const primaryButtonClass =
    'inline-flex items-center justify-center rounded-lg bg-brand-800 px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-800 focus-visible:ring-offset-2 disabled:cursor-not-allowed disabled:opacity-60'

export default function ProfileEdit({ user }) {
    const { flash, errors } = usePage().props

    const profile = useForm({
        name: user.name,
        email: user.email,
    })

    const password = useForm({
        current_password: '',
        password: '',
        password_confirmation: '',
    })

    const accessibility = useForm({
        text_scale: user.text_scale ?? 100,
        view_scale: user.view_scale ?? 100,
    })

    const deletion = useForm({
        password: '',
    })

    const [confirmOpen, setConfirmOpen] = useState(
        Boolean(errors?.userDeletion?.password)
    )

    const submitProfile = (e) => {
        e.preventDefault()
        profile.patch('/profile')
    }

    const submitPassword = (e) => {
        e.preventDefault()
        password.put('/password', {
            onFinish: () => password.reset('current_password', 'password', 'password_confirmation'),
        })
    }

    const submitAccessibility = (e) => {
        e.preventDefault()
        accessibility.patch('/profile/accessibility')
    }

    const sendVerification = (e) => {
        e.preventDefault()
        router.post('/email/verification-notification')
    }

    const submitDeletion = (e) => {
        e.preventDefault()
        deletion.delete('/profile')
    }

    const textScaleOptions = [
        { value: 100, label: 'Normal' },
        { value: 125, label: 'Grande' },
        { value: 150, label: 'Muy grande' },
    ]

    const viewScaleOptions = [
        { value: 100, label: 'Normal' },
        { value: 110, label: '110%' },
        { value: 125, label: '125%' },
        { value: 150, label: '150%' },
    ]

    return (
        <>
            <Head title="Perfil" />

            <div className="page-enter">
                <div className="max-w-7xl mx-auto px-5 sm:px-6 lg:px-8 space-y-6">
                    <div className="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                        <div className="max-w-xl">
                            <section>
                                <header>
                                    <h2 className="text-lg font-medium text-gray-900">
                                        Información del perfil
                                    </h2>

                                    <p className="mt-1 text-sm text-gray-600">
                                        Actualiza la información de tu cuenta y tu correo electrónico.
                                    </p>
                                </header>

                                <form onSubmit={submitProfile} className="mt-6 space-y-6">
                                    <div>
                                        <label htmlFor="name" className="block text-sm font-medium text-gray-700">
                                            Nombre
                                        </label>
                                        <input
                                            id="name"
                                            name="name"
                                            type="text"
                                            value={profile.data.name}
                                            onChange={(e) => profile.setData('name', e.target.value)}
                                            autoComplete="name"
                                            required
                                            className={inputClass}
                                        />
                                        <InputError message={errors.name} />
                                    </div>

                                    <div>
                                        <label htmlFor="email" className="block text-sm font-medium text-gray-700">
                                            Correo electrónico
                                        </label>
                                        <input
                                            id="email"
                                            name="email"
                                            type="email"
                                            value={profile.data.email}
                                            onChange={(e) => profile.setData('email', e.target.value)}
                                            autoComplete="username"
                                            required
                                            className={inputClass}
                                        />
                                        <InputError message={errors.email} />

                                        {user.email_verified_at === null && (
                                            <div>
                                                <p className="mt-2 text-sm text-gray-800">
                                                    Tu correo electrónico no está verificado.{' '}

                                                    <button
                                                        type="button"
                                                        onClick={sendVerification}
                                                        className="rounded-md text-sm text-gray-600 underline hover:text-gray-900 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-blue-500 focus-visible:ring-offset-2"
                                                    >
                                                        Haz clic aquí para reenviar el correo de verificación.
                                                    </button>
                                                </p>

                                                {flash?.info === 'verification-link-sent' && (
                                                    <p className="mt-2 text-sm font-medium text-green-600">
                                                        Se ha enviado un nuevo enlace de verificación a tu correo electrónico.
                                                    </p>
                                                )}
                                            </div>
                                        )}
                                    </div>

                                    <div className="flex items-center gap-4">
                                        <button type="submit" disabled={profile.processing} className={primaryButtonClass}>
                                            {profile.processing ? 'Guardando...' : 'Guardar'}
                                        </button>

                                        <SavedNotice show={flash?.info === 'profile-updated'} />
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>

                    <div className={cardClass}>
                        <div className="max-w-xl">
                            <section>
                                <header>
                                    <h2 className="text-lg font-medium text-gray-900">
                                        Actualizar contraseña
                                    </h2>

                                    <p className="mt-1 text-sm text-gray-600">
                                        Asegúrate de que tu cuenta use una contraseña larga y aleatoria para mantenerse segura.
                                    </p>
                                </header>

                                <form onSubmit={submitPassword} className="mt-6 space-y-6">
                                    <div>
                                        <label htmlFor="update_password_current_password" className="block text-sm font-medium text-gray-700">
                                            Contraseña actual
                                        </label>
                                        <input
                                            id="update_password_current_password"
                                            name="current_password"
                                            type="password"
                                            value={password.data.current_password}
                                            onChange={(e) => password.setData('current_password', e.target.value)}
                                            autoComplete="current-password"
                                            className={inputClass}
                                        />
                                        <InputError message={errors.updatePassword?.current_password} />
                                    </div>

                                    <div>
                                        <label htmlFor="update_password_password" className="block text-sm font-medium text-gray-700">
                                            Nueva contraseña
                                        </label>
                                        <input
                                            id="update_password_password"
                                            name="password"
                                            type="password"
                                            value={password.data.password}
                                            onChange={(e) => password.setData('password', e.target.value)}
                                            autoComplete="new-password"
                                            className={inputClass}
                                        />
                                        <InputError message={errors.updatePassword?.password} />
                                    </div>

                                    <div>
                                        <label htmlFor="update_password_password_confirmation" className="block text-sm font-medium text-gray-700">
                                            Confirmar contraseña
                                        </label>
                                        <input
                                            id="update_password_password_confirmation"
                                            name="password_confirmation"
                                            type="password"
                                            value={password.data.password_confirmation}
                                            onChange={(e) => password.setData('password_confirmation', e.target.value)}
                                            autoComplete="new-password"
                                            className={inputClass}
                                        />
                                        <InputError message={errors.updatePassword?.password_confirmation} />
                                    </div>

                                    <div className="flex items-center gap-4">
                                        <button type="submit" disabled={password.processing} className={primaryButtonClass}>
                                            {password.processing ? 'Guardando...' : 'Guardar'}
                                        </button>

                                        <SavedNotice show={flash?.info === 'password-updated'} />
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>

                    <div className={cardClass}>
                        <div className="max-w-xl">
                            <section>
                                <header>
                                    <h2 className="text-lg font-medium text-gray-900">
                                        Accesibilidad
                                    </h2>

                                    <p className="mt-1 text-sm text-gray-600">
                                        Ajusta el tamaño del texto y de toda la vista para mejorar la legibilidad.
                                    </p>
                                </header>

                                <form onSubmit={submitAccessibility} className="mt-6 space-y-6">
                                    <div>
                                        <span className="block text-sm font-medium text-gray-700">
                                            Tamaño del texto
                                        </span>

                                        <div className="mt-2 grid grid-cols-1 sm:grid-cols-3 gap-2">
                                            {textScaleOptions.map((option) => (
                                                <label key={option.value} className="cursor-pointer">
                                                    <input
                                                        type="radio"
                                                        name="text_scale"
                                                        value={option.value}
                                                        checked={accessibility.data.text_scale === option.value}
                                                        onChange={() => accessibility.setData('text_scale', option.value)}
                                                        className="peer sr-only"
                                                    />
                                                    <span className="block rounded-lg border border-gray-300 p-3 text-center text-sm font-medium text-gray-700 transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:ring-1 peer-checked:ring-indigo-500">
                                                        {option.label}
                                                    </span>
                                                </label>
                                            ))}
                                        </div>

                                        <InputError message={errors.text_scale} />
                                    </div>

                                    <div>
                                        <span className="block text-sm font-medium text-gray-700">
                                            Tamaño de la vista
                                        </span>

                                        <div className="mt-2 grid grid-cols-2 sm:grid-cols-4 gap-2">
                                            {viewScaleOptions.map((option) => (
                                                <label key={option.value} className="cursor-pointer">
                                                    <input
                                                        type="radio"
                                                        name="view_scale"
                                                        value={option.value}
                                                        checked={accessibility.data.view_scale === option.value}
                                                        onChange={() => accessibility.setData('view_scale', option.value)}
                                                        className="peer sr-only"
                                                    />
                                                    <span className="block rounded-lg border border-gray-300 p-3 text-center text-sm font-medium text-gray-700 transition peer-checked:border-indigo-500 peer-checked:bg-indigo-50 peer-checked:text-indigo-700 peer-checked:ring-1 peer-checked:ring-indigo-500">
                                                        {option.label}
                                                    </span>
                                                </label>
                                            ))}
                                        </div>

                                        <InputError message={errors.view_scale} />
                                    </div>

                                    <div className="flex items-center gap-4">
                                        <button type="submit" disabled={accessibility.processing} className={primaryButtonClass}>
                                            {accessibility.processing ? 'Guardando...' : 'Guardar'}
                                        </button>

                                        <SavedNotice show={flash?.info === 'accessibility-updated'} />
                                    </div>
                                </form>
                            </section>
                        </div>
                    </div>

                    <div className={cardClass}>
                        <div className="max-w-xl">
                            <section className="space-y-6">
                                <header>
                                    <h2 className="text-lg font-medium text-gray-900">
                                        Eliminar cuenta
                                    </h2>

                                    <p className="mt-1 text-sm text-gray-600">
                                        Una vez eliminada tu cuenta, todos sus recursos y datos serán eliminados permanentemente. Antes de eliminarla, descarga cualquier dato o información que desees conservar.
                                    </p>
                                </header>

                                <button
                                    type="button"
                                    onClick={() => setConfirmOpen(true)}
                                    className="inline-flex items-center justify-center rounded-lg bg-red-600 px-5 py-2.5 text-sm font-bold text-white transition-colors hover:bg-red-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-red-500 focus-visible:ring-offset-2"
                                >
                                    Eliminar cuenta
                                </button>
                            </section>
                        </div>
                    </div>
                </div>
            </div>

            {confirmOpen && (
                <div className="fixed inset-0 z-50 flex items-center justify-center p-4">
                    <div
                        className="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
                        onClick={() => setConfirmOpen(false)}
                        aria-hidden="true"
                    ></div>

                    <div className="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-2xl">
                        <form onSubmit={submitDeletion} className="space-y-6">
                            <h2 className="text-lg font-medium text-gray-900">
                                ¿Estás seguro de que deseas eliminar tu cuenta?
                            </h2>

                            <p className="text-sm text-gray-600">
                                Una vez eliminada tu cuenta, todos sus recursos y datos serán eliminados permanentemente. Ingresa tu contraseña para confirmar que deseas eliminar tu cuenta de forma definitiva.
                            </p>

                            <div>
                                <label htmlFor="delete_password" className="sr-only">
                                    Contraseña
                                </label>

                                <input
                                    id="delete_password"
                                    name="password"
                                    type="password"
                                    value={deletion.data.password}
                                    onChange={(e) => deletion.setData('password', e.target.value)}
                                    placeholder="Contraseña"
                                    className="mt-1 block w-3/4 rounded-lg border-slate-200 bg-slate-50/80 text-sm font-medium text-slate-900 outline-none transition-all duration-300 hover:border-slate-300 hover:bg-white focus:border-blue-500 focus:bg-white focus:ring-4 focus:ring-blue-500/10"
                                />

                                <InputError message={errors.userDeletion?.password} />
                            </div>

                            <div className="flex justify-end">
                                <button
                                    type="button"
                                    onClick={() => setConfirmOpen(false)}
                                    className="rounded-lg border border-slate-300 px-4 py-2 text-sm font-bold text-slate-600 transition-colors hover:bg-slate-50"
                                >
                                    Cancelar
                                </button>

                                <button
                                    type="submit"
                                    disabled={deletion.processing}
                                    className="ms-3 inline-flex items-center justify-center rounded-lg bg-red-600 px-4 py-2 text-sm font-bold text-white transition-colors hover:bg-red-700 disabled:cursor-not-allowed disabled:opacity-60"
                                >
                                    {deletion.processing ? 'Eliminando...' : 'Eliminar cuenta'}
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            )}
        </>
    )
}

ProfileEdit.layout = (page) => <AppLayout>{page}</AppLayout>
