@extends('layouts.guest')
@section('title', '| Confirm Password')
@section('subtitle', 'Security verification')

@section('content')
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        This is a secure area of the application. Please confirm your password before continuing.
    </p>

    <form method="POST" action="{{ route('password.confirm') }}" class="space-y-5">
        @csrf

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

        {{-- Submit --}}
        <x-ui.button type="submit" variant="primary" block>
            Confirm
        </x-ui.button>
    </form>
@endsection
