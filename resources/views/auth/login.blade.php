@extends('layouts.guest')

@section('title', '| User Login')
@section('subtitle', 'Sign in as a User')
@section('left_bg_gradient', 'from-blue-600/30 to-blue-900/40')

@section('left_panel_content')
    <h2 class="text-3xl md:text-4xl font-black mb-4 tracking-tight text-white">Stakeholder Workspace</h2>
    <p class="text-base text-gray-300 leading-relaxed mb-8 font-medium">
        Access your personalized dashboard to monitor high-level application performance, view beautifully rendered sentiment reports, and track real-time user satisfaction metrics without diving into complex data.
    </p>
    <div class="space-y-3">
        <div class="flex items-center space-x-4 bg-white/5 p-4 rounded-xl border border-white/5 backdrop-blur-md">
            <div class="w-10 h-10 bg-blue-500/20 rounded-lg flex items-center justify-center text-blue-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-white text-base">Sentiment Overview</h4>
                <p class="text-sm text-gray-400 font-medium">Track positive, neutral, and negative trends across platforms.</p>
            </div>
        </div>
        <div class="flex items-center space-x-4 bg-white/5 p-4 rounded-xl border border-white/5 backdrop-blur-md">
            <div class="w-10 h-10 bg-cyan-500/20 rounded-lg flex items-center justify-center text-cyan-400 shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-white text-base">Feature Requests</h4>
                <p class="text-sm text-gray-400 font-medium">See what your users want built next in real-time.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    @if (session('status'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 text-emerald-600 dark:text-emerald-400 font-medium text-sm flex items-center">
            <svg class="w-5 h-5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('login') }}" class="space-y-6"
          x-data="{ email: localStorage.getItem('remembered_user_email') || '{{ old('email') }}', remember: localStorage.getItem('remembered_user_email') ? true : false, submitting: false }"
          @submit="submitting = true; remember ? localStorage.setItem('remembered_user_email', email) : localStorage.removeItem('remembered_user_email')">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Email Address') }}</label>
            <x-ui.input id="email" class="block w-full py-2.5 px-3 bg-gray-50 dark:bg-gray-900/50 text-sm" type="email" name="email" x-model="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-1.5">{{ __('Password') }}</label>
            <div class="relative">
                <x-ui.input id="password" class="block w-full py-2.5 px-3 bg-gray-50 dark:bg-gray-900/50 pr-12 text-sm" ::type="show ? 'text' : 'password'" type="password" name="password" required autocomplete="current-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500 transition-colors">
                    <svg x-show="!show" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg x-show="show" x-cloak class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.978 9.978 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        {{-- Remember Me & Forgot Password --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-blue-600 shadow-sm focus:ring-blue-500" name="remember" x-model="remember">
                <span class="ms-2 text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-blue-600 hover:text-blue-500 dark:text-blue-400 dark:hover:text-blue-300 font-bold transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" x-bind:disabled="submitting" class="w-full flex justify-center py-2.5 px-4 border border-transparent rounded-lg shadow-md shadow-blue-500/25 text-sm font-semibold text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 dark:focus:ring-offset-[#0B0F19] transition-all disabled:opacity-75 disabled:cursor-not-allowed">
                <span x-show="!submitting">{{ __('Sign In') }}</span>
                <span x-show="submitting" x-cloak class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Authenticating...
                </span>
            </button>
        </div>

        @if (Route::has('register'))
            <p class="text-center text-sm font-medium text-gray-600 dark:text-gray-400 mt-6">
                Don't have an account? <a href="{{ route('register') }}" class="font-bold text-blue-600 hover:text-blue-500 dark:text-blue-400 transition-colors">Create one</a>
            </p>
        @endif
    </form>
@endsection
