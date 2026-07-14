<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <title>{{ config('app.name', 'Sentiment Analysis') }} @yield('title')</title>

    @php
        try {
            $themeColors = \App\Models\Setting::whereIn('key', [
                'theme_primary', 'theme_accent', 'theme_positive',
                'theme_negative', 'theme_neutral', 'theme_bg',
            ])->pluck('value', 'key');
        } catch (\Throwable $e) {
            $themeColors = collect();
        }
    @endphp
    <style>
        :root {
            --color-primary: {{ $themeColors->get('theme_primary', '#1E3A8A') }};
            --color-accent: {{ $themeColors->get('theme_accent', '#6366F1') }};
            --color-positive: {{ $themeColors->get('theme_positive', '#16A34A') }};
            --color-negative: {{ $themeColors->get('theme_negative', '#DC2626') }};
            --color-neutral: {{ $themeColors->get('theme_neutral', '#CA8A04') }};
            --color-bg: {{ $themeColors->get('theme_bg', '#F3F4F6') }};
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-bg text-gray-900 dark:bg-dark-950 dark:text-gray-100 transition-colors duration-200">
    <div class="min-h-full flex" x-data="{ sidebarOpen: false, desktopSidebarOpen: localStorage.getItem('desktopSidebarOpen') !== 'false' }" x-init="$watch('desktopSidebarOpen', val => localStorage.setItem('desktopSidebarOpen', val))">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Topbar -->
            @include('layouts.partials.topbar')

            <!-- Main section -->
            <main class="flex-1 relative" x-data="{ navigating: false }" @beforeunload.window="navigating = true" @pageshow.window="navigating = false">
                <div x-show="!navigating" class="py-8 px-4 sm:px-6 lg:px-8">
                    @hasSection('breadcrumbs')
                        @include('layouts.partials.breadcrumbs', ['breadcrumbs' => View::getSection('breadcrumbs_data', [])])
                    @endif

                    @yield('content')
                </div>

                <!-- Global Preloader -->
                <div x-show="navigating" style="display: none;" class="absolute inset-0 z-50 flex flex-col items-center justify-start pt-32 bg-bg/50 dark:bg-dark-950/50 backdrop-blur-sm">
                    <div class="relative flex items-center justify-center">
                        <div class="w-16 h-16 border-4 border-primary-200 dark:border-primary-900/50 rounded-full"></div>
                        <div class="w-16 h-16 border-4 border-primary-600 dark:border-primary-500 rounded-full border-t-transparent dark:border-t-transparent animate-spin absolute"></div>
                    </div>
                    <p class="mt-4 text-sm font-medium text-gray-500 dark:text-gray-400 animate-pulse">Loading...</p>
                </div>
            </main>

        </div>
    </div>
    <x-ui.toast />
    <x-ui.confirm />
    
    @stack('modals')
    @stack('scripts')
</body>
</html>
