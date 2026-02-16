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
    | Seeders
    |--------------------------------------------------------------------------
    |
    | Seeders executed right after tenant migrations.
    |
    */
    'seeders' => [
        Database\Seeders\RolesAndPermissionsSeeder::class,
    ],
];
