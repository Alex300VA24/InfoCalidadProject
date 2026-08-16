import { Link } from '@inertiajs/react'

export default function ModuleCard({ href, icon, title, description, tag, variant }) {
    return (
        <Link href={href} className={`dash-module ${variant}`}>
            <span className="dash-module__icon material-symbols-outlined" aria-hidden="true">{icon}</span>
            <span className="dash-module__arrow material-symbols-outlined" aria-hidden="true">arrow_forward</span>
            <h3>{title}</h3><p>{description}</p><small>{tag}</small>
        </Link>
    )
}
