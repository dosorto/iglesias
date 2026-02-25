<?php

namespace App\Services\Tenancy;

use App\Models\Iglesias; // Cambiado de Organization a Iglesias
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantProvisioner
{
    public function provisionDatabase(Iglesias $iglesia): array // Tipo de dato corregido
    {
        $centralConnection = config('tenancy.central_connection') ?: config('database.default');
        $tenantConnection = config('tenancy.tenant_connection', 'tenant');
        $prefix = config('tenancy.database_prefix', 'tenant_');

        $centralConfig = config("database.connections.{$centralConnection}");
        $driver = $centralConfig['driver'] ?? null;

        // Caso para testing o drivers no compatibles
        if (app()->environment('testing') || !in_array($driver, ['mysql', 'mariadb'], true)) {
            return [
                'connection' => config('database.default'),
                'host' => null,
                'port' => null,
                'database' => config('database.connections.' . config('database.default') . '.database'),
                'username' => null,
                'password' => null,
            ];
        }

        // Generar nombre de base de datos usando el nuevo modelo
        $databaseName = $this->tenantDatabaseName($iglesia, $prefix);
        
        // Crear la base de datos
        DB::connection($centralConnection)->statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        // Configurar la conexión dinámica para el Tenant
        $tenantConfig = array_merge($centralConfig, [
            'database' => $databaseName,
        ]);

        config(["database.connections.{$tenantConnection}" => $tenantConfig]);
        DB::purge($tenantConnection);
        DB::reconnect($tenantConnection);

        // Ejecutar migraciones en la nueva base de datos
        Artisan::call('migrate', [
            '--database' => $tenantConnection,
            '--force' => true,
        ]);

        // Ejecutar seeders si existen en la configuración
        foreach (config('tenancy.seeders', []) as $seederClass) {
            Artisan::call('db:seed', [
                '--database' => $tenantConnection,
                '--class' => $seederClass,
                '--force' => true,
            ]);
        }

        // Retornar los datos de conexión para guardarlos en la tabla iglesias
        return [
            'connection' => $tenantConnection,
            'host' => $tenantConfig['host'] ?? null,
            'port' => (string) ($tenantConfig['port'] ?? ''),
            'database' => $tenantConfig['database'] ?? null,
            'username' => $tenantConfig['username'] ?? null,
            'password' => $tenantConfig['password'] ?? null,
        ];
    }

    /**
     * Genera el nombre de la base de datos basado en el modelo Iglesias
     */
    private function tenantDatabaseName(Iglesias $iglesia, string $prefix): string
    {
        // Como Iglesias no tiene slug (según tu código previo), usamos el nombre
        $base = Str::slug($iglesia->nombre, '_');
        $base = $base !== '' ? $base : 'iglesia';
        
        // El nombre será algo como: tenant_parroquia_san_jose_1
        $name = "{$prefix}{$base}_{$iglesia->id}";

        return Str::limit($name, 64, '');
    }
}