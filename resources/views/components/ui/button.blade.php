@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, danger, outline, ghost
    'size' => 'md', // sm, md, lg
    'block' => false,
    'disabled' => false
])

@php
    $baseClasses = 'inline-flex justify-center items-center font-medium rounded-lg transition-colors focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed';
    
    $sizeClasses = match($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-4 py-2 text-sm',
    };

    $variantClasses = match($variant) {
        'secondary' => 'bg-white text-gray-700 border border-gray-300 hover:bg-gray-50 focus:ring-primary-500',
        'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500 border border-transparent',
        'outline' => 'bg-transparent text-primary-600 border border-primary-600 hover:bg-primary-50 focus:ring-primary-500',
        'ghost' => 'bg-transparent text-gray-600 hover:text-gray-900 hover:bg-gray-100 border border-transparent focus:ring-gray-500',
        default => 'bg-primary-600 text-white hover:bg-primary-700 focus:ring-primary-500 border border-transparent shadow-sm', // primary
    };

    $blockClass = $block ? 'w-full' : '';
@endphp

<button 
    type="{{ $type }}" 
    {{ $disabled ? 'disabled' : '' }}
    {{ $attributes->merge(['class' => "{$baseClasses} {$sizeClasses} {$variantClasses} {$blockClass}"]) }}
>
    {{ $slot }}
</button>
