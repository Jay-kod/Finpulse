@extends('layouts.app')

@section('title', 'Dashboard')

@push('scripts')
    <script>
        window.sentimentTrendsData = @json($sentimentTrends);
        window.sentimentDistributionData = @json($sentimentDistribution);
        // Calculate positive percentage for the center of the doughnut chart
        const total = window.sentimentDistributionData.data.reduce((a, b) => a + b, 0);
        window.positivePercentage = total > 0 ? Math.round((window.sentimentDistributionData.data[0] / total) * 100) : 0;
    </script>
    @vite(['resources/js/dashboard.js'])
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dashboard Overview</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Reviews --}}
        <x-ui.card class="flex flex-col justify-between" glow="true">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Reviews Analyzed</h3>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-blue-500/20 to-indigo-600/20 flex items-center justify-center text-blue-600 dark:text-blue-400 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-4xl font-black bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">{{ $kpis['total_reviews']['value'] }}</div>
                <div class="mt-3 flex items-center text-sm font-medium {{ $kpis['total_reviews']['trend_up'] ? 'text-emerald-500' : 'text-red-500' }}">
                    <div class="flex items-center px-2 py-1 rounded-full {{ $kpis['total_reviews']['trend_up'] ? 'bg-emerald-500/10' : 'bg-red-500/10' }}">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpis['total_reviews']['trend_up'] ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"></path></svg>
                        <span>{{ $kpis['total_reviews']['trend'] }}</span>
                    </div>
                    <span class="text-gray-400 ml-2">vs last month</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Average Sentiment --}}
        <x-ui.card class="flex flex-col justify-between" glow="true">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Average Sentiment</h3>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-emerald-500/20 to-teal-600/20 flex items-center justify-center text-emerald-600 dark:text-emerald-400 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-4xl font-black bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">{{ $kpis['average_sentiment']['value'] }}</div>
                <div class="mt-3 flex items-center text-sm font-medium {{ $kpis['average_sentiment']['trend_up'] ? 'text-emerald-500' : 'text-red-500' }}">
                    <div class="flex items-center px-2 py-1 rounded-full {{ $kpis['average_sentiment']['trend_up'] ? 'bg-emerald-500/10' : 'bg-red-500/10' }}">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpis['average_sentiment']['trend_up'] ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"></path></svg>
                        <span>{{ $kpis['average_sentiment']['trend'] }}</span>
                    </div>
                    <span class="text-gray-400 ml-2">vs last month</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Active Datasets --}}
        <x-ui.card class="flex flex-col justify-between" glow="true">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Datasets</h3>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-cyan-500/20 to-blue-600/20 flex items-center justify-center text-cyan-600 dark:text-cyan-400 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-4xl font-black bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">{{ $kpis['active_datasets']['value'] }}</div>
                <div class="mt-3 flex items-center text-sm font-medium text-gray-400">
                    <div class="flex items-center px-2 py-1 rounded-full bg-gray-500/10">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                        <span>{{ $kpis['active_datasets']['trend'] }}</span>
                    </div>
                </div>
            </div>
        </x-ui.card>

        {{-- Anomalies Detected --}}
        <x-ui.card class="flex flex-col justify-between" glow="true">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Anomalies Detected</h3>
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-amber-500/20 to-orange-600/20 flex items-center justify-center text-amber-600 dark:text-amber-400 shadow-inner">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-4xl font-black bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">{{ $kpis['anomalies_detected']['value'] }}</div>
                <div class="mt-3 flex items-center text-sm font-medium {{ $kpis['anomalies_detected']['trend_up'] ? 'text-emerald-500' : 'text-amber-500' }}">
                    <div class="flex items-center px-2 py-1 rounded-full {{ $kpis['anomalies_detected']['trend_up'] ? 'bg-emerald-500/10' : 'bg-amber-500/10' }}">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpis['anomalies_detected']['trend_up'] ? 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' : 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' }}"></path></svg>
                        <span>{{ $kpis['anomalies_detected']['trend'] }}</span>
                    </div>
                    <span class="text-gray-400 ml-2">vs last month</span>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Line Chart --}}
        <x-ui.card class="lg:col-span-2" glow="true">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sentiment Trends</h3>
            <div class="h-80 w-full relative">
                <canvas id="sentimentTrendsChart"></canvas>
            </div>
        </x-ui.card>

        {{-- Doughnut Chart --}}
        <x-ui.card glow="true">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Overall Breakdown</h3>
            <div class="h-80 w-full relative flex items-center justify-center">
                <canvas id="sentimentBreakdownChart"></canvas>
                {{-- Centered Text inside Doughnut --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-4">
                    <span id="doughnutCenterText" class="text-3xl font-bold text-gray-900 dark:text-white">68%</span>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Positive</span>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Recent Activity Table --}}
    <x-ui.card glow="true" padding="p-0">
        <div class="p-6 border-b border-gray-100/80 dark:border-gray-700/50 flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Real-Time Reviews</h3>
            <x-ui.button tag="a" href="#" variant="ghost" size="sm">View All</x-ui.button>
        </div>
        <div class="overflow-x-auto">
            <div class="inline-block min-w-full align-middle">
                <x-ui.table class="w-full">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/40">
                        <x-ui.table.tr>
                            <x-ui.table.th class="px-6">App</x-ui.table.th>
                            <x-ui.table.th>Review Snippet</x-ui.table.th>
                            <x-ui.table.th>Sentiment</x-ui.table.th>
                            <x-ui.table.th>Score</x-ui.table.th>
                            <x-ui.table.th class="px-6 text-right">Time</x-ui.table.th>
                        </x-ui.table.tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/80 dark:divide-gray-700/50">
                        @foreach($recentReviews as $review)
                        <x-ui.table.tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-colors">
                            <x-ui.table.td class="px-6 py-4">
                                <span class="font-bold text-gray-900 dark:text-white">{{ $review['app'] }}</span>
                            </x-ui.table.td>
                            <x-ui.table.td class="py-4">
                                <div class="max-w-md truncate text-gray-600 dark:text-gray-300 font-medium" title="{{ $review['text'] }}">
                                    "{{ $review['text'] }}"
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td class="py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold
                                    {{ match($review['sentiment']) {
                                        'Positive' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20',
                                        'Negative' => 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20',
                                        default => 'bg-gray-500/10 text-gray-600 dark:text-gray-400 border border-gray-500/20'
                                    } }}">
                                    {{ $review['sentiment'] }}
                                </span>
                            </x-ui.table.td>
                            <x-ui.table.td class="py-4">
                                <div class="flex items-center">
                                    <span class="text-sm font-bold {{ $review['score'] > 0.5 ? 'text-emerald-600 dark:text-emerald-400' : ($review['score'] < -0.5 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400') }}">
                                        {{ number_format($review['score'], 2) }}
                                    </span>
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td class="px-6 py-4 text-right text-sm font-medium text-gray-500 dark:text-gray-400">
                                {{ $review['date'] }}
                            </x-ui.table.td>
                        </x-ui.table.tr>
                        @endforeach
                    </tbody>
                </x-ui.table>
            </div>
        </div>
    </x-ui.card>
</div>
@endsection
