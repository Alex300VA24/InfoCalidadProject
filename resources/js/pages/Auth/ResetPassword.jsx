import { Head, Link, useForm } from '@inertiajs/react'
import GuestLayout from '../../layouts/GuestLayout'
import { AuthPanel, errorClass, fieldClass, primaryButtonClass, secondaryLinkClass } from '../../components/auth/AuthPanel'
import PasswordField from '../../components/auth/PasswordField'
import AuthSubmitButton from '../../components/auth/AuthSubmitButton'

export default function ResetPassword({ token, email }) {
    const { data, setData, post, processing, errors, reset } = useForm({ token, email, password: '', password_confirmation: '' })
    const submit = (event) => { event.preventDefault(); post('/reset-password', { onFinish: () => reset('password', 'password_confirmation') }) }
    return <><Head title="Restablecer contraseña" /><AuthPanel icon="password" title="Cree una nueva contraseña" description="Elija una contraseña segura para recuperar el acceso a su cuenta institucional."><form onSubmit={submit} className="space-y-5">
        <div><label htmlFor="email" className="mb-2 block text-sm font-bold text-ink-800">Correo electrónico</label><input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} autoComplete="username" required className={fieldClass} />{errors.email && <p className={errorClass}>{errors.email}</p>}</div>
        <PasswordField id="password" label="Nueva contraseña" value={data.password} onChange={(e) => setData('password', e.target.value)} error={errors.password} autoComplete="new-password" autoFocus placeholder="Mín. 8 caracteres" />
        <PasswordField id="password_confirmation" label="Confirmar contraseña" value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} error={errors.password_confirmation} autoComplete="new-password" placeholder="Repita la contraseña" />
        <AuthSubmitButton processing={processing} idleLabel="Guardar nueva contraseña" processingLabel="Restableciendo acceso…" icon="password" />
    </form><Link href="/login" className={`${secondaryLinkClass} mt-6 inline-flex items-center gap-2`}><span className="material-symbols-outlined text-[18px]">arrow_back</span>Volver al inicio de sesión</Link></AuthPanel></>
}
ResetPassword.layout = (page) => <GuestLayout>{page}</GuestLayout>
