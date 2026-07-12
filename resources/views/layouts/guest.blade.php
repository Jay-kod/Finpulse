<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full antialiased dark scroll-smooth" x-data="{ darkMode: true }" :class="{ 'dark': darkMode }">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" type="image/png" href="{{ asset('favicon.png') }}">

    <title>{{ config('app.name', 'Finpulse') }} @yield('title')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-gray-50 dark:bg-[#0B0F19] text-gray-800 dark:text-gray-300 transition-colors duration-200">
    <div class="min-h-screen flex flex-col lg:flex-row">
        
        {{-- Left Panel (Details/Dashboard Info) --}}
        <div class="hidden lg:flex lg:w-5/12 xl:w-1/2 relative bg-[#080B13] overflow-hidden items-center justify-center p-12">
            <!-- Background effects -->
            <div class="absolute inset-0 bg-gradient-to-br @yield('left_bg_gradient', 'from-blue-600/20 to-emerald-500/20') opacity-40"></div>
            <div class="absolute inset-0" style="background-image: linear-gradient(to right, rgba(255,255,255,0.03) 1px, transparent 1px), linear-gradient(to bottom, rgba(255,255,255,0.03) 1px, transparent 1px); background-size: 40px 40px;"></div>
            
            <div class="relative z-10 w-full max-w-lg text-white">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-3 mb-12 hover:opacity-80 transition-opacity">
                    <img src="{{ asset('finpulse-icon.png') }}" alt="Finpulse Logo" class="w-12 h-12 rounded-xl ring-1 ring-white/20" />
                    <span class="text-3xl font-black tracking-tighter">Finpulse<span class="text-emerald-400">.</span></span>
                </a>
                
                @hasSection('left_panel_content')
                    @yield('left_panel_content')
                @else
                    <h2 class="text-4xl md:text-5xl font-black mb-6 tracking-tight">Welcome to Finpulse</h2>
                    <p class="text-lg text-gray-400 leading-relaxed font-medium">
                        The ultimate sentiment intelligence platform for financial applications. Transform raw user reviews into actionable insights.
                    </p>
                @endif
            </div>
        </div>

        {{-- Right Panel (Login Form) --}}
        <div class="flex-1 flex flex-col justify-center items-center p-6 lg:p-12 relative bg-white dark:bg-[#0B0F19]">
            <div class="absolute inset-0 bg-gradient-to-br from-white to-gray-50 dark:from-[#0B0F19] dark:to-[#080B13] -z-10"></div>
            
            {{-- Mobile Logo (visible only on small screens) --}}
            <div class="lg:hidden flex items-center space-x-3 mb-10 w-full max-w-md">
                <a href="{{ url('/') }}" class="inline-flex items-center space-x-3">
                    <img src="{{ asset('finpulse-icon.png') }}" alt="Finpulse Logo" class="w-10 h-10 rounded-xl ring-1 ring-white/10 dark:ring-white/20" />
                    <span class="text-2xl font-black text-gray-900 dark:text-white tracking-tighter">Finpulse<span class="text-emerald-400">.</span></span>
                </a>
            </div>

            <div class="w-full max-w-md">
                <div class="mb-10">
                    <h2 class="text-3xl font-black text-gray-900 dark:text-white mb-2 tracking-tight">@yield('subtitle', 'Sign in to your account')</h2>
                    <p class="text-gray-500 dark:text-gray-400 font-medium text-lg">Enter your credentials to securely access your workspace.</p>
                </div>

                {{-- Auth Card --}}
                <div class="bg-white dark:bg-gray-900/40 rounded-[2rem] shadow-2xl border border-gray-100 dark:border-white/5 p-8 lg:p-10 backdrop-blur-xl relative overflow-hidden">
                    {{-- Inner glow effect for auth card --}}
                    <div class="absolute top-0 inset-x-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
                    
                    {{-- Session Status --}}
                    @if (session('status'))
                        <x-ui.alert variant="success" class="mb-6">
                            {{ session('status') }}
                        </x-ui.alert>
                    @endif

                    @yield('content')
                    {{ $slot ?? '' }}
                </div>
            </div>
        </div>
    </div>
</body>
</html>
