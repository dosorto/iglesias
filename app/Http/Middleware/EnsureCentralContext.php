<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureCentralContext
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->hasSession() && $request->session()->has('tenant.id_iglesia')) {
            abort(403, 'No tienes permiso para gestionar datos globales desde un tenant activo.');
        }

        return $next($request);
    }
}
