@extends('layouts.app')

@section('title', $report['title'])

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('viewer.reports.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Reports
        </a>
    </div>

    {{-- Report Header --}}
    <x-ui.card class="mb-6">
        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-4">
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <x-ui.badge variant="{{ match($report['app']) {
                        'OPay' => 'success',
                        'PalmPay' => 'warning',
                        'Kuda' => 'primary',
                        default => 'secondary'
                    } }}">{{ $report['app'] }}</x-ui.badge>
                    <x-ui.badge variant="secondary">{{ $report['status'] }}</x-ui.badge>
                </div>
                <h1 class="text-2xl font-bold text-gray-900 dark:text-white leading-snug">{{ $report['title'] }}</h1>
            </div>
        </div>

        <div class="flex items-center gap-4 text-sm text-gray-500 dark:text-gray-400 border-t border-gray-100 dark:border-gray-700 pt-4">
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ $report['date'] }}
            </div>
            <div class="flex items-center">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ $report['author'] }}
            </div>
        </div>
    </x-ui.card>

    {{-- Report Content --}}
    <x-ui.card>
        <div class="prose prose-sm sm:prose dark:prose-invert max-w-none
                    prose-headings:font-semibold prose-headings:text-gray-900 dark:prose-headings:text-white
                    prose-p:text-gray-600 dark:prose-p:text-gray-400
                    prose-li:text-gray-600 dark:prose-li:text-gray-400
                    prose-strong:text-gray-900 dark:prose-strong:text-white">
            {!! $report['content'] !!}
        </div>
    </x-ui.card>
</div>
@endsection
