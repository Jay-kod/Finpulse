@props([
    'disabled' => false,
    'error' => false
])

@php
    $baseClasses = 'rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors';
    
    $stateClasses = $error ? 'border-red-300 focus:border-red-300 focus:ring-red-200' : '';
@endphp

<input type="checkbox" {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses}"]) }}>
