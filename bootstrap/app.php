<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\InitializeTenantFromSubdomain;
use App\Http\Middleware\InitializeTenantFromSession;
use App\Http\Middleware\InitializeTenantFromDocument;
use App\Http\Middleware\EnsureCentralContext;
use App\Http\Middleware\EnsurePendingEncargadoRegistration;

use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            InitializeTenantFromSubdomain::class,
            InitializeTenantFromSession::class,
        ]);

        // Ensure tenant DB is initialized AFTER the session starts
        // but BEFORE SubstituteBindings resolves route-model bindings.
        $middleware->priority([
            \Illuminate\Session\Middleware\StartSession::class,
            InitializeTenantFromSubdomain::class,
            InitializeTenantFromSession::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);

        $middleware->alias([
            'permission' => PermissionMiddleware::class,
            'role' => RoleMiddleware::class,
            'role_or_permission' => RoleOrPermissionMiddleware::class,
            'central.context' => EnsureCentralContext::class,
            'tenant.document' => InitializeTenantFromDocument::class,
            'encargado.pending' => EnsurePendingEncargadoRegistration::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
