import { useEffect, useState } from 'react'
import { usePage } from '@inertiajs/react'
import Sidebar from '../components/Sidebar'
import Topbar from '../components/Topbar'
import FlashMessage from '../components/FlashMessage'

export default function AppLayout({ children }) {
    const { auth } = usePage().props
    const user = auth?.user

    const [sidebarOpen, setSidebarOpen] = useState(false)
    const [sidebarCollapsed, setSidebarCollapsed] = useState(false)

    useEffect(() => {
        const root = document.documentElement
        const previous = root.style.fontSize

        if (user?.text_scale) {
            root.style.fontSize = `${user.text_scale}%`
        }

        return () => {
            root.style.fontSize = previous
        }
    }, [user?.text_scale])

    useEffect(() => {
        const root = document.documentElement
        const isMobile = window.matchMedia('(max-width: 1023px)').matches

        if (sidebarOpen && isMobile) {
            root.classList.add('sidebar-open')
        } else {
            root.classList.remove('sidebar-open')
        }

        return () => root.classList.remove('sidebar-open')
    }, [sidebarOpen])

    return (
        <div
            className={`app-shell ${sidebarCollapsed ? 'sidebar-collapsed' : ''}`}
            style={user?.view_scale ? { zoom: `${user.view_scale}%` } : undefined}
        >
            <div
                className={`sidebar-backdrop ${sidebarOpen ? 'is-visible' : ''}`}
                onClick={() => setSidebarOpen(false)}
                aria-hidden="true"
            ></div>

            <Sidebar
                collapsed={sidebarCollapsed}
                open={sidebarOpen}
                onOpenChange={setSidebarOpen}
            />

            <button
                type="button"
                className={`sidebar-toggle ${sidebarCollapsed ? 'is-collapsed' : ''}`}
                onClick={() => setSidebarCollapsed((collapsed) => !collapsed)}
                aria-label={sidebarCollapsed ? 'Expandir menú lateral' : 'Contraer menú lateral'}
                title={sidebarCollapsed ? 'Expandir menú lateral' : 'Contraer menú lateral'}
            >
                <span className="material-symbols-outlined" aria-hidden="true">
                    {sidebarCollapsed ? 'chevron_right' : 'chevron_left'}
                </span>
            </button>

            <div className="app-main page-enter">
                <Topbar onMenuClick={() => setSidebarOpen(true)} />

                <div className="mx-auto w-full max-w-[1440px] px-6 pt-4 sm:px-9">
                    <FlashMessage />
                </div>

                <main className="page-content">{children}</main>
            </div>
        </div>
    )
}
