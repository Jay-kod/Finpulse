@extends('layouts.app')

@section('title', 'Create Report')

@section('content')
<div class="max-w-3xl mx-auto">
    <div class="mb-8 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New Report</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Define filters to save a specific analytics view.</p>
        </div>
        <x-ui.button variant="secondary" href="{{ route('analyst.reports.index') }}">
            Cancel
        </x-ui.button>
    </div>

    <x-ui.card>
        <form action="{{ route('analyst.reports.store') }}" method="POST" class="space-y-6">
            @csrf

            <x-ui.form-group label="Report Title" for="title" required>
                <x-ui.input type="text" name="title" id="title" value="{{ old('title') }}" required placeholder="e.g. Q2 PalmPay Sentiment" />
                @error('title')
                    <x-ui.error>{{ $message }}</x-ui.error>
                @enderror
            </x-ui.form-group>

            <x-ui.form-group label="Description" for="description">
                <textarea name="description" id="description" rows="3" class="w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white shadow-sm">{{ old('description') }}</textarea>
                @error('description')
                    <x-ui.error>{{ $message }}</x-ui.error>
                @enderror
            </x-ui.form-group>

            <hr class="border-gray-200 dark:border-gray-700" />

            <div>
                <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Analytics Filters</h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.form-group label="Specific App" for="app_id">
                        <select name="app_id" id="app_id" class="w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 text-sm focus:ring-primary-500 focus:border-primary-500 dark:text-white shadow-sm">
                            <option value="">-- All Apps --</option>
                            @foreach($apps as $app)
                                <option value="{{ $app->id }}" {{ old('app_id') == $app->id ? 'selected' : '' }}>{{ $app->name }}</option>
                            @endforeach
                        </select>
                        @error('app_id')
                            <x-ui.error>{{ $message }}</x-ui.error>
                        @enderror
                    </x-ui.form-group>

                    <div class="grid grid-cols-2 gap-4">
                        <x-ui.form-group label="Start Date" for="start_date">
                            <x-ui.input type="date" name="start_date" id="start_date" value="{{ old('start_date') }}" />
                            @error('start_date')
                                <x-ui.error>{{ $message }}</x-ui.error>
                            @enderror
                        </x-ui.form-group>

                        <x-ui.form-group label="End Date" for="end_date">
                            <x-ui.input type="date" name="end_date" id="end_date" value="{{ old('end_date') }}" />
                            @error('end_date')
                                <x-ui.error>{{ $message }}</x-ui.error>
                            @enderror
                        </x-ui.form-group>
                    </div>
                </div>
                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">Leave filters blank to include all data.</p>
            </div>

            <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
                <x-ui.button type="submit" variant="primary">
                    Save Report
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
