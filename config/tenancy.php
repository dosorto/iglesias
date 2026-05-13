<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Central Connection
    |--------------------------------------------------------------------------
    |
    | Base connection used to create tenant databases.
    |
    */
    'central_connection' => env('TENANCY_CENTRAL_CONNECTION', env('DB_CONNECTION', 'mysql')),

    /*
    |--------------------------------------------------------------------------
    | Tenant Connection
    |--------------------------------------------------------------------------
    |
    | Runtime connection name used for tenant operations.
    |
    */
    'tenant_connection' => env('TENANCY_TENANT_CONNECTION', 'tenant'),

    /*
    |--------------------------------------------------------------------------
    | Database Name Prefix
    |--------------------------------------------------------------------------
    */
    'database_prefix' => env('TENANCY_DATABASE_PREFIX', 'tenant_'),

    /*
    |--------------------------------------------------------------------------
    | Base Domain For Tenant Subdomains
    |--------------------------------------------------------------------------
    |
    | Example: if set to "iglesia.local", tenant urls become
    | "https://{subdomain}.iglesia.local".
    |
    */
    'base_domain' => env('TENANCY_BASE_DOMAIN'),

    /*
    |--------------------------------------------------------------------------
    | Seeders
    |--------------------------------------------------------------------------
    |
    | Seeders executed right after tenant migrations.
    |
    */
    'seeders' => [
        Database\Seeders\TenantRolesSeeder::class,
        Database\Seeders\TenantTipoCursoSeeder::class,
    ],
];
