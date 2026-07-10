@props([
    'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10',
    'title' => 'No data found',
    'description' => '',
])

<div class="flex flex-col items-center justify-center py-16 text-center">
    <div class="bg-gray-100 dark:bg-gray-800 rounded-full p-4 mb-4">
        <svg class="w-10 h-10 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $icon }}"></path>
        </svg>
    </div>
    <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300">{{ $title }}</h3>
    @if($description)
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm">{{ $description }}</p>
    @endif
    {{ $slot ?? '' }}
</div>
