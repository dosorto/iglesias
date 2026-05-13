<?php

namespace App\Http\Middleware;

use App\Models\Iglesias;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantFromDocument
{
    /**
     * Initialize tenant connection from document verification code.
     * Used for public document verification routes that don't have an active session.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Extract codigo from route parameter
        $codigo = strtoupper(trim((string) $request->route('codigo')));
        
        if ($codigo === '') {
            return $next($request);
        }

        $centralConnection = config('tenancy.central_connection', 'mysql');
        $tenantConnection = config('tenancy.tenant_connection', 'tenant');

        try {
            $iglesiaId = $this->resolveIglesiaIdFromCode($codigo, $centralConnection);

            if (! $iglesiaId) {
                return $next($request);
            }

            $baseConfig = config("database.connections.{$centralConnection}");
            if (!$baseConfig) {
                return $next($request);
            }

            // Get iglesia configuration for tenant database
            $iglesia = Iglesias::on($centralConnection)->find($iglesiaId);

            if (!$iglesia || empty($iglesia->db_database)) {
                return $next($request);
            }

            // Configure tenant connection
            $tenantConfig = array_merge($baseConfig, [
                'host'     => $iglesia->db_host ?: $baseConfig['host'] ?? null,
                'port'     => $iglesia->db_port ?: $baseConfig['port'] ?? null,
                'database' => $iglesia->db_database,
                'username' => $iglesia->db_username ?: $baseConfig['username'] ?? null,
                'password' => $iglesia->db_password ?: $baseConfig['password'] ?? null,
            ]);

            config([
                "database.connections.{$tenantConnection}" => $tenantConfig,
                'database.default' => $tenantConnection,
            ]);

            DB::purge($tenantConnection);

            if ($request->hasSession()) {
                $request->session()->put('tenant', [
                    'id_iglesia' => $iglesia->id,
                    'connection' => $tenantConnection,
                    'subdomain' => $iglesia->subdomain,
                ]);
            }

        } catch (\Exception $e) {
            // If anything fails, continue with default connection
            // The controller will handle the 404 if document isn't found
        }

        return $next($request);
    }

    private function resolveIglesiaIdFromCode(string $codigo, string $centralConnection): ?int
    {
        $documentoCentral = DB::connection($centralConnection)
            ->table('documentos_generados')
            ->where('codigo_verificacion', $codigo)
            ->latest('id')
            ->first();

        if ($documentoCentral && ! empty($documentoCentral->iglesia_id)) {
            return (int) $documentoCentral->iglesia_id;
        }

        // Fallback: some environments persist documentos_generados only in tenant DBs.
        // Scan tenant databases to find where this verification code exists.
        return $this->scanTenantDatabasesForCode($codigo, $centralConnection);
    }

    private function scanTenantDatabasesForCode(string $codigo, string $centralConnection): ?int
    {
        $baseConfig = config("database.connections.{$centralConnection}");

        if (! is_array($baseConfig) || empty($baseConfig)) {
            return null;
        }

        $iglesias = Iglesias::on($centralConnection)
            ->whereNotNull('db_database')
            ->get([
                'id',
                'db_host',
                'db_port',
                'db_database',
                'db_username',
                'db_password',
            ]);

        foreach ($iglesias as $iglesia) {
            $tempConnection = 'tenant_qr_scan';

            try {
                $tenantConfig = array_merge($baseConfig, [
                    'host' => $iglesia->db_host ?: ($baseConfig['host'] ?? null),
                    'port' => $iglesia->db_port ?: ($baseConfig['port'] ?? null),
                    'database' => $iglesia->db_database,
                    'username' => $iglesia->db_username ?: ($baseConfig['username'] ?? null),
                    'password' => $iglesia->db_password ?: ($baseConfig['password'] ?? null),
                ]);

                config(["database.connections.{$tempConnection}" => $tenantConfig]);
                DB::purge($tempConnection);

                $exists = DB::connection($tempConnection)
                    ->table('documentos_generados')
                    ->where('codigo_verificacion', $codigo)
                    ->exists();

                if ($exists) {
                    DB::disconnect($tempConnection);
                    DB::purge($tempConnection);

                    return (int) $iglesia->id;
                }
            } catch (\Throwable $e) {
                // Ignore tenant scan failures and continue with next tenant.
            }

            DB::disconnect($tempConnection);
            DB::purge($tempConnection);
        }

        return null;
    }
}
