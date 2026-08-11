import { Link } from '@inertiajs/react'

const toRelative = (url) => {
    if (!url) return null
    try {
        const parsed = new URL(url, window.location.origin)
        return parsed.pathname + parsed.search
    } catch {
        return url
    }
}

const stripLabel = (label) =>
    String(label).replace(/&laquo;/g, '«').replace(/&raquo;/g, '»').replace(/&nbsp;/g, ' ')

export default function Pagination({ links }) {
    if (!links || links.length <= 3) return null

    return (
        <nav className="flex flex-wrap items-center justify-center gap-1.5" aria-label="Paginación">
            {links.map((link, index) => {
                const href = toRelative(link.url)

                if (!href) {
                    return (
                        <span
                            key={index}
                            className="inline-flex h-9 min-w-9 items-center justify-center rounded-lg border border-slate-200 px-2 text-sm text-slate-300"
                            aria-disabled="true"
                        >
                            {stripLabel(link.label)}
                        </span>
                    )
                }

                return (
                    <Link
                        key={index}
                        href={href}
                        preserveScroll
                        className={`inline-flex h-9 min-w-9 items-center justify-center rounded-lg border px-2 text-sm font-semibold transition-colors ${
                            link.active
                                ? 'border-navy bg-navy text-white'
                                : 'border-slate-200 text-slate-600 hover:bg-slate-50'
                        }`}
                        dangerouslySetInnerHTML={{ __html: stripLabel(link.label) }}
                    />
                )
            })}
        </nav>
    )
}
