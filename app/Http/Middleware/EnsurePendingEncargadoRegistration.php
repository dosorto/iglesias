<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePendingEncargadoRegistration
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->hasSession() || ! Auth::check()) {
            return $next($request);
        }

        if (! $request->session()->get('pending_encargado_registration')) {
            return $next($request);
        }

        $currentRoute = $request->route()?->getName();
        $allowedRoutes = [
            'register-perfil',
            'logout',
        ];

        if (in_array($currentRoute, $allowedRoutes, true)) {
            return $next($request);
        }

        return redirect()->route('register-perfil');
    }
}
