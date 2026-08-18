function percentageTone(value) {
    if (value < 50) return 'critical'
    if (value < 80) return 'attention'
    return 'ok'
}

export function deriveAcademicHealth({ activePeriod, kpis = {} }) {
    if (!activePeriod) {
        return {
            state: 'neutral',
            icon: 'event_busy',
            label: 'Sin periodo activo',
            description: 'No hay un periodo académico activo registrado.',
            metrics: [],
        }
    }

    const hasData = Number(kpis.totalVacantes) > 0 || Number(kpis.matriculados) > 0
    if (!hasData) {
        return {
            state: 'neutral',
            icon: 'hourglass_empty',
            label: 'Datos disponibles',
            description: 'Aún no se registran suficientes datos del periodo para evaluar su estado.',
            metrics: [],
        }
    }

    const cobertura = Number(kpis.cobertura) || 0
    const tasaMatricula = Number(kpis.tasaMatricula) || 0
    const worst = Math.min(cobertura, tasaMatricula)
    const metrics = [
        { label: 'Cobertura', value: `${kpis.cobertura ?? 0}%` },
        { label: 'Matrícula', value: `${kpis.tasaMatricula ?? 0}%` },
    ]

    // Regla de producto: menos de 50% es crítico y menos de 80% requiere atención.
    if (worst < 50) {
        return {
            state: 'critical', icon: 'error', label: 'Crítico',
            description: 'La cobertura de vacantes o la tasa de matrícula están por debajo del 50%.', metrics,
        }
    }
    if (worst < 80) {
        return {
            state: 'attention', icon: 'warning', label: 'Atención',
            description: 'Algunos indicadores de admisión o matrícula están por debajo del objetivo (80%).', metrics,
        }
    }
    return {
        state: 'ok', icon: 'check_circle', label: 'Correcto',
        description: 'Los indicadores de admisión y matrícula se encuentran dentro de los rangos esperados.', metrics,
    }
}

export { percentageTone }
