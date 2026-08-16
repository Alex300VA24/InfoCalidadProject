import EmptyState from './EmptyState'

export default function HorizontalBarChart({ data, emptyMessage, ariaLabel }) {
    if (!data.length) return <EmptyState icon="bar_chart" message={emptyMessage} />
    const max = Math.max(...data.map((item) => Number(item.value) || 0), 1)
    return (
        <div className="dash-bars" role="img" aria-label={ariaLabel}>
            {data.map((item) => (
                <div className="dash-bars__row" key={item.label}>
                    <span title={item.label}>{item.label}</span>
                    <div><i style={{ width: `${((Number(item.value) || 0) / max) * 100}%` }}></i></div>
                    <strong>{item.value}</strong>
                </div>
            ))}
        </div>
    )
}
