@props([
    'align' => 'left' // left, center, right
])

@php
    $alignClass = match($align) {
        'center' => 'text-center',
        'right' => 'text-right',
        default => 'text-left',
    };
@endphp

<td {{ $attributes->merge(['class' => "whitespace-nowrap px-6 py-4 text-sm text-gray-700 {$alignClass}"]) }}>
    {{ $slot }}
</td>
