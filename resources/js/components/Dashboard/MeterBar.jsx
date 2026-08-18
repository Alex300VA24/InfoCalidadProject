export default function MeterBar({ label, value, tone, valueLabel }) {
    const boundedValue = Math.min(Math.max(Number(value) || 0, 0), 100)
    return (
        <div className={`dash-meter dash-meter--${tone}`}>
            <div><span>{label}</span><strong>{valueLabel ?? `${value}%`}</strong></div>
            <div className="dash-meter__track" role="meter" aria-label={label} aria-valuemin="0" aria-valuemax="100" aria-valuenow={boundedValue}>
                <span style={{ width: `${boundedValue}%` }}></span>
            </div>
        </div>
    )
}
