import { Head, useForm, usePage } from '@inertiajs/react'
import GuestLayout from '../../layouts/GuestLayout'
import { AuthPanel, noticeClass, primaryButtonClass, secondaryLinkClass } from '../../components/auth/AuthPanel'

export default function VerifyEmail() {
    const { flash } = usePage().props
    const resend = useForm({}); const logout = useForm({})
    const sendVerification = (event) => { event.preventDefault(); resend.post('/email/verification-notification') }
    const handleLogout = (event) => { event.preventDefault(); logout.post('/logout') }
    return <><Head title="Verificar correo electrónico" /><AuthPanel icon="mark_email_read" title="Verifique su correo" description="Abra el enlace enviado a su correo electrónico para confirmar su cuenta y continuar. Puede solicitar otro si no lo recibió.">{flash?.info === 'verification-link-sent' && <div className={noticeClass} role="status">Enviamos un nuevo enlace de verificación.</div>}<div className="flex flex-col gap-3"><button type="button" onClick={sendVerification} disabled={resend.processing} className={`${primaryButtonClass} w-full`}>{resend.processing ? 'Enviando…' : 'Reenviar correo de verificación'}</button><button type="button" onClick={handleLogout} disabled={logout.processing} className={`${secondaryLinkClass} min-h-11`}>Cerrar sesión</button></div></AuthPanel></>
}
VerifyEmail.layout = (page) => <GuestLayout>{page}</GuestLayout>
