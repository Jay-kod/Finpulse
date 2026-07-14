<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Custom guest middleware that only redirects if the SPECIFIC guard is authenticated.
 * This prevents cross-guard interference when multiple guards share the same session/provider.
 */
class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                // Redirect to the appropriate dashboard for this guard
                return redirect($this->redirectTo($guard));
            }
        }

        return $next($request);
    }

    protected function redirectTo(?string $guard): string
    {
        return match ($guard) {
            'admin' => route('admin.dashboard', absolute: false),
            'analyst' => route('analyst.dashboard', absolute: false),
            default => route('viewer.dashboard', absolute: false),
        };
    }
}
