@props([
    'variant' => 'info', // success, warning, error, info, dark
    'size' => 'md', // sm, md
    'rounded' => 'full' // full, md
])

@php
    $baseClasses = 'inline-flex items-center font-medium';
    
    $sizeClasses = match($size) {
        'sm' => 'px-2 py-0.5 text-xs',
        default => 'px-2.5 py-0.5 text-sm',
    };
    
    $roundedClasses = $rounded === 'full' ? 'rounded-full' : 'rounded-md';

    $variantClasses = match($variant) {
        'success' => 'bg-green-100 text-green-800',
        'warning' => 'bg-yellow-100 text-yellow-800',
        'error', 'danger' => 'bg-red-100 text-red-800',
        'dark' => 'bg-gray-100 text-gray-800',
        default => 'bg-blue-100 text-blue-800', // info
    };
@endphp

<span {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$roundedClasses} {$variantClasses}"]) }}>
    {{ $slot }}
</span>
