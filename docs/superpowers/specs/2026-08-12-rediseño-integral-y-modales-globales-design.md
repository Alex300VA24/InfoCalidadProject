# Rediseño integral y modales globales

Fecha: 2026-08-12

## Objetivo

Unificar dashboard, landing pública, autenticación y operaciones CRUD bajo un sistema visual institucional coherente. Las acciones Crear, Ver y Editar se abrirán en modales responsivos sin alterar la URL de la página principal. Los procesos largos o especializados conservarán navegación de página completa.

## Alcance

Incluye:

- Rediseño completo del dashboard institucional.
- Rediseño de la landing pública.
- Mejora del inicio de sesión y pantallas relacionadas de autenticación.
- Modales para Crear, Ver y Editar en todos los módulos CRUD pendientes.
- Sistema compartido de tokens, componentes, estados y comportamiento responsivo.
- Verificación funcional, visual y accesible.

No incluye:

- Convertir evaluaciones, reportes, padrones, estadísticas o flujos extensos en modales.
- Cambiar reglas de negocio, permisos, modelos o contratos del backend salvo ajustes mínimos indispensables para presentar contenido dentro del modal.
- Cambiar rutas públicas existentes ni depender del historial del navegador para cerrar modales.

## Dirección visual

La interfaz usará una identidad institucional moderna: azul marino como base, azul funcional para acciones, dorado sobrio como acento, fondos claros y profundidad suave. La tipografía actual se conservará para mantener legibilidad y evitar una mezcla visual inconsistente. Material Symbols seguirá como familia única de iconos.

El sistema respetará:

- Contraste WCAG AA.
- Objetivos interactivos mínimos de 44 x 44 px.
- Estados visibles de foco, carga, error, éxito y deshabilitado.
- Ritmo de espaciado basado en múltiplos de 4 y 8 px.
- Transiciones entre 150 y 300 ms.
- `prefers-reduced-motion`.
- Breakpoints de referencia: 375, 768, 1024 y 1440 px.

## Arquitectura visual compartida

Los estilos globales expondrán tokens semánticos para superficies, texto, bordes, acciones, estados, radios, sombras, espaciado y movimiento. Dashboard, landing, autenticación y modales consumirán esos tokens sin introducir paletas independientes.

Los componentes compartidos cubrirán botones, campos, mensajes, tarjetas, encabezados, estados vacíos, indicadores, esqueletos y ventanas modales. Se mantendrán componentes pequeños con una responsabilidad clara; las páginas solo compondrán contenido y configurarán comportamiento.

## Entrega 1: base visual

- Consolidar tokens semánticos existentes.
- Normalizar botones, campos, tarjetas, foco y estados.
- Evitar colores y sombras ad hoc cuando exista un token equivalente.
- Mantener compatibilidad con las pantallas actuales durante la migración.

## Entrega 2: dashboard

El dashboard funcionará como centro de control académico:

- Encabezado contextual con saludo, rol, fecha y periodo activo.
- Estado de salud académica con explicación legible y no dependiente solo del color.
- KPIs con contexto y formato consistente.
- Panel de calidad y distribuciones académicas con estados vacíos.
- Accesos directos filtrados por permisos.
- Estadísticas administrativas separadas de indicadores académicos.
- Composición adaptable de una columna en móvil a retícula amplia en escritorio.

No se modificarán las fórmulas o fuentes de datos existentes durante el rediseño visual.

## Entrega 3: landing pública

La landing explicará el valor institucional antes de solicitar acceso:

1. Navegación simple y CTA de acceso.
2. Hero con propuesta de valor y vista previa visual del sistema.
3. Indicadores o señales de confianza institucional basados únicamente en información disponible.
4. Áreas del ciclo académico.
5. Beneficios y forma de trabajo.
6. Llamado final a iniciar sesión.
7. Footer institucional.

No se inventarán cifras, acreditaciones ni testimonios.

## Entrega 4: autenticación

Login, recuperación, restablecimiento, confirmación, verificación y registro compartirán una composición institucional común:

- Panel de contexto institucional y panel de formulario.
- Etiquetas persistentes, ayuda y errores junto al campo.
- Mostrar u ocultar contraseña con control accesible.
- Estado de procesamiento sin saltos de layout.
- Navegación clara hacia inicio y acciones relacionadas.
- Diseño de una columna en móvil y composición dividida cuando el ancho lo permita.

## Entrega 5: sistema modal global

`ModalFrame` será la única infraestructura para páginas CRUD embebidas. Usará un portal a `document.body`, bloqueará el scroll del documento principal y permanecerá centrado respecto de toda la ventana.

Comportamiento obligatorio:

- La URL principal no cambia al abrir o cerrar.
- Cerrar no realiza recarga completa.
- Tras una operación exitosa, se actualizan datos Inertia preservando scroll y estado cuando sea necesario.
- Escape y botón cerrar funcionan, salvo durante una operación no interrumpible.
- Clic en fondo puede cerrar cuando no existen cambios pendientes.
- Cambios sin guardar solicitan confirmación.
- El foco entra al modal, queda contenido dentro y vuelve al disparador al cerrar.
- Encabezado, contenido y pie se adaptan a móvil, paisaje y escritorio.
- El iframe recibe `modal=1` para ocultar el shell duplicado.
- El modal muestra esqueleto mientras carga y un estado de error recuperable si la carga falla.

Cada apertura proporcionará título, ruta, tamaño, rutas de salida y política de actualización. La presentación no estará acoplada a Gestión Curricular; el contexto institucional será configurable por módulo.

## Entrega 6: migración de CRUD

Se auditarán todos los índices y acciones visibles. Crear, Ver y Editar usarán el modal global en:

- Admisión.
- Matrícula.
- Evaluaciones.
- Ejecución curricular.
- Grados, títulos y certificados.
- Egresados y encuestas.
- Movilidad.
- Tutoría y programas de nivelación.
- Investigación.
- Convenios.
- Actas de comité.
- Recursos y sílabos pendientes.
- Cualquier otro CRUD detectado en rutas y páginas.

Evaluar, revisar técnicamente, responder procesos complejos, generar reportes, consultar padrones y ver estadísticas permanecerán como páginas completas. Descargar archivos seguirá como navegación o descarga normal.

## Flujo de datos y cierre

El índice conserva estado del modal en React. Al abrir, pasa ruta y metadatos a `ModalFrame`. El contenido embebido ejecuta solicitudes Inertia normales. Si navega hacia una ruta de salida declarada, el padre cierra el modal y recarga solo los datos necesarios. Si el usuario cancela, el padre cierra sin navegación ni recarga completa.

No se usará `history.pushState`, `history.back` ni `window.location.reload` para controlar modales.

## Errores y casos límite

- Error de validación: permanece dentro del modal y conserva datos.
- Error de red: muestra feedback dentro del formulario o estado recuperable del marco.
- Sesión expirada o acceso denegado: permite que la respuesta del servidor conduzca al flujo de autenticación autorizado.
- Contenido alto: solo el cuerpo del modal desplaza; encabezado y pie permanecen disponibles.
- Pantalla pequeña o teclado virtual: el diálogo ocupa el viewport seguro sin desbordamiento horizontal.
- Aperturas repetidas: no duplican listeners ni portales.

## Verificación

- Compilación Vite sin errores.
- Pruebas Laravel relevantes para páginas y permisos.
- Verificación de Crear, Ver, Editar, guardar, cancelar y cerrar en cada módulo.
- Confirmación de que URL principal permanece estable.
- Confirmación de que no existe recarga completa al cerrar.
- Navegación por teclado, foco inicial, trampa y restauración.
- Contraste, etiquetas accesibles y objetivos interactivos.
- Pruebas visuales en 375, 768, 1024 y 1440 px, además de móvil en paisaje.
- Verificación con movimiento reducido.

## Orden de implementación

1. Base visual compartida.
2. Infraestructura modal global y pruebas del patrón.
3. Dashboard.
4. Landing pública.
5. Autenticación.
6. Migración de CRUD por módulo.
7. Auditoría visual, accesible y funcional final.

Este orden reduce retrabajo: primero estabiliza componentes reutilizados y después migra superficies y módulos.
