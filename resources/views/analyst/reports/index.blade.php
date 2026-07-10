@extends('layouts.app')

@section('title', 'Saved Reports')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Saved Reports</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage and view custom analytics configurations.</p>
        </div>
        <div class="flex items-center gap-3">
            <x-ui.button variant="primary" href="{{ route('analyst.reports.create') }}">
                Create Report
            </x-ui.button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6">
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        </div>
    @endif

    <x-ui.card>
        <div class="overflow-x-auto -mx-6">
            <div class="inline-block min-w-full align-middle">
                <x-ui.table class="border-t border-gray-200 dark:border-gray-700">
                    <thead>
                        <x-ui.table.tr>
                            <x-ui.table.th>Title</x-ui.table.th>
                            <x-ui.table.th>Description</x-ui.table.th>
                            <x-ui.table.th>Filters</x-ui.table.th>
                            <x-ui.table.th>Author</x-ui.table.th>
                            <x-ui.table.th class="text-right">Created At</x-ui.table.th>
                        </x-ui.table.tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reports as $report)
                            <x-ui.table.tr>
                                <x-ui.table.td>
                                    <a href="{{ route('analyst.reports.show', $report) }}" class="text-primary-600 dark:text-primary-400 font-medium hover:underline">
                                        {{ $report->title }}
                                    </a>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-sm text-gray-600 dark:text-gray-400 max-w-xs line-clamp-1">{{ $report->description ?? '—' }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <div class="flex flex-wrap gap-1">
                                        @if(empty($report->parameters))
                                            <span class="text-xs text-gray-500 italic">Global (No Filters)</span>
                                        @else
                                            @if(!empty($report->parameters['app_id']))
                                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700">App ID: {{ $report->parameters['app_id'] }}</span>
                                            @endif
                                            @if(!empty($report->parameters['start_date']))
                                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700">From: {{ $report->parameters['start_date'] }}</span>
                                            @endif
                                            @if(!empty($report->parameters['end_date']))
                                                <span class="px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-800 dark:text-gray-200 border border-gray-200 dark:border-gray-700">To: {{ $report->parameters['end_date'] }}</span>
                                            @endif
                                        @endif
                                    </div>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-sm text-gray-900 dark:text-white">{{ $report->user->name ?? 'Unknown' }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td class="text-right text-sm text-gray-500 dark:text-gray-400">
                                    {{ $report->created_at->format('M d, Y') }}
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @empty
                            <x-ui.table.tr>
                                <x-ui.table.td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    No reports have been created yet.
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @endforelse
                    </tbody>
                </x-ui.table>
            </div>
        </div>
    </x-ui.card>
</div>
@endsection
