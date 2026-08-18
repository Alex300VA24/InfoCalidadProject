import { useEffect, useRef, useState } from 'react'
import { Link, router, usePage } from '@inertiajs/react'
import ConfirmModal from './Modal/ConfirmModal'

const sectionFor = (path) => {
    if (path.startsWith('/curriculum') || path.startsWith('/syllabi') || path.startsWith('/resources')) return 'Gestión Curricular'
    if (path.startsWith('/admission') || path.startsWith('/enrollment')) return 'Gestión del Ingreso'
    if (path.startsWith('/degrees') || path.startsWith('/graduates')) return 'Resultados de la Formación'
    if (path.startsWith('/evaluations') || path.startsWith('/execution') || path.startsWith('/tutoring') || path.startsWith('/mobility') || path.startsWith('/research')) return 'Enseñanza y Aprendizaje'
    return 'Centro de Control Académico'
}

export default function Topbar({ onMenuClick }) {
    const { auth } = usePage().props
    const { url } = usePage()
    const user = auth?.user
    const [openPanel, setOpenPanel] = useState(null)
    const [scrolled, setScrolled] = useState(false)
    const [logoutOpen, setLogoutOpen] = useState(false)
    const [loggingOut, setLoggingOut] = useState(false)
    const actionsRef = useRef(null)

    const handleLogout = () => {
        setLoggingOut(true)
        router.post('/logout', {}, {
            onFinish: () => {
                setLoggingOut(false)
                setLogoutOpen(false)
            },
        })
    }

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 8)
        window.addEventListener('scroll', onScroll, { passive: true })
        return () => window.removeEventListener('scroll', onScroll)
    }, [])

    useEffect(() => {
        if (!openPanel) return undefined
        const onKey = (event) => event.key === 'Escape' && setOpenPanel(null)
        const onClick = (event) => actionsRef.current && !actionsRef.current.contains(event.target) && setOpenPanel(null)
        document.addEventListener('keydown', onKey)
        document.addEventListener('pointerdown', onClick)
        return () => {
            document.removeEventListener('keydown', onKey)
            document.removeEventListener('pointerdown', onClick)
        }
    }, [openPanel])

    const toggle = (panel) => setOpenPanel((current) => current === panel ? null : panel)

    return (
        <header className={`app-topbar ${scrolled ? 'is-scrolled' : ''}`}>
            <div className="app-topbar__identity">
                <button type="button" className="topbar-icon lg:hidden" onClick={onMenuClick} aria-label="Abrir navegación">
                    <span className="material-symbols-outlined" aria-hidden="true">menu</span>
                </button>
                <span className="app-topbar__seal material-symbols-outlined" aria-hidden="true">verified_user</span>
                <span className="app-topbar__title">
                    <strong>Sistema de Gestión de Calidad</strong>
                    <small>{sectionFor(url.split('?')[0])}</small>
                </span>
            </div>

            <div className="app-topbar__actions" ref={actionsRef}>
                <div className="notification-menu">
                    <button
                        type="button"
                        className="nexo-notification"
                        aria-label="Abrir notificaciones"
                        aria-haspopup="dialog"
                        aria-expanded={openPanel === 'notifications'}
                        aria-controls="notification-panel"
                        onClick={() => toggle('notifications')}
                    >
                        <span className="material-symbols-outlined" aria-hidden="true">notifications</span>
                    </button>
                    <div id="notification-panel" className={`notification-panel ${openPanel === 'notifications' ? 'is-open' : ''}`} role="dialog" aria-label="Notificaciones" aria-hidden={openPanel !== 'notifications'} inert={openPanel === 'notifications' ? undefined : ''}>
                        <header><div><strong>Notificaciones</strong><small>Actividad institucional</small></div><button type="button" onClick={() => setOpenPanel(null)} aria-label="Cerrar notificaciones"><span className="material-symbols-outlined" aria-hidden="true">close</span></button></header>
                        <div className="notification-panel__empty"><span className="material-symbols-outlined" aria-hidden="true">notifications_off</span><strong>Todo está al día</strong><p>No tienes notificaciones pendientes.</p></div>
                    </div>
                </div>

                <div className="user-menu">
                    <button type="button" className="user-menu__btn" aria-expanded={openPanel === 'profile'} aria-haspopup="menu" onClick={() => toggle('profile')}>
                        <span className="user-chip">
                            <span className="user-chip__avatar">{user?.name?.charAt(0)?.toUpperCase() ?? '?'}</span>
                            <span className="hidden sm:block"><strong>{user?.name}</strong><small>{user?.role}</small></span>
                            <span className="material-symbols-outlined hidden sm:block" aria-hidden="true">expand_more</span>
                        </span>
                    </button>
                    <div className={`user-menu__panel ${openPanel === 'profile' ? 'is-open' : ''}`} role="menu" aria-hidden={openPanel !== 'profile'} inert={openPanel === 'profile' ? undefined : ''}>
                        <div className="user-menu__header"><strong>{user?.name}</strong><small>{user?.role} · {user?.email}</small></div>
                        <Link href="/profile" prefetch="hover" className="user-menu__item" role="menuitem" onClick={() => setOpenPanel(null)}><span className="material-symbols-outlined" aria-hidden="true">settings</span><span>Configuración</span></Link>
                        <div className="user-menu__divider"></div>
                        <div className="user-menu__item user-menu__item--logout" role="menuitem"><button type="button" onClick={() => { setOpenPanel(null); setLogoutOpen(true) }}><span className="material-symbols-outlined" aria-hidden="true">logout</span><span>Cerrar sesión</span></button></div>
                    </div>
                </div>
            </div>

            <ConfirmModal
                open={logoutOpen}
                title="¿Cerrar sesión?"
                message="Se cerrará tu sesión actual y deberás iniciar sesión nuevamente para continuar."
                confirmLabel="Cerrar sesión"
                tone="primary"
                processing={loggingOut}
                onConfirm={handleLogout}
                onCancel={() => setLogoutOpen(false)}
            />
        </header>
    )
}
