<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->group(base_path('routes/admin.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'payment/callback'
        ]);

        $middleware->alias([
            'role'               => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission'         => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
            'admin.active'       => \App\Http\Middleware\EnsureAdminIsActive::class,
            'api.token'          => \App\Http\Middleware\VerifyApiToken::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (\Spatie\Permission\Exceptions\UnauthorizedException $e, \Illuminate\Http\Request $request) {
            if ($request->is('admin*')) {
                if (auth()->check()) {
                    return redirect()->route('dashboard.index')
                        ->with('error', 'Akses Ditolak: Akun Anda tidak memiliki izin untuk mengakses Panel Admin Toko.');
                }
                return redirect()->route('login')
                    ->with('error', 'Silakan masuk menggunakan akun Admin/Staff untuk mengakses Panel Admin.');
            }
        });
    })->create();
