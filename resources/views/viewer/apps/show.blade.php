@extends('layouts.app')

@section('title', $app->name . ' — App Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('viewer.apps.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to App Directory
        </a>
    </div>

    {{-- App Header --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div class="flex items-center space-x-5">
                @if($app->logo_url)
                    <img src="{{ $app->logo_url }}" alt="{{ $app->name }} Logo" class="h-20 w-20 rounded-2xl object-cover shadow-md bg-gray-100">
                @else
                    <div class="h-20 w-20 rounded-2xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-3xl shadow-md">
                        {{ substr($app->name, 0, 1) }}
                    </div>
                @endif

                <div>
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $app->name }}</h1>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $app->package_name }}</p>
                    <div class="flex items-center space-x-3 mt-2">
                        @if($app->platform === 'android' || $app->platform === 'both')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-400">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993.0004.5511-.4482.9997-.9993.9997zm-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997zm11.4045-6.02l1.9973-3.4592c.1148-.1988.0461-.4523-.1527-.5671-.1992-.1148-.4527-.0465-.5675.1527l-2.0305 3.5165c-1.4255-.6507-3.037-.1-4.7081-1.0118-1.745.002-3.4217.375-4.9084 1.0553l-2.0006-3.464c-.1148-.1992-.3683-.2675-.5675-.1527-.1988.1148-.2671.3683-.1527.5671l1.9682 3.4093c-3.1518 1.7335-5.3283 5.0115-5.4673 8.8475h17.1023c-.1391-3.836-2.3155-7.114-5.4673-8.8475z"/></svg>
                                Android
                            </span>
                        @endif
                        @if($app->platform === 'ios' || $app->platform === 'both')
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">
                                <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>
                                iOS
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="mt-4 md:mt-0 flex items-center space-x-2">
                <div class="flex items-center bg-yellow-50 dark:bg-yellow-900/20 px-4 py-2 rounded-xl">
                    <svg class="w-6 h-6 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <span class="text-2xl font-bold text-yellow-600 dark:text-yellow-400">{{ number_format($app->average_rating, 1) }}</span>
                    <span class="text-xs text-yellow-600 dark:text-yellow-400 ml-1">/ 5.0</span>
                </div>
            </div>
        </div>

        @if($app->description)
            <p class="mt-6 text-sm text-gray-600 dark:text-gray-400 leading-relaxed border-t border-gray-100 dark:border-gray-700 pt-4">
                {{ $app->description }}
            </p>
        @endif
    </div>

    {{-- Statistics Row --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Downloads</p>
                <span class="p-2 bg-blue-50 dark:bg-blue-900/30 rounded-lg">
                    <svg class="w-4 h-4 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($app->downloads) }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Reviews</p>
                <span class="p-2 bg-purple-50 dark:bg-purple-900/30 rounded-lg">
                    <svg class="w-4 h-4 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-gray-900 dark:text-white">{{ number_format($totalReviews) }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Positive (4-5★)</p>
                <span class="p-2 bg-green-50 dark:bg-green-900/30 rounded-lg">
                    <svg class="w-4 h-4 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-green-600 dark:text-green-400">{{ number_format($goodReviews) }}</p>
        </div>

        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5">
            <div class="flex items-center justify-between">
                <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Negative (1-2★)</p>
                <span class="p-2 bg-red-50 dark:bg-red-900/30 rounded-lg">
                    <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </span>
            </div>
            <p class="mt-3 text-2xl font-bold text-red-600 dark:text-red-400">{{ number_format($badReviews) }}</p>
        </div>
    </div>

    {{-- Rating Distribution Bar --}}
    @if($totalReviews > 0)
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Rating Distribution</h2>
        <div class="w-full bg-gray-200 dark:bg-gray-700 rounded-full h-4 overflow-hidden flex">
            @php
                $goodPct = $totalReviews > 0 ? round(($goodReviews / $totalReviews) * 100) : 0;
                $neutralPct = $totalReviews > 0 ? round(($neutralReviews / $totalReviews) * 100) : 0;
                $badPct = 100 - $goodPct - $neutralPct;
            @endphp
            <div class="bg-green-500 h-4 transition-all" style="width: {{ $goodPct }}%" title="Positive: {{ $goodPct }}%"></div>
            <div class="bg-yellow-400 h-4 transition-all" style="width: {{ $neutralPct }}%" title="Neutral: {{ $neutralPct }}%"></div>
            <div class="bg-red-500 h-4 transition-all" style="width: {{ $badPct }}%" title="Negative: {{ $badPct }}%"></div>
        </div>
        <div class="flex justify-between mt-2 text-xs text-gray-500 dark:text-gray-400">
            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-1"></span> Positive {{ $goodPct }}%</span>
            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-yellow-400 mr-1"></span> Neutral {{ $neutralPct }}%</span>
            <span class="flex items-center"><span class="w-2 h-2 rounded-full bg-red-500 mr-1"></span> Negative {{ $badPct }}%</span>
        </div>
    </div>
    @endif

    {{-- Recent Reviews --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
        <div class="flex items-center justify-between mb-6">
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Reviews</h2>
            <span class="text-xs text-gray-500 dark:text-gray-400">Showing latest {{ $recentReviews->count() }} of {{ number_format($totalReviews) }}</span>
        </div>

        @if($recentReviews->isEmpty())
            <p class="text-sm text-gray-500 dark:text-gray-400 text-center py-8">No reviews have been synced yet for this application.</p>
        @else
            <div class="space-y-4">
                @foreach($recentReviews as $review)
                    <div class="border-b border-gray-100 dark:border-gray-700 pb-4 last:border-b-0 last:pb-0">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <div class="h-8 w-8 rounded-full bg-gray-100 dark:bg-gray-700 flex items-center justify-center text-xs font-bold text-gray-500 dark:text-gray-400">
                                    {{ substr($review->author_name ?? 'A', 0, 2) }}
                                </div>
                                <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $review->author_name ?? 'Anonymous' }}</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-xs text-gray-400 dark:text-gray-500">{{ $review->published_at ? \Carbon\Carbon::parse($review->published_at)->diffForHumans() : '' }}</span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 pl-10">{{ $review->content }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
