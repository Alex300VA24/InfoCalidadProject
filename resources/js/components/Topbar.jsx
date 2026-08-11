import { useEffect, useRef, useState } from 'react'
import { Link, usePage } from '@inertiajs/react'

export default function Topbar({ onMenuClick }) {
    const { auth } = usePage().props
    const user = auth?.user

    const [menuOpen, setMenuOpen] = useState(false)
    const [scrolled, setScrolled] = useState(false)
    const menuRef = useRef(null)

    useEffect(() => {
        const onScroll = () => setScrolled(window.scrollY > 8)
        window.addEventListener('scroll', onScroll, { passive: true })
        return () => window.removeEventListener('scroll', onScroll)
    }, [])

    useEffect(() => {
        if (!menuOpen) return undefined

        const onKey = (e) => {
            if (e.key === 'Escape') setMenuOpen(false)
        }
        const onClick = (e) => {
            if (menuRef.current && !menuRef.current.contains(e.target)) setMenuOpen(false)
        }

        document.addEventListener('keydown', onKey)
        document.addEventListener('click', onClick)

        return () => {
            document.removeEventListener('keydown', onKey)
            document.removeEventListener('click', onClick)
        }
    }, [menuOpen])

    return (
        <header className={`app-topbar ${scrolled ? 'is-scrolled' : ''}`}>
            <button
                type="button"
                className="topbar-icon lg:hidden"
                onClick={onMenuClick}
                aria-label="Abrir navegación"
            >
                <span className="material-symbols-outlined">menu</span>
            </button>

            <label className="nexo-search"></label>

            <div className="app-topbar__actions">
                <button type="button" className="nexo-notification" aria-label="Notificaciones">
                    <span className="material-symbols-outlined">notifications</span>
                    <i></i>
                </button>

                <div className="user-menu" ref={menuRef}>
                    <button
                        type="button"
                        className="user-menu__btn"
                        aria-expanded={menuOpen}
                        aria-haspopup="menu"
                        onClick={() => setMenuOpen((open) => !open)}
                    >
                        <span className="user-chip">
                            <span className="user-chip__avatar">
                                {user?.name?.charAt(0)?.toUpperCase() ?? '?'}
                            </span>
                            <span className="hidden sm:block">
                                <strong>{user?.name}</strong>
                                <small>{user?.role}</small>
                            </span>
                            <span className="material-symbols-outlined hidden sm:block">expand_more</span>
                        </span>
                    </button>

                    <div
                        className={`user-menu__panel ${menuOpen ? 'is-open' : ''}`}
                        role="menu"
                        aria-hidden={!menuOpen}
                    >
                        <div className="user-menu__header">
                            <strong>{user?.name}</strong>
                            <small>{user?.role} · {user?.email}</small>
                        </div>

                        <Link
                            href="/profile"
                            className="user-menu__item"
                            role="menuitem"
                            onClick={() => setMenuOpen(false)}
                        >
                            <span className="material-symbols-outlined">settings</span>
                            <span>Configuración</span>
                        </Link>

                        <div className="user-menu__divider"></div>

                        <div className="user-menu__item user-menu__item--logout" role="menuitem">
                            <Link
                                href="/logout"
                                method="post"
                                as="button"
                                type="button"
                                onClick={() => setMenuOpen(false)}
                            >
                                <span className="material-symbols-outlined">logout</span>
                                <span>Cerrar sesión</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </header>
    )
}
