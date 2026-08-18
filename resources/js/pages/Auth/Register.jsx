import { Head, Link, useForm } from '@inertiajs/react'
import GuestLayout from '../../layouts/GuestLayout'
import { AuthPanel, errorClass, fieldClass, primaryButtonClass, secondaryLinkClass } from '../../components/auth/AuthPanel'
import PasswordField from '../../components/auth/PasswordField'
import AuthSubmitButton from '../../components/auth/AuthSubmitButton'

export default function Register({ roles }) {
    const { data, setData, post, processing, errors } = useForm({ name: '', email: '', role_id: roles.find((role) => role.slug === 'docente')?.id ?? roles[0]?.id ?? '', password: '', password_confirmation: '' })
    const submit = (event) => { event.preventDefault(); post('/register') }
    return <><Head title="Crear cuenta" /><AuthPanel icon="person_add" title="Cree su cuenta" description="Complete sus datos para solicitar acceso a la Plataforma de Calidad Académica."><form onSubmit={submit} className="space-y-5">
        <div><label htmlFor="name" className="mb-2 block text-sm font-bold text-ink-800">Nombre completo</label><input id="name" type="text" value={data.name} onChange={(e) => setData('name', e.target.value)} autoComplete="name" autoFocus required placeholder="Nombre y apellidos" className={fieldClass} />{errors.name && <p className={errorClass}>{errors.name}</p>}</div>
        <div><label htmlFor="email" className="mb-2 block text-sm font-bold text-ink-800">Correo electrónico</label><input id="email" type="email" value={data.email} onChange={(e) => setData('email', e.target.value)} autoComplete="username" required placeholder="usuario@unitru.edu.pe" className={fieldClass} />{errors.email && <p className={errorClass}>{errors.email}</p>}</div>
        <div><label htmlFor="role_id" className="mb-2 block text-sm font-bold text-ink-800">Rol de acceso</label><select id="role_id" value={data.role_id} onChange={(e) => setData('role_id', e.target.value)} className={fieldClass}>{roles.map((role) => <option key={role.id} value={role.id}>{role.name}</option>)}</select>{errors.role_id && <p className={errorClass}>{errors.role_id}</p>}</div>
        <div className="grid gap-5 sm:grid-cols-2"><PasswordField id="password" label="Contraseña" value={data.password} onChange={(e) => setData('password', e.target.value)} error={errors.password} autoComplete="new-password" placeholder="Mín. 8 caracteres" /><PasswordField id="password_confirmation" label="Confirmar contraseña" value={data.password_confirmation} onChange={(e) => setData('password_confirmation', e.target.value)} error={errors.password_confirmation} autoComplete="new-password" placeholder="Repita la contraseña" /></div>
        <AuthSubmitButton processing={processing} idleLabel="Crear cuenta" processingLabel="Registrando cuenta…" icon="person_add" />
        <p className="text-center text-sm text-ink-600">¿Ya tiene cuenta? <Link href="/login" className={secondaryLinkClass}>Iniciar sesión</Link></p>
    </form></AuthPanel></>
}
Register.layout = (page) => <GuestLayout>{page}</GuestLayout>
