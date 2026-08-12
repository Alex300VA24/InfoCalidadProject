import { Head, useForm } from '@inertiajs/react'
import GuestLayout from '../../layouts/GuestLayout'
import { AuthPanel, errorClass, fieldClass, primaryButtonClass } from '../../components/auth/AuthPanel'

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({ password: '' })
    const submit = (event) => { event.preventDefault(); post('/confirm-password', { onFinish: () => reset('password') }) }
    return <><Head title="Confirmar contraseña" /><AuthPanel icon="shield_lock" title="Confirme su identidad" description="Esta acción protege información sensible. Ingrese nuevamente su contraseña para continuar."><form onSubmit={submit} className="space-y-5"><div><label htmlFor="password" className="mb-2 block text-sm font-bold text-ink-800">Contraseña</label><input id="password" type="password" value={data.password} onChange={(e) => setData('password', e.target.value)} autoComplete="current-password" autoFocus required placeholder="Ingrese su contraseña" className={fieldClass} />{errors.password && <p className={errorClass}>{errors.password}</p>}</div><button type="submit" disabled={processing} className={`${primaryButtonClass} w-full`}>{processing ? 'Confirmando…' : 'Confirmar y continuar'}</button></form></AuthPanel></>
}
ConfirmPassword.layout = (page) => <GuestLayout>{page}</GuestLayout>
