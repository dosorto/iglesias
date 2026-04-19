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

        $baseDomain = strtolower(trim((string) config('tenancy.base_domain', '')));
        $hostAsLabel = null;

        if ($baseDomain !== '') {
            $suffix = '.' . $baseDomain;

            if ($host === $baseDomain) {
                return $next($request);
            }

            if (str_ends_with($host, $suffix)) {
                $hostAsLabel = substr($host, 0, -strlen($suffix));
            }
        }

        $iglesia = Iglesias::query()
            ->whereNotNull('subdomain')
            ->where(function ($query) use ($host, $hostAsLabel) {
                $query->whereRaw('LOWER(subdomain) = ?', [$host]);

                if ($hostAsLabel !== null && $hostAsLabel !== '') {
                    $query->orWhereRaw('LOWER(subdomain) = ?', [$hostAsLabel]);
                }
            })
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
