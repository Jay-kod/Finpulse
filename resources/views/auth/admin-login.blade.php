@extends('layouts.guest')

@section('title', '| Admin Login')
@section('subtitle', 'Sign in as an Administrator')
@section('left_bg_gradient', 'from-purple-600/30 to-fuchsia-900/40')

@section('left_panel_content')
    <h2 class="text-4xl md:text-5xl font-black mb-6 tracking-tight text-white">Admin Workspace</h2>
    <p class="text-lg text-gray-300 leading-relaxed mb-10 font-medium">
        The core command center for Finpulse. Log in to manage global application settings, configure user roles, and monitor system health and API integrations.
    </p>
    <div class="space-y-4">
        <div class="flex items-center space-x-5 bg-white/5 p-5 rounded-2xl border border-white/5 backdrop-blur-md">
            <div class="w-14 h-14 bg-purple-500/20 rounded-xl flex items-center justify-center text-purple-400 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-white text-lg">User Management</h4>
                <p class="text-gray-400 font-medium">Control access and permissions for all users.</p>
            </div>
        </div>
        <div class="flex items-center space-x-5 bg-white/5 p-5 rounded-2xl border border-white/5 backdrop-blur-md">
            <div class="w-14 h-14 bg-fuchsia-500/20 rounded-xl flex items-center justify-center text-fuchsia-400 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            </div>
            <div>
                <h4 class="font-bold text-white text-lg">System Health</h4>
                <p class="text-gray-400 font-medium">Monitor API integrations and platform status.</p>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <form method="POST" action="{{ route('admin.login') }}" class="space-y-6"
          x-data="{ email: localStorage.getItem('remembered_admin_email') || '{{ old('email') }}', remember: localStorage.getItem('remembered_admin_email') ? true : false, submitting: false }"
          @submit="submitting = true; remember ? localStorage.setItem('remembered_admin_email', email) : localStorage.removeItem('remembered_admin_email')">
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
                <input id="remember_me" type="checkbox" class="rounded dark:bg-gray-800 border-gray-300 dark:border-gray-600 text-purple-600 shadow-sm focus:ring-purple-500" name="remember" x-model="remember">
                <span class="ms-2 text-sm font-medium text-gray-600 dark:text-gray-400">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="pt-2">
            <button type="submit" x-bind:disabled="submitting" class="w-full flex justify-center py-4 px-4 border border-transparent rounded-xl shadow-lg shadow-purple-500/25 text-base font-bold text-white bg-purple-600 hover:bg-purple-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-purple-500 dark:focus:ring-offset-[#0B0F19] transition-all disabled:opacity-75 disabled:cursor-not-allowed">
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
    </form>
@endsection
