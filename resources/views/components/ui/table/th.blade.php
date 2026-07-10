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

<th scope="col" {{ $attributes->merge(['class' => "px-6 py-3 text-xs font-medium uppercase tracking-wider text-gray-500 {$alignClass}"]) }}>
    {{ $slot }}
</th>
