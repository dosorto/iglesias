<?php

namespace App\Console\Commands;

use App\Models\Iglesias;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class SeedTenantsCommand extends Command
{
    protected $signature = 'tenants:seed
                            {--id=        : Seed only the tenant with this iglesia ID}
                            {--class=     : Seeder class to run (default: all seeders in tenancy config)}
                            {--force      : Force the operation to run in production}';

    protected $description = 'Run seeders on tenant databases';

    public function handle(): int
    {
        $centralConnection = config('tenancy.central_connection') ?: config('database.default');
        $tenantConnection  = config('tenancy.tenant_connection', 'tenant');
        $centralConfig     = config("database.connections.{$centralConnection}");

        $query = Iglesias::on($centralConnection)->whereNotNull('db_database');

        if ($id = $this->option('id')) {
            $query->where('id', $id);
        }

        $iglesias = $query->get();

        if ($iglesias->isEmpty()) {
            $this->warn('No tenant iglesias found.');
            return self::SUCCESS;
        }

        $seeders = $this->option('class')
            ? [$this->option('class')]
            : config('tenancy.seeders', []);

        if (empty($seeders)) {
            $this->warn('No seeders configured. Use --class or add seeders to config/tenancy.php.');
            return self::SUCCESS;
        }

        $this->info("Seeding {$iglesias->count()} tenant(s)...");

        foreach ($iglesias as $iglesia) {
            $this->line("  → {$iglesia->nombre} ({$iglesia->db_database})");

            $tenantConfig = array_merge($centralConfig, [
                'host'     => $iglesia->db_host     ?: $centralConfig['host'],
                'port'     => $iglesia->db_port     ?: $centralConfig['port'],
                'database' => $iglesia->db_database,
                'username' => $iglesia->db_username ?: $centralConfig['username'],
                'password' => $iglesia->db_password ?: $centralConfig['password'],
            ]);

            config(["database.connections.{$tenantConnection}" => $tenantConfig]);
            DB::purge($tenantConnection);
            DB::reconnect($tenantConnection);
            Config::set('database.default', $tenantConnection);

            foreach ($seeders as $seederClass) {
                $this->line("    Seeding: {$seederClass}");

                Artisan::call('db:seed', [
                    '--class' => $seederClass,
                    '--force' => true,
                ]);

                // Reconectar por si Artisan resetea la conexión
                config(["database.connections.{$tenantConnection}" => $tenantConfig]);
                DB::purge($tenantConnection);
                DB::reconnect($tenantConnection);
                Config::set('database.default', $tenantConnection);

                $output = Artisan::output();
                if (trim($output)) {
                    $this->line(collect(explode("\n", trim($output)))->map(fn ($l) => "    {$l}")->implode("\n"));
                }
            }
        }

        // Restaurar la conexión central como default
        Config::set('database.default', $centralConnection);

        $this->info('Done.');

        return self::SUCCESS;
    }
}
