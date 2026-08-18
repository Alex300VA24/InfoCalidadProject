import EmptyState from './EmptyState'
import MeterBar from './MeterBar'
import RingGauge from './RingGauge'
import { percentageTone } from '../../utils/dashboardHealth'

export default function QualityPanel({ kpis = {} }) {
    return (
        <section className="dash-quality" aria-labelledby="quality-title">
            <div className="dash-section-head"><div><h2 id="quality-title">Salud académica</h2><p>Indicadores del periodo y resultados de formación.</p></div><span className="material-symbols-outlined" aria-hidden="true">monitoring</span></div>
            <div className="dash-quality__main">
                <div className="dash-quality__outcomes">
                    {Number(kpis.encuestas) === 0 ? <EmptyState icon="assignment_late" /> : (
                        <><RingGauge value={kpis.insercionLaboral ?? 0} label="Inserción laboral" /><div className="dash-competency"><span>Logro de competencias</span><strong>{kpis.competenciaPromedio ?? 0}<small>/20</small></strong><p>Promedio registrado en encuestas de egresados.</p></div></>
                    )}
                </div>
                <div className="dash-quality__meters">
                    <MeterBar label="Cobertura de vacantes" value={kpis.cobertura ?? 0} tone={percentageTone(Number(kpis.cobertura) || 0)} />
                    <MeterBar label="Tasa de matrícula" value={kpis.tasaMatricula ?? 0} tone={percentageTone(Number(kpis.tasaMatricula) || 0)} />
                </div>
            </div>
            <dl className="dash-quality__secondary">
                <div><dt>Vacantes ofrecidas</dt><dd>{kpis.totalVacantes ?? 0}</dd></div>
                <div><dt>Ingresantes</dt><dd>{kpis.ingresantes ?? 0}</dd></div>
                <div><dt>Inserción laboral</dt><dd>{kpis.insercionLaboral ?? 0}%</dd></div>
                <div><dt>Encuestas de egresados</dt><dd>{kpis.encuestas ?? 0}</dd></div>
            </dl>
        </section>
    )
}
