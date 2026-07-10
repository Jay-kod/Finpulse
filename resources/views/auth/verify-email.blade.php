@extends('layouts.guest')
@section('title', '| Verify Email')
@section('subtitle', 'Check your email')

@section('content')
    <p class="text-sm text-gray-600 dark:text-gray-400 mb-6">
        Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you?
    </p>

    @if (session('status') == 'verification-link-sent')
        <x-ui.alert variant="success" class="mb-4">
            A new verification link has been sent to your email address.
        </x-ui.alert>
    @endif

    <div class="flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <x-ui.button type="submit" variant="primary">
                Resend Verification Email
            </x-ui.button>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <x-ui.button type="submit" variant="ghost">
                Log Out
            </x-ui.button>
        </form>
    </div>
@endsection
