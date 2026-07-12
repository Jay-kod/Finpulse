<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Display the analyst login view.
     */
    public function createAnalyst(): View
    {
        return view('auth.analyst-login');
    }

    /**
     * Display the admin login view.
     */
    public function createAdmin(): View
    {
        return view('auth.admin-login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('web', 'Viewer');

        $request->session()->regenerate();

        return redirect()->intended(route('viewer.dashboard', absolute: false));
    }

    public function storeAnalyst(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('analyst', 'Analyst');

        $request->session()->regenerate();

        return redirect()->intended(route('analyst.dashboard', absolute: false));
    }

    public function storeAdmin(LoginRequest $request): RedirectResponse
    {
        $request->authenticate('admin', 'Admin');

        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('login'));
    }

    public function destroyAnalyst(Request $request): RedirectResponse
    {
        Auth::guard('analyst')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('analyst.login'));
    }

    public function destroyAdmin(Request $request): RedirectResponse
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect(route('admin.login'));
    }
}
