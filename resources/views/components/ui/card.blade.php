@props([
    'padding' => 'p-6',
    'noShadow' => false,
    'glow' => false
])

@php
    $baseClasses = 'bg-white dark:bg-gray-800/60 backdrop-blur-xl rounded-2xl border border-gray-200/60 dark:border-gray-700/50 overflow-hidden transition-all duration-300';
    $shadowClass = $noShadow ? '' : 'shadow-sm hover:shadow-lg hover:shadow-gray-200/50 dark:hover:shadow-black/20';
    $glowClass = $glow ? 'ring-1 ring-primary-500/10 dark:ring-primary-400/10' : '';
@endphp

<div {{ $attributes->merge(['class' => "{$baseClasses} {$shadowClass} {$glowClass}"]) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-gray-100/80 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-800/40">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 border-t border-gray-100/80 dark:border-gray-700/50 bg-gray-50/50 dark:bg-gray-800/40">
            {{ $footer }}
        </div>
    @endif
</div>

