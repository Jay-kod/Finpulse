@extends('layouts.app')

@section('title', 'Analytics Hub')

@push('scripts')
    @vite(['resources/js/analytics.js'])
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Analytics Dashboard</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">High-level insights aggregated from processed review data.</p>
        </div>
        <div class="flex items-center gap-3">
            <x-ui.button variant="secondary" href="{{ route('analyst.export.all') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export All CSV
            </x-ui.button>
        </div>
    </div>

    {{-- Overview Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.card class="!p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-lg bg-primary-100 dark:bg-primary-900/30 p-3">
                    <svg class="w-6 h-6 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Analyzed Reviews</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($overviewStats['total_reviews']) }}</p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="!p-5">
            <div class="flex items-center">
                @php
                    $sentColor = $overviewStats['avg_sentiment'] >= 0.05 ? 'positive' : ($overviewStats['avg_sentiment'] <= -0.05 ? 'negative' : 'neutral');
                @endphp
                <div class="flex-shrink-0 rounded-lg bg-{{ $sentColor }}-100 dark:bg-{{ $sentColor }}-900/30 p-3">
                    <svg class="w-6 h-6 text-{{ $sentColor }}-600 dark:text-{{ $sentColor }}-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Sentiment</p>
                    <p class="text-2xl font-bold text-{{ $sentColor }}-600 dark:text-{{ $sentColor }}-400">{{ $overviewStats['avg_sentiment'] }}</p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="!p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-lg bg-red-100 dark:bg-red-900/30 p-3">
                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bug Rate</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $overviewStats['bug_rate'] }}%</p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="!p-5">
            <div class="flex items-center">
                <div class="flex-shrink-0 rounded-lg bg-blue-100 dark:bg-blue-900/30 p-3">
                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Top Topic</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $overviewStats['top_topic'] }}</p>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Charts Row 1: Sentiment Trend + Distribution --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Sentiment Over Time (Line Chart) --}}
        <x-ui.card class="lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Sentiment Trend (Last 30 Days)</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Average compound sentiment score over time.</p>
            <div class="h-72 w-full relative">
                <canvas id="sentimentOverTimeChart" 
                    data-chart-labels="{{ json_encode($sentimentOverTime['labels']) }}" 
                    data-chart-data="{{ json_encode($sentimentOverTime['data']) }}"></canvas>
            </div>
        </x-ui.card>

        {{-- Sentiment Distribution (Doughnut) --}}
        <x-ui.card>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Sentiment Split</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Distribution of positive, neutral, and negative reviews.</p>
            <div class="h-72 w-full relative flex items-center justify-center">
                <canvas id="sentimentDistributionChart" 
                    data-chart-labels="{{ json_encode($sentimentDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($sentimentDistribution['data']) }}"></canvas>
            </div>
        </x-ui.card>
    </div>

    {{-- Charts Row 2: Topic + Intent --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Topic Distribution (Bar Chart) --}}
        <x-ui.card>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">Top 5 Topics</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">Most frequently discussed topics in reviews.</p>
            <div class="h-72 w-full relative">
                <canvas id="topicDistributionChart" 
                    data-chart-labels="{{ json_encode($topicDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($topicDistribution['data']) }}"></canvas>
            </div>
        </x-ui.card>

        {{-- Intent Distribution (Bar Chart) --}}
        <x-ui.card>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">User Intents</h3>
            <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">What users are trying to express in their reviews.</p>
            <div class="h-72 w-full relative">
                <canvas id="intentDistributionChart" 
                    data-chart-labels="{{ json_encode($intentDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($intentDistribution['data']) }}"></canvas>
            </div>
        </x-ui.card>
    </div>

    {{-- Bug Flagged Reviews Table --}}
    <x-ui.card>
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Bug Reports</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Reviews flagged as potential software bugs by the ML engine.</p>
            </div>
        </div>
        
        <div class="overflow-x-auto -mx-6">
            <div class="inline-block min-w-full align-middle">
                <x-ui.table class="border-t border-gray-200 dark:border-gray-700">
                    <thead>
                        <x-ui.table.tr>
                            <x-ui.table.th>ID</x-ui.table.th>
                            <x-ui.table.th>App</x-ui.table.th>
                            <x-ui.table.th>Cleaned Text</x-ui.table.th>
                            <x-ui.table.th>Topic</x-ui.table.th>
                            <x-ui.table.th>Intent</x-ui.table.th>
                            <x-ui.table.th>Sentiment</x-ui.table.th>
                            <x-ui.table.th class="text-right">Date</x-ui.table.th>
                        </x-ui.table.tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($recentAnomalies as $review)
                        <x-ui.table.tr>
                            <x-ui.table.td>
                                <span class="text-xs font-mono text-gray-500 dark:text-gray-400">#{{ $review->id }}</span>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <span class="font-medium text-gray-900 dark:text-white">{{ $review->dataset->fintechApp->name ?? '—' }}</span>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                <div class="max-w-xs text-sm text-gray-600 dark:text-gray-400 line-clamp-2">
                                    {{ $review->cleaned_content ?? $review->content }}
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>
                                @if($review->topic)
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300">{{ $review->topic }}</span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </x-ui.table.td>
                            <x-ui.table.td>
                                @if($review->intent)
                                    <span class="px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-300">{{ $review->intent }}</span>
                                @else
                                    <span class="text-xs text-gray-400">—</span>
                                @endif
                            </x-ui.table.td>
                            <x-ui.table.td>
                                @php
                                    $score = $review->sentiment_compound;
                                    $color = $score >= 0.05 ? 'text-positive-600' : ($score <= -0.05 ? 'text-negative-600' : 'text-neutral-600');
                                    $label = $score >= 0.05 ? 'Positive' : ($score <= -0.05 ? 'Negative' : 'Neutral');
                                @endphp
                                <span class="text-sm font-semibold {{ $color }}">{{ $label }} ({{ number_format($score, 2) }})</span>
                            </x-ui.table.td>
                            <x-ui.table.td class="text-right text-sm text-gray-500 dark:text-gray-400">
                                {{ $review->published_at ? $review->published_at->format('M d, Y') : $review->created_at->format('M d, Y') }}
                            </x-ui.table.td>
                        </x-ui.table.tr>
                        @empty
                        <x-ui.table.tr>
                            <x-ui.table.td colspan="7" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                No bug reports have been flagged yet.
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
