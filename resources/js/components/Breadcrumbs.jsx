import { Link } from '@inertiajs/react'

export default function Breadcrumbs({ items, backHref }) {
    return (
        <div className="breadcrumbs-bar">
            {backHref && (
                <Link href={backHref} className="breadcrumbs-back" aria-label="Regresar">
                    <span className="material-symbols-outlined" aria-hidden="true">arrow_back</span>
                </Link>
            )}
            <nav className="breadcrumbs" aria-label="Ruta de navegación">
                {items.map((item, index) => (
                    <span key={index} className="breadcrumbs__item">
                        {item.href ? (
                            <Link href={item.href} className="breadcrumbs__link">{item.label}</Link>
                        ) : (
                            <span className="breadcrumbs__current">{item.label}</span>
                        )}
                        {index < items.length - 1 && (
                            <span className="material-symbols-outlined breadcrumbs__sep" aria-hidden="true">chevron_right</span>
                        )}
                    </span>
                ))}
            </nav>
        </div>
    )
}
