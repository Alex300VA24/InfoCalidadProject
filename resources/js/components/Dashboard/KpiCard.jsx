export default function KpiCard({ icon, label, value, unit, context, trend, tone = 'strategic' }) {
    return (
        <article className={`dash-kpi dash-kpi--${tone}`}>
            <div className="dash-kpi__top">
                <span className="material-symbols-outlined" aria-hidden="true">{icon}</span>
                {trend && <span className="dash-kpi__trend">{trend}</span>}
            </div>
            <p>{label}</p>
            <strong>{value}<small>{unit}</small></strong>
            {context && <span className="dash-kpi__context">{context}</span>}
        </article>
    )
}
