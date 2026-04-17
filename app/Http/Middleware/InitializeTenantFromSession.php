<?php

namespace App\Http\Middleware;

use App\Models\Iglesias;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantFromSession
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->hasSession()) {
            return $next($request);
        }

        $tenant = $request->session()->get('tenant');
        $tenantIglesiaId = isset($tenant['id_iglesia']) ? (int) $tenant['id_iglesia'] : null;

        if (!$tenant || !$tenantIglesiaId) {
            return $next($request);
        }

        $centralConnection = config('tenancy.central_connection', 'mysql');
        $tenantConnection  = config('tenancy.tenant_connection', 'tenant');

        $baseConfig = config("database.connections.{$centralConnection}");

        if (!$baseConfig) {
            return $next($request);
        }

        $iglesia = Iglesias::on($centralConnection)->find($tenantIglesiaId);

        // Sesion tenant invalida o iglesia sin base tenant configurada.
        if (!$iglesia || empty($iglesia->db_database)) {
            $request->session()->forget('tenant');
            $request->session()->forget('tenant_can_return_global');

            return $next($request);
        }

        $tenantConfig = array_merge($baseConfig, [
            'host'     => $iglesia->db_host     ?: $baseConfig['host'] ?? null,
            'port'     => $iglesia->db_port     ?: $baseConfig['port'] ?? null,
            'database' => $iglesia->db_database,
            'username' => $iglesia->db_username ?: $baseConfig['username'] ?? null,
            'password' => $iglesia->db_password ?: $baseConfig['password'] ?? null,
        ]);

        // Compatibilidad hacia adelante: normalizar payload de sesion tenant
        // para que no contenga credenciales de BD.
        $request->session()->put('tenant', [
            'id_iglesia' => $iglesia->id,
            'connection' => $tenantConnection,
        ]);

        config([
            "database.connections.{$tenantConnection}" => $tenantConfig,
            'database.default'                          => $tenantConnection,
        ]);

        DB::purge($tenantConnection);

        return $next($request);
    }
}