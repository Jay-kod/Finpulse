@extends('layouts.guest')
@section('title', '| Login')
@section('subtitle', 'Sign in to your account')

@section('content')
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- Email --}}
        <x-ui.form-group label="Email Address" for="email">
            <x-ui.input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="you@example.com"
                :error="$errors->has('email')" 
            />
        </x-ui.form-group>

        {{-- Password --}}
        <x-ui.form-group label="Password" for="password">
            <x-ui.input 
                id="password" 
                type="password" 
                name="password" 
                required 
                autocomplete="current-password"
                placeholder="••••••••"
                :error="$errors->has('password')" 
            />
        </x-ui.form-group>

        {{-- Remember Me --}}
        <div class="flex items-center justify-between">
            <div class="flex items-center">
                <x-ui.checkbox id="remember_me" name="remember" />
                <label for="remember_me" class="ml-2 block text-sm text-gray-700 dark:text-gray-300">
                    Remember me
                </label>
            </div>

            @if (Route::has('password.request'))
                <a class="text-sm font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors" href="{{ route('password.request') }}">
                    Forgot password?
                </a>
            @endif
        </div>

        {{-- Submit --}}
        <x-ui.button type="submit" variant="primary" block>
            Sign in
        </x-ui.button>

        {{-- Register Link --}}
        @if (Route::has('register'))
            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
                Don't have an account?
                <a href="{{ route('register') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                    Create one
                </a>
            </p>
        @endif
    </form>
@endsection
