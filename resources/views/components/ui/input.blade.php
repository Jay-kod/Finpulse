@props([
    'disabled' => false,
    'error' => false
])

@php
    $baseClasses = 'block w-full rounded-lg border shadow-sm sm:text-sm transition-colors focus:ring-2 focus:ring-offset-0 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100';
    
    $stateClasses = $error
        ? 'border-red-300 dark:border-red-500 text-red-900 dark:text-red-400 focus:border-red-500 focus:ring-red-500/20'
        : 'border-gray-300 dark:border-gray-700 focus:border-primary-500 focus:ring-primary-500/20';
@endphp

<input {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses}"]) }}>
