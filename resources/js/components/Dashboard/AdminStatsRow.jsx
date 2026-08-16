export default function AdminStatsRow({ stats }) {
    const entries = Object.entries(stats ?? {})
    if (!entries.length) return null
    return (
        <section className="dash-admin" aria-labelledby="admin-stats-title">
            <h2 id="admin-stats-title">Datos administrativos</h2>
            <dl>{entries.map(([label, value]) => <div key={label}><dt>{label}</dt><dd>{value}</dd></div>)}</dl>
        </section>
    )
}
