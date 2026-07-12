@extends('layouts.app')

@section('title', $app->name . ' — App Details')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('viewer.apps.index') }}" class="group inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-full px-4 py-2 hover:bg-gray-50 dark:hover:bg-gray-700 hover:text-primary-600 dark:hover:text-primary-400 hover:border-primary-200 dark:hover:border-primary-700 transition-all shadow-sm">
            <svg class="w-4 h-4 mr-2 -ml-1 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to App Directory
        </a>
    </div>

    {{-- App Header (Premium Card) --}}
    <div class="relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 mb-8">
        {{-- Decorative background gradient --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-primary-100 to-accent-100 dark:from-primary-900/20 dark:to-accent-900/20 rounded-full blur-3xl opacity-50 -mr-20 -mt-20 pointer-events-none"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-start md:justify-between gap-6">
            <div class="flex flex-col sm:flex-row items-center sm:items-start space-y-4 sm:space-y-0 sm:space-x-6 text-center sm:text-left">
                @if($app->logo_url)
                    <img src="{{ $app->logo_url }}" alt="{{ $app->name }} Logo" class="h-24 w-24 sm:h-20 sm:w-20 rounded-3xl object-cover shadow-md bg-white border border-gray-100 dark:border-gray-700 shrink-0">
                @else
                    <div class="h-24 w-24 sm:h-20 sm:w-20 rounded-3xl bg-gradient-to-br from-primary-400 to-primary-600 flex items-center justify-center text-white font-bold text-4xl sm:text-3xl shadow-md shrink-0">
                        {{ substr($app->name, 0, 1) }}
                    </div>
                @endif

                <div class="flex-1 mt-1">
                    <h1 class="text-3xl sm:text-2xl font-extrabold text-gray-900 dark:text-white tracking-tight">{{ $app->name }}</h1>
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mt-1">{{ $app->package_name }}</p>
                    <div class="flex flex-wrap justify-center sm:justify-start items-center gap-2 mt-3">
                        @if($app->platform === 'android' || $app->platform === 'both')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-100 dark:border-green-800/30">
                                <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993.0004.5511-.4482.9997-.9993.9997zm-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997zm11.4045-6.02l1.9973-3.4592c.1148-.1988.0461-.4523-.1527-.5671-.1992-.1148-.4527-.0465-.5675.1527l-2.0305 3.5165c-1.4255-.6507-3.037-.1-4.7081-1.0118-1.745.002-3.4217.375-4.9084 1.0553l-2.0006-3.464c-.1148-.1992-.3683-.2675-.5675-.1527-.1988.1148-.2671.3683-.1527.5671l1.9682 3.4093c-3.1518 1.7335-5.3283 5.0115-5.4673 8.8475h17.1023c-.1391-3.836-2.3155-7.114-5.4673-8.8475z"/></svg>
                                Android
                            </span>
                        @endif
                        @if($app->platform === 'ios' || $app->platform === 'both')
                            <span class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold tracking-wider uppercase bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                <svg class="w-3.5 h-3.5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M14.07 14.28c-.02.48.06.91.24 1.3.18.39.43.73.74 1.03.31.3.68.53 1.08.7.4.17.84.26 1.32.26v.02c-.51 1.4-1.25 2.76-2.22 4.09-.96 1.3-1.92 2.37-2.88 3.2-.38.35-.78.61-1.2.78-.42.17-.89.26-1.42.26h-.14c-.6-.05-1.19-.24-1.78-.58-.58-.33-1.19-.5-1.84-.5-.65 0-1.28.17-1.88.51-.6.34-1.2.53-1.8.57h-.14c-.5-.03-.96-.13-1.37-.32-.41-.18-.8-.43-1.16-.76-.88-.8-1.76-1.81-2.65-3.03C1 20.31.25 18.73-.34 17.06c-.6-1.67-.9-3.32-.9-4.95 0-1.87.35-3.51 1.05-4.94.7-1.43 1.64-2.58 2.82-3.46 1.18-.88 2.5-1.32 3.96-1.32.74 0 1.48.16 2.23.49.75.33 1.34.61 1.77.84.28.16.57.24.87.24.28 0 .58-.08.9-.24.32-.16.92-.44 1.8-.84.88-.4 1.67-.58 2.38-.56 1.73.08 3.12.67 4.18 1.77 1.06 1.1 1.65 2.53 1.78 4.31-.95-.49-1.96-.73-3.03-.73-1.13 0-2.1.32-2.91.95-.81.63-1.31 1.47-1.5 2.52h-.03zm1.6-9.75c-.88 0-1.76-.32-2.65-.95-.89-.63-1.52-1.45-1.89-2.46.04-.15.06-.32.06-.51 0-.85.3-1.63.9-2.34.6-.71 1.36-1.17 2.29-1.38-.03.18-.04.37-.04.56 0 .8.28 1.55.85 2.25.57.7 1.29 1.18 2.16 1.44-.06.2-.14.41-.26.63-.12.22-.29.43-.51.63-.22.2-.49.38-.81.54-.32.16-.7.24-1.12.24l.02.35z"/></svg>
                                iOS
                            </span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center sm:justify-end w-full md:w-auto mt-2 md:mt-0">
                <div class="flex items-center bg-amber-50/80 dark:bg-amber-900/20 border border-amber-100 dark:border-amber-900/30 px-5 py-3 rounded-2xl shadow-sm backdrop-blur-sm">
                    <svg class="w-7 h-7 text-amber-500 mr-2.5 drop-shadow-sm" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                    <div class="flex items-baseline">
                        <span class="text-3xl font-extrabold text-amber-600 dark:text-amber-500 tracking-tight">{{ number_format($app->average_rating, 1) }}</span>
                        <span class="text-sm font-semibold text-amber-600/70 dark:text-amber-500/70 ml-1">/ 5.0</span>
                    </div>
                </div>
            </div>
        </div>

        @if($app->description)
            <div class="mt-8 pt-6 border-t border-gray-100 dark:border-gray-700/60 relative z-10 text-sm text-gray-600 dark:text-gray-300">
                @php
                    $lines = explode("\n", $app->description);
                    $formatted = '<div class="space-y-3 text-justify">';
                    $inList = false;
                    $listType = '';
                    
                    foreach ($lines as $line) {
                        $line = trim($line);
                        if (empty($line)) continue;
                        
                        $isHeading = mb_strpos($line, '◉') === 0;
                        $isUl = str_starts_with($line, '-');
                        $isOl = preg_match('/^\d+\./', $line);
                        
                        if ($inList && !$isUl && !$isOl) {
                            $formatted .= $listType === 'ul' ? '</ul>' : '</ol>';
                            $inList = false;
                        }
                        
                        if ($isHeading) {
                            $text = trim(mb_substr($line, 1)); // Remove ◉
                            $formatted .= '<h3 class="text-[15px] font-bold text-gray-900 dark:text-white mt-8 mb-3 tracking-wide">' . e($text) . '</h3>';
                        } elseif ($isUl) {
                            if (!$inList) {
                                $formatted .= '<ul class="space-y-2 my-4">';
                                $inList = true;
                                $listType = 'ul';
                            }
                            $text = trim(substr($line, 1));
                            $formatted .= '<li class="ml-5 list-disc pl-1 marker:text-primary-500">' . e($text) . '</li>';
                        } elseif ($isOl) {
                            if (!$inList) {
                                $formatted .= '<ol class="space-y-2 my-4">';
                                $inList = true;
                                $listType = 'ol';
                            }
                            $text = trim(preg_replace('/^\d+\./', '', $line));
                            $formatted .= '<li class="ml-5 list-decimal pl-1 marker:font-bold marker:text-gray-500">' . e($text) . '</li>';
                        } else {
                            $formatted .= '<p class="leading-relaxed">' . e($line) . '</p>';
                        }
                    }
                    
                    if ($inList) {
                        $formatted .= $listType === 'ul' ? '</ul>' : '</ol>';
                    }
                    $formatted .= '</div>';
                @endphp
                {!! $formatted !!}
            </div>
        @endif
    </div>

    {{-- Statistics Row --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-5 mb-8">
        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6 transition-transform hover:-translate-y-1 hover:shadow-md duration-300">
            <div class="absolute -right-6 -top-6 opacity-[0.07] dark:opacity-[0.03] transition-transform group-hover:scale-110 duration-300 pointer-events-none">
                <svg class="w-36 h-36 text-primary-600 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($app->downloads) }}</p>
                <p class="mt-1 text-[11px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Downloads</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6 transition-transform hover:-translate-y-1 hover:shadow-md duration-300">
            <div class="absolute -right-6 -top-6 opacity-[0.07] dark:opacity-[0.03] transition-transform group-hover:scale-110 duration-300 pointer-events-none">
                <svg class="w-36 h-36 text-accent-600 dark:text-accent-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-3xl sm:text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($totalReviews) }}</p>
                <p class="mt-1 text-[11px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Reviews</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6 transition-transform hover:-translate-y-1 hover:shadow-md duration-300">
            <div class="absolute -right-6 -top-6 opacity-[0.07] dark:opacity-[0.03] transition-transform group-hover:scale-110 duration-300 pointer-events-none">
                <svg class="w-36 h-36 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-3xl sm:text-4xl font-black text-green-600 dark:text-green-400 tracking-tight">{{ number_format($goodReviews) }}</p>
                <p class="mt-1 text-[11px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Positive (4-5★)</p>
            </div>
        </div>

        <div class="group relative overflow-hidden bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-5 sm:p-6 transition-transform hover:-translate-y-1 hover:shadow-md duration-300">
            <div class="absolute -right-6 -top-6 opacity-[0.07] dark:opacity-[0.03] transition-transform group-hover:scale-110 duration-300 pointer-events-none">
                <svg class="w-36 h-36 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-3xl sm:text-4xl font-black text-red-600 dark:text-red-400 tracking-tight">{{ number_format($badReviews) }}</p>
                <p class="mt-1 text-[11px] sm:text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Negative (1-2★)</p>
            </div>
        </div>
    </div>

    {{-- Rating Distribution Bar --}}
    @if($totalReviews > 0)
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8 mb-8">
        <h2 class="text-lg font-bold text-gray-900 dark:text-white mb-5">Rating Distribution</h2>
        <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-5 overflow-hidden flex shadow-inner">
            @php
                $goodPct = $totalReviews > 0 ? round(($goodReviews / $totalReviews) * 100) : 0;
                $neutralPct = $totalReviews > 0 ? round(($neutralReviews / $totalReviews) * 100) : 0;
                $badPct = 100 - $goodPct - $neutralPct;
            @endphp
            <div class="bg-green-500 h-full transition-all" style="width: {{ $goodPct }}%" title="Positive: {{ $goodPct }}%"></div>
            <div class="bg-yellow-400 h-full transition-all" style="width: {{ $neutralPct }}%" title="Neutral: {{ $neutralPct }}%"></div>
            <div class="bg-red-500 h-full transition-all" style="width: {{ $badPct }}%" title="Negative: {{ $badPct }}%"></div>
        </div>
        <div class="flex flex-wrap justify-between gap-3 mt-4 text-xs font-medium text-gray-500 dark:text-gray-400">
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-green-500 mr-2 shadow-sm"></span> Positive ({{ $goodPct }}%)</span>
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-yellow-400 mr-2 shadow-sm"></span> Neutral ({{ $neutralPct }}%)</span>
            <span class="flex items-center"><span class="w-2.5 h-2.5 rounded-full bg-red-500 mr-2 shadow-sm"></span> Negative ({{ $badPct }}%)</span>
        </div>
    </div>
    @endif

    {{-- Recent Reviews --}}
    <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 gap-4">
            <div>
                <h2 class="text-xl font-bold text-gray-900 dark:text-white">Recent Reviews</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Showing latest {{ $recentReviews->count() }} of {{ number_format($totalReviews) }}</p>
            </div>
            
            @if($totalReviews > 0)
                <a href="{{ route('viewer.apps.reviews', $app) }}" class="inline-flex items-center justify-center px-4 py-2.5 text-sm font-semibold text-white bg-primary-600 rounded-xl hover:bg-primary-700 transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                    View All Reviews
                    <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                </a>
            @endif
        </div>

        @if($recentReviews->isEmpty())
            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-2xl p-12 text-center border border-dashed border-gray-200 dark:border-gray-700">
                <svg class="w-12 h-12 text-gray-300 dark:text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-1">No Reviews Found</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400">No reviews have been synced yet for this application.</p>
            </div>
        @else
            <div class="space-y-5">
                @foreach($recentReviews as $review)
                    <div class="bg-gray-50 dark:bg-gray-900/40 rounded-2xl p-5 border border-gray-100 dark:border-gray-700/50 hover:border-gray-200 dark:hover:border-gray-600 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3 mb-3">
                            <div class="flex items-center space-x-3">
                                <div class="h-10 w-10 rounded-full bg-white dark:bg-gray-800 shadow-sm border border-gray-100 dark:border-gray-700 flex items-center justify-center text-sm font-bold text-gray-600 dark:text-gray-300 shrink-0">
                                    {{ strtoupper(substr($review->author_name ?? 'A', 0, 2)) }}
                                </div>
                                <div>
                                    <div class="flex flex-wrap items-center gap-1.5">
                                        <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $review->author_name ?? 'Anonymous' }}</span>
                                        <span class="text-xs text-gray-400 dark:text-gray-500">on {{ $app->name }}</span>
                                    </div>
                                    @if($review->dataset || $review->source_id)
                                        <div class="mt-1">
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
                                                    'google play' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.609 22.186a.996.996 0 01-.609-.92V2.734a1 1 0 01.609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.199l2.302 2.302a1 1 0 010 1.38l-2.302 2.302L15.396 13l2.302-2.492zM5.864 2.658L16.801 9.99l-2.302 2.302L5.864 2.658z"/></svg>',
                                                    'app store', 'apple', 'ios' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"/></svg>',
                                                    'twitter', 'x' => '<svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>',
                                                    default => '<svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg>',
                                                };
                                                $sourceColor = match(strtolower($source)) {
                                                    'google play' => 'text-green-700 bg-green-100 dark:text-green-400 dark:bg-green-900/40',
                                                    'app store', 'apple', 'ios' => 'text-gray-700 bg-gray-200 dark:text-gray-300 dark:bg-gray-700',
                                                    'twitter', 'x' => 'text-sky-700 bg-sky-100 dark:text-sky-400 dark:bg-sky-900/40',
                                                    default => 'text-primary-700 bg-primary-100 dark:text-primary-400 dark:bg-primary-900/40',
                                                };
                                            @endphp
                                            <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded text-[10px] font-bold tracking-wide uppercase {{ $sourceColor }}">
                                                {!! $sourceIcon !!} {{ $source }}
                                            </span>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex items-center sm:flex-col sm:items-end sm:justify-center ml-13 sm:ml-0 gap-2 sm:gap-1">
                                <div class="flex items-center">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-3.5 h-3.5 {{ $i <= $review->rating ? 'text-yellow-400' : 'text-gray-200 dark:text-gray-700' }}" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                    @endfor
                                </div>
                                <span class="text-[11px] font-medium text-gray-400 dark:text-gray-500">
                                    {{ $review->published_at ? \Carbon\Carbon::parse($review->published_at)->diffForHumans() : '' }}
                                </span>
                            </div>
                        </div>
                        <p class="text-sm text-gray-700 dark:text-gray-300 leading-relaxed sm:ml-13 mt-2 sm:mt-0">{{ $review->content }}</p>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
