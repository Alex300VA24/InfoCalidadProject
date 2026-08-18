export default function AcademicHealthBadge({ state, label, description, icon, metrics }) {
    return (
        <section className={`dash-health dash-health--${state}`} aria-labelledby="academic-health-title">
            <span className="dash-health__icon material-symbols-outlined" aria-hidden="true">{icon}</span>
            <div className="dash-health__copy">
                <p>Estado académico</p>
                <h2 id="academic-health-title">{label}</h2>
                <span>{description}</span>
            </div>
            {metrics.length > 0 && (
                <dl className="dash-health__metrics">
                    {metrics.map((metric) => <div key={metric.label}><dt>{metric.label}</dt><dd>{metric.value}</dd></div>)}
                </dl>
            )}
        </section>
    )
}
