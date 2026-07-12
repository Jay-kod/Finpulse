@extends('layouts.app')

@section('title', 'Analytics Hub')

@push('scripts')
    @vite(['resources/js/analytics.js'])
@endpush

@section('content')
<div class="max-w-7xl mx-auto space-y-6">
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Analytics Hub</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Real-time sentiment and intent analysis across your active portfolio.</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Period Filter Dropdown --}}
            <div x-data="{ open: false }" class="relative hidden sm:block">
                <button @click="open = !open" @click.outside="open = false" type="button"
                    class="flex items-center bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg px-3 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 shadow-sm cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                    <svg class="w-4 h-4 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    @if(($period ?? '30') == '7') Last 7 Days
                    @elseif(($period ?? '30') == '14') Last 14 Days
                    @elseif(($period ?? '30') == '90') Last 90 Days
                    @else Last 30 Days
                    @endif
                    <svg class="w-4 h-4 ml-2 text-gray-400 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </button>
                <div x-show="open" x-transition:enter="transition ease-out duration-100" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                    class="absolute right-0 mt-2 w-44 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-xl shadow-lg z-50 py-1 overflow-hidden">
                    @foreach(['7' => 'Last 7 Days', '14' => 'Last 14 Days', '30' => 'Last 30 Days', '90' => 'Last 90 Days'] as $val => $label)
                        <a href="?period={{ $val }}"
                            class="block px-4 py-2.5 text-sm transition-colors {{ ($period ?? '30') == $val ? 'bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 font-semibold' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700/50' }}">
                            {{ $label }}
                        </a>
                    @endforeach
                </div>
            </div>
            @hasanyrole(['Super Admin', 'Admin', 'Analyst'])
            <x-ui.button variant="primary" href="{{ route('analyst.export.all') }}" class="shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export Report
            </x-ui.button>
            @endhasanyrole
        </div>
    </div>

    {{-- Premium Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Card 1 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Analyzed</h3>
                <div class="p-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                </div>
            </div>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ number_format($overviewStats['total_reviews']) }}</span>
                <span class="text-sm font-medium text-positive-600 dark:text-positive-400 flex items-center pb-1">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path></svg>
                    12%
                </span>
            </div>
        </div>

        {{-- Card 2 --}}
        @php
            $sentColor = $overviewStats['avg_sentiment'] >= 0.05 ? 'positive' : ($overviewStats['avg_sentiment'] <= -0.05 ? 'negative' : 'neutral');
        @endphp
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Avg Sentiment</h3>
                <div class="p-1.5 bg-{{ $sentColor }}-50 dark:bg-{{ $sentColor }}-900/30 text-{{ $sentColor }}-600 dark:text-{{ $sentColor }}-400 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-bold text-{{ $sentColor }}-600 dark:text-{{ $sentColor }}-400 tracking-tight">{{ $overviewStats['avg_sentiment'] }}</span>
                <span class="text-sm font-medium text-gray-500 pb-1">score</span>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Bug Flag Rate</h3>
                <div class="p-1.5 bg-red-50 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                </div>
            </div>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-bold text-red-600 dark:text-red-500 tracking-tight">{{ $overviewStats['bug_rate'] }}%</span>
                <span class="text-sm font-medium text-negative-600 dark:text-negative-400 flex items-center pb-1">
                    <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path></svg>
                    2.1%
                </span>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Top Topic</h3>
                <div class="p-1.5 bg-cyan-50 dark:bg-cyan-900/30 text-cyan-600 dark:text-cyan-400 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                </div>
            </div>
            <div class="flex items-end space-x-2 mt-1">
                <span class="text-xl font-bold text-gray-900 dark:text-white tracking-tight truncate max-w-full" title="{{ $overviewStats['top_topic'] }}">{{ $overviewStats['top_topic'] }}</span>
            </div>
        </div>
    </div>

    {{-- Charts Row 1: Sentiment Trend + Distribution --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Sentiment Over Time (Line Chart) --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm relative">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Sentiment Trend</h3>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-6">Average compound sentiment score over the last 30 days.</p>
            <div class="h-64 w-full relative">
                <canvas id="sentimentOverTimeChart" 
                    data-chart-labels="{{ json_encode($sentimentOverTime['labels']) }}" 
                    data-chart-data="{{ json_encode($sentimentOverTime['datasets']) }}"></canvas>
            </div>
        </div>

        {{-- Sentiment Distribution (Doughnut) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Sentiment Split</h3>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-6">Distribution of positive, neutral, and negative reviews.</p>
            <div class="h-64 w-full relative flex items-center justify-center">
                <canvas id="sentimentDistributionChart" 
                    data-chart-labels="{{ json_encode($sentimentDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($sentimentDistribution['data']) }}"></canvas>
            </div>
        </div>
    </div>

    {{-- Charts Row 2: Topic + Intent --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        {{-- Topic Distribution (Bar Chart) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">Top 5 Topics</h3>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-6">Most frequently discussed topics in reviews.</p>
            <div class="h-64 w-full relative">
                <canvas id="topicDistributionChart" 
                    data-chart-labels="{{ json_encode($topicDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($topicDistribution['data']) }}"></canvas>
            </div>
        </div>

        {{-- Intent Distribution (Bar Chart) --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-1">User Intents</h3>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mb-6">What users are trying to express in their reviews.</p>
            <div class="h-64 w-full relative">
                <canvas id="intentDistributionChart" 
                    data-chart-labels="{{ json_encode($intentDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($intentDistribution['data']) }}"></canvas>
            </div>
        </div>
    </div>

    {{-- Bug Flagged Reviews Table --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white">Recent Bug Reports</h3>
            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 mt-1">Reviews flagged as potential software bugs by the ML engine.</p>
        </div>
        
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">App</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cleaned Text</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Topic</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($recentAnomalies as $review)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            {{ $review->dataset->fintechApp->name ?? '—' }}
                            <div class="text-xs text-gray-400 font-mono mt-0.5">
                                #{{ $review->id }} &bull; {{ $review->dataset->source ?? 'Unknown Source' }}
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-600 dark:text-gray-300 line-clamp-2 max-w-md">
                                {{ $review->cleaned_content ?? $review->content }}
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($review->topic)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-100 dark:border-blue-800/50">{{ $review->topic }}</span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($review->intent)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-100 dark:border-purple-800/50">{{ $review->intent }}</span>
                            @else
                                <span class="text-xs text-gray-400">—</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                            {{ $review->published_at ? $review->published_at->format('M d, Y') : $review->created_at->format('M d, Y') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-800 mb-4">
                                <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <h3 class="text-sm font-medium text-gray-900 dark:text-white">No bug reports found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Your portfolio is currently free of flagged issues.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
