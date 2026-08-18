# Funcionalidad del Sistema — Plataforma de Gestión Académica

Análisis técnico de los módulos que componen la plataforma: propósito, datos necesarios, dependencias entre datos y estado funcional.

**Stack:** Laravel 13 (PHP 8.3) + `nwidart/laravel-modules` + PostgreSQL con esquemas separados.

**Esquemas de base de datos:**
| Esquema | Módulo |
|---|---|
| `core` | Core |
| `app_gestion_curricular` | GestionCurricular |
| `app_gestion_ingreso` | GestionIngreso |
| `app_ensenanza_aprendizaje` | EnsenanzaAprendizaje |
| `app_resultados_formacion` | ResultadosFormacion |

---

## Resumen global de dependencias de datos

```
                    ┌──────────── CORE (esquema core) ────────────┐
                    │  users · roles · students · careers        │
                    │  academic_periods · subjects · faculties   │
                    └───────────────────┬────────────────────────┘
        ┌──────────────┬───────────────┼───────────────┬───────────────┐
        ▼              ▼               ▼               ▼               ▼
 GestionCurricular  GestionIngreso  EnsenanzaAprendizaje  ResultadosFormacion
 (app_gestion_...)  (app_gestion_)  (app_ensenanza_...)   (app_resultados_...)
  usa: users,       usa: careers,    usa: students,        usa: students,
  careers, subjects, periods,        subjects, periods,    users (asesores)
  periods, users     students, users  users, syllabi(GC)   y Graduate→GraduateSurvey
                                    (+academic_tutoring/remedial de sí mismo,
                                     consultados por GestionIngreso)
```

**Regla clave:** todos los módulos dependen de **Core** (modelos, tablas, roles y Gates definidos en `Modules/Core/app/Providers/CoreServiceProvider.php:52-80`). Core no debe desactivarse. `EnsenanzaAprendizaje` además referencia `app_gestion_curricular.syllabi`.

**Veredicto global:** la aplicación arranca, migra y tiene datos semilla. Es **funcional**. Único bloqueo real: el flujo central de GestionCurricular no tiene datos de catálogo. Además hay una migración pendiente de ejecutar.

---

## 1. Core

### Qué hace
Autenticación y registro de usuarios, gestión de perfil (incluida accesibilidad), dashboard central de KPIs y el **catálogo maestro**: roles, facultades, carreras, periodos académicos, asignaturas y estudiantes. Define los Gates de autorización por rol que usan los demás módulos.

### Datos necesarios por registro
| Registro | Campos obligatorios | Opcionales |
|---|---|---|
| User | `name`, `email`, `password` (+confirmación), `role_id` | `career_id`, `dni`, `telefono` (en BD, no se capturan en el formulario) |
| Role | `name`, `slug` (único) | `description`, `is_active` |
| Faculty | `code` (único), `name` | `description`, `is_active` |
| Career | `code` (único), `name` | `faculty_id`, `description`, `is_active` |
| AcademicPeriod | `name` (único) | `start_date`, `end_date`, `is_active` |
| Subject | `career_id` (FK), `code` (único), `name` | `credits`, `hours`, `type`, `is_active` |
| Student | `user_id` (FK única), `codigo` (único) | `ciclo`, `fecha_nacimiento`, `direccion`, `estado` |

### Dependencias de datos
- No depende de ningún módulo. Es la base de todos.
- El dashboard **lee** datos de GestionIngreso (admisión/matrícula) y ResultadosFormacion (encuestas de egresados); si no hay datos muestra ceros sin romper.

### Estado: ✅ Funcional
- BD sembrada: 14 usuarios, 11 roles, 4 carreras, 3 periodos, 3 estudiantes, 5 asignaturas.
- Todas las rutas, vistas, controladores y Gates verificados.
- **Pendiente:** ejecutar la migración `2026_08_10_000001_add_accessibility_settings_to_users_table` (está *pending*); buscador vacío en el topbar; URLs hardcodeadas en la landing.

---

## 2. GestionCurricular

### Qué hace
- **Revisión curricular** con lista de cotejo (Presidente de Cotejo): inicia revisiones por carrera/periodo, evalúa criterios (0-5) y asigna tipo de acción curricular.
- **Informes técnicos**: redacción, edición, finalización y descarga en PDF.
- **Aprobaciones**: el Director de Escuela dictamina (aprobado/observado).
- **Repositorio de sílabos**: carga/descarga PDF por asignatura-periodo-docente y visado.
- **Solicitudes de recursos**: solicitud de recursos y adjunto del documento de respuesta.

### Datos necesarios por registro
| Registro | Campos obligatorios | Opcionales |
|---|---|---|
| Revisión curricular | `checklist_template_id`, `academic_period_id` | `career_id` (default por carrera), `action_type_id` (al completar), `observations` |
| Evaluación de criterios | `scores[]` (0-5 por criterio) | `observations[]` |
| Informe técnico | `content` | — |
| Aprobación | `decision` (approved/observed) | `comments` |
| Sílabo | `subject_id`, `academic_period_id`, `teacher_id`, archivo PDF | `career_id`, `version` (default '1.0') |
| Solicitud de recurso | `title`, `request_type` (bibliographic/hemerographic/equipment), `academic_period_id` | `description`, `documents[]`, `attachments[]` |
| Documento de respuesta | `document_number`, `subject`, archivo PDF | — |

### Dependencias de datos
- **Core:** `core.users` (revisor/preparador/aprobador/visador/solicitante), `core.careers`, `core.subjects`, `core.academic_periods`.
- No depende de otros módulos.

### Estado: ⚠️ Parcialmente funcional
- **El flujo central NO es usable de fábrica:** el seeder del módulo está vacío y **no existen datos** de `checklist_templates` (plantillas) ni `action_types` (tipos de acción), confirmado en BD (ambos en 0). Sin esos catálogos no se puede crear ni completar una revisión. Sílabos y solicitudes de recursos sí operan.
- Riesgos detectados: subir 2 veces el mismo sílabo en el mismo periodo rompe el unique (la versión siempre es '1.0') → error 500; accesos `->actionType->name` sin null-safe en 3 vistas; estados `in_process/rejected` de recursos no tienen flujo que los asigne; `resources.add-response` no verifica rol de secretaría.

---

## 3. GestionIngreso

### Qué hace
- **Admisión:** procesos/convocatorias (CRUD, abrir/cerrar), registro de postulantes, registro de resultados (score y estado ingresante/no_ingresante), **conversión automática de ingresante → estudiante** (crea User + Student en Core) y constancias de ingreso en PDF.
- **Matrícula:** registro con asignación de asignaturas, ficha de matrícula PDF, orden de pago PDF, registro de pagos, padrón virtual y reportes (egresados, cronograma).

### Datos necesarios por registro
| Registro | Campos obligatorios | Opcionales |
|---|---|---|
| Proceso de admisión | `name`, `academic_period_id`, `career_id`, `vacancies`, `modality`, `status` | `start_date`, `end_date` |
| Postulante | `admission_process_id`, `dni`, `paterno`, `nombres`, `career_id` | `materno`, `email`, `telefono` |
| Resultado de postulante | `score` (0-100), `status` (ingresante/no_ingresante) | — |
| Matrícula | `student_id`, `academic_period_id`, `career_id`, `subjects[]` (mín. 1) | `matricula_fee` |
| Pago | `receipt_number` | — |

### Dependencias de datos
- **Core:** `core.careers`, `core.academic_periods`, `core.subjects`, `core.students`, `core.users`, `core.roles`.
- **EnsenanzaAprendizaje (opcional):** consulta `academic_tutoring` y `remedial_programs` para requisitos de matrícula (protegido con `Schema::hasTable`).

### Estado: ✅ Funcional (con bugs menores)
- BD sembrada: 1 proceso de admisión, 3 matrículas.
- **Bugs:** inconsistencia de estado (`activo` en la UI/validación vs `abierto` en el modelo); `saveResult` no acepta "postulante" aunque la UI lo ofrece; código de matrícula con `max(id)+1` (riesgo de colisión); sin validación amigable de DNI duplicado (da 500).

---

## 4. EnsenanzaAprendizaje

### Qué hace
- **Evaluación del estudiante:** notas por tipo (prácticas, parcial, final, extraordinario), promedios ponderados, generación/cierre/descarga de **actas oficiales PDF**.
- **Ejecución del plan curricular:** cargas académicas, sesiones de clase, socialización de sílabos, avance de ejecución de asignaturas, evaluación de desempeño docente.
- **Tutoría académica:** tutorías y programas de nivelación/recuperación.
- **Movilidad y becas:** solicitudes de movilidad, becas y gestión de convenios.
- **Investigación formativa:** proyectos de investigación de estudiantes.

### Datos necesarios por registro
| Registro | Campos obligatorios | Opcionales |
|---|---|---|
| Evaluación | `student_id`, `subject_id`, `academic_period_id`, `evaluation_type`, `score` (0-20), `evaluation_date` | `observations` |
| Sesión de clase | `subject_id`, `academic_period_id`, `topic`, `hours`, `session_date`, `status` | `teacher_id`, `notes` |
| Tutoría | `student_id`, `academic_period_id`, `tutoring_date`, `type`, `status` | `tutor_id`, `reason`, `outcome` |
| Movilidad | `student_id`, `academic_period_id`, `type`, `application_date`, `status` | `destination_institution`, `program_name`, `scholarship_name`, fechas, `notes`, `agreement_id` |
| Proyecto de investigación | `student_id`, `academic_period_id`, `title`, `status` | `advisor_id`, `description`, `area`, `score` (0-20), fechas, `document` |
| Carga académica | `teacher_id`, `subject_id`, `academic_period_id`, `hours` | `section` |
| Socialización | `syllabus_id`, `date` | `evidence_path`, `notes`, `registered_by` |
| Ejecución de asignatura | `subject_id`, `academic_period_id`, `progress_pct` (0-100), `status` | `teacher_id`, `syllabus_id` |
| Desempeño docente | `teacher_id`, `academic_period_id`, `score` (0-20), `source` | `observations`, `evaluated_at` |
| Convenio | `name`, `institution`, `type`, `status` | `description`, fechas, `document_path` |
| Programa de nivelación | `student_id`, `academic_period_id`, `status` | `subject_id`, `description`, `plan_path` |

### Dependencias de datos
- **Core:** `core.students`, `core.subjects`, `core.academic_periods`, `core.users` (docentes/tutores/registradores).
- **GestionCurricular:** `app_gestion_curricular.syllabi` (socializaciones y ejecución).

### Estado: ✅ Funcional
- 51 rutas y 31 vistas verificadas; todos los controladores y modelos existen.
- Detalles: `agreement_id` en movilidad no está conectado a la UI; `StoreOfficialActRequest` es código muerto; el seeder crea tutorías `completada` que la validación no acepta; para PDFs/archivos requiere `storage/app/actas` y `storage/app/public/research_projects`.

---

## 5. ResultadosFormacion

### Qué hace
- **Grados y títulos:** certificados PDF (estudios, prácticas, constancia de egresado, constancia de matrícula), expedientes de grado/título con flujo de estados (`en_tramite → revisado → aprobado → otorgado → observado`) y actas de comité (sustentación/suficiencia).
- **Seguimiento de egresados:** registro de egresados, encuestas por periodo y estadísticas de inserción laboral.

### Datos necesarios por registro
| Registro | Campos obligatorios | Opcionales |
|---|---|---|
| Certificado | `student_id`, `type`, `concept`, `issued_at` | `issued_by` |
| Expediente de grado | `student_id`, `type`, `application_date` | `thesis_title`, `advisor_id`, `resolution_date`, `resolution_number`, `notes` |
| Acta de comité | `degree_application_id` (inyectado), `act_type`, `result`, `score` (0-20) | `session_date`, `pdf_path` |
| Egresado | `student_id`, `work_status` | `graduation_date`, `employer`, `job_position`, `monthly_income`, `survey_date`, `employment_relationship`, `observations` |
| Encuesta | `period`, `survey_date`, `employed` | `job_related_to_career`, `competency_level_score`, `income`, `observations` |

### Dependencias de datos
- **Core:** `core.students` (certificados, expedientes, egresados) y `core.users` (asesor del expediente).
- No usa GestionIngreso.

### Estado: ✅ Funcional
- 22 rutas verificadas con `route:list`; vistas y modelos completos.
- Detalles: sin funcionalidad de borrado; las actas no permiten subir PDF desde la UI; códigos `CER-`/`EXP-` generados con `max(id)+1` (riesgo teórico de duplicados).

---

## Estado funcional por módulo

| Módulo | Estado | Observación |
|---|---|---|
| Core | ✅ Funcional | Migración de accesibilidad pendiente de correr |
| GestionCurricular | ⚠️ Parcial | Catálogos (checklist y tipos de acción) sin datos semilla → flujo central inutilizable |
| GestionIngreso | ✅ Funcional | Bugs menores de validación/estado |
| EnsenanzaAprendizaje | ✅ Funcional | Detalles de afinamiento |
| ResultadosFormacion | ✅ Funcional | Mejoras menores |

---

## Acciones recomendadas

1. Ejecutar la migración pendiente: `php artisan migrate`.
2. **GestionCurricular:** crear seeders para `action_types`, `checklist_templates` y `checklist_criteria` (único bloqueo real del sistema).
3. **GestionIngreso:** corregir el estado `activo` → `abierto`, la validación de "postulante" en `saveResult` y el manejo de DNI duplicado.
4. **Robustez general:** validar duplicados (sílabos, evaluaciones) con mensajes amigables y reemplazar `max(id)+1` por secuencias/códigos seguros.
