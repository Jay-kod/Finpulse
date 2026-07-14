@extends('layouts.app')

@section('title', 'Manage Pages')

@section('breadcrumbs')
    @php
        View::share('breadcrumbs_data', [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Pages', 'url' => route('admin.pages.index')],
        ]);
    @endphp
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Manage Pages</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Edit the content of the public pages.</p>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-900 shadow-sm rounded-xl border border-gray-200 dark:border-dark-800 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-dark-800">
                <thead class="bg-gray-50 dark:bg-dark-950">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Title</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Slug</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-dark-900 divide-y divide-gray-200 dark:divide-dark-800">
                    @forelse($pages as $page)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                {{ $page->title }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                {{ $page->slug }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('admin.pages.show', $page) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3">View</a>
                                <a href="{{ route('admin.pages.edit', $page) }}" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300 mr-3">Edit</a>
                                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-300">View on Website</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                No pages found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
