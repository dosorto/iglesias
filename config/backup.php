<?php

return [
    'enabled' => env('BACKUP_ENABLED', true),
    'time' => env('BACKUP_TIME', '02:00'),
    'keep_days' => (int) env('BACKUP_KEEP_DAYS', 14),
    'directory' => env('BACKUP_DIRECTORY', 'backups'),
    'iglesia_id' => env('BACKUP_IGLESIA_ID'),
    'include_env' => env('BACKUP_INCLUDE_ENV', true),
    'include_files' => env('BACKUP_INCLUDE_FILES', true),
    'tenant_name' => env('BACKUP_TENANT_NAME'),
    'tenant_subdomain' => env('BACKUP_TENANT_SUBDOMAIN'),
    'tenant_db_host' => env('BACKUP_TENANT_DB_HOST'),
    'tenant_db_port' => env('BACKUP_TENANT_DB_PORT'),
    'tenant_db_database' => env('BACKUP_TENANT_DB_DATABASE'),
    'tenant_db_username' => env('BACKUP_TENANT_DB_USERNAME'),
    'tenant_db_password' => env('BACKUP_TENANT_DB_PASSWORD'),
];
