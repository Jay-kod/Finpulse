@props([
    'label' => null,
    'for' => null,
    'help' => null,
    'error' => null,
    'messages' => null
])

<div {{ $attributes->merge(['class' => 'mb-4']) }}>
    @if($label)
        <x-ui.label :for="$for" class="mb-1">
            {{ $label }}
        </x-ui.label>
    @endif

    {{ $slot }}

    @if($help)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif

    @if(is_string($error) && $error !== '1' && $error !== '')
        <x-ui.error :messages="[$error]" />
    @elseif(isset($messages))
        <x-ui.error :messages="$messages" />
    @elseif($error !== false && $for)
        <x-ui.error :for="$for" />
    @endif
</div>
