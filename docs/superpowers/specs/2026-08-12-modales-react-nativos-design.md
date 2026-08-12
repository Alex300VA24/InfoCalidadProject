# Modales React nativos

Fecha: 2026-08-12

## Objetivo

Reemplazar los modales basados en `iframe` por modales React nativos. Crear, Ver y Editar se renderizarán dentro del árbol React actual, sin cargar una página Inertia independiente, cambiar URL o recargar el listado al cerrar.

## Decisión arquitectónica

`ModalFrame` será únicamente un contenedor visual y accesible. Recibirá componentes React como contenido y administrará portal, foco, cierre, scroll, tamaños, estados pendientes y responsive. No conocerá rutas, iframes ni navegación.

Cada módulo expondrá componentes de contenido independientes:

- Formulario de creación.
- Vista de detalle.
- Formulario de edición cuando exista.

Las páginas Inertia actuales podrán reutilizar esos componentes mientras dure la migración, pero las acciones desde índices abrirán contenido React nativo.

## Datos

Los datos específicos del modal se obtendrán mediante endpoints JSON protegidos por los mismos middleware, permisos y políticas actuales. Las listas pequeñas necesarias para crear podrán venir en el índice si ya están disponibles; datos pesados o detalles se solicitarán al abrir.

Cada respuesta JSON tendrá forma estable:

```json
{
  "data": {},
  "options": {},
  "meta": {}
}
```

Los formularios enviarán JSON o `FormData` mediante `fetch`, incluyendo token CSRF y método HTTP correspondiente. Las descargas seguirán usando navegación normal.

## Flujo

1. Usuario activa Crear, Ver o Editar.
2. Índice establece descriptor del modal.
3. `ModalFrame` aparece inmediatamente.
4. Componente solicita solo datos requeridos cuando sea necesario.
5. Guardar ejecuta solicitud sin navegación Inertia.
6. Respuesta exitosa actualiza listado mediante recarga parcial Inertia o estado local.
7. Modal cierra y muestra mensaje de éxito.
8. Cancelar cierra sin solicitud ni recarga.

## Estados

- El marco aparece sin esperar red.
- Carga pertenece al contenido, no a una segunda página.
- Crear puede mostrarse inmediatamente cuando sus opciones ya existen.
- Detalle y edición usan un esqueleto localizado.
- Errores de validación aparecen junto al campo.
- Error de red permite reintentar sin cerrar.
- Cambios sin guardar requieren confirmación.
- Guardado bloquea solo acciones del modal.

## Accesibilidad

- `role="dialog"`, `aria-modal="true"` y título asociado.
- Foco inicial dentro del modal.
- Trampa de foco y restauración al disparador.
- Escape y botón cerrar, salvo guardado activo.
- Objetivos interactivos mínimos de 44 px.
- Contenido desplazable sin ocultar encabezado o acciones.
- Movimiento reducido respetado.

## Compatibilidad

Las rutas web actuales permanecerán disponibles durante la migración para acceso directo y compatibilidad. No serán usadas para renderizar modales. Los controladores compartirán servicios o métodos de consulta con endpoints JSON para evitar duplicar reglas de negocio.

## Migración

1. Simplificar `ModalFrame` y crear cliente HTTP/CSRF compartido.
2. Gestión Curricular: revisiones, sílabos, recursos y aprobaciones.
3. Admisión y matrícula.
4. Evaluaciones y ejecución curricular.
5. Grados, egresados y movilidad.
6. Tutoría, investigación, convenios y restantes.
7. Eliminar `ModalLink` basado en rutas, estilos `.is-modal-frame` y soporte `?modal=1` cuando no existan consumidores.

Cada módulo se considera migrado cuando Crear, Ver y Editar aplicables funcionan sin `iframe`, URL nueva ni recarga al cancelar.

## Pruebas

- Pruebas HTTP para endpoints JSON, permisos y validación.
- Pruebas unitarias para cliente y transformación de errores.
- Smoke tests de rutas existentes.
- Verificación DOM: ningún modal contiene `iframe`.
- Verificación de cancelar sin solicitudes adicionales.
- Verificación de guardar, actualización del listado y feedback.
- Teclado, foco, 375/768/1024/1440 px y movimiento reducido.

## Exclusiones

Evaluaciones complejas, reportes, padrones y estadísticas continúan como páginas completas. Este cambio no modifica reglas de negocio ni permisos.
