@props([
    'padding' => 'p-6',
    'noShadow' => false
])

@php
    $baseClasses = 'bg-white rounded-xl border border-gray-100 overflow-hidden';
    $shadowClass = $noShadow ? '' : 'shadow-sm';
@endphp

<div {{ $attributes->merge(['class' => "{$baseClasses} {$shadowClass}"]) }}>
    @if(isset($header))
        <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
            {{ $header }}
        </div>
    @endif

    <div class="{{ $padding }}">
        {{ $slot }}
    </div>

    @if(isset($footer))
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $footer }}
        </div>
    @endif
</div>
