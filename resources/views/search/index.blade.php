@extends('layouts.app')

@section('title', 'Search Results')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Search Results</h1>
        @if(!empty(trim($query)))
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Found {{ $totalResults }} result(s) for "<span class="font-semibold text-gray-900 dark:text-white">{{ $query }}</span>"
            </p>
        @else
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Please enter a search term above.
            </p>
        @endif
    </div>

    @if(empty(trim($query)))
        <x-ui.card class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">Start searching</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Search for datasets, reviews, apps, and more.</p>
        </x-ui.card>
    @elseif($totalResults === 0)
        <x-ui.card class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h3 class="mt-2 text-sm font-semibold text-gray-900 dark:text-white">No results found</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">We couldn't find anything matching "{{ $query }}".</p>
        </x-ui.card>
    @else
        <div class="space-y-8">
            
            {{-- Users Section --}}
            @if($results['users']->count() > 0)
                <section>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Users ({{ $results['users']->count() }})
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($results['users'] as $user)
                            <a href="{{ route('admin.users.edit', $user) }}" class="block group">
                                <x-ui.card class="h-full transition-colors group-hover:bg-gray-50 dark:group-hover:bg-gray-800">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10 rounded-full bg-primary-100 dark:bg-primary-900 flex items-center justify-center text-primary-600 dark:text-primary-300 font-bold">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </x-ui.card>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Fintech Apps Section --}}
            @if($results['apps']->count() > 0)
                <section>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Fintech Apps ({{ $results['apps']->count() }})
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($results['apps'] as $app)
                            <a href="{{ route('admin.fintech-apps.edit', $app) }}" class="block group">
                                <x-ui.card class="h-full transition-colors group-hover:bg-gray-50 dark:group-hover:bg-gray-800">
                                    <div class="text-base font-semibold text-gray-900 dark:text-white mb-1">{{ $app->name }}</div>
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $app->description }}</p>
                                </x-ui.card>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Datasets Section --}}
            @if($results['datasets']->count() > 0)
                <section>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Datasets ({{ $results['datasets']->count() }})
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($results['datasets'] as $dataset)
                            <a href="{{ route('analyst.datasets.edit', $dataset) }}" class="block group">
                                <x-ui.card class="h-full transition-colors group-hover:bg-gray-50 dark:group-hover:bg-gray-800">
                                    <div class="flex items-center justify-between mb-2">
                                        <div class="text-base font-semibold text-gray-900 dark:text-white">{{ $dataset->name }}</div>
                                        <x-ui.badge variant="secondary">{{ $dataset->fintechApp->name ?? 'Unknown App' }}</x-ui.badge>
                                    </div>
                                    <p class="text-sm text-gray-500 line-clamp-2">{{ $dataset->description }}</p>
                                </x-ui.card>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Reviews Section --}}
            @if($results['reviews']->count() > 0)
                <section>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Reviews ({{ $results['reviews']->count() }})
                    </h2>
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        @foreach($results['reviews'] as $review)
                            <a href="{{ route('analyst.reviews.edit', $review) }}" class="block group">
                                <x-ui.card class="h-full transition-colors group-hover:bg-gray-50 dark:group-hover:bg-gray-800">
                                    <div class="flex items-start justify-between mb-2">
                                        <div class="flex gap-2">
                                            <x-ui.badge variant="{{ $review->rating >= 4 ? 'success' : ($review->rating <= 2 ? 'danger' : 'warning') }}">
                                                ★ {{ $review->rating }}
                                            </x-ui.badge>
                                            @if($review->topic)
                                                <x-ui.badge variant="secondary">{{ $review->topic }}</x-ui.badge>
                                            @endif
                                            @if($review->is_bug)
                                                <x-ui.badge variant="danger">Bug</x-ui.badge>
                                            @endif
                                        </div>
                                        <span class="text-xs text-gray-400">{{ $review->dataset->fintechApp->name ?? '' }}</span>
                                    </div>
                                    <p class="text-sm text-gray-700 dark:text-gray-300 line-clamp-3">
                                        {{ $review->content }}
                                    </p>
                                </x-ui.card>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            {{-- Reports Section --}}
            @if($results['reports']->count() > 0)
                <section>
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4 border-b border-gray-200 dark:border-gray-700 pb-2">
                        Reports ({{ $results['reports']->count() }})
                    </h2>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($results['reports'] as $report)
                            <a href="{{ route('viewer.reports.show', $report) }}" class="block group">
                                <x-ui.card class="h-full transition-colors group-hover:bg-gray-50 dark:group-hover:bg-gray-800">
                                    <div class="text-base font-semibold text-gray-900 dark:text-white mb-1">{{ $report->title }}</div>
                                    <p class="text-sm text-gray-500 line-clamp-2 mb-2">{{ $report->excerpt }}</p>
                                    <div class="text-xs text-gray-400">By {{ $report->author->name ?? 'Unknown' }}</div>
                                </x-ui.card>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

        </div>
    @endif
</div>
@endsection
