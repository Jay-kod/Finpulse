@extends('layouts.app')

@section('title', $page->title)

@section('breadcrumbs')
    @php
        View::share('breadcrumbs_data', [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Pages', 'url' => route('admin.pages.index')],
            ['label' => $page->title, 'url' => ''],
        ]);
    @endphp
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">{{ $page->title }}</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Previewing page content within the dashboard.</p>
        </div>
        <div class="mt-4 sm:mt-0 space-x-3">
            <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-dark-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 hover:bg-gray-50 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Back to List
            </a>
            <a href="{{ route('admin.pages.edit', $page) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-dark-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 hover:bg-gray-50 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Edit Page
            </a>
            <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                View on Website <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-900 shadow-sm rounded-xl border border-gray-200 dark:border-dark-800 p-8">
        <div class="prose dark:prose-invert max-w-none">
            {!! $page->content !!}
        </div>
    </div>
</div>
@endsection
