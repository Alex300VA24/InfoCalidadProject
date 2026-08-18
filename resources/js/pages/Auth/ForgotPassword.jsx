import { Head, Link, useForm, usePage } from '@inertiajs/react'
import GuestLayout from '../../layouts/GuestLayout'
import { AuthPanel, errorClass, fieldClass, noticeClass, primaryButtonClass, secondaryLinkClass } from '../../components/auth/AuthPanel'
import AuthSubmitButton from '../../components/auth/AuthSubmitButton'

export default function ForgotPassword() {
    const { flash } = usePage().props
    const { data, setData, post, processing, errors } = useForm({ email: '' })
    const submit = (event) => { event.preventDefault(); post('/forgot-password') }
    return <><Head title="Recuperar contraseña" /><AuthPanel icon="lock_reset" title="Recupere su acceso" description="Indique su correo institucional. Le enviaremos un enlace seguro para crear una nueva contraseña.">
        {flash?.info && <div className={noticeClass} role="status">{flash.info}</div>}
        <form onSubmit={submit} className="space-y-5"><div><label htmlFor="email" className="mb-2 block text-sm font-bold text-ink-800">Correo electrónico</label><input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} autoComplete="username" autoFocus required placeholder="usuario@unitru.edu.pe" aria-invalid={Boolean(errors.email)} aria-describedby={errors.email ? 'email-error' : undefined} className={fieldClass} />{errors.email && <p id="email-error" className={errorClass}>{errors.email}</p>}</div><AuthSubmitButton processing={processing} idleLabel="Enviar enlace de recuperación" processingLabel="Enviando enlace…" icon="send" /></form>
        <Link href="/login" className={`${secondaryLinkClass} mt-6 inline-flex items-center gap-2`}><span className="material-symbols-outlined text-[18px]">arrow_back</span>Volver al inicio de sesión</Link>
    </AuthPanel></>
}
ForgotPassword.layout = (page) => <GuestLayout>{page}</GuestLayout>
