@extends('layouts.app')

@section('title', 'Add Manual Review')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('analyst.reviews.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Reviews
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Manual Review</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manually insert a feedback record into a dataset.</p>
    </div>

    <x-ui.card>
        <form action="{{ route('analyst.reviews.store') }}" method="POST">
            @csrf

            <x-ui.form-group label="Target Dataset" name="dataset_id" required class="mb-6">
                <select name="dataset_id" id="dataset_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">
                    <option value="">Select a dataset...</option>
                    @foreach($datasets as $dataset)
                        <option value="{{ $dataset->id }}" {{ old('dataset_id') == $dataset->id ? 'selected' : '' }}>
                            {{ $dataset->fintechApp->name ?? 'Unknown' }} - {{ $dataset->name }}
                        </option>
                    @endforeach
                </select>
            </x-ui.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <x-ui.form-group label="Author Name (Optional)" name="author_name">
                    <x-ui.input type="text" name="author_name" id="author_name" value="{{ old('author_name') }}" placeholder="e.g. John Doe" />
                </x-ui.form-group>

                <x-ui.form-group label="Rating (1-5)" name="rating">
                    <x-ui.input type="number" name="rating" id="rating" value="{{ old('rating') }}" min="1" max="5" placeholder="5" />
                </x-ui.form-group>
            </div>

            <x-ui.form-group label="Review Content" name="content" required class="mb-6">
                <textarea name="content" id="content" rows="4" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white" placeholder="Enter the full review text here...">{{ old('content') }}</textarea>
            </x-ui.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <x-ui.form-group label="Processing Status" name="processed_status" required>
                    <select name="processed_status" id="processed_status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">
                        <option value="pending" {{ old('processed_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processed" {{ old('processed_status') == 'processed' ? 'selected' : '' }}>Processed</option>
                        <option value="error" {{ old('processed_status') == 'error' ? 'selected' : '' }}>Error</option>
                    </select>
                </x-ui.form-group>

                <x-ui.form-group label="Published Date (Optional)" name="published_at">
                    <x-ui.input type="date" name="published_at" id="published_at" value="{{ old('published_at') }}" />
                </x-ui.form-group>
            </div>

            <div class="flex items-center justify-end border-t border-gray-200 dark:border-gray-700 pt-6">
                <a href="{{ route('analyst.reviews.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Cancel</a>
                <x-ui.button type="submit" variant="primary">Save Review</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
