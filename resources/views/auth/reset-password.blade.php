@extends('layouts.guest')
@section('title', '| Reset Password')
@section('subtitle', 'Set your new password')

@section('content')
    <form method="POST" action="{{ route('password.store') }}" class="space-y-5">
        @csrf

        {{-- Password Reset Token --}}
        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        {{-- Email --}}
        <x-ui.form-group label="Email Address" for="email">
            <x-ui.input 
                id="email" 
                type="email" 
                name="email" 
                :value="old('email', $request->email)" 
                required 
                autofocus 
                autocomplete="username"
                placeholder="you@example.com"
                :error="$errors->has('email')" 
            />
        </x-ui.form-group>

        {{-- Password --}}
        <x-ui.form-group label="New Password" for="password">
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
            Reset Password
        </x-ui.button>
    </form>
@endsection
