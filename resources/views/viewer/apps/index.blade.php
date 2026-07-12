@extends('layouts.app')

@section('title', 'App Directory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 animate-fade-in">
    {{-- Modern Gradient Hero Banner --}}
    <div class="mb-10 relative overflow-hidden rounded-3xl bg-gradient-to-br from-primary-900 via-primary-800 to-primary-600 shadow-glow p-8 sm:p-10 text-white">
        <!-- Background decorative elements -->
        <div class="absolute top-0 right-0 -mr-20 -mt-20 w-64 h-64 rounded-full bg-white opacity-10 blur-3xl"></div>
        <div class="absolute bottom-0 right-1/4 w-32 h-32 rounded-full bg-white opacity-10 blur-2xl"></div>
        
        <div class="relative z-10 flex flex-col md:flex-row md:items-center md:justify-between gap-6">
            <div>
                <h1 class="text-4xl sm:text-5xl font-extrabold tracking-tight mb-3">App Directory</h1>
                <p class="text-primary-100 max-w-2xl text-lg sm:text-xl">
                    Browse the fintech applications currently being tracked and analyzed by our system.
                </p>
            </div>
        </div>
    </div>

    @if($apps->isEmpty())
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center animate-slide-up">
            <div class="w-20 h-20 mx-auto bg-gray-50 dark:bg-gray-900 rounded-full flex items-center justify-center mb-6 shadow-inner">
                <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
            <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Apps Found</h3>
            <p class="text-gray-500 dark:text-gray-400 max-w-sm mx-auto">The system is not currently tracking any fintech applications.</p>
        </div>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($apps as $app)
                <div class="group relative bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700/60 overflow-hidden hover:-translate-y-1 hover:shadow-xl hover:border-primary-200 dark:hover:border-primary-900/80 transition-all duration-300 flex flex-col animate-slide-up" style="animation-delay: {{ $loop->index * 50 }}ms">
                    
                    {{-- Subtle top gradient border indicator --}}
                    <div class="h-1.5 w-full bg-gradient-to-r from-primary-400 to-accent-500 opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    
                    <div class="p-6 flex-1 flex flex-col">
                        <div class="flex items-start space-x-4 mb-5">
                            @if($app->logo_url)
                                <img src="{{ $app->logo_url }}" alt="{{ $app->name }} Logo" class="h-14 w-14 rounded-2xl object-cover shadow-sm bg-white border border-gray-50 dark:border-gray-700 shrink-0 group-hover:scale-105 transition-transform duration-300">
                            @else
                                <div class="h-14 w-14 rounded-2xl bg-gradient-to-br from-primary-100 to-primary-200 dark:from-primary-900/50 dark:to-primary-800/50 flex items-center justify-center text-primary-700 dark:text-primary-300 font-bold text-2xl shadow-sm border border-primary-50 dark:border-primary-900 shrink-0 group-hover:scale-105 transition-transform duration-300">
                                    {{ substr($app->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div class="flex-1 min-w-0 pt-0.5">
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white truncate">
                                    <a href="{{ route('viewer.apps.show', $app) }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors focus:outline-none">
                                        {{-- Link spans entire card --}}
                                        <span class="absolute inset-0" aria-hidden="true"></span>
                                        {{ $app->name }}
                                    </a>
                                </h2>
                                <div class="flex flex-wrap items-center gap-1.5 mt-1.5">
                                    @if($app->platform === 'android' || $app->platform === 'both')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase bg-green-50 text-green-700 dark:bg-green-900/30 dark:text-green-400 border border-green-100 dark:border-green-800/30">
                                            <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993.0004.5511-.4482.9997-.9993.9997zm-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997zm11.4045-6.02l1.9973-3.4592c.1148-.1988.0461-.4523-.1527-.5671-.1992-.1148-.4527-.0465-.5675.1527l-2.0305 3.5165c-1.4255-.6507-3.037-.1-4.7081-1.0118-1.745.002-3.4217.375-4.9084 1.0553l-2.0006-3.464c-.1148-.1992-.3683-.2675-.5675-.1527-.1988.1148-.2671.3683-.1527.5671l1.9682 3.4093c-3.1518 1.7335-5.3283 5.0115-5.4673 8.8475h17.1023c-.1391-3.836-2.3155-7.114-5.4673-8.8475z"/></svg>
                                            Android
                                        </span>
                                    @endif
                                    @if($app->platform === 'ios' || $app->platform === 'both')
                                        <span class="inline-flex items-center px-1.5 py-0.5 rounded text-[9px] font-bold tracking-wider uppercase bg-gray-100 text-gray-700 dark:bg-gray-700 dark:text-gray-300 border border-gray-200 dark:border-gray-600">
                                            <svg class="w-2.5 h-2.5 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M14.07 14.28c-.02.48.06.91.24 1.3.18.39.43.73.74 1.03.31.3.68.53 1.08.7.4.17.84.26 1.32.26v.02c-.51 1.4-1.25 2.76-2.22 4.09-.96 1.3-1.92 2.37-2.88 3.2-.38.35-.78.61-1.2.78-.42.17-.89.26-1.42.26h-.14c-.6-.05-1.19-.24-1.78-.58-.58-.33-1.19-.5-1.84-.5-.65 0-1.28.17-1.88.51-.6.34-1.2.53-1.8.57h-.14c-.5-.03-.96-.13-1.37-.32-.41-.18-.8-.43-1.16-.76-.88-.8-1.76-1.81-2.65-3.03C1 20.31.25 18.73-.34 17.06c-.6-1.67-.9-3.32-.9-4.95 0-1.87.35-3.51 1.05-4.94.7-1.43 1.64-2.58 2.82-3.46 1.18-.88 2.5-1.32 3.96-1.32.74 0 1.48.16 2.23.49.75.33 1.34.61 1.77.84.28.16.57.24.87.24.28 0 .58-.08.9-.24.32-.16.92-.44 1.8-.84.88-.4 1.67-.58 2.38-.56 1.73.08 3.12.67 4.18 1.77 1.06 1.1 1.65 2.53 1.78 4.31-.95-.49-1.96-.73-3.03-.73-1.13 0-2.1.32-2.91.95-.81.63-1.31 1.47-1.5 2.52h-.03zm1.6-9.75c-.88 0-1.76-.32-2.65-.95-.89-.63-1.52-1.45-1.89-2.46.04-.15.06-.32.06-.51 0-.85.3-1.63.9-2.34.6-.71 1.36-1.17 2.29-1.38-.03.18-.04.37-.04.56 0 .8.28 1.55.85 2.25.57.7 1.29 1.18 2.16 1.44-.06.2-.14.41-.26.63-.12.22-.29.43-.51.63-.22.2-.49.38-.81.54-.32.16-.7.24-1.12.24l.02.35z"/></svg>
                                            iOS
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5 line-clamp-2 leading-relaxed flex-1 relative z-20 pointer-events-none">
                            {{ $app->description ?? 'No description provided.' }}
                        </p>
                        
                        <div class="grid grid-cols-2 gap-3 mb-5 relative z-20 pointer-events-none">
                            <div class="bg-primary-50/50 dark:bg-gray-900/50 border border-primary-100/50 dark:border-gray-700/50 rounded-2xl p-2.5 flex flex-col items-center justify-center">
                                <div class="flex items-center justify-center space-x-1 mb-1">
                                    <svg class="w-3.5 h-3.5 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Downloads</p>
                                </div>
                                <p class="text-base font-extrabold text-gray-900 dark:text-white">{{ number_format($app->downloads) }}</p>
                            </div>
                            <div class="bg-amber-50/50 dark:bg-gray-900/50 border border-amber-100/50 dark:border-gray-700/50 rounded-2xl p-2.5 flex flex-col items-center justify-center">
                                <div class="flex items-center justify-center space-x-1 mb-1">
                                    <svg class="w-3.5 h-3.5 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <p class="text-[10px] font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Avg Rating</p>
                                </div>
                                <p class="text-base font-extrabold text-gray-900 dark:text-white">{{ number_format($app->average_rating, 1) }}</p>
                            </div>
                        </div>

                        <div class="mt-auto relative z-20">
                            {{-- We keep this visually as a button but it's redundant since the whole card is wrapped in the absolute link, but this adds a nice hover indicator --}}
                            <div class="w-full flex items-center justify-center py-2.5 px-4 text-sm font-semibold rounded-xl bg-gray-50 text-gray-700 dark:bg-gray-700/50 dark:text-gray-200 group-hover:bg-gradient-to-r group-hover:from-primary-600 group-hover:to-primary-500 group-hover:text-white transition-all duration-300 shadow-sm group-hover:shadow-glow">
                                View Details
                                <svg class="w-4 h-4 ml-1.5 opacity-0 -translate-x-2 group-hover:opacity-100 group-hover:translate-x-0 transition-all duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
