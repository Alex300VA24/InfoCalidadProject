import { Head, Link, useForm, usePage } from '@inertiajs/react'
import GuestLayout from '../../layouts/GuestLayout'
import { AuthPanel, errorClass, fieldClass, noticeClass, primaryButtonClass, secondaryLinkClass } from '../../components/auth/AuthPanel'

export default function ForgotPassword() {
    const { flash } = usePage().props
    const { data, setData, post, processing, errors } = useForm({ email: '' })
    const submit = (event) => { event.preventDefault(); post('/forgot-password') }
    return <><Head title="Recuperar contraseña" /><AuthPanel icon="lock_reset" title="Recupere su acceso" description="Indique su correo institucional. Le enviaremos un enlace seguro para crear una nueva contraseña.">
        {flash?.info && <div className={noticeClass} role="status">{flash.info}</div>}
        <form onSubmit={submit} className="space-y-5"><div><label htmlFor="email" className="mb-2 block text-sm font-bold text-ink-800">Correo electrónico</label><input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} autoComplete="username" autoFocus required placeholder="usuario@unitru.edu.pe" className={fieldClass} />{errors.email && <p className={errorClass}>{errors.email}</p>}</div><button type="submit" disabled={processing} className={`${primaryButtonClass} w-full`}>{processing ? 'Enviando…' : 'Enviar enlace de recuperación'}</button></form>
        <Link href="/login" className={`${secondaryLinkClass} mt-6 inline-flex items-center gap-2`}><span className="material-symbols-outlined text-[18px]">arrow_back</span>Volver al inicio de sesión</Link>
    </AuthPanel></>
}
ForgotPassword.layout = (page) => <GuestLayout>{page}</GuestLayout>
