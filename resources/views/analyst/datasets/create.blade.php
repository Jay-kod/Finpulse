@extends('layouts.app')

@section('title', 'Import Dataset')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('analyst.datasets.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Datasets
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Import Dataset</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Register a new dataset of reviews for processing.</p>
    </div>

    <x-ui.card>
        <form action="{{ route('analyst.datasets.store') }}" method="POST">
            @csrf

            <x-ui.form-group label="Dataset Name" name="name" required class="mb-6">
                <x-ui.input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. OPay Q1 Reviews 2026" />
            </x-ui.form-group>

            <x-ui.form-group label="Target Application" name="fintech_app_id" required class="mb-6">
                <select name="fintech_app_id" id="fintech_app_id" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">
                    <option value="">Select an application...</option>
                    @foreach($apps as $app)
                        <option value="{{ $app->id }}" {{ old('fintech_app_id') == $app->id ? 'selected' : '' }}>
                            {{ $app->name }}
                        </option>
                    @endforeach
                </select>
            </x-ui.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <x-ui.form-group label="Source" name="source" required>
                    <select name="source" id="source" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">
                        <option value="Google Play" {{ old('source') == 'Google Play' ? 'selected' : '' }}>Google Play Store</option>
                        <option value="App Store" {{ old('source') == 'App Store' ? 'selected' : '' }}>Apple App Store</option>
                        <option value="Twitter" {{ old('source') == 'Twitter' ? 'selected' : '' }}>Twitter / X</option>
                        <option value="Custom" {{ old('source') == 'Custom' ? 'selected' : '' }}>Custom Import</option>
                    </select>
                </x-ui.form-group>

                <x-ui.form-group label="Record Count" name="record_count" required>
                    <x-ui.input type="number" name="record_count" id="record_count" value="{{ old('record_count', 0) }}" required min="0" />
                </x-ui.form-group>
            </div>

            <x-ui.form-group label="Processing Status" name="status" required class="mb-8">
                <select name="status" id="status" required class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">
                    <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="processing" {{ old('status') == 'processing' ? 'selected' : '' }}>Processing</option>
                    <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                    <option value="failed" {{ old('status') == 'failed' ? 'selected' : '' }}>Failed</option>
                </select>
            </x-ui.form-group>

            <div class="flex items-center justify-end border-t border-gray-200 dark:border-gray-700 pt-6">
                <a href="{{ route('analyst.datasets.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Cancel</a>
                <x-ui.button type="submit" variant="primary">Register Dataset</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
