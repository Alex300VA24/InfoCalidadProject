export default function DashboardHeader({ firstName, greeting, dateLabel, roleLabel, activePeriod }) {
    return (
        <header className="dash-header">
            <div>
                <h1>{greeting}, {firstName || 'bienvenido'}.</h1>
                <p>Centro de Control Académico Institucional</p>
            </div>
            <dl className="dash-header__context">
                <div><dt>Fecha</dt><dd>{dateLabel}</dd></div>
                <div><dt>Rol</dt><dd>{roleLabel || 'Usuario institucional'}</dd></div>
                <div><dt>Periodo</dt><dd>{activePeriod?.name ?? 'Sin periodo activo'}</dd></div>
            </dl>
        </header>
    )
}
