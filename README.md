# Plataforma de Gestión Académica

Este repositorio contiene una aplicación Laravel modular para la gestión académica universitaria. La aplicación principal está en este mismo repositorio y utiliza el paquete `nwidart/laravel-modules` para cargar los módulos desde `Modules/`.

## Estructura principal

- `/`: raíz del proyecto Laravel.
- `app/`: código principal de la aplicación Laravel.
- `bootstrap/`, `config/`, `database/`, `resources/`, `routes/`, `storage/`, `vendor/`: estructura clásica de Laravel.
- `Modules/`: módulos independientes de la aplicación.
- `public/`: punto de entrada web.
- `package.json`: scripts frontend y dependencias Vite/Tailwind.
- `composer.json`: dependencias PHP y scripts de Composer.
- `modules_statuses.json`: lista de módulos habilitados.
- `.env.example`: configuración de entorno.

## Requisitos previos

- PHP 8.3 o superior.
- Composer.
- Node.js y npm.
- Extensiones PHP recomendadas: `pdo_sqlite`, `pdo_mysql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`.
- En Windows, PowerShell es útil para ejecutar comandos.

## Instalación

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

5. Ejecutar migraciones de base de datos:

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

## Ejecución local

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

- Iniciar el servidor Vite:

  ```powershell
  npm run dev
  ```

- Crear build de producción:

  ```powershell
  npm run build
  ```

- Ejecutar pruebas:

  ```powershell
  composer run test
  ```

- Limpiar caché de configuración:

  ```powershell
  php artisan config:clear
  ```

## Notas importantes

- El proyecto utiliza `sqlite` por defecto en `.env.example` y existe el archivo `database/database.sqlite`.
- Los módulos están habilitados en `modules_statuses.json` y cargados con `nwidart/laravel-modules`.
- Las rutas de la aplicación principal se definen en `Modules/Core/routes/web.php` y los módulos adicionales también suelen cargar sus propias rutas desde `Modules/{Nombre}/routes/`.

## Módulos presentes

- `Core`
- `GestionCurricular`
- `GestionIngreso`
- `EnsenanzaAprendizaje`
- `ResultadosFormacion`

## Licencia

Este repositorio está basado en Laravel y usa la licencia MIT, salvo acuerdo contrario del proyecto.
