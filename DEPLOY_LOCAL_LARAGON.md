# Instalación Local Con Laragon

Esta guía deja el sistema listo para una sola iglesia en una PC Windows usando Laragon y copias de seguridad en OneDrive.

## Requisitos

- Laragon instalado
- Git instalado
- OneDrive configurado e iniciando sesión en Windows

## 1. Clonar el proyecto

Ubica el proyecto dentro de `C:\laragon\www` para aprovechar los dominios `.test` de Laragon.

Ejemplo:

```powershell
cd C:\laragon\www
git clone <URL_DEL_REPOSITORIO> iglesias
```

## 2. Crear el entorno

Dentro de la carpeta del proyecto:

```powershell
copy .env.example .env
composer install
npm install
php artisan key:generate
```

## 3. Configurar el archivo `.env`

Valores recomendados para Laragon:

```env
APP_NAME="Sistema Iglesia"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://iglesias.test
QR_PUBLIC_BASE_URL=http://iglesias.test

APP_LOCALE=es
APP_FALLBACK_LOCALE=es
APP_FAKER_LOCALE=es_HN

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=iglesias
DB_USERNAME=root
DB_PASSWORD=

SESSION_DRIVER=file

BACKUP_ENABLED=true
BACKUP_TIME=02:00
BACKUP_KEEP_DAYS=14
BACKUP_DIRECTORY=C:\Users\NOMBRE_USUARIO\OneDrive\RespaldosIglesia
BACKUP_IGLESIA_ID=1
BACKUP_INCLUDE_ENV=true
BACKUP_INCLUDE_FILES=true
BACKUP_TENANT_NAME=
BACKUP_TENANT_SUBDOMAIN=
BACKUP_TENANT_DB_HOST=
BACKUP_TENANT_DB_PORT=
BACKUP_TENANT_DB_DATABASE=
BACKUP_TENANT_DB_USERNAME=
BACKUP_TENANT_DB_PASSWORD=
```

Notas:

- Reemplaza `NOMBRE_USUARIO` por el usuario real de Windows.
- Si la iglesia tendrá otro `id`, cambia `BACKUP_IGLESIA_ID`.
- El nombre real de la base tenant no es fijo: el sistema la crea con el patrón `tenant_{slug}_{id}`.
- Si quieres que el backup funcione aun cuando la base central falle, completa los `BACKUP_TENANT_DB_*` después de crear la iglesia y confirma el nombre real de su base tenant.

## 4. Crear la base de datos

Desde HeidiSQL o phpMyAdmin de Laragon, crea una base llamada:

```text
iglesias
```

## 5. Ejecutar migraciones

```powershell
php artisan migrate --force
php artisan tenants:migrate --force
php artisan storage:link
```

Si el proyecto usa seeders en tu flujo:

```powershell
php artisan db:seed --force
php artisan tenants:seed --force
```

## 6. Compilar frontend

Para desarrollo:

```powershell
npm run dev
```

Para dejarlo listo:

```powershell
npm run build
```

## 7. Abrir el sistema

Si la carpeta del proyecto es `iglesias`, Laragon normalmente lo expone como:

```text
http://iglesias.test
```

## 8. Probar el respaldo manual

Antes de automatizarlo:

```powershell
php artisan backup:run
```

El archivo `.zip` debe quedar en la carpeta configurada en `BACKUP_DIRECTORY`.

El respaldo incluye:

- dump SQL de la base tenant
- `tenant.json` con los datos de la iglesia
- `restore_iglesia.sql` para recrear el registro central de la iglesia
- `.env` si `BACKUP_INCLUDE_ENV=true`
- archivos locales de `storage/app/public`

## 9. Automatizar el scheduler en Windows

El proyecto ya incluye:

- `scripts/run_scheduler.bat`

Ese script intenta usar primero el PHP de Laragon y, si no lo encuentra, usa `php` del sistema.

Configura una tarea en el Programador de tareas de Windows para correr cada minuto:

- Programa/script:
  `C:\laragon\www\iglesias\scripts\run_scheduler.bat`

Con eso Laravel ejecutará automáticamente el respaldo a la hora definida en:

```env
BACKUP_TIME=02:00
```

## 10. Recomendación Para Una Sola Iglesia

Si este sistema quedará solo para una iglesia:

- mantén una sola base tenant activa
- usa `BACKUP_IGLESIA_ID` apuntando a esa iglesia
- guarda los respaldos en OneDrive
- evita crear tenants adicionales en producción local
- deja configurados los `BACKUP_TENANT_DB_*` para que el backup no dependa de la base central

## Verificación Rápida

Todo debería quedar bien si esto funciona:

```powershell
php artisan migrate:status
php artisan backup:run
php artisan storage:link
```

Y si puedes abrir:

```text
http://iglesias.test
```
