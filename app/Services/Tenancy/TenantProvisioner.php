<?php

namespace App\Services\Tenancy;

use App\Models\Organization;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class TenantProvisioner
{
    public function provisionDatabase(Organization $organization): array
    {
        $centralConnection = config('tenancy.central_connection') ?: config('database.default');
        $tenantConnection = config('tenancy.tenant_connection', 'tenant');
        $prefix = config('tenancy.database_prefix', 'tenant_');

        $centralConfig = config("database.connections.{$centralConnection}");
        $driver = $centralConfig['driver'] ?? null;

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

        $databaseName = $this->tenantDatabaseName($organization, $prefix);
        DB::connection($centralConnection)->statement("CREATE DATABASE IF NOT EXISTS `{$databaseName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

        $tenantConfig = array_merge($centralConfig, [
            'database' => $databaseName,
        ]);

        config(["database.connections.{$tenantConnection}" => $tenantConfig]);
        DB::purge($tenantConnection);
        DB::reconnect($tenantConnection);

        Artisan::call('migrate', [
            '--database' => $tenantConnection,
            '--force' => true,
        ]);

        foreach (config('tenancy.seeders', []) as $seederClass) {
            Artisan::call('db:seed', [
                '--database' => $tenantConnection,
                '--class' => $seederClass,
                '--force' => true,
            ]);
        }

        return [
            'connection' => $tenantConnection,
            'host' => $tenantConfig['host'] ?? null,
            'port' => (string) ($tenantConfig['port'] ?? ''),
            'database' => $tenantConfig['database'] ?? null,
            'username' => $tenantConfig['username'] ?? null,
            'password' => $tenantConfig['password'] ?? null,
        ];
    }

    private function tenantDatabaseName(Organization $organization, string $prefix): string
    {
        $base = Str::slug($organization->slug ?: $organization->name, '_');
        $base = $base !== '' ? $base : 'org';
        $name = "{$prefix}{$base}_{$organization->id}";

        return Str::limit($name, 64, '');
    }
}
