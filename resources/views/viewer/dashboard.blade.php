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
<div class="max-w-7xl mx-auto animate-fade-in">
    {{-- Header --}}
    <div class="mb-8 bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm relative overflow-hidden">
        <div class="absolute -right-10 -top-10 opacity-[0.04] pointer-events-none">
            <svg class="w-48 h-48 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
        </div>
        <div class="relative z-10">
            <h1 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 tracking-tight">Dashboard Overview</h1>
            <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400 tracking-wide">Welcome back, {{ Auth::user()->name }}! Here's what's happening today.</p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
        {{-- Total Reviews --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6 transition-transform hover:-translate-y-1 hover:shadow-md duration-300">
            <div class="absolute -right-6 -top-6 opacity-[0.07] dark:opacity-[0.03] transition-transform group-hover:scale-110 duration-300 pointer-events-none">
                <svg class="w-36 h-36 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ $kpis['total_reviews']['value'] }}</p>
                <p class="mt-1 text-[11px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Reviews</p>
                <div class="mt-3 flex items-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $kpis['total_reviews']['trend_up'] ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-red-500/10 text-red-600 dark:text-red-400' }}">
                        <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpis['total_reviews']['trend_up'] ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' }}"></path></svg>
                        {{ $kpis['total_reviews']['trend'] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Average Sentiment --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6 transition-transform hover:-translate-y-1 hover:shadow-md duration-300">
            <div class="absolute -right-6 -top-6 opacity-[0.07] dark:opacity-[0.03] transition-transform group-hover:scale-110 duration-300 pointer-events-none">
                <svg class="w-36 h-36 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-3xl sm:text-4xl font-black {{ $kpis['average_sentiment']['trend_up'] ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} tracking-tight">{{ $kpis['average_sentiment']['value'] }}</p>
                <p class="mt-1 text-[11px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg Sentiment</p>
                <div class="mt-3 flex items-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $kpis['average_sentiment']['trend_up'] ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-red-500/10 text-red-600 dark:text-red-400' }}">
                        {{ $kpis['average_sentiment']['trend'] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Active Datasets --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6 transition-transform hover:-translate-y-1 hover:shadow-md duration-300">
            <div class="absolute -right-6 -top-6 opacity-[0.07] dark:opacity-[0.03] transition-transform group-hover:scale-110 duration-300 pointer-events-none">
                <svg class="w-36 h-36 text-cyan-600 dark:text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ $kpis['active_datasets']['value'] }}</p>
                <p class="mt-1 text-[11px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Active Datasets</p>
                <div class="mt-3 flex items-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-500/10 text-gray-500 dark:text-gray-400">
                        {{ $kpis['active_datasets']['trend'] }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Anomalies Detected --}}
        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6 transition-transform hover:-translate-y-1 hover:shadow-md duration-300">
            <div class="absolute -right-6 -top-6 opacity-[0.07] dark:opacity-[0.03] transition-transform group-hover:scale-110 duration-300 pointer-events-none">
                <svg class="w-36 h-36 text-amber-600 dark:text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-3xl sm:text-4xl font-black text-amber-600 dark:text-amber-400 tracking-tight">{{ $kpis['anomalies_detected']['value'] }}</p>
                <p class="mt-1 text-[11px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Bug Report Rate</p>
                <div class="mt-3 flex items-center">
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold {{ $kpis['anomalies_detected']['trend_up'] ? 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400' : 'bg-amber-500/10 text-amber-600 dark:text-amber-400' }}">
                        <svg class="w-3 h-3 mr-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $kpis['anomalies_detected']['trend_up'] ? 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' : 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' }}"></path></svg>
                        {{ $kpis['anomalies_detected']['trend'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5 mb-8">
        {{-- Line Chart --}}
        <div class="lg:col-span-2 bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Sentiment Trends</h3>
            <div class="h-80 w-full relative">
                <canvas id="sentimentTrendsChart"></canvas>
            </div>
        </div>

        {{-- Doughnut Chart --}}
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Overall Breakdown</h3>
            <div class="h-80 w-full relative flex items-center justify-center">
                <canvas id="sentimentBreakdownChart"></canvas>
                {{-- Centered Text inside Doughnut --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-4">
                    <span id="doughnutCenterText" class="text-3xl font-bold text-gray-900 dark:text-white">68%</span>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Positive</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Reviews Section --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
        <div class="p-6 border-b border-gray-100 dark:border-gray-700 flex items-center justify-between">
            <div>
                <h3 class="text-base font-bold text-gray-900 dark:text-white">Recent Reviews Across Apps</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Latest analyzed reviews from all your fintech apps</p>
            </div>
            <a href="{{ route('viewer.apps.index') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                View All Apps
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
            </a>
        </div>

        @if(count($recentReviews) === 0)
            <div class="p-12 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
                <p class="text-sm text-gray-500 dark:text-gray-400">No analyzed reviews yet. Sync some apps to see reviews here.</p>
            </div>
        @else
            <div class="divide-y divide-gray-100 dark:divide-gray-700/60">
                @foreach($recentReviews as $review)
                <div class="p-4 sm:p-5 hover:bg-gray-50/50 dark:hover:bg-gray-800/50 transition-colors duration-200 group">
                    <div class="flex flex-col sm:flex-row sm:items-start gap-3 sm:gap-4">
                        {{-- App info & source --}}
                        <div class="flex items-center gap-3 sm:min-w-[160px] shrink-0">
                            <div class="h-9 w-9 rounded-xl bg-gradient-to-br from-primary-400 to-accent-500 flex items-center justify-center text-white text-xs font-bold shadow-sm shrink-0">
                                {{ substr($review['app'], 0, 1) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-sm font-bold text-gray-900 dark:text-white truncate">{{ $review['app'] }}</p>
                                <div class="flex items-center gap-1.5 mt-0.5">
                                    @if($review['source'] === 'apple_app_store')
                                        <span class="inline-flex items-center text-[10px] font-bold text-gray-500 dark:text-gray-400">
                                            <svg class="w-3 h-3 mr-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                                            App Store
                                        </span>
                                    @else
                                        <span class="inline-flex items-center text-[10px] font-bold text-gray-500 dark:text-gray-400">
                                            <svg class="w-3 h-3 mr-0.5" viewBox="0 0 24 24" fill="currentColor"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 01-.61-.92V2.734a1 1 0 01.609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-1.398l2.5 1.448a1 1 0 010 1.486l-2.5 1.448-2.537-2.537 2.537-2.537zM5.864 2.658L16.8 9.291l-2.302 2.302-8.634-8.935z"/></svg>
                                            Google Play
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Review content --}}
                        <div class="flex-1 min-w-0">
                            <p class="text-sm text-gray-600 dark:text-gray-300 leading-relaxed line-clamp-2" title="{{ $review['text'] }}">
                                "{{ $review['text'] }}"
                            </p>
                        </div>

                        {{-- Sentiment, Score & Time --}}
                        <div class="flex items-center gap-3 sm:gap-4 shrink-0 sm:flex-col sm:items-end">
                            <div class="flex items-center gap-2">
                                {{-- Rating stars --}}
                                @if($review['rating'])
                                    <div class="flex items-center gap-0.5">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3.5 h-3.5 {{ $i <= $review['rating'] ? 'text-amber-400' : 'text-gray-200 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endfor
                                    </div>
                                @endif

                                {{-- Sentiment badge --}}
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                    {{ match($review['sentiment']) {
                                        'Positive' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20',
                                        'Negative' => 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20',
                                        default => 'bg-gray-500/10 text-gray-600 dark:text-gray-400 border border-gray-500/20'
                                    } }}">
                                    {{ $review['sentiment'] }}
                                </span>

                                {{-- Score --}}
                                <span class="text-xs font-bold {{ $review['score'] > 0.5 ? 'text-emerald-600 dark:text-emerald-400' : ($review['score'] < -0.5 ? 'text-red-600 dark:text-red-400' : 'text-gray-500 dark:text-gray-400') }}">
                                    {{ number_format($review['score'], 2) }}
                                </span>
                            </div>

                            <span class="text-[11px] text-gray-400 dark:text-gray-500 font-medium">{{ $review['date'] }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection

