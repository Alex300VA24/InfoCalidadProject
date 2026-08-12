# Plataforma de Gestión Académica

Este repositorio contiene una aplicación Laravel modular para la gestión académica universitaria. La aplicación principal está en este mismo repositorio y utiliza el paquete `nwidart/laravel-modules` para cargar los módulos desde `Modules/`.

## Stack

- **Backend:** Laravel 13 (PHP 8.3+) organizado en módulos independientes con sus propias rutas, controladores, migraciones y tests.
- **Frontend:** React 19 con Inertia.js 3 (renderizado dirigido por el servidor).
- **Bundling:** Vite 8 con el plugin de Laravel.
- **Estilos:** Tailwind CSS 3 con PostCSS.
- **Base de datos:** PostgreSQL 17 (vía Docker) o SQLite (desarrollo local).

## Estructura principal

- `/`: raíz del proyecto Laravel.
- `app/`: código principal de la aplicación Laravel.
- `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/`, `vendor/`: estructura clásica de Laravel.
- `Modules/`: módulos independientes de la aplicación. Cada módulo contiene su propio código (`app/`), migraciones y seeders (`database/`), rutas (`routes/`), componentes React (`resources/js/Pages/`) y tests.
- `Modules/{Modulo}/database/migrations/`: esquema de base de datos de cada módulo.
- `public/`: punto de entrada web.
- `package.json`: scripts frontend y dependencias React/Inertia/Vite/Tailwind.
- `composer.json`: dependencias PHP y scripts de Composer.
- `modules_statuses.json`: lista de módulos habilitados.
- `.env.example`: configuración de entorno.

## Requisitos previos

- PHP 8.3 o superior.
- Composer 2.
- Node.js y npm.
- Extensiones PHP recomendadas: `pdo_sqlite`, `pdo_pgsql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`.
- En Windows, PowerShell es útil para ejecutar comandos.

## Instalación

### Instalación rápida (recomendada)

El script `composer run setup` instala dependencias, crea el `.env`, genera la clave, ejecuta migraciones e instala/construye los assets:

```powershell
composer run setup
```

### Instalación paso a paso

1. Abrir terminal en la raíz del proyecto:

   ```powershell
   cd C:\Users\ASUS\Desktop\plataforma
   ```

2. Instalar dependencias PHP:

   ```powershell
   composer install
   ```

3. Crear el archivo de entorno:

   ```powershell
   copy .env.example .env
   ```

4. Generar la clave de aplicación:

   ```powershell
   php artisan key:generate
   ```

5. Ejecutar migraciones de base de datos (incluye las de los módulos):

   ```powershell
   php artisan migrate --force
   ```

6. Instalar dependencias JavaScript:

   ```powershell
   npm install --ignore-scripts
   ```

7. Construir los activos estáticos:

   ```powershell
   npm run build
   ```

### Nota sobre la base de datos

- Por defecto `.env.example` usa **SQLite** (`DB_CONNECTION=sqlite`), con el archivo `database/database.sqlite` ya presente. No requiere configuración adicional.
- Si quieres usar **PostgreSQL**, ajusta en tu `.env`: `DB_CONNECTION=pgsql`, `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` y `DB_SCHEMA` con los esquemas de los módulos. La forma más sencilla de levantar PostgreSQL es con Docker (ver más abajo).

## Instalación con Docker

El proyecto incluye una configuración Docker (`docker-compose.yml` y `docker/php/Dockerfile`) que levanta tres servicios:

- `calidad_unt_db`: base de datos PostgreSQL 17 (puerto `5433` del host).
- `calidad_unt_app`: aplicación web Laravel (puerto `8000`).
- `calidad_unt_queue`: worker de colas (`queue:listen`).

La base de datos usa la misma configuración que el `.env` de desarrollo (`plataforma_calidad`, usuario `postgres`, contraseña `admin123`), con los esquemas de los módulos (`core`, `app_gestion_*`).

### Requisitos previos

- Docker y Docker Compose instalados.

### Pasos

1. Crear el archivo de entorno del contenedor. Está basado en tu `.env`, pero apunta a la base de datos del servicio `db` (PostgreSQL). Si tu `.env` usa SQLite, asegúrate de que `.env.docker` tenga `DB_CONNECTION=pgsql`:

   ```powershell
   copy .env .env.docker
   ```

   Y en `.env.docker` cambia `DB_HOST` a `db` y, si es necesario, `DB_CONNECTION` a `pgsql`:

   ```powershell
   (Get-Content .env.docker -Raw) -replace 'DB_HOST=127.0.0.1', 'DB_HOST=db' | Set-Content .env.docker
   ```

2. Instalar dependencias PHP:

   ```powershell
   composer install
   ```

3. Instalar dependencias JavaScript y construir los activos estáticos (la imagen Docker no incluye Node/npm):

   ```powershell
   npm install --ignore-scripts
   npm run build
   ```

4. Construir y levantar los contenedores:

   ```powershell
   docker compose up --build -d
   ```

   La aplicación quedará disponible en `http://localhost:8000`.

5. Ejecutar las migraciones dentro del contenedor:

   ```powershell
   docker compose exec app php artisan migrate --force
   ```

   > Nota: los esquemas de los módulos deben existir antes de migrar. En un arranque limpio (volumen de BD nuevo), `docker-compose.yml` los crea automáticamente vía `docker/db/init.sql`. Si la base de datos ya fue inicializada sin ellos, créalos con:
   >
   > ```powershell
   > docker compose exec db psql -U postgres -d plataforma_calidad -c "CREATE SCHEMA IF NOT EXISTS core; CREATE SCHEMA IF NOT EXISTS app_gestion_curricular; CREATE SCHEMA IF NOT EXISTS app_gestion_ingreso; CREATE SCHEMA IF NOT EXISTS app_ensenanza_aprendizaje; CREATE SCHEMA IF NOT EXISTS app_resultados_formacion;"
   > ```

### Comandos útiles

- Ver estado de los contenedores:

  ```powershell
  docker compose ps
  ```

- Ver los logs de la aplicación:

  ```powershell
  docker compose logs -f app
  ```

- Reconstruir tras cambios en el Dockerfile o el compose:

  ```powershell
  docker compose down
  docker compose up --build -d
  ```

- Ejecutar comandos de Artisan dentro del contenedor:

  ```powershell
  docker compose exec app php artisan tinker
  ```

- Conectarse a la base de datos:

  ```powershell
  docker compose exec db psql -U postgres -d plataforma_calidad
  ```

- Detener los contenedores (los datos de la BD persisten en el volumen `db_data`):

  ```powershell
  docker compose down
  ```

### Notas

- El contenedor monta `./.env.docker` sobre `/app/.env`. Esto es necesario porque `php artisan serve` elimina del proceso web las variables de entorno del `docker-compose.yml` y lee el archivo `.env` del proyecto.
- Los pasos 2 y 3 se ejecutan en el host porque el contenedor monta la carpeta del proyecto (`.:/app`) y su imagen no incluye Node/npm.
- La base de datos del contenedor está expuesta en el puerto `5433` del host para no chocar con una PostgreSQL local.
- Es una configuración de desarrollo (`php artisan serve`); para producción se recomienda nginx/php-fpm.

## Ejecución local

### Todo en uno (recomendado)

Levanta el servidor Artisan, el worker de colas, el log `pail` y Vite en modo desarrollo con un solo comando:

```powershell
composer run dev
```

### Usar Artisan directamente

```powershell
php artisan serve --host=127.0.0.1 --port=8000
```

### Usar Vite en modo desarrollo

```powershell
npm run dev
```

## Comandos útiles

- Instalar dependencias y preparar el proyecto:

  ```powershell
  composer run setup
  ```

- Iniciar todo el entorno de desarrollo (server + queue + logs + Vite):

  ```powershell
  composer run dev
  ```

- Iniciar el servidor Vite:

  ```powershell
  npm run dev
  ```

- Crear build de producción:

  ```powershell
  npm run build
  ```

- Build de CSS o JS por separado:

  ```powershell
  npm run build:css
  npm run build:js
  ```

- Ejecutar pruebas:

  ```powershell
  composer run test
  ```

- Listar módulos y su estado:

  ```powershell
  php artisan module:list
  ```

- Listar rutas:

  ```powershell
  php artisan route:list
  ```

- Limpiar caché de configuración:

  ```powershell
  php artisan config:clear
  ```

## Notas importantes

- El proyecto utiliza `sqlite` por defecto en `.env.example` y existe el archivo `database/database.sqlite`. Para PostgreSQL (Docker) usa `.env.docker`.
- Los módulos están habilitados en `modules_statuses.json` y cargados con `nwidart/laravel-modules`.
- Las migraciones de cada módulo se encuentran en `Modules/{Modulo}/database/migrations/` y se ejecutan con `php artisan migrate` (el paquete de módulos las carga automáticamente).
- Las rutas de la aplicación principal se definen en `Modules/Core/routes/web.php` y los módulos adicionales cargan sus propias rutas desde `Modules/{Nombre}/routes/`.
- Los componentes de interfaz viven en `Modules/{Modulo}/resources/js/Pages/` y se sirven mediante Inertia.js desde los controladores.
- Los tests usan PostgreSQL (`phpunit.xml`): crea la base `plataforma_calidad_test` con los esquemas de los módulos antes de ejecutar `composer run test`.

## Módulos presentes

- `Core`
- `GestionCurricular`
- `GestionIngreso`
- `EnsenanzaAprendizaje`
- `ResultadosFormacion`

## Licencia

Este repositorio está basado en Laravel y usa la licencia MIT, salvo acuerdo contrario del proyecto.
