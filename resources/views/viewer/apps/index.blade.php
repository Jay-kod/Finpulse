@extends('layouts.app')

@section('title', 'App Directory')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white">App Directory</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Browse the fintech applications currently being tracked and analyzed by our system.</p>
        </div>
    </div>

    @if($apps->isEmpty())
        <x-ui.empty-state 
            icon="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" 
            title="No Apps Found" 
            description="The system is not currently tracking any fintech applications."
        />
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @foreach($apps as $app)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden hover:shadow-md transition-shadow">
                    <div class="p-6">
                        <div class="flex items-center space-x-4 mb-4">
                            @if($app->logo_url)
                                <img src="{{ $app->logo_url }}" alt="{{ $app->name }} Logo" class="h-14 w-14 rounded-xl object-cover shadow-sm bg-gray-100">
                            @else
                                <div class="h-14 w-14 rounded-xl bg-gradient-to-br from-primary-100 to-primary-200 flex items-center justify-center text-primary-700 font-bold text-xl shadow-sm">
                                    {{ substr($app->name, 0, 1) }}
                                </div>
                            @endif
                            
                            <div>
                                <h2 class="text-lg font-bold text-gray-900 dark:text-white">
                                    <a href="{{ route('viewer.apps.show', $app) }}" class="hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
                                        {{ $app->name }}
                                    </a>
                                </h2>
                                <div class="flex items-center text-xs text-gray-500 mt-1">
                                    @if($app->platform === 'android' || $app->platform === 'both')
                                        <span class="inline-flex items-center mr-2 text-green-600 dark:text-green-400">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M17.523 15.3414c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993.0004.5511-.4482.9997-.9993.9997zm-11.046 0c-.5511 0-.9993-.4486-.9993-.9997s.4482-.9993.9993-.9993c.5511 0 .9993.4482.9993.9993 0 .5511-.4482.9997-.9993.9997zm11.4045-6.02l1.9973-3.4592c.1148-.1988.0461-.4523-.1527-.5671-.1992-.1148-.4527-.0465-.5675.1527l-2.0305 3.5165c-1.4255-.6507-3.037-.1-4.7081-1.0118-1.745.002-3.4217.375-4.9084 1.0553l-2.0006-3.464c-.1148-.1992-.3683-.2675-.5675-.1527-.1988.1148-.2671.3683-.1527.5671l1.9682 3.4093c-3.1518 1.7335-5.3283 5.0115-5.4673 8.8475h17.1023c-.1391-3.836-2.3155-7.114-5.4673-8.8475z"/></svg>
                                            Android
                                        </span>
                                    @endif
                                    @if($app->platform === 'ios' || $app->platform === 'both')
                                        <span class="inline-flex items-center text-gray-700 dark:text-gray-300">
                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 24 24"><path d="M14.07 14.28c-.02.48.06.91.24 1.3.18.39.43.73.74 1.03.31.3.68.53 1.08.7.4.17.84.26 1.32.26v.02c-.51 1.4-1.25 2.76-2.22 4.09-.96 1.3-1.92 2.37-2.88 3.2-.38.35-.78.61-1.2.78-.42.17-.89.26-1.42.26h-.14c-.6-.05-1.19-.24-1.78-.58-.58-.33-1.19-.5-1.84-.5-.65 0-1.28.17-1.88.51-.6.34-1.2.53-1.8.57h-.14c-.5-.03-.96-.13-1.37-.32-.41-.18-.8-.43-1.16-.76-.88-.8-1.76-1.81-2.65-3.03C1 20.31.25 18.73-.34 17.06c-.6-1.67-.9-3.32-.9-4.95 0-1.87.35-3.51 1.05-4.94.7-1.43 1.64-2.58 2.82-3.46 1.18-.88 2.5-1.32 3.96-1.32.74 0 1.48.16 2.23.49.75.33 1.34.61 1.77.84.28.16.57.24.87.24.28 0 .58-.08.9-.24.32-.16.92-.44 1.8-.84.88-.4 1.67-.58 2.38-.56 1.73.08 3.12.67 4.18 1.77 1.06 1.1 1.65 2.53 1.78 4.31-.95-.49-1.96-.73-3.03-.73-1.13 0-2.1.32-2.91.95-.81.63-1.31 1.47-1.5 2.52h-.03zm1.6-9.75c-.88 0-1.76-.32-2.65-.95-.89-.63-1.52-1.45-1.89-2.46.04-.15.06-.32.06-.51 0-.85.3-1.63.9-2.34.6-.71 1.36-1.17 2.29-1.38-.03.18-.04.37-.04.56 0 .8.28 1.55.85 2.25.57.7 1.29 1.18 2.16 1.44-.06.2-.14.41-.26.63-.12.22-.29.43-.51.63-.22.2-.49.38-.81.54-.32.16-.7.24-1.12.24l.02.35z"/></svg>
                                            iOS
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-6 line-clamp-2">
                            {{ $app->description ?? 'No description provided.' }}
                        </p>
                        
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Downloads</p>
                                <p class="text-lg font-bold text-gray-900 dark:text-white">{{ number_format($app->downloads) }}</p>
                            </div>
                            <div class="bg-gray-50 dark:bg-gray-900/50 rounded-lg p-3 text-center">
                                <p class="text-xs text-gray-500 dark:text-gray-400 mb-1">Avg Rating</p>
                                <div class="flex items-center justify-center">
                                    <p class="text-lg font-bold text-yellow-500 mr-1">{{ number_format($app->average_rating, 1) }}</p>
                                    <svg class="w-4 h-4 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                </div>
                            </div>
                        </div>

                        <a href="{{ route('viewer.apps.show', $app) }}" class="block w-full py-2.5 px-4 text-center text-sm font-medium text-primary-700 bg-primary-50 dark:bg-primary-900/30 dark:text-primary-400 rounded-lg hover:bg-primary-100 dark:hover:bg-primary-900/50 transition-colors">
                            View Details & Reviews
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>
@endsection
