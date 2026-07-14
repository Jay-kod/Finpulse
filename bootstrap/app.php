<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        // Where to send AUTHENTICATED users who hit "guest-only" routes (e.g. login pages)
        $middleware->redirectUsersTo(function (Request $request) {
            if ($request->is('admin/*') && Auth::guard('admin')->check()) {
                return route('admin.dashboard', absolute: false);
            }
            if ($request->is('analyst/*') && Auth::guard('analyst')->check()) {
                return route('analyst.dashboard', absolute: false);
            }
            if (Auth::guard('web')->check()) {
                return route('viewer.dashboard', absolute: false);
            }

            // Not actually authenticated on the relevant guard — let them through
            return null;
        });

        // Where to send UNAUTHENTICATED users who hit protected routes
        $middleware->redirectGuestsTo(function (Request $request) {
            if ($request->is('admin/*')) {
                return route('admin.login');
            }
            if ($request->is('analyst/*')) {
                return route('analyst.login');
            }
            return route('login');
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
