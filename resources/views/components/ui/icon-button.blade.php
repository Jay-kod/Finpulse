@props([
    'type' => 'button',
    'variant' => 'ghost', // primary, secondary, ghost, danger
    'size' => 'md', // sm, md, lg
    'disabled' => false
])

@php
    $baseClasses = 'inline-flex justify-center items-center rounded-full transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'p-1.5',
        'lg' => 'p-3',
        default => 'p-2', // md
    };

    $variantClasses = match($variant) {
        'primary' => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500',
        'secondary' => 'bg-gray-100 text-gray-600 hover:bg-gray-200 focus:ring-gray-500',
        'danger' => 'bg-red-100 text-red-600 hover:bg-red-200 focus:ring-red-500',
        default => 'bg-transparent text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:ring-gray-500', // ghost
    };
@endphp

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses}"]) }}
>
    {{ $slot }}
</button>
