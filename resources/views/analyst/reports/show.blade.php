@extends('layouts.app')

@section('title', 'Report: ' . $report->title)

@push('scripts')
    @vite(['resources/js/analytics.js'])
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <div class="flex items-center gap-3">
                @php
                    $backRoute = request()->routeIs('viewer.*') ? route('viewer.reports.index') : route('analyst.reports.index');
                @endphp
                <a href="{{ $backRoute }}" class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                </a>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $report->title }}</h1>
            </div>
            @if($report->description)
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 ml-9">{{ $report->description }}</p>
            @endif
            
            <div class="mt-3 ml-9 flex flex-wrap gap-2">
                @if($appName)
                    <span class="px-2 py-1 rounded text-xs font-medium bg-blue-50 text-blue-700 dark:bg-blue-900/30 dark:text-blue-300 border border-blue-200 dark:border-blue-800">App: {{ $appName }}</span>
                @else
                    <span class="px-2 py-1 rounded text-xs font-medium bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300 border border-gray-200 dark:border-gray-700">App: All</span>
                @endif
                
                @if(!empty($report->parameters['start_date']) || !empty($report->parameters['end_date']))
                    <span class="px-2 py-1 rounded text-xs font-medium bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-300 border border-purple-200 dark:border-purple-800">
                        Date: {{ $report->parameters['start_date'] ?? 'Beginning' }} to {{ $report->parameters['end_date'] ?? 'Now' }}
                    </span>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-3">
            @php
                $csvExportRoute = request()->routeIs('viewer.*') ? route('viewer.export.report', $report) : route('analyst.export.report', $report);
                $pdfExportRoute = request()->routeIs('viewer.*') ? route('viewer.export.report.pdf', $report) : route('analyst.export.report.pdf', $report);
            @endphp
            <x-ui.button variant="secondary" href="{{ $csvExportRoute }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export CSV
            </x-ui.button>
            <x-ui.button variant="secondary" href="{{ $pdfExportRoute }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Export PDF
            </x-ui.button>
        </div>
    </div>

    {{-- Overview Stats Row --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <x-ui.card class="!p-5 border-l-4 border-l-primary-500">
            <div class="flex items-center">
                <div class="ml-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Analyzed Reviews</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($overviewStats['total_reviews']) }}</p>
                </div>
            </div>
        </x-ui.card>

        @php
            $sentColor = $overviewStats['avg_sentiment'] >= 0.05 ? 'green' : ($overviewStats['avg_sentiment'] <= -0.05 ? 'red' : 'gray');
        @endphp
        <x-ui.card class="!p-5 border-l-4 border-l-{{ $sentColor }}-500">
            <div class="flex items-center">
                <div class="ml-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Avg Sentiment</p>
                    <p class="text-2xl font-bold text-{{ $sentColor }}-600 dark:text-{{ $sentColor }}-400">{{ $overviewStats['avg_sentiment'] }}</p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="!p-5 border-l-4 border-l-red-500">
            <div class="flex items-center">
                <div class="ml-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Bug Rate</p>
                    <p class="text-2xl font-bold text-red-600 dark:text-red-400">{{ $overviewStats['bug_rate'] }}%</p>
                </div>
            </div>
        </x-ui.card>

        <x-ui.card class="!p-5 border-l-4 border-l-blue-500">
            <div class="flex items-center">
                <div class="ml-2">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400">Top Topic</p>
                    <p class="text-2xl font-bold text-blue-600 dark:text-blue-400">{{ $overviewStats['top_topic'] }}</p>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Charts Row 1 --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <x-ui.card class="lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sentiment Trend</h3>
            <div class="h-72 w-full relative">
                <canvas id="sentimentOverTimeChart" 
                    data-chart-labels="{{ json_encode($sentimentOverTime['labels']) }}" 
                    data-chart-data="{{ json_encode($sentimentOverTime['data']) }}"></canvas>
            </div>
        </x-ui.card>

        <x-ui.card>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Sentiment Split</h3>
            <div class="h-72 w-full relative flex items-center justify-center">
                <canvas id="sentimentDistributionChart" 
                    data-chart-labels="{{ json_encode($sentimentDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($sentimentDistribution['data']) }}"></canvas>
            </div>
        </x-ui.card>
    </div>

    {{-- Charts Row 2 --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <x-ui.card>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Top Topics</h3>
            <div class="h-72 w-full relative">
                <canvas id="topicDistributionChart" 
                    data-chart-labels="{{ json_encode($topicDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($topicDistribution['data']) }}"></canvas>
            </div>
        </x-ui.card>

        <x-ui.card>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Intents</h3>
            <div class="h-72 w-full relative">
                <canvas id="intentDistributionChart" 
                    data-chart-labels="{{ json_encode($intentDistribution['labels']) }}" 
                    data-chart-data="{{ json_encode($intentDistribution['data']) }}"></canvas>
            </div>
        </x-ui.card>
    </div>
</div>
@endsection
