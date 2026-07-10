@extends('layouts.app')

@section('title', 'Datasets')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Dataset Engine</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage review datasets and their processing status.</p>
        </div>
        <x-ui.button variant="primary" href="{{ route('analyst.datasets.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Import Dataset
        </x-ui.button>
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
                            <x-ui.table.th>Name</x-ui.table.th>
                            <x-ui.table.th>Application</x-ui.table.th>
                            <x-ui.table.th>Source</x-ui.table.th>
                            <x-ui.table.th>Records</x-ui.table.th>
                            <x-ui.table.th>Status</x-ui.table.th>
                            <x-ui.table.th class="text-right">Actions</x-ui.table.th>
                        </x-ui.table.tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($datasets as $dataset)
                            <x-ui.table.tr>
                                <x-ui.table.td>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $dataset->name }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $dataset->fintechApp->name ?? 'N/A' }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $dataset->source }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ number_format($dataset->record_count) }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <x-ui.badge variant="{{ match($dataset->status) {
                                        'completed' => 'success',
                                        'processing' => 'warning',
                                        'failed' => 'danger',
                                        default => 'secondary'
                                    } }}">{{ ucfirst($dataset->status) }}</x-ui.badge>
                                </x-ui.table.td>
                                <x-ui.table.td class="text-right whitespace-nowrap text-sm font-medium space-x-3">
                                    <a href="{{ route('analyst.datasets.edit', $dataset) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300">Edit</a>
                                    <form action="{{ route('analyst.datasets.destroy', $dataset) }}" method="POST" class="inline-block" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this dataset?', confirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                    </form>
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @empty
                            <x-ui.table.tr>
                                <x-ui.table.td colspan="6" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    No datasets have been imported yet.
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
