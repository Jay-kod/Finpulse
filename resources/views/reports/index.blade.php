@extends('layouts.app')

@section('title', 'Published Reports')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Published Reports</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Browse curated sentiment analysis reports and research summaries.</p>
    </div>

    {{-- Reports Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($reports as $report)
        <x-ui.card class="flex flex-col justify-between hover:shadow-lg transition-shadow duration-200">
            <div>
                {{-- App Badge --}}
                <div class="flex items-center justify-between mb-3">
                    <x-ui.badge variant="{{ match($report['app']) {
                        'OPay' => 'success',
                        'PalmPay' => 'warning',
                        'Kuda' => 'primary',
                        default => 'secondary'
                    } }}">{{ $report['app'] }}</x-ui.badge>
                    <span class="text-xs text-gray-500 dark:text-gray-400">{{ $report['date'] }}</span>
                </div>

                {{-- Title --}}
                <h3 class="text-base font-semibold text-gray-900 dark:text-white mb-2 leading-snug">
                    {{ $report['title'] }}
                </h3>

                {{-- Excerpt --}}
                <p class="text-sm text-gray-600 dark:text-gray-400 line-clamp-3 mb-4">
                    {{ $report['excerpt'] }}
                </p>
            </div>

            {{-- Footer --}}
            <div class="flex items-center justify-between pt-4 border-t border-gray-100 dark:border-gray-700">
                <div class="flex items-center text-xs text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                    {{ $report['author'] }}
                </div>
                <a href="{{ route('viewer.reports.show', $report['id']) }}" class="inline-flex items-center text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors">
                    View Report
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                </a>
            </div>
        </x-ui.card>
        @endforeach
    </div>
</div>
@endsection
