<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased" x-data="{ darkMode: localStorage.getItem('darkMode') === 'true' }" x-init="$watch('darkMode', val => localStorage.setItem('darkMode', val))" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
            --color-bg: {{ $themeColors->get('theme_bg', '#F8FAFC') }};
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-bg text-gray-900 dark:bg-gray-900 dark:text-white transition-colors duration-200">
    <div class="min-h-full flex" x-data="{ sidebarOpen: false }">
        <!-- Sidebar -->
        @include('layouts.partials.sidebar')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-h-screen">
            <!-- Topbar -->
            @include('layouts.partials.topbar')

            <!-- Main section -->
            <main class="flex-1 py-8 px-4 sm:px-6 lg:px-8">
                @hasSection('breadcrumbs')
                    @include('layouts.partials.breadcrumbs', ['breadcrumbs' => View::getSection('breadcrumbs_data', [])])
                @endif

                @yield('content')
            </main>

            <!-- Footer -->
            @include('layouts.partials.footer')
        </div>
    </div>
    <x-ui.toast />
    <x-ui.confirm />
    
    @stack('modals')
    @stack('scripts')
</body>
</html>
