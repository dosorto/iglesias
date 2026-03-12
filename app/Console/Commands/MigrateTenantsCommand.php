<?php

namespace App\Console\Commands;

use App\Models\Iglesias;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class MigrateTenantsCommand extends Command
{
    protected $signature = 'tenants:migrate
                            {--id= : Migrate only the tenant with this iglesia ID}
                            {--force : Force the operation to run in production}';

    protected $description = 'Run pending migrations on all tenant databases';

    public function handle(): int
    {
        $centralConnection = config('tenancy.central_connection') ?: config('database.default');
        $tenantConnection  = config('tenancy.tenant_connection', 'tenant');
        $centralConfig     = config("database.connections.{$centralConnection}");

        $query = Iglesias::on($centralConnection)
            ->whereNotNull('db_database');

        if ($id = $this->option('id')) {
            $query->where('id', $id);
        }

        $iglesias = $query->get();

        if ($iglesias->isEmpty()) {
            $this->warn('No tenant iglesias found.');
            return self::SUCCESS;
        }

        $this->info("Migrating {$iglesias->count()} tenant(s)...");

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

            Artisan::call('migrate', [
                '--database' => $tenantConnection,
                '--force'    => true,
            ]);

            $output = Artisan::output();
            if (trim($output)) {
                $this->line(collect(explode("\n", trim($output)))->map(fn ($l) => "    {$l}")->implode("\n"));
            }
        }

        $this->info('Done.');

        return self::SUCCESS;
    }
}
