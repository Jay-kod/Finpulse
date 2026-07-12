{{-- Mobile sidebar overlay --}}
<div x-show="sidebarOpen" class="fixed inset-0 z-40 md:hidden" x-cloak>
    {{-- Backdrop --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition-opacity ease-linear duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-linear duration-300"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="sidebarOpen = false"
         class="fixed inset-0 bg-gray-900/60 backdrop-blur-sm"></div>

    {{-- Mobile sidebar panel --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative flex flex-col w-72 bg-white dark:bg-gradient-to-b dark:from-dark-900 dark:via-dark-900 dark:to-dark-950 shadow-2xl h-full">

        {{-- Close button --}}
        <div class="absolute top-0 right-0 -mr-12 pt-2">
            <button @click="sidebarOpen = false" class="ml-1 flex items-center justify-center h-10 w-10 rounded-full focus:outline-none focus:ring-2 focus:ring-inset focus:ring-white">
                <span class="sr-only">Close sidebar</span>
                <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        {{-- Logo --}}
        <div class="h-16 flex items-center px-6 border-b border-gray-100 dark:border-dark-800/80 shrink-0">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/25">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span class="text-lg font-bold text-gray-900 dark:text-transparent dark:bg-gradient-to-r dark:from-white dark:to-gray-300 dark:bg-clip-text">Finpulse</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            @include('layouts.partials.sidebar-nav', ['isMobile' => true])
        </nav>
    </div>
</div>

{{-- Desktop sidebar --}}
<aside :class="desktopSidebarOpen ? 'w-64' : 'w-20'" class="bg-white dark:bg-gradient-to-b dark:from-dark-900 dark:via-dark-900 dark:to-dark-950 hidden md:flex md:flex-col transition-all duration-300 shrink-0 border-r border-gray-200 dark:border-dark-800/80 sticky top-0 h-screen">
    <div class="h-full flex flex-col overflow-hidden">
        {{-- Logo --}}
        <div class="h-16 flex items-center transition-all duration-300 border-b border-gray-100 dark:border-dark-800/80 shrink-0" :class="desktopSidebarOpen ? 'px-6 justify-start' : 'px-0 justify-center'">
            <div class="flex items-center space-x-3">
                <div class="w-8 h-8 bg-gradient-to-br from-blue-500 to-indigo-600 rounded-lg flex items-center justify-center shadow-lg shadow-blue-500/25 shrink-0 hover:animate-pulse-slow">
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                </div>
                <span x-show="desktopSidebarOpen" x-transition.opacity.duration.200ms class="text-lg font-bold text-gray-900 dark:text-transparent dark:bg-gradient-to-r dark:from-white dark:to-gray-300 dark:bg-clip-text whitespace-nowrap">Finpulse</span>
            </div>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-3 py-6 space-y-1 overflow-y-auto overflow-x-hidden">
            @include('layouts.partials.sidebar-nav', ['isMobile' => false])
        </nav>

        {{-- Sidebar footer --}}
        <div class="p-4 border-t border-gray-100 dark:border-dark-800/80 shrink-0">
            <div class="flex items-center justify-center text-xs text-gray-500 whitespace-nowrap overflow-hidden">
                <span x-show="desktopSidebarOpen" x-transition.opacity.duration.200ms>v{{ config('sentiment.version', '1.0.0') }} &middot; {{ config('sentiment.name') }}</span>
                <span x-show="!desktopSidebarOpen" x-transition.opacity.duration.200ms>v{{ config('sentiment.version', '1.0.0') }}</span>
            </div>
        </div>
    </div>
</aside>

