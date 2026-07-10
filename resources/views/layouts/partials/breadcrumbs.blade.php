<nav aria-label="Breadcrumb" class="mb-6">
    <ol class="flex items-center space-x-2 text-sm text-gray-500">
        <li>
            <a href="{{ url('/') }}" class="hover:text-primary-600 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
            </a>
        </li>
        @if(isset($breadcrumbs))
            @foreach($breadcrumbs as $crumb)
                <li class="flex items-center">
                    <svg class="w-4 h-4 mx-1 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                    @if(!$loop->last)
                        <a href="{{ $crumb['url'] }}" class="hover:text-primary-600 transition-colors">
                            {{ $crumb['label'] }}
                        </a>
                    @else
                        <span class="text-gray-700 font-medium">{{ $crumb['label'] }}</span>
                    @endif
                </li>
            @endforeach
        @endif
    </ol>
</nav>
