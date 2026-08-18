import { useState } from 'react'
import { Head, Link, useForm, usePage } from '@inertiajs/react'
import GuestLayout from '../../layouts/GuestLayout'
import { AuthPanel, errorClass, fieldClass, noticeClass, primaryButtonClass, secondaryLinkClass } from '../../components/auth/AuthPanel'

export default function Login() {
    const { flash } = usePage().props
    const { data, setData, post, processing, errors, reset } = useForm({ email: '', password: '', remember: false })
    const [showPassword, setShowPassword] = useState(false)
    const submit = (event) => { event.preventDefault(); post('/login', { onFinish: () => reset('password') }) }

    return <><Head title="Iniciar sesión" /><AuthPanel icon="login" title="Bienvenido de nuevo" description="Use sus credenciales institucionales para ingresar al centro de control académico.">
        {flash?.info && <div className={noticeClass} role="status">{flash.info}</div>}
        <form onSubmit={submit} className="space-y-5">
            <div><label htmlFor="email" className="mb-2 block text-sm font-bold text-ink-800">Correo electrónico</label><input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} autoComplete="username" autoFocus required placeholder="usuario@unitru.edu.pe" className={fieldClass} />{errors.email && <p className={errorClass}>{errors.email}</p>}</div>
            <div><div className="mb-2 flex items-center justify-between gap-3"><label htmlFor="password" className="text-sm font-bold text-ink-800">Contraseña</label><Link href="/forgot-password" className={`${secondaryLinkClass} inline-flex min-h-11 items-center`}>¿Olvidó su contraseña?</Link></div><div className="relative"><input id="password" type={showPassword ? 'text' : 'password'} value={data.password} onChange={(e) => setData('password', e.target.value)} autoComplete="current-password" required placeholder="Ingrese su contraseña" className={`${fieldClass} pr-12`} /><button type="button" onClick={() => setShowPassword((value) => !value)} aria-label={showPassword ? 'Ocultar contraseña' : 'Mostrar contraseña'} aria-pressed={showPassword} className="absolute inset-y-0 right-0 min-w-11 px-3 text-ink-500 hover:text-brand-700 focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-brand-400"><span className="material-symbols-outlined text-[20px]">{showPassword ? 'visibility_off' : 'visibility'}</span></button></div>{errors.password && <p className={errorClass}>{errors.password}</p>}</div>
            <label className="flex w-fit items-center gap-3 text-sm font-medium text-ink-700"><input type="checkbox" checked={data.remember} onChange={(e) => setData('remember', e.target.checked)} className="rounded border-ink-300 text-brand-700 focus:ring-brand-500" />Recordar sesión</label>
            <button type="submit" disabled={processing} aria-busy={processing} className={`${primaryButtonClass} w-full gap-2`}><span className={`material-symbols-outlined text-[19px] ${processing ? 'auth-spin' : ''}`}>{processing ? 'progress_activity' : 'lock_open'}</span>{processing ? 'Verificando acceso…' : 'Ingresar al sistema'}</button>
            <p className="border-t border-ink-100 pt-5 text-center text-sm text-ink-600">¿No tiene una cuenta? <Link href="/register" className={`${secondaryLinkClass} inline-flex min-h-11 items-center`}>Registrarse</Link></p>
        </form>
        <Link href="/" className={`${secondaryLinkClass} mt-6 inline-flex min-h-11 items-center gap-2`}><span className="material-symbols-outlined text-[18px]">arrow_back</span>Volver a la página de inicio</Link>
    </AuthPanel></>
}
Login.layout = (page) => <GuestLayout>{page}</GuestLayout>
