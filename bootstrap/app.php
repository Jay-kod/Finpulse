<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);

        $middleware->redirectUsersTo(function (Request $request) {
            if (Auth::guard('admin')->check()) {
                return route('admin.dashboard', absolute: false);
            }
            if (Auth::guard('analyst')->check()) {
                return route('analyst.dashboard', absolute: false);
            }
            return route('dashboard', absolute: false);
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*') || $request->wantsJson(),
        );

        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*') || $request->wantsJson()) {
                $code = $e->getCode();
                if ($code < 100 || $code > 599) {
                    $code = 500;
                }
                
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return \App\Shared\Core\Helpers\ResponseHelper::error('Validation Error', 422, $e->errors());
                }

                if ($e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException) {
                    return \App\Shared\Core\Helpers\ResponseHelper::error('Resource not found', 404);
                }

                if ($e instanceof \Illuminate\Auth\AuthenticationException) {
                    return \App\Shared\Core\Helpers\ResponseHelper::error('Unauthenticated', 401);
                }

                $message = config('app.debug') ? $e->getMessage() : 'Server Error';
                $code = $e instanceof \Symfony\Component\HttpKernel\Exception\HttpExceptionInterface ? $e->getStatusCode() : 500;
                
                return \App\Shared\Core\Helpers\ResponseHelper::error($message, $code);
            }
        });
    })->create();
