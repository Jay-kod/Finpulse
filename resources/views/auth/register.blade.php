@extends('layouts.guest')
@section('title', '| Register')
@section('subtitle', 'Create your account')

@section('content')
    <form method="POST" action="{{ route('register') }}" class="space-y-5">
        @csrf

        {{-- Name --}}
        <x-ui.form-group label="Full Name" for="name">
            <x-ui.input 
                id="name" 
                type="text" 
                name="name" 
                :value="old('name')" 
                required 
                autofocus 
                autocomplete="name"
                placeholder="John Doe"
                :error="$errors->has('name')" 
            />
        </x-ui.form-group>

        {{-- Email --}}
        <x-ui.form-group label="Email Address" for="email">
            <x-ui.input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email')" 
                required 
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
                autocomplete="new-password"
                placeholder="••••••••"
                :error="$errors->has('password')" 
            />
        </x-ui.form-group>

        {{-- Confirm Password --}}
        <x-ui.form-group label="Confirm Password" for="password_confirmation">
            <x-ui.input 
                id="password_confirmation" 
                type="password" 
                name="password_confirmation" 
                required 
                autocomplete="new-password"
                placeholder="••••••••"
                :error="$errors->has('password_confirmation')" 
            />
        </x-ui.form-group>

        {{-- Submit --}}
        <x-ui.button type="submit" variant="primary" block>
            Create Account
        </x-ui.button>

        {{-- Login Link --}}
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
            Already have an account?
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                Sign in
            </a>
        </p>
    </form>
@endsection
