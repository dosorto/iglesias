<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Iglesias;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class MigrateAllTenants extends Command
{
    protected $signature = 'migrate:tenants {--iglesia_id= : Optional iglesia ID to migrate only one}';
    protected $description = 'Execute migrations for all or specific tenant databases';

    public function handle()
    {
        $iglesia_id = $this->option('iglesia_id');
        
        if ($iglesia_id) {
            $iglesias = Iglesias::where('id', $iglesia_id)->whereNotNull('db_database')->get();
        } else {
            $iglesias = Iglesias::whereNotNull('db_database')->get();
        }

        if ($iglesias->isEmpty()) {
            $this->info('No iglesias with database configured found.');
            return;
        }

        $centralConnection = config('tenancy.central_connection', 'mysql');

        foreach ($iglesias as $iglesia) {
            $this->info("\n➜ Migrating tenant: {$iglesia->nombre} (ID: {$iglesia->id})");
            $this->info("  Database: {$iglesia->db_database}");

            try {
                // Configurar la conexión para este tenant
                $tenantConfig = [
                    'driver'   => 'mysql',
                    'host'     => $iglesia->db_host ?? config('database.connections.mysql.host'),
                    'port'     => $iglesia->db_port ?? config('database.connections.mysql.port', 3306),
                    'database' => $iglesia->db_database,
                    'username' => $iglesia->db_username ?? config('database.connections.mysql.username'),
                    'password' => $iglesia->db_password ?? config('database.connections.mysql.password'),
                    'charset'  => 'utf8mb4',
                    'collation'=> 'utf8mb4_unicode_ci',
                    'prefix'   => '',
                    'strict'   => true,
                    'engine'   => 'InnoDB',
                ];

                // Registrar la conexión dinámicamente
                $connName = "tenant_temp_{$iglesia->id}";
                config(["database.connections.{$connName}" => $tenantConfig]);

                // Cambiar a esta conexión
                DB::setDefaultConnection($connName);
                
                // Ejecutar migraciones
                Artisan::call('migrate', [
                    '--database' => $connName,
                ]);

                $this->info("  ✓ Successfully migrated: {$iglesia->nombre}");
            } catch (\Exception $e) {
                $this->error("  ✗ Failed to migrate {$iglesia->nombre}");
                $this->error("    Error: {$e->getMessage()}");
            }
        }

        // Restaurar conexión por defecto
        DB::setDefaultConnection($centralConnection);
        
        $this->info('\n✓ All tenant migrations completed!');
    }
}
