@props([
    'disabled' => false,
    'error' => false,
    'options' => [],
    'selected' => null,
    'placeholder' => null
])

@php
    $baseClasses = 'block w-full rounded-lg border shadow-sm sm:text-sm transition-colors focus:ring-2 focus:ring-offset-0 disabled:opacity-50 disabled:bg-gray-50 disabled:cursor-not-allowed';
    
    $stateClasses = $error
        ? 'border-red-300 text-red-900 focus:border-red-500 focus:ring-red-500/20'
        : 'border-gray-300 focus:border-primary-500 focus:ring-primary-500/20';
@endphp

<select {{ $disabled ? 'disabled' : '' }} {{ $attributes->merge(['class' => "{$baseClasses} {$stateClasses}"]) }}>
    @if($placeholder)
        <option value="" disabled {{ is_null($selected) ? 'selected' : '' }}>{{ $placeholder }}</option>
    @endif
    
    @if($slot->isNotEmpty())
        {{ $slot }}
    @else
        @foreach($options as $value => $label)
            <option value="{{ $value }}" {{ (string)$selected === (string)$value ? 'selected' : '' }}>
                {{ $label }}
            </option>
        @endforeach
    @endif
</select>
