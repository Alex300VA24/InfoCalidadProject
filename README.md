# Plataforma de Gestión Académica

Este repositorio implementa una aplicación web de gestión académica construida con Laravel 13, React y Inertia.js, con una arquitectura modular basada en `nwidart/laravel-modules`.

## Stack tecnológico

- Backend: Laravel 13 con PHP 8.3+
- Frontend: React 19 + Inertia.js
- Styling: Tailwind CSS 3 + PostCSS
- Bundler: Vite 8
- Base de datos: SQLite para desarrollo local y PostgreSQL para Docker
- Módulos: `Core`, `GestionCurricular`, `GestionIngreso`, `EnsenanzaAprendizaje`, `ResultadosFormacion`

## Arquitectura del proyecto

### Estructura general

- `app/`: aplicación principal de Laravel
- `bootstrap/`: bootstrap del framework
- `config/`: configuración global
- `database/`: migraciones y base de datos
- `resources/`: layout principal, CSS y JS global
- `routes/`: rutas del sistema principal
- `Modules/`: módulos funcionales
- `public/`: assets públicos
- `storage/`: logs y archivos temporales
- `vendor/`: dependencias Composer
- `docker-compose.yml`: entorno Docker para PostgreSQL + app + queue

### Organización por módulo

Cada módulo tiene una estructura estándar:

- `Modules/{Nombre}/app/`: lógica del módulo
- `Modules/{Nombre}/routes/web.php`: rutas específicas del módulo
- `Modules/{Nombre}/database/migrations/`: migraciones
- `Modules/{Nombre}/resources/js/`: vistas/React para Inertia
- `Modules/{Nombre}/tests/`: pruebas del módulo
- `Modules/{Nombre}/module.json`: metadata del módulo

### Módulos activos

Los módulos habilitados se encuentran en `modules_statuses.json` y actualmente son:

- `Core`
- `GestionCurricular`
- `GestionIngreso`
- `EnsenanzaAprendizaje`
- `ResultadosFormacion`

## Cómo funciona Inertia.js en este proyecto

Este proyecto no está usando Blade tradicional para cada pantalla; la navegación y la renderización de páginas se hace con Inertia.js.

### Flujo principal

1. Un controlador Laravel responde con `Inertia::render()`.
2. Laravel envía los props al frontend React.
3. React renderiza la vista correspondiente desde `Modules/{Modulo}/resources/js/Pages/...`.
4. La app principal se monta en `resources/views/app.blade.php` y se inicia con `resources/js/app.jsx`.

### Archivos clave

- `resources/views/app.blade.php`: layout base HTML del frontend
- `resources/js/app.jsx`: bootstrap de Inertia en React
- `resources/js/app.js`: Turbo + Alpine + utilidades frontend
- `vite.config.js`: configuración de Vite para Laravel + React + Inertia
- `Modules/*/app/Http/Controllers/*`: controladores que devuelven páginas Inertia

### Ejemplo de uso

```php
use Inertia\Inertia;

public function index()
{
    return Inertia::render('Dashboard/Index', [
        'stats' => [...],
        'activePeriod' => [...],
    ]);
}
```

Y la vista en React puede vivir en un módulo como:

```text
Modules/Core/resources/js/Pages/Dashboard/Index.jsx
```

El layout base de todas las páginas usa el componente `<x-inertia::app />` y la plantilla global de Blade.

## Requisitos previos

- PHP 8.3+
- Composer 2
- Node.js 18+ / npm
- Git
- Docker + Docker Compose (solo para entorno Docker)
- Windows: PowerShell o Git Bash

PHP recomendado:

- `pdo_sqlite`
- `pdo_pgsql`
- `openssl`
- `mbstring`
- `tokenizer`
- `xml`
- `ctype`
- `json`

## Instalación local

### 1) Clonar el proyecto

```bash
git clone <url-del-repositorio>
cd plataforma
```

### 2) Instalar dependencias PHP

```bash
composer install
```

### 3) Configurar el entorno

Crea el archivo `.env` a partir del ejemplo:

```bash
copy .env.example .env
```

En Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Luego genera la clave de Laravel:

```bash
php artisan key:generate
```

### 4) Configurar la base de datos local

El proyecto viene configurado por defecto con SQLite en `.env.example`:

```env
DB_CONNECTION=sqlite
```

Si el archivo `database/database.sqlite` no existe, créalo:

```bash
type nul > database\database.sqlite
```

o en bash:

```bash
touch database/database.sqlite
```

### 5) Ejecutar migraciones

```bash
php artisan migrate --force
```

> Si el proyecto usa módulos, las migraciones de cada módulo se cargarán automáticamente con `nwidart/laravel-modules`.

### 6) Instalar dependencias del frontend

```bash
npm install --ignore-scripts
```

### 7) Compilar assets del frontend

```bash
npm run build
```

### 8) Ejecutar la aplicación local

#### Opción recomendada: todo en un solo comando

```bash
composer run dev
```

Esto levanta:

- Laravel server
- queue worker
- pail logs
- Vite dev server

#### Opción manual

```bash
php artisan serve --host=127.0.0.1 --port=8000
```

En otra terminal:

```bash
npm run dev
```

La app quedará disponible en:

```text
http://127.0.0.1:8000
```

## Instalación con Docker

Este repositorio incluye un entorno Docker listo para levantar PostgreSQL y la app Laravel.

### Archivo de Docker

- `docker-compose.yml`
- `docker/php/Dockerfile`

### Servicios incluidos

- `db`: PostgreSQL 17
- `app`: Laravel servido con `php artisan serve`
- `queue`: worker de colas

### 1) Preparar el entorno Docker

Desde la raíz del proyecto:

```bash
copy .env.example .env.docker
```

Luego asegúrate de que `.env.docker` tenga la configuración de PostgreSQL:

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=plataforma_calidad
DB_USERNAME=postgres
DB_PASSWORD=admin123
```

### 2) Levantar los contenedores

```bash
docker compose up --build -d
```

Luego valida que estén corriendo:

```bash
docker compose ps
```

### 3) Ejecutar migraciones en el contenedor

```bash
docker compose exec app php artisan migrate --force
```

### 4) Verificar la app

La aplicación quedará disponible en:

```text
http://localhost:8000
```

### 5) Conectarte a la base PostgreSQL

```bash
docker compose exec db psql -U postgres -d plataforma_calidad
```

### 6) Rebuild o reinicio

```bash
docker compose down
docker compose up --build -d
```

### 7) Logs

```bash
docker compose logs -f app
```

## Variables de entorno importantes

### SQLite local

```env
APP_ENV=local
APP_KEY=
APP_DEBUG=true
DB_CONNECTION=sqlite
SESSION_DRIVER=database
QUEUE_CONNECTION=database
CACHE_STORE=database
```

### PostgreSQL Docker

```env
APP_ENV=local
APP_DEBUG=true
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=plataforma_calidad
DB_USERNAME=postgres
DB_PASSWORD=admin123
```

## Comandos útiles

### Laravel

```bash
php artisan serve
php artisan migrate
php artisan migrate:fresh --seed
php artisan route:list
php artisan module:list
php artisan test
php artisan config:clear
php artisan cache:clear
```

### Frontend

```bash
npm install
npm run dev
npm run build
npm run build:css
npm run build:js
```

### Docker

```bash
docker compose up -d
docker compose down
docker compose logs -f
docker compose exec app php artisan tinker
```

## Flujo de trabajo recomendado

### Para desarrollo local

1. `composer install`
2. `copy .env.example .env`
3. `php artisan key:generate`
4. `php artisan migrate`
5. `npm install`
6. `composer run dev`

### Para desarrollo con Docker

1. crear `.env.docker`
2. `docker compose up --build -d`
3. `docker compose exec app php artisan migrate --force`
4. abrir `http://localhost:8000`

## Consideraciones

- La raíz del proyecto es la aplicación Laravel; no hay una carpeta separada llamada `plataforma/`.
- Los módulos se registran y cargan automáticamente con `nwidart/laravel-modules`.
- La UI principal usa React + Inertia en lugar de vistas Blade puras.
- La base de datos PostgreSQL del entorno Docker queda en el puerto `5433` del host, no en el `5432` por defecto.
- El login y dashboard principal viven en el módulo `Core`.

## Licencia

Este repositorio usa licencia MIT, salvo indicación contraria de la organización o del proyecto.
