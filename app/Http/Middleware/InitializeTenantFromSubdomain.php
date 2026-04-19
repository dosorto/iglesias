<?php

namespace App\Http\Middleware;

use App\Models\Iglesias;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class InitializeTenantFromSubdomain
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->hasSession()) {
            return $next($request);
        }

        $host = strtolower(trim((string) $request->getHost()));

        if ($host === '') {
            return $next($request);
        }

        $iglesia = Iglesias::query()
            ->whereNotNull('subdomain')
            ->whereRaw('LOWER(subdomain) = ?', [$host])
            ->first();

        if (! $iglesia || empty($iglesia->db_database)) {
            return $next($request);
        }

        $request->session()->put('tenant', [
            'id_iglesia' => $iglesia->id,
            'connection' => config('tenancy.tenant_connection', 'tenant'),
            'subdomain' => $host,
        ]);

        // El acceso por subdominio es tenant directo; no habilitar regreso global.
        $request->session()->forget('tenant_can_return_global');

        return $next($request);
    }
}
