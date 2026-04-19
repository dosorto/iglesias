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
        $codigo = $request->route('codigo');
        
        if (!$codigo) {
            return $next($request);
        }

        $centralConnection = config('tenancy.central_connection', 'mysql');
        $tenantConnection = config('tenancy.tenant_connection', 'tenant');

        try {
            // Search for document in CENTRAL database to find which tenant it belongs to
            $documento = DB::connection($centralConnection)
                ->table('documentos_generados')
                ->where('codigo_verificacion', strtoupper(trim($codigo)))
                ->latest('id')
                ->first();

            if (!$documento || !$documento->iglesia_id) {
                return $next($request);
            }

            $baseConfig = config("database.connections.{$centralConnection}");
            if (!$baseConfig) {
                return $next($request);
            }

            // Get iglesia configuration for tenant database
            $iglesia = Iglesias::on($centralConnection)->find($documento->iglesia_id);

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

        } catch (\Exception $e) {
            // If anything fails, continue with default connection
            // The controller will handle the 404 if document isn't found
        }

        return $next($request);
    }
}
