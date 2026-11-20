# SIGH - Sistema Integral de Gestión Hospitalaria (versión 1)

Aplicación web en **Laravel (PHP)** con MySQL (XAMPP) para gestionar pacientes, médicos,
especialidades, citas y consultas (historia clínica básica).

## Módulos de la versión 1

| Módulo | Qué permite |
|---|---|
| Autenticación | Inicio y cierre de sesión del personal |
| Panel | Indicadores (pacientes, médicos, citas de hoy y pendientes) y próximas citas |
| Pacientes | CRUD, búsqueda, ficha con citas e historial de consultas |
| Médicos | CRUD, asociados a una especialidad, activos/inactivos |
| Especialidades | CRUD (no se elimina si tiene médicos) |
| Citas | CRUD, filtro por estado, bloqueo de cruce de horario del médico |
| Consultas | CRUD con motivo, diagnóstico y tratamiento |

Arquitectura: MVC de Laravel (Rutas -> Controladores -> Modelos Eloquent -> MySQL; vistas Blade).

## Instalación sobre tu proyecto Laravel

1. Debes tener el proyecto creado con `composer create-project laravel/laravel sigh-gestion-hospitalaria`.
2. Extrae este ZIP **sobre la raíz del proyecto** (acepta reemplazar `routes/web.php`,
   `database/seeders/DatabaseSeeder.php`, `tests/Feature/ExampleTest.php` y el workflow si ya existe).
   En PowerShell, dentro de la carpeta del proyecto:

   ```
   Expand-Archive -Path "RUTA\SIGH_v1_modulos.zip" -DestinationPath . -Force
   ```

3. No se necesita `npm install` ni `npm run build`: los estilos están en `public/css/sigh.css`.

## Despliegue local con XAMPP

1. Abre el **Panel de XAMPP** y arranca **MySQL** (y **Apache** si usarás la opción B).
2. Crea la base de datos. En `http://localhost/phpmyadmin` -> Nueva -> nombre `sigh`,
   cotejamiento `utf8mb4_unicode_ci`. (O por consola: `C:\xampp\mysql\bin\mysql.exe -u root -e "CREATE DATABASE sigh CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"`).
3. Edita el archivo `.env` del proyecto (no `.env.example`):

   ```
   APP_NAME=SIGH
   APP_LOCALE=es
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=sigh
   DB_USERNAME=root
   DB_PASSWORD=
   ```

4. Si no existe `APP_KEY` en `.env`, ejecuta `php artisan key:generate`.
5. Crea las tablas y los datos de demostración:

   ```
   php artisan migrate --seed
   ```

6. Inicia el sistema:
   - **Opción A (recomendada):** `php artisan serve` y abre `http://127.0.0.1:8000`.
   - **Opción B (Apache de XAMPP):** copia la carpeta del proyecto a `C:\xampp\htdocs\sigh-gestion-hospitalaria`
     y abre `http://localhost/sigh-gestion-hospitalaria/public`.
7. Ingresa con **admin@sigh.test** / **password**.

Para reiniciar los datos de demostración: `php artisan migrate:fresh --seed`.

## Pruebas automáticas

```
php artisan test
```

Las pruebas usan SQLite en memoria (no tocan tu base MySQL). Verifica que `phpunit.xml` tenga,
sin comentar, estas dos líneas:

```
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

Se incluyen pruebas de autenticación, CRUD de pacientes y reglas de citas (cruce de horario,
fechas pasadas, eliminación de especialidades con médicos).

## Estándares de codificación aplicados

- PSR-12 y convenciones de Laravel (clases StudlyCaps, métodos y variables camelCase, tablas en snake_case y plural).
- Un controlador por recurso (`Route::resource`), validación en el servidor con mensajes en español,
  eliminación lógica (`SoftDeletes`) en pacientes y médicos para conservar el historial.
- PHPDoc en clases y métodos, vistas Blade con parciales reutilizables (`resources/views/partials`).
- Para revisar el estilo: `./vendor/bin/pint` (en Windows: `vendor\bin\pint`).

## Mejoras futuras (para la sustentación)

Roles y permisos (administrador, médico, recepción), facturación, inventario de medicamentos,
agenda por franjas horarias, exportación a PDF de la historia clínica y notificaciones de citas.
