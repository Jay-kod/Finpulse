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
         class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm"></div>

    {{-- Mobile sidebar panel --}}
    <div x-show="sidebarOpen"
         x-transition:enter="transition ease-in-out duration-300 transform"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in-out duration-300 transform"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         class="relative flex flex-col w-72 bg-white shadow-xl h-full">

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
        <div class="h-16 flex items-center px-6 border-b border-gray-200 shrink-0">
            <span class="text-xl font-bold text-primary-600">Fintech Analyzer</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            @include('layouts.partials.sidebar-nav')
        </nav>
    </div>
</div>

{{-- Desktop sidebar --}}
<aside class="w-64 bg-white border-r border-gray-200 hidden md:flex md:flex-col transition-colors duration-200 shrink-0">
    <div class="h-full flex flex-col">
        {{-- Logo --}}
        <div class="h-16 flex items-center px-6 border-b border-gray-200 shrink-0">
            <span class="text-xl font-bold text-primary-600">Fintech Analyzer</span>
        </div>

        {{-- Navigation --}}
        <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
            @include('layouts.partials.sidebar-nav')
        </nav>

        {{-- Sidebar footer --}}
        <div class="p-4 border-t border-gray-200 shrink-0">
            <div class="flex items-center text-xs text-gray-400">
                <span>v{{ config('sentiment.version', '1.0.0') }}</span>
                <span class="mx-2">&middot;</span>
                <span>{{ config('sentiment.name') }}</span>
            </div>
        </div>
    </div>
</aside>
