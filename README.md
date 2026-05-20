# Sistema Iglesia

Proyecto Laravel para gestión parroquial y emisión de documentos sacramentales.

## Instalación Local

Para instalar este proyecto en una PC local con Laragon y respaldos en OneDrive, revisa:

- [DEPLOY_LOCAL_LARAGON.md](./DEPLOY_LOCAL_LARAGON.md)

## Comandos Útiles

```powershell
php artisan migrate --force
php artisan tenants:migrate --force
php artisan backup:run
php artisan schedule:run
```

## Respaldo

El sistema incluye un respaldo pensado para una sola iglesia local:

- genera un `.zip`
- incluye la base tenant
- incluye metadata de la iglesia para restauración
- puede guardar el respaldo directamente en OneDrive

La configuración está en:

- [config/backup.php](./config/backup.php)
- [.env.example](./.env.example)

## Scheduler

Para Windows se incluye:

- [scripts/run_scheduler.bat](./scripts/run_scheduler.bat)

Ese script puede usarse desde el Programador de tareas para ejecutar `php artisan schedule:run` automáticamente.
