@extends('layouts.app')

@section('title', $app->name . ' — All Reviews')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    {{-- Breadcrumb --}}
    <nav class="flex items-center space-x-2 text-sm mb-6">
        <a href="{{ route($routePrefix . '.apps.index') }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">App Directory</a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <a href="{{ route($routePrefix . '.apps.show', $app) }}" class="text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">{{ $app->name }}</a>
        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
        <span class="text-gray-900 dark:text-white font-medium">Reviews</span>
    </nav>

    {{-- Page Header --}}
    <div class="flex flex-col md:flex-row md:items-end md:justify-between mb-8">
        <div class="flex items-center space-x-4">
            @if($app->logo_url)
                <img src="{{ $app->logo_url }}" alt="{{ $app->name }} Logo" class="h-14 w-14 rounded-xl object-cover shadow-sm bg-gray-100">
            @else
                <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-xl shadow-sm">
                    {{ substr($app->name, 0, 1) }}
                </div>
            @endif
            <div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $app->name }} Reviews</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                    {{ number_format($totalReviews) }} reviews &middot;
                    @if($avgRating)
                        <span class="text-yellow-500">★ {{ number_format($avgRating, 1) }}</span> average
                    @else
                        No ratings yet
                    @endif
                    @if($bugCount > 0)
                        &middot; <span class="text-red-500">{{ $bugCount }} bug {{ Str::plural('report', $bugCount) }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Filters & Search Bar --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-4 mb-6">
        <form method="GET" action="{{ route($routePrefix . '.apps.reviews', $app) }}" id="reviewFiltersForm">
            <div class="flex flex-col lg:flex-row lg:items-end gap-4">
                {{-- Search --}}
                <div class="flex-1">
                    <label for="search" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <input type="text" name="search" id="search" value="{{ request('search') }}" placeholder="Search reviews or authors..."
                               class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                    </div>
                </div>

                {{-- Rating Filter --}}
                <div class="w-full lg:w-36">
                    <label for="rating" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Rating</label>
                    <select name="rating" id="rating"
                            class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="">All Ratings</option>
                        @for($i = 5; $i >= 1; $i--)
                            <option value="{{ $i }}" {{ request('rating') == $i ? 'selected' : '' }}>{{ $i }} Star{{ $i > 1 ? 's' : '' }}</option>
                        @endfor
                    </select>
                </div>

                {{-- Sentiment Filter --}}
                <div class="w-full lg:w-36">
                    <label for="sentiment" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Sentiment</label>
                    <select name="sentiment" id="sentiment"
                            class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="">All Sentiments</option>
                        <option value="positive" {{ request('sentiment') === 'positive' ? 'selected' : '' }}>😊 Positive</option>
                        <option value="neutral" {{ request('sentiment') === 'neutral' ? 'selected' : '' }}>😐 Neutral</option>
                        <option value="negative" {{ request('sentiment') === 'negative' ? 'selected' : '' }}>😠 Negative</option>
                    </select>
                </div>

                {{-- Topic Filter --}}
                @if($topics->isNotEmpty())
                <div class="w-full lg:w-40">
                    <label for="topic" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Topic</label>
                    <select name="topic" id="topic"
                            class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="">All Topics</option>
                        @foreach($topics as $topic)
                            <option value="{{ $topic }}" {{ request('topic') === $topic ? 'selected' : '' }}>{{ ucfirst($topic) }}</option>
                        @endforeach
                    </select>
                </div>
                @endif

                {{-- Sort --}}
                <div class="w-full lg:w-44">
                    <label for="sort" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Sort By</label>
                    <select name="sort" id="sort"
                            class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="published_at" {{ request('sort', 'published_at') === 'published_at' ? 'selected' : '' }}>Date Published</option>
                        <option value="rating" {{ request('sort') === 'rating' ? 'selected' : '' }}>Rating</option>
                        <option value="sentiment_compound" {{ request('sort') === 'sentiment_compound' ? 'selected' : '' }}>Sentiment Score</option>
                        <option value="word_count" {{ request('sort') === 'word_count' ? 'selected' : '' }}>Review Length</option>
                    </select>
                </div>

                {{-- Direction --}}
                <div class="w-full lg:w-28">
                    <label for="dir" class="block text-xs font-medium text-gray-500 dark:text-gray-400 mb-1.5">Order</label>
                    <select name="dir" id="dir"
                            class="block w-full py-2 px-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-900 text-sm text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-transparent transition">
                        <option value="desc" {{ request('dir', 'desc') === 'desc' ? 'selected' : '' }}>Newest</option>
                        <option value="asc" {{ request('dir') === 'asc' ? 'selected' : '' }}>Oldest</option>
                    </select>
                </div>

                {{-- Bugs Toggle --}}
                <div class="flex items-end">
                    <label class="inline-flex items-center cursor-pointer py-2">
                        <input type="checkbox" name="bugs_only" value="1" {{ request('bugs_only') ? 'checked' : '' }}
                               class="sr-only peer">
                        <div class="relative w-9 h-5 bg-gray-200 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-primary-300 dark:peer-focus:ring-primary-800 rounded-full peer dark:bg-gray-700 peer-checked:after:translate-x-full rtl:peer-checked:after:-translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:start-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-4 after:w-4 after:transition-all dark:after:border-gray-600 peer-checked:bg-red-500"></div>
                        <span class="ms-2 text-xs font-medium text-gray-600 dark:text-gray-400 whitespace-nowrap">Bugs Only</span>
                    </label>
                </div>

                {{-- Actions --}}
                <div class="flex items-end space-x-2">
                    <button type="submit"
                            class="inline-flex items-center px-4 py-2 bg-primary-600 text-white text-sm font-medium rounded-lg hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-primary-500 transition-colors">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                        Filter
                    </button>
                    @if(request()->hasAny(['search', 'rating', 'sentiment', 'topic', 'sort', 'dir', 'bugs_only']))
                        <a href="{{ route($routePrefix . '.apps.reviews', $app) }}"
                           class="inline-flex items-center px-3 py-2 border border-gray-300 dark:border-gray-600 text-sm font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                            Clear
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    {{-- Active Filters Summary --}}
    @if(request()->hasAny(['search', 'rating', 'sentiment', 'topic', 'bugs_only']))
    <div class="flex flex-wrap items-center gap-2 mb-6">
        <span class="text-xs font-medium text-gray-500 dark:text-gray-400">Active filters:</span>
        @if(request('search'))
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-primary-100 text-primary-800 dark:bg-primary-900/40 dark:text-primary-400">
                Search: "{{ request('search') }}"
            </span>
        @endif
        @if(request('rating'))
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/40 dark:text-yellow-400">
                {{ request('rating') }} Star{{ request('rating') > 1 ? 's' : '' }}
            </span>
        @endif
        @if(request('sentiment'))
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-400">
                {{ ucfirst(request('sentiment')) }} sentiment
            </span>
        @endif
        @if(request('topic'))
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-teal-100 text-teal-800 dark:bg-teal-900/40 dark:text-teal-400">
                Topic: {{ ucfirst(request('topic')) }}
            </span>
        @endif
        @if(request('bugs_only'))
            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-400">
                🐛 Bug reports only
            </span>
        @endif
    </div>
    @endif

    {{-- Results Count --}}
    <div class="flex items-center justify-between mb-4">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Showing <span class="font-semibold text-gray-900 dark:text-white">{{ $reviews->firstItem() ?? 0 }}</span>–<span class="font-semibold text-gray-900 dark:text-white">{{ $reviews->lastItem() ?? 0 }}</span>
            of <span class="font-semibold text-gray-900 dark:text-white">{{ number_format($reviews->total()) }}</span> reviews
        </p>
    </div>

    {{-- Reviews List --}}
    @if($reviews->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-12 text-center">
            <svg class="mx-auto h-12 w-12 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            <h3 class="mt-3 text-sm font-semibold text-gray-900 dark:text-white">No reviews found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your filters or search terms.</p>
        </div>
    @else
        <div class="space-y-4">
            @foreach($reviews as $review)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    {{-- Review Header --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-3">
                        <div class="flex items-center space-x-3">
                            <div class="h-9 w-9 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 flex items-center justify-center text-xs font-bold text-gray-600 dark:text-gray-300 shrink-0">
                                {{ strtoupper(substr($review->author_name ?? 'A', 0, 2)) }}
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="text-sm font-semibold text-gray-900 dark:text-white">{{ $review->author_name ?? 'Anonymous' }}</p>
                                    <span class="text-xs text-gray-500 dark:text-gray-400">on {{ $app->name }}</span>
                                    @if($review->dataset || $review->source_id)
                                        @php
                                            $sourceId = $review->source_id ?? '';
                                            if (str_starts_with($sourceId, 'apple_')) {
                                                $source = 'App Store';
                                            } elseif (str_starts_with($sourceId, 'gplay_')) {
                                                $source = 'Google Play';
                                            } else {
                                                $source = $review->dataset->source ?? 'Unknown';
                                                if ($source === 'App Store Sync') {
                                                    $source = 'App Store'; // Fallback for aesthetic
                                                }
                                            }
                                            
                                            $sourceIcon = match(strtolower($source)) {
                                                'google play' => '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.609 22.186a.996.996 0 01-.609-.92V2.734a1 1 0 01.609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.199l2.302 2.302a1 1 0 010 1.38l-2.302 2.302L15.396 13l2.302-2.492zM5.864 2.658L16.801 9.99l-2.302 2.302L5.864 2.658z"/></svg>',
                                                'app store', 'apple', 'ios' => '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>',
                                                'twitter', 'x' => '<svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
                                                default => '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>',
                                            };
                                            $sourceColor = match(strtolower($source)) {
                                                'google play' => 'text-green-600 bg-green-50 dark:text-green-400 dark:bg-green-900/30',
                                                'app store', 'apple', 'ios' => 'text-gray-700 bg-gray-100 dark:text-gray-300 dark:bg-gray-700',
                                                'twitter', 'x' => 'text-sky-600 bg-sky-50 dark:text-sky-400 dark:bg-sky-900/30',
                                                default => 'text-indigo-600 bg-indigo-50 dark:text-indigo-400 dark:bg-indigo-900/30',
                                            };
                                        @endphp
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[10px] font-medium {{ $sourceColor }}">
                                            {!! $sourceIcon !!} {{ $source }}
                                        </span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-400 dark:text-gray-500">
                                    {{ $review->published_at ? $review->published_at->format('M d, Y') : 'Date unknown' }}
                                    @if($review->published_at)
                                        <span class="text-gray-300 dark:text-gray-600 mx-1">&middot;</span>
                                        {{ $review->published_at->diffForHumans() }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center space-x-3 mt-2 sm:mt-0">
                            {{-- Star Rating --}}
                            <div class="flex items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    <svg class="w-4 h-4 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                    </svg>
                                @endfor
                            </div>

                            {{-- Sentiment Badge --}}
                            @if($review->sentiment_compound !== null)
                                @php
                                    $sentimentLabel = 'Neutral';
                                    $sentimentClasses = 'bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300';
                                    if ($review->sentiment_compound > 0.05) {
                                        $sentimentLabel = 'Positive';
                                        $sentimentClasses = 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-400';
                                    } elseif ($review->sentiment_compound < -0.05) {
                                        $sentimentLabel = 'Negative';
                                        $sentimentClasses = 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-400';
                                    }
                                @endphp
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium {{ $sentimentClasses }}">
                                    {{ $sentimentLabel }}
                                    <span class="ml-1 opacity-60">{{ number_format($review->sentiment_compound, 2) }}</span>
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Review Content --}}
                    <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed pl-12">
                        {{ $review->content }}
                    </p>

                    {{-- Review Meta Tags --}}
                    <div class="flex flex-wrap items-center gap-2 mt-3 pl-12">
                        @if($review->topic)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-indigo-50 text-indigo-700 dark:bg-indigo-900/30 dark:text-indigo-400">
                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                {{ ucfirst($review->topic) }}
                            </span>
                        @endif
                        @if($review->intent)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-purple-50 text-purple-700 dark:bg-purple-900/30 dark:text-purple-400">
                                {{ ucfirst($review->intent) }}
                            </span>
                        @endif
                        @if($review->is_bug)
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-red-50 text-red-700 dark:bg-red-900/30 dark:text-red-400">
                                🐛 Bug Report
                            </span>
                        @endif
                        @if($review->detected_language && $review->detected_language !== 'en')
                            <span class="inline-flex items-center px-2 py-0.5 rounded-md text-xs font-medium bg-gray-50 text-gray-600 dark:bg-gray-700 dark:text-gray-400">
                                🌐 {{ strtoupper($review->detected_language) }}
                            </span>
                        @endif
                        @if($review->word_count)
                            <span class="text-xs text-gray-400 dark:text-gray-500">
                                {{ $review->word_count }} words
                            </span>
                        @endif
                    </div>

                    {{-- Sentiment Breakdown Bar (for analyzed reviews) --}}
                    @if($review->sentiment_positive !== null)
                        <div class="mt-3 pl-12">
                            <div class="flex items-center space-x-2">
                                <span class="text-xs text-gray-400 dark:text-gray-500 w-16 shrink-0">Sentiment:</span>
                                <div class="flex-1 bg-gray-200 dark:bg-gray-700 rounded-full h-1.5 overflow-hidden flex">
                                    <div class="bg-green-500 h-1.5" style="width: {{ round($review->sentiment_positive * 100) }}%"></div>
                                    <div class="bg-gray-400 h-1.5" style="width: {{ round($review->sentiment_neutral * 100) }}%"></div>
                                    <div class="bg-red-500 h-1.5" style="width: {{ round($review->sentiment_negative * 100) }}%"></div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        <div class="mt-8">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
