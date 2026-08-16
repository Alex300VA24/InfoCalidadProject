export default function RingGauge({ value, label, size = 116 }) {
    const boundedValue = Math.min(Math.max(Number(value) || 0, 0), 100)
    const radius = 46
    const circumference = 2 * Math.PI * radius
    return (
        <figure className="dash-ring" aria-label={`${label}: ${boundedValue}%`}>
            <svg width={size} height={size} viewBox="0 0 116 116" role="img">
                <circle className="dash-ring__track" cx="58" cy="58" r={radius} />
                <circle className="dash-ring__value" cx="58" cy="58" r={radius} strokeDasharray={circumference} strokeDashoffset={circumference * (1 - boundedValue / 100)} />
                <text x="58" y="63" textAnchor="middle">{boundedValue}%</text>
            </svg>
            <figcaption>{label}</figcaption>
        </figure>
    )
}
