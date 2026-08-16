export default function EmptyState({ icon = 'inbox', message = 'Todavía no existen datos suficientes para calcular este indicador.' }) {
    return (
        <div className="dash-empty" role="status">
            <span className="material-symbols-outlined" aria-hidden="true">{icon}</span>
            <p>{message}</p>
        </div>
    )
}
