@props([
    'headers' => null,
])

<div class="overflow-x-auto rounded-lg border border-gray-200">
    <table {{ $attributes->merge(['class' => 'min-w-full divide-y divide-gray-200']) }}>
        @if($headers)
            <thead class="bg-gray-50">
                <tr>
                    {{ $headers }}
                </tr>
            </thead>
        @endif
        
        <tbody class="divide-y divide-gray-200 bg-white">
            {{ $slot }}
        </tbody>
        
        @if(isset($tfoot))
            <tfoot class="bg-gray-50">
                {{ $tfoot }}
            </tfoot>
        @endif
    </table>
</div>
