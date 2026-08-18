import { Head, useForm } from '@inertiajs/react'
import GuestLayout from '../../layouts/GuestLayout'
import { AuthPanel, errorClass, fieldClass, primaryButtonClass } from '../../components/auth/AuthPanel'
import PasswordField from '../../components/auth/PasswordField'
import AuthSubmitButton from '../../components/auth/AuthSubmitButton'

export default function ConfirmPassword() {
    const { data, setData, post, processing, errors, reset } = useForm({ password: '' })
    const submit = (event) => { event.preventDefault(); post('/confirm-password', { onFinish: () => reset('password') }) }
    return <><Head title="Confirmar contraseña" /><AuthPanel icon="shield_lock" title="Confirme su identidad" description="Esta acción protege información sensible. Ingrese nuevamente su contraseña para continuar."><form onSubmit={submit} className="space-y-5"><PasswordField id="password" label="Contraseña" value={data.password} onChange={(e) => setData('password', e.target.value)} error={errors.password} autoFocus /><AuthSubmitButton processing={processing} idleLabel="Confirmar y continuar" processingLabel="Confirmando identidad…" icon="shield_lock" /></form></AuthPanel></>
}
ConfirmPassword.layout = (page) => <GuestLayout>{page}</GuestLayout>
