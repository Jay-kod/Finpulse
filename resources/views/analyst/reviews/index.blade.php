@extends('layouts.app')

@section('title', 'Reviews Management')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Review Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage individual feedback records parsed from datasets.</p>
        </div>
        <x-ui.button variant="primary" href="{{ route('analyst.reviews.create') }}">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
            Add Manual Review
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
                            <x-ui.table.th>App Context</x-ui.table.th>
                            <x-ui.table.th>Review Snippet</x-ui.table.th>
                            <x-ui.table.th>Rating</x-ui.table.th>
                            <x-ui.table.th>Status</x-ui.table.th>
                            <x-ui.table.th class="text-right">Actions</x-ui.table.th>
                        </x-ui.table.tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($reviews as $review)
                            <x-ui.table.tr x-data="{ expanded: false }">
                                <x-ui.table.td>
                                    <div class="flex flex-col">
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $review->dataset->fintechApp->name ?? 'Unknown App' }}</span>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 truncate max-w-[150px]" title="{{ $review->dataset->name ?? 'Unknown Dataset' }}">
                                            {{ $review->dataset->name ?? 'Unknown Dataset' }}
                                        </span>
                                    </div>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <div class="max-w-md">
                                        <p class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer" :class="expanded ? '' : 'line-clamp-2'" @click="expanded = !expanded">
                                            {{ $review->content }}
                                        </p>
                                        @if(Str::length($review->content) > 80)
                                        <button @click="expanded = !expanded" class="mt-1 text-xs font-medium text-primary-600 dark:text-primary-400 hover:underline">
                                            <span x-text="expanded ? '▲ Show less' : '▼ Show more'"></span>
                                        </button>
                                        @endif
                                    </div>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <div class="flex items-center">
                                        <span class="text-yellow-400 mr-1">★</span>
                                        <span class="font-medium text-gray-700 dark:text-gray-300">{{ $review->rating ?? '-' }}</span>
                                    </div>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <x-ui.badge variant="{{ match($review->processed_status) {
                                        'processed' => 'success',
                                        'error' => 'danger',
                                        default => 'warning'
                                    } }}">{{ ucfirst($review->processed_status) }}</x-ui.badge>
                                </x-ui.table.td>
                                <x-ui.table.td class="text-right whitespace-nowrap text-sm font-medium space-x-2">
                                    <a href="{{ route('analyst.predictions.index') }}?text={{ urlencode($review->content) }}" class="inline-flex items-center gap-1 text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300" title="Predict this review">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Predict
                                    </a>
                                    <a href="{{ route('analyst.reviews.edit', $review) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300">Edit</a>
                                    <form action="{{ route('analyst.reviews.destroy', $review) }}" method="POST" class="inline-block" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this review?', confirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                    </form>
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @empty
                            <x-ui.table.tr>
                                <x-ui.table.td colspan="5" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    No reviews have been imported yet.
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @endforelse
                    </tbody>
                </x-ui.table>
            </div>
        </div>
        
        @if($reviews->hasPages())
            <div class="mt-4 border-t border-gray-200 dark:border-gray-700 pt-4">
                {{ $reviews->links() }}
            </div>
        @endif
    </x-ui.card>
</div>
@endsection
