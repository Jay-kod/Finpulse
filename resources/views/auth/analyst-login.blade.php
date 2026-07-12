@extends('layouts.guest')

@section('title', '| Analyst Login')
@section('subtitle', 'Sign in as an Analyst')
@section('left_bg_gradient', 'from-emerald-600/30 to-teal-900/40')

@section('left_panel_content')
    <h2 class="text-4xl md:text-5xl font-black mb-6 tracking-tight text-white">Analyst Workspace</h2>
    <p class="text-lg text-gray-300 leading-relaxed mb-10 font-medium">
        Engineered for Data Scientists and NLP Researchers requiring deep access to raw data pipelines. Log in to manage datasets, configure data cleaning pipelines, and execute machine learning preprocessing.
    </p>
    <div class="space-y-4">
        <div class="flex items-center space-x-5 bg-white/5 p-5 rounded-2xl border border-white/5 backdrop-blur-md">
            <div class="w-14 h-14 bg-emerald-500/20 rounded-xl flex items-center justify-center text-emerald-400 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-white text-lg">Dataset Management</h4>
                <p class="text-gray-400 font-medium">Clean and normalize raw review data feeds.</p>
            </div>
        </div>
        <div class="flex items-center space-x-5 bg-white/5 p-5 rounded-2xl border border-white/5 backdrop-blur-md">
            <div class="w-14 h-14 bg-teal-500/20 rounded-xl flex items-center justify-center text-teal-400 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-white text-lg">ML Pipelines</h4>
                <p class="text-gray-400 font-medium">Execute and monitor NLP preprocessing jobs.</p>
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

    <form method="POST" action="{{ route('analyst.login') }}" class="space-y-6"
          x-data="{ email: localStorage.getItem('remembered_analyst_email') || '{{ old('email') }}', remember: localStorage.getItem('remembered_analyst_email') ? true : false, submitting: false }"
          @submit="submitting = true; remember ? localStorage.setItem('remembered_analyst_email', email) : localStorage.removeItem('remembered_analyst_email')">
        @csrf

        {{-- Email Address --}}
        <div>
            <label for="email" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Email Address') }}</label>
            <x-ui.input id="email" class="block w-full py-3 px-4 bg-gray-50 dark:bg-gray-900/50" type="email" name="email" x-model="email" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        {{-- Password --}}
        <div x-data="{ show: false }">
            <label for="password" class="block text-sm font-bold text-gray-700 dark:text-gray-300 mb-2">{{ __('Password') }}</label>
            <div class="relative">
                <x-ui.input id="password" class="block w-full py-3 px-4 bg-gray-50 dark:bg-gray-900/50 pr-12" ::type="show ? 'text' : 'password'" type="password" name="password" required autocomplete="current-password" />
                <button type="button" @click="show = !show" class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-500 transition-colors">
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
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember" x-model="remember">
                <span class="ms-2 text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 dark:hover:text-emerald-300 font-bold transition-colors" href="{{ route('password.request') }}">
                    {{ __('Forgot password?') }}
                </a>
            @endif
        </div>

        <div class="pt-2">
            <button type="submit" x-bind:disabled="submitting" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-emerald-500/25 text-base font-bold text-white bg-emerald-600 hover:bg-emerald-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-emerald-500 dark:focus:ring-offset-[#0B0F19] transition-all disabled:opacity-75 disabled:cursor-not-allowed">
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

        <p class="text-center text-sm font-medium text-gray-600 dark:text-gray-400 mt-6">
            Don't have an account? <a href="{{ route('analyst.register') }}" class="font-bold text-emerald-600 hover:text-emerald-500 dark:text-emerald-400 transition-colors">Create one</a>
        </p>
    </form>
@endsection
