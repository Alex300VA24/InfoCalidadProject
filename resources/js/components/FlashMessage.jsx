import { useEffect, useState } from 'react'
import { usePage } from '@inertiajs/react'

const STYLES = {
    success: 'border-green-200 bg-green-50 text-green-800',
    error: 'border-red-200 bg-red-50 text-red-700',
    warning: 'border-gold-300 bg-gold-100 text-gold-900',
    info: 'border-brand-200 bg-brand-50 text-brand-900',
}

const ICONS = {
    success: 'check_circle',
    error: 'error',
    warning: 'warning',
    info: 'info',
}

export default function FlashMessage() {
    const { flash } = usePage().props

    const type = flash?.success
        ? 'success'
        : flash?.error
            ? 'error'
            : flash?.warning
                ? 'warning'
                : flash?.info
                    ? 'info'
                    : null

    const message = type ? flash[type] : null
    const [dismissed, setDismissed] = useState(false)

    useEffect(() => {
        if (!message) return undefined

        setDismissed(false)
        const timer = setTimeout(() => setDismissed(true), 5000)

        return () => clearTimeout(timer)
    }, [message])

    if (!type || !message || dismissed) return null

    return (
        <div
            role="status"
            className={`flex items-center gap-3 rounded-xl border px-4 py-3 text-sm font-medium shadow-soft ${STYLES[type]}`}
        >
            <span className="material-symbols-outlined text-lg" aria-hidden="true">
                {ICONS[type]}
            </span>

            <span className="flex-1">{message}</span>

            <button
                type="button"
                onClick={() => setDismissed(true)}
                className="rounded-md p-1 opacity-60 transition hover:opacity-100"
                aria-label="Cerrar mensaje"
            >
                <span className="material-symbols-outlined text-base">close</span>
            </button>
        </div>
    )
}
