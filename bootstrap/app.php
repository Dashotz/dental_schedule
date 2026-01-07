<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Add security headers globally
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        
        // Use custom authenticate middleware
        $middleware->alias([
            'auth' => \App\Http\Middleware\Authenticate::class,
            'role' => \App\Http\Middleware\RoleMiddleware::class,
            'subdomain.check' => \App\Http\Middleware\CheckSubdomainStatus::class,
            'subdomain.view' => \App\Http\Middleware\SetSubdomainViewPath::class,
            'parent.domain' => \App\Http\Middleware\EnsureParentDomain::class,
            'restrict.login.port' => \App\Http\Middleware\RestrictLoginToPort::class,
            'restrict.port' => \App\Http\Middleware\RestrictToPort::class,
            'allow.subdomain.ports' => \App\Http\Middleware\AllowSubdomainPorts::class,
            'account.lockout' => \App\Http\Middleware\AccountLockout::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Handle unauthenticated exceptions - redirect to appropriate login
        $exceptions->render(function (\Illuminate\Auth\AuthenticationException $e, \Illuminate\Http\Request $request) {
            if ($request->is('admin/*')) {
                return redirect()->route('admin.login');
            }
            return redirect()->route('doctor.login');
        });
    })->create();
