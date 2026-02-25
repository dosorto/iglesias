<?php

namespace App\Services\Tenancy;

use App\Models\Iglesias;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;

class TenantProvisioner
{
    public function provisionDatabase(Iglesias $iglesia): array
    {
        set_time_limit(300); // ← Agregar

        $centralConnection = config('tenancy.central_connection') ?: config('database.default');
        $tenantConnection  = config('tenancy.tenant_connection', 'tenant');
        $prefix            = config('tenancy.database_prefix', 'tenant_');

        $centralConfig = config("database.connections.{$centralConnection}");
        $driver        = $centralConfig['driver'] ?? null;

        logger('TenantProvisioner: inicio', [
            'centralConnection' => $centralConnection,
            'tenantConnection'  => $tenantConnection,
            'prefix'            => $prefix,
            'driver'            => $driver,
            'iglesia_id'        => $iglesia->id,
            'environment'       => app()->environment(),
        ]);

        if (app()->environment('testing') || !in_array($driver, ['mysql', 'mariadb'], true)) {
            logger('TenantProvisioner: ENTRÓ AL BLOQUE SKIP - no va a crear BD', [
                'environment' => app()->environment(),
                'driver'      => $driver,
            ]);
            return [
                'connection' => config('database.default'),
                'host'       => null,
                'port'       => null,
                'database'   => config('database.connections.' . config('database.default') . '.database'),
                'username'   => null,
                'password'   => null,
            ];
        }

        $databaseName = $this->tenantDatabaseName($iglesia, $prefix);
        logger('TenantProvisioner: nombre BD generado', ['databaseName' => $databaseName]);

        DB::connection($centralConnection)->statement(
            "CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
        );
        logger('TenantProvisioner: BD creada exitosamente');

        $tenantConfig = array_merge($centralConfig, [
            'database' => $databaseName,
        ]);

        config(["database.connections.{$tenantConnection}" => $tenantConfig]);
        DB::purge($tenantConnection);
        DB::reconnect($tenantConnection);
        logger('TenantProvisioner: conexión tenant configurada');

        $alreadyMigrated = Schema::connection($tenantConnection)->hasTable('migrations');
        logger('TenantProvisioner: verificación migrations', ['alreadyMigrated' => $alreadyMigrated]);

        if (!$alreadyMigrated) {
            logger('TenantProvisioner: corriendo migraciones...');
            Artisan::call('migrate', [
                '--database' => $tenantConnection,
                '--force'    => true,
            ]);
            logger('TenantProvisioner: migraciones completadas');

            foreach (config('tenancy.seeders', []) as $seederClass) {
                logger('TenantProvisioner: corriendo seeder', ['seeder' => $seederClass]);
                Artisan::call('db:seed', [
                    '--database' => $tenantConnection,
                    '--class'    => $seederClass,
                    '--force'    => true,
                ]);
            }
        }

        $result = [
            'connection' => $tenantConnection,
            'host'       => $tenantConfig['host']     ?? null,
            'port'       => (string) ($tenantConfig['port'] ?? ''),
            'database'   => $tenantConfig['database'] ?? null,
            'username'   => $tenantConfig['username'] ?? null,
            'password'   => $tenantConfig['password'] ?? null,
        ];

        logger('TenantProvisioner: proceso completado', $result);

        return $result;
    }

    private function tenantDatabaseName(Iglesias $iglesia, string $prefix): string
    {
        $base = Str::slug($iglesia->nombre, '_');
        $base = $base !== '' ? $base : 'iglesia';
        $name = "{$prefix}{$base}_{$iglesia->id}";

        return Str::limit($name, 64, '');
    }
}