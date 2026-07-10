@extends('layouts.app')

@section('title', 'Data Pipeline')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Data Pipeline Management</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Monitor and trigger NLP, ML Classification, and Sentiment Analysis for reviews.</p>
    </div>

    @if(session('success'))
        <div class="mb-6">
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        </div>
    @endif

    {{-- Service Status Banner --}}
    <div class="mb-6 rounded-lg border px-4 py-3 flex items-center gap-3 {{ $serviceOnline ? 'bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800' : 'bg-red-50 dark:bg-red-900/20 border-red-200 dark:border-red-800' }}">
        <span class="flex h-3 w-3 relative">
            <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $serviceOnline ? 'bg-green-400' : 'bg-red-400' }} opacity-75"></span>
            <span class="relative inline-flex rounded-full h-3 w-3 {{ $serviceOnline ? 'bg-green-500' : 'bg-red-500' }}"></span>
        </span>
        <span class="text-sm font-medium {{ $serviceOnline ? 'text-green-800 dark:text-green-300' : 'text-red-800 dark:text-red-300' }}">
            Microservice Backend: {{ $serviceOnline ? 'Online' : 'Offline — pipelines will mark records as error' }}
        </span>
    </div>

    {{-- Stats Cards Overview --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <x-ui.card class="!p-5 bg-gray-50 dark:bg-gray-800/50">
            <div class="flex flex-col text-center">
                <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($stats['total']) }}</span>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Total Reviews</span>
            </div>
        </x-ui.card>
        
        <x-ui.card class="!p-5 border-yellow-200 dark:border-yellow-900/50">
            <div class="flex flex-col text-center">
                <span class="text-3xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($stats['nlp_pending']) }}</span>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Awaiting NLP</span>
            </div>
        </x-ui.card>

        <x-ui.card class="!p-5 border-blue-200 dark:border-blue-900/50">
            <div class="flex flex-col text-center">
                <span class="text-3xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($stats['ml_pending']) }}</span>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Awaiting ML</span>
            </div>
        </x-ui.card>

        <x-ui.card class="!p-5 border-purple-200 dark:border-purple-900/50">
            <div class="flex flex-col text-center">
                <span class="text-3xl font-bold text-purple-600 dark:text-purple-400">{{ number_format($stats['sentiment_pending']) }}</span>
                <span class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">Awaiting Sentiment</span>
            </div>
        </x-ui.card>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Stage 1: NLP Preprocessing --}}
        <x-ui.card>
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Stage 1: NLP</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Text cleaning & language.</p>
                </div>
            </div>

            <div class="flex gap-3 mb-6">
                <div class="flex-1 bg-green-50 dark:bg-green-900/20 p-2 rounded-lg border border-green-100 dark:border-green-800 text-center">
                    <div class="text-xs text-green-800 dark:text-green-300 font-medium mb-1">Processed</div>
                    <div class="text-lg font-bold text-green-700 dark:text-green-400">{{ number_format($stats['nlp_processed']) }}</div>
                </div>
                <div class="flex-1 bg-red-50 dark:bg-red-900/20 p-2 rounded-lg border border-red-100 dark:border-red-800 text-center">
                    <div class="text-xs text-red-800 dark:text-red-300 font-medium mb-1">Errors</div>
                    <div class="text-lg font-bold text-red-700 dark:text-red-400">{{ number_format($stats['nlp_error']) }}</div>
                </div>
            </div>

            <form action="{{ route('analyst.preprocessing.dispatch') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                @csrf
                <x-ui.input type="number" name="limit" value="50" min="1" max="500" class="w-20" aria-label="Batch size" />
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto flex-1">
                    Run NLP
                </x-ui.button>
            </form>
        </x-ui.card>

        {{-- Stage 2: ML Classification --}}
        <x-ui.card>
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Stage 2: ML</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Topic, intent & bugs.</p>
                </div>
            </div>

            <div class="flex gap-3 mb-6">
                <div class="flex-1 bg-green-50 dark:bg-green-900/20 p-2 rounded-lg border border-green-100 dark:border-green-800 text-center">
                    <div class="text-xs text-green-800 dark:text-green-300 font-medium mb-1">Classified</div>
                    <div class="text-lg font-bold text-green-700 dark:text-green-400">{{ number_format($stats['ml_classified']) }}</div>
                </div>
                <div class="flex-1 bg-red-50 dark:bg-red-900/20 p-2 rounded-lg border border-red-100 dark:border-red-800 text-center">
                    <div class="text-xs text-red-800 dark:text-red-300 font-medium mb-1">Errors</div>
                    <div class="text-lg font-bold text-red-700 dark:text-red-400">{{ number_format($stats['ml_error']) }}</div>
                </div>
            </div>

            <form action="{{ route('analyst.preprocessing.dispatch-ml') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                @csrf
                <x-ui.input type="number" name="limit" value="50" min="1" max="500" class="w-20" aria-label="Batch size" />
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto flex-1">
                    Run ML
                </x-ui.button>
            </form>
        </x-ui.card>

        {{-- Stage 3: Sentiment Analysis --}}
        <x-ui.card>
            <div class="flex items-start justify-between mb-6">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Stage 3: Sentiment</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Pos, Neg, Neutral scores.</p>
                </div>
            </div>

            <div class="flex gap-3 mb-6">
                <div class="flex-1 bg-green-50 dark:bg-green-900/20 p-2 rounded-lg border border-green-100 dark:border-green-800 text-center">
                    <div class="text-xs text-green-800 dark:text-green-300 font-medium mb-1">Analyzed</div>
                    <div class="text-lg font-bold text-green-700 dark:text-green-400">{{ number_format($stats['sentiment_analyzed']) }}</div>
                </div>
                <div class="flex-1 bg-red-50 dark:bg-red-900/20 p-2 rounded-lg border border-red-100 dark:border-red-800 text-center">
                    <div class="text-xs text-red-800 dark:text-red-300 font-medium mb-1">Errors</div>
                    <div class="text-lg font-bold text-red-700 dark:text-red-400">{{ number_format($stats['sentiment_error']) }}</div>
                </div>
            </div>

            <form action="{{ route('analyst.preprocessing.dispatch-sentiment') }}" method="POST" class="flex flex-col sm:flex-row items-center gap-2 bg-gray-50 dark:bg-gray-800/50 p-3 rounded-lg border border-gray-200 dark:border-gray-700">
                @csrf
                <x-ui.input type="number" name="limit" value="50" min="1" max="500" class="w-20" aria-label="Batch size" />
                <x-ui.button type="submit" variant="primary" class="w-full sm:w-auto flex-1">
                    Run Sentiment
                </x-ui.button>
            </form>
        </x-ui.card>
    </div>

    {{-- Recently Processed --}}
    <x-ui.card>
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Pipeline Activity Logs</h2>
        <div class="overflow-x-auto -mx-6">
            <div class="inline-block min-w-full align-middle">
                <x-ui.table class="border-t border-gray-200 dark:border-gray-700">
                    <thead>
                        <x-ui.table.tr>
                            <x-ui.table.th>ID</x-ui.table.th>
                            <x-ui.table.th>Pipeline State</x-ui.table.th>
                            <x-ui.table.th>Cleaned Content</x-ui.table.th>
                            <x-ui.table.th>ML Classification</x-ui.table.th>
                            <x-ui.table.th>Sentiment</x-ui.table.th>
                        </x-ui.table.tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentlyProcessed as $review)
                            <x-ui.table.tr>
                                <x-ui.table.td>
                                    <span class="text-xs font-mono text-gray-500 dark:text-gray-400">#{{ $review->id }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <div class="flex flex-col gap-1">
                                        <x-ui.badge variant="{{ $review->processed_status === 'processed' ? 'success' : ($review->processed_status === 'error' ? 'danger' : 'warning') }}" class="text-[9px]">
                                            NLP: {{ strtoupper($review->processed_status) }}
                                        </x-ui.badge>
                                        <x-ui.badge variant="{{ $review->ml_status === 'classified' ? 'success' : ($review->ml_status === 'error' ? 'danger' : 'warning') }}" class="text-[9px]">
                                            ML: {{ strtoupper($review->ml_status) }}
                                        </x-ui.badge>
                                        <x-ui.badge variant="{{ $review->sentiment_status === 'analyzed' ? 'success' : ($review->sentiment_status === 'error' ? 'danger' : 'warning') }}" class="text-[9px]">
                                            SENT: {{ strtoupper($review->sentiment_status) }}
                                        </x-ui.badge>
                                    </div>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 max-w-[200px]" title="{{ $review->cleaned_content }}">{{ $review->cleaned_content ?? '—' }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    @if($review->ml_status === 'classified')
                                        <div class="flex flex-col gap-1">
                                            @if($review->topic)
                                                <span class="text-xs text-gray-600 dark:text-gray-400"><span class="font-medium text-gray-900 dark:text-white">Topic:</span> {{ $review->topic }}</span>
                                            @endif
                                            @if($review->intent)
                                                <span class="text-xs text-gray-600 dark:text-gray-400"><span class="font-medium text-gray-900 dark:text-white">Intent:</span> {{ $review->intent }}</span>
                                            @endif
                                            @if($review->is_bug)
                                                <span class="text-xs font-bold text-red-600 dark:text-red-400">⚠️ BUG</span>
                                            @endif
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Pending...</span>
                                    @endif
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    @if($review->sentiment_status === 'analyzed')
                                        @php
                                            $score = $review->sentiment_compound;
                                            $color = $score >= 0.05 ? 'text-green-600' : ($score <= -0.05 ? 'text-red-600' : 'text-gray-600');
                                            $label = $score >= 0.05 ? 'Positive' : ($score <= -0.05 ? 'Negative' : 'Neutral');
                                        @endphp
                                        <div class="flex flex-col gap-1">
                                            <span class="text-sm font-bold {{ $color }}">{{ $label }} ({{ number_format($score, 2) }})</span>
                                            <div class="flex gap-2 text-[10px] text-gray-500">
                                                <span>P: {{ number_format($review->sentiment_positive, 2) }}</span>
                                                <span>N: {{ number_format($review->sentiment_negative, 2) }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-xs text-gray-400 italic">Pending...</span>
                                    @endif
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @empty
                            <x-ui.table.tr>
                                <x-ui.table.td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    No records have entered the pipeline yet.
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @endforelse
                    </tbody>
                </x-ui.table>
            </div>
        </div>
    </x-ui.card>
</div>
@endsection
