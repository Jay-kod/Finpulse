@extends('layouts.guest')
@section('title', '| Forgot Password')
@section('subtitle', 'Reset your password')

@section('content')
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        Forgot your password? No problem. Enter your email address and we'll send you a password reset link.
    </p>

    <form method="POST" action="{{ route('password.email') }}" class="space-y-5">
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
                placeholder="you@example.com"
                :error="$errors->has('email')" 
            />
        </x-ui.form-group>

        {{-- Submit --}}
        <x-ui.button type="submit" variant="primary" block>
            Email Password Reset Link
        </x-ui.button>

        {{-- Back to login --}}
        <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-4">
            <a href="{{ route('login') }}" class="font-medium text-primary-600 hover:text-primary-500 dark:text-primary-400 dark:hover:text-primary-300 transition-colors">
                &larr; Back to login
            </a>
        </p>
    </form>
@endsection
