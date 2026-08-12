# Rediseño del Dashboard: Centro de Control Académico Institucional

## Objetivo

Reemplazar la estética decorativa del dashboard por una interfaz institucional, sobria y operativa que permita reconocer en menos de diez segundos el periodo activo, la salud académica, los indicadores estratégicos y los asuntos que requieren atención.

El cambio se limita a UI/UX. No modifica rutas, middleware, modelos, controladores, props de Inertia ni reglas de permisos.

## Alcance y restricciones

- Conservar en `Dashboard/Index.jsx` `const { auth, can } = usePage().props` y `DashboardIndex.layout = (page) => <AppLayout>{page}</AppLayout>`.
- Conservar literalmente `const modules = MODULES.filter((module) => can[module.gate])`.
- No modificar `SECTIONS`, `can(ability)` ni el filtrado por gate del Sidebar.
- No modificar `DashboardController.php` ni la forma de `activePeriod`, `stats` o `kpis`.
- Reutilizar las funciones actuales de formato, redondeo y capitalización.
- Mantener visibles los textos y valores cubiertos por `DashboardKpisTest.php`: `Vacantes ofrecidas`, `Inserción laboral`, `Logro de competencias`, `Cobertura de vacantes`, `Ordinaria`, `5`, `50%` y `16/20`.
- No añadir dependencias de gráficos.
- No usar animaciones decorativas, glows permanentes, sweeps de brillo, desplazamientos hover superiores a 4 px ni color como única señal.
- Respetar `prefers-reduced-motion`.

## Dirección visual

La superficie operará como un centro de control académico, no como una landing SaaS. La jerarquía se apoya en tipografía, espacio, bordes y densidad; el color queda reservado para marca y estados semánticos. Los paneles usan superficies sólidas, sombras contenidas y radios coherentes.

Se consolidan los tokens de color, radio y sombra bajo `--color-*`, `--radius-*` y `--shadow-*`. El dashboard nuevo usa el prefijo `.dash-*`; las clases `.nexo-*` permanecen únicamente donde siguen siendo parte del shell compartido.

## Arquitectura

Los nuevos componentes vivirán en `resources/js/components/Dashboard/`:

- `DashboardHeader`: saludo contextual, fecha, rol y periodo activo.
- `AcademicHealthBadge`: estado académico con icono, etiqueta, explicación y métricas.
- `KpiCard`: KPI estratégica o secundaria, sin tendencias inventadas.
- `QualityPanel`: inserción laboral, cobertura, matrícula, competencias y KPIs secundarias.
- `MeterBar`: proporción expresada por longitud y texto.
- `RingGauge`: anillo SVG accesible con valor numérico central.
- `HorizontalBarChart`: barras horizontales de una serie y estado vacío.
- `ModuleCard`: acceso directo sujeto a gates existentes.
- `EmptyState`: mensaje profesional reutilizable.
- `AdminStatsRow`: datos administrativos de bajo peso visual.

La función pura `deriveAcademicHealth` vivirá en `resources/js/utils/dashboardHealth.js` para separar la regla de producto del renderizado.

## Estado académico

La evaluación usa cobertura de vacantes y tasa de matrícula porque son los indicadores disponibles más directamente vinculados con la salud del periodo.

- Sin periodo activo: estado neutral, `Sin periodo activo`.
- Sin vacantes ni matriculados: estado neutral, `Datos disponibles`, indicando que aún no hay datos suficientes.
- Peor indicador menor de 50%: `Crítico`.
- Peor indicador entre 50% y menos de 80%: `Atención`.
- Ambos indicadores iguales o superiores a 80%: `Correcto`.

Los umbrales de 50% y 80% son una regla fija de producto y quedarán documentados en el código. Los medidores de cobertura y matrícula usarán exactamente la misma clasificación para evitar contradicciones. Cada estado incluirá icono y texto además del color.

## Composición y flujo de datos

`Dashboard/Index.jsx` recibe los props existentes, deriva únicamente valores de presentación y entrega props simples a componentes especializados. El orden del DOM será:

1. Encabezado ejecutivo.
2. Estado académico.
3. Cuatro KPIs estratégicas: cobertura, tasa de matrícula, matriculados y logro de competencias.
4. Panel de salud académica y KPIs secundarias.
5. Gráficos de ingresantes por modalidad y matriculados por carrera, ordenados descendentemente.
6. Accesos directos filtrados por gate.
7. Estadísticas administrativas.

El saludo se calcula inline por hora. La modalidad conserva la capitalización actual. No se generan tendencias, objetivos ni datos que no provengan de los props existentes.

## Estados vacíos y accesibilidad

- Si `encuestas === 0`, el área dependiente de encuestas mostrará que todavía no existen datos suficientes para calcular el indicador.
- Cada gráfico vacío mostrará un estado vacío interno.
- El anillo SVG tendrá etiqueta accesible y valor textual real.
- Los medidores siempre expondrán su valor como texto.
- El link activo del Sidebar recibirá `aria-current="page"`.
- Los focos visibles tendrán contraste suficiente sobre fondo claro y sobre `--color-nav`.
- Navegación, accesos y controles conservarán objetivos táctiles de al menos 44 × 44 px.

## Shell compartido

En `Topbar.jsx` se elimina el buscador vacío y todo su CSS. También se elimina el punto rojo ficticio de notificaciones. La campana se conserva como botón simple si el elemento sigue siendo útil en la estructura; el menú de usuario no cambia.

En `Sidebar.jsx` solo se añade `aria-current` y se ajustan densidad, hover y foco mediante CSS. No se cambian secciones, rutas ni gates.

## Responsive

- Desde 1280 px: KPIs en cuatro columnas, panel de calidad en proporción 2:1, gráficos en dos columnas y módulos en tres.
- Entre 768 y 1279 px: KPIs en 2 × 2, panel de calidad y gráficos en una columna, módulos en dos.
- Hasta 767 px: todo en una columna y en el mismo orden del DOM, sin desplazamiento horizontal.

El diseño debe tolerar los ajustes existentes de `text_scale` y `view_scale` sin cortar contenido ni solapar controles.

## Verificación

- Ejecutar `php artisan test --filter=DashboardKpisTest`.
- Ejecutar el build frontend.
- Revisar en aproximadamente 1280 px, 900 px y 375 px.
- Confirmar estados con y sin periodo, datos, encuestas y breakdowns.
- Confirmar que distintos gates producen los mismos accesos permitidos que antes.
- Verificar `prefers-reduced-motion`, contraste WCAG AA, foco visible, objetivos táctiles y ausencia de scroll horizontal.

## Fuera de alcance

- Cambios de backend o base de datos.
- Nuevas fuentes de datos, objetivos o tendencias.
- Modificaciones de navegación o autorización.
- Dependencias de visualización.
- Refactors no relacionados de estilos o componentes fuera del dashboard y su integración mínima con Topbar y Sidebar.
