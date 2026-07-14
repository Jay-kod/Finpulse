@extends('layouts.app')

@section('title', 'Edit Page: ' . $page->title)

@section('breadcrumbs')
    @php
        View::share('breadcrumbs_data', [
            ['label' => 'Dashboard', 'url' => route('admin.dashboard')],
            ['label' => 'Pages', 'url' => route('admin.pages.index')],
            ['label' => 'Edit ' . $page->title, 'url' => ''],
        ]);
    @endphp
@endsection

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit Page</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update the contents of the page.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <a href="{{ route('admin.pages.index') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-dark-700 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-dark-800 hover:bg-gray-50 dark:hover:bg-dark-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                Cancel
            </a>
        </div>
    </div>

    <div class="bg-white dark:bg-dark-900 shadow-sm rounded-xl border border-gray-200 dark:border-dark-800 p-6">
        <form action="{{ route('admin.pages.update', $page) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-6">
                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $page->title) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-dark-950 dark:border-dark-700 dark:text-white" required>
                    @error('title')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Content (HTML allowed)</label>
                    <textarea name="content" id="content" rows="15" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:bg-dark-950 dark:border-dark-700 dark:text-white font-mono">{{ old('content', $page->content) }}</textarea>
                    @error('content')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary-600 hover:bg-primary-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary-500">
                        Save Changes
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
