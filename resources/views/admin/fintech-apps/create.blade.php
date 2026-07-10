@extends('layouts.app')

@section('title', 'Add Fintech Application')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.fintech-apps.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Applications
        </a>
    </div>

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Fintech Application</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Register a new mobile application to track and analyze.</p>
    </div>

    <x-ui.card>
        <form action="{{ route('admin.fintech-apps.store') }}" method="POST">
            @csrf

            <x-ui.form-group label="Application Name" name="name" required class="mb-6">
                <x-ui.input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. OPay" />
            </x-ui.form-group>

            <x-ui.form-group label="Package Name (Legacy)" name="package_name" required class="mb-6">
                <x-ui.input type="text" name="package_name" id="package_name" value="{{ old('package_name') }}" required placeholder="e.g. team.opay.pay" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">The unique identifier used internally.</p>
            </x-ui.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <x-ui.form-group label="Platform" name="platform" required>
                    <select name="platform" id="platform" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">
                        <option value="android" {{ old('platform') == 'android' ? 'selected' : '' }}>Android (Play Store)</option>
                        <option value="ios" {{ old('platform') == 'ios' ? 'selected' : '' }}>iOS (App Store)</option>
                        <option value="both" {{ old('platform') == 'both' ? 'selected' : '' }}>Both</option>
                    </select>
                </x-ui.form-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <x-ui.form-group label="Google Play Store ID" name="playstore_id">
                    <x-ui.input type="text" name="playstore_id" id="playstore_id" value="{{ old('playstore_id') }}" placeholder="e.g. com.opay.app" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required if Android is supported.</p>
                </x-ui.form-group>

                <x-ui.form-group label="Apple App Store ID" name="appstore_id">
                    <x-ui.input type="text" name="appstore_id" id="appstore_id" value="{{ old('appstore_id') }}" placeholder="e.g. 1461642822" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required if iOS is supported.</p>
                </x-ui.form-group>
            </div>

            <x-ui.form-group label="Description" name="description" class="mb-6">
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">{{ old('description') }}</textarea>
            </x-ui.form-group>

            <x-ui.form-group label="Logo URL" name="logo_url" class="mb-6">
                <x-ui.input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url') }}" placeholder="https://example.com/logo.png" />
            </x-ui.form-group>

            <div class="mb-8">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 ml-6">If inactive, the pipeline will stop fetching new reviews for this app.</p>
            </div>

            <div class="flex items-center justify-end border-t border-gray-200 dark:border-gray-700 pt-6">
                <a href="{{ route('admin.fintech-apps.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Cancel</a>
                <x-ui.button type="submit" variant="primary">Save Application</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
