# Refactor de Core, navegación y reactividad

Fecha: 2026-08-12

## Objetivo

Refactorizar Core y propagar un layout institucional consistente a Enseñanza y Aprendizaje, Gestión Curricular, Gestión del Ingreso y Resultados de la Formación. La interfaz debe reducir carga cognitiva, mantener contexto visible y responder inmediatamente a navegación y operaciones locales.

## Decisión aprobada

Se aplicará una evolución progresiva del sistema compartido. Se preservan rutas, permisos, contratos del backend y páginas actuales; Core concentra navegación, encabezado, modales, estados y precarga. Los módulos consumen estos componentes sin implementar variantes propias.

## Sidebar

- Las cinco áreas principales serán grupos desplegables: Principal y los cuatro módulos funcionales.
- El grupo correspondiente a la ruta activa permanecerá abierto automáticamente.
- Solo un grupo funcional estará expandido a la vez en escritorio para reducir longitud; Principal permanece visible.
- El estado de expansión se conserva durante la sesión.
- En modo compacto se muestran iconos con tooltip accesible.
- Los enlaces autorizados y la opción activa siguen derivados de permisos y URL existentes.
- Navegación Inertia usa precarga por intención (`hover` y `focus`) y conserva scroll cuando corresponda.
- En móvil, elegir una opción cierra el panel sin bloquear la navegación.

## Header

- Izquierda: icono institucional, título “Sistema de Gestión de Calidad” y contexto corto de la sección actual.
- Derecha: notificaciones y bloque de perfil alineados al extremo.
- Notificaciones abren un popover accesible, cerrable con Escape, clic exterior y control explícito.
- Sin datos de notificaciones, muestra estado vacío honesto; no se inventan alertas.
- Perfil y notificaciones son controles independientes con foco visible y objetivos mínimos de 44 px.

## ModalBase

- Portal directo a `document.body`, sin iframe ni navegación del documento principal.
- Apertura y cierre visual inmediatos; sin spinner, overlay o loader bloqueante.
- Anatomía única: header con título y X, body desplazable y footer de acciones.
- Formularios usan grid responsivo de una columna en móvil y hasta dos columnas en escritorio.
- Cada modal presenta una sola acción de cierre visual. Botones Cancelar internos redundantes se integran en el footer o se eliminan.
- El footer mantiene Cancelar como acción secundaria y Guardar/Confirmar como acción principal.
- Foco contenido, restauración al disparador, Escape y confirmación de cambios no guardados.
- El tamaño se adapta al contenido con límite del viewport; solo el body desplaza cuando el contenido lo exige.
- Las vistas modales pueden almacenarse en una caché corta por ruta y precargarse desde el disparador. La caché nunca sustituye una actualización posterior a una mutación.

## Carga y reactividad

- Se elimina `LoadingOverlay` del layout y cualquier indicador global asociado a navegación Inertia.
- Navegaciones locales conservan el contenido actual hasta recibir la vista siguiente, evitando pantallas vacías y parpadeos.
- Enlaces del Sidebar y disparadores de modal precargan componente y datos por intención.
- Apertura de modal muestra inmediatamente su marco y contenido cacheado disponible. Si una solicitud pesada supera 400 ms, solo el área de datos afectada muestra skeleton local.
- Submit deshabilita exclusivamente la acción principal y muestra micro-spinner dentro del botón, manteniendo el formulario visible.
- Cierre no recarga página. Tras guardar, se actualizan únicamente props necesarias con Inertia y se preservan estado y scroll.
- Optimistic UI se limita a cambios reversibles y deterministas; errores restauran estado y muestran recuperación junto a la acción.

## Consistencia modular

Los cuatro módulos heredarán el mismo shell, jerarquía, tokens, botones, formularios, tablas, estados vacíos y ModalBase. Crear, Ver y Editar usarán modal cuando sean tareas acotadas. Evaluaciones extensas, reportes, estadísticas, padrones y procesos multietapa seguirán como páginas completas.

## Accesibilidad y responsive

- Contraste WCAG AA, navegación por teclado, `aria-expanded`, `aria-current`, `aria-controls` y etiquetas para controles de icono.
- Objetivos táctiles mínimos de 44 × 44 px.
- Soporte para 375, 768, 1024 y 1440 px, teclado virtual y áreas seguras móviles.
- Movimiento funcional entre 150 y 220 ms, con `prefers-reduced-motion`.

## Manejo de errores

- Error al precargar no bloquea navegación normal.
- Error de contenido modal aparece dentro del body con reintento; el marco continúa operable.
- Error de validación conserva campos y foco en el primer error.
- Sesión expirada conserva el flujo autorizado del servidor.

## Verificación

- Pruebas de permisos y rutas existentes.
- Apertura y cierre modal sin cambio de URL, recarga ni loader global.
- Submit con estado exclusivo dentro del botón.
- Sidebar sin scroll prolongado en roles con muchas opciones.
- Popover de notificaciones accesible y estado vacío.
- Verificación visual y teclado en los cuatro breakpoints.
- Compilación Vite, pruebas Laravel relevantes y revisión de cambios sin errores de formato.

## Orden de implementación

1. Core: estado compartido, eliminación de loader global y precarga.
2. Sidebar jerárquico y Header institucional.
3. ModalBase, caché y contratos de acciones.
4. Propagación por los cuatro módulos.
5. Verificación funcional, responsive y accesible.
