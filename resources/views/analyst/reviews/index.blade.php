@extends('layouts.app')

@section('title', 'Reviews Management')

@section('content')
<style>
    .review-clamp {
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }
    .review-text {
        text-align: justify;
    }
</style>
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

    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
        @forelse($reviews as $review)
            <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200 flex flex-col h-full" x-data="{ expanded: false }">
                <!-- Header -->
                <div class="p-5 border-b border-gray-100 dark:border-gray-700 flex justify-between items-start">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-lg bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                            @if(($review->dataset->source ?? '') === 'Apple App Store')
                                <svg class="w-6 h-6" viewBox="0 0 384 512" fill="currentColor"><path d="M318.7 268.7c-.2-36.7 16.4-64.4 50-84.8-18.8-26.9-47.2-41.7-84.7-44.6-35.5-2.8-74.3 20.7-88.5 20.7-15 0-49.4-19.7-76.4-19.7C63.3 141.2 4 184.8 4 273.5q0 39.3 14.4 81.2c12.8 36.7 59 126.7 107.2 125.2 25.2-.6 43-17.9 75.8-17.9 31.8 0 48.3 17.9 76.4 17.9 48.6-.7 90.4-82.5 102.6-119.3-65.2-30.7-61.7-90-61.7-91.9zm-56.6-164.2c27.3-32.4 24.8-61.9 24-72.5-24.1 1.4-52 16.4-67.9 34.9-17.5 19.8-27.8 44.3-25.6 71.9 26.1 2 49.9-11.4 69.5-34.3z"/></svg>
                            @elseif(($review->dataset->source ?? '') === 'Google Play Store')
                                <svg class="w-6 h-6" viewBox="0 0 512 512" fill="currentColor"><path d="M325.3 234.3L104.6 13l280.8 161.2-60.1 60.1zM47 0C34 6.8 25.3 19.2 25.3 35.3v441.3c0 16.1 8.7 28.5 21.7 35.3l256.6-256L47 0zm425.2 225.6l-58.9-34.1-65.7 64.5 65.7 64.5 60.1-34.1c18-14.3 18-46.5-1.2-60.8zM104.6 499l280.8-161.2-60.1-60.1L104.6 499z"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                            @endif
                        </div>
                        <div>
                            <h3 class="font-bold text-gray-900 dark:text-white leading-tight">{{ $review->dataset->fintechApp->name ?? 'Unknown App' }}</h3>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $review->dataset->source ?? 'Unknown Source' }}</p>
                        </div>
                    </div>
                    <div>
                        <x-ui.badge variant="{{ match($review->processed_status) {
                            'processed' => 'success',
                            'error' => 'danger',
                            default => 'warning'
                        } }}">{{ ucfirst($review->processed_status) }}</x-ui.badge>
                    </div>
                </div>

                <!-- Body -->
                <div class="p-5 flex-1 flex flex-col">
                    <!-- Stars -->
                    <div class="flex items-center gap-1 mb-3">
                        @for($i = 1; $i <= 5; $i++)
                            <svg class="w-4 h-4 {{ $i <= ($review->rating ?? 0) ? 'text-amber-400' : 'text-gray-300 dark:text-gray-600' }}" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                        @endfor
                    </div>

                    <!-- Text -->
                    <div class="mb-4">
                        <div x-show="!expanded">
                            <p class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer" 
                               style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; text-align: justify; word-break: break-word;"
                               @click="expanded = true">
                                {{ $review->content }}
                            </p>
                        </div>
                        <div x-show="expanded" style="display: none;">
                            <p class="text-sm text-gray-600 dark:text-gray-300 cursor-pointer" 
                               style="text-align: justify; word-break: break-word;"
                               @click="expanded = false">
                                {{ $review->content }}
                            </p>
                        </div>
                        @if(Str::length($review->content) > 80)
                        <button @click="expanded = !expanded" class="mt-2 text-xs font-semibold text-primary-600 dark:text-primary-400 hover:text-primary-800 transition-colors">
                            <span x-text="expanded ? '▲ Show less' : '▼ Show more'"></span>
                        </button>
                        @endif
                    </div>
                </div>

                <!-- Footer -->
                <div class="p-4 bg-gray-50 dark:bg-gray-800/50 border-t border-gray-100 dark:border-gray-700 rounded-b-xl flex items-center justify-between mt-auto">
                    <a href="{{ route('analyst.predictions.index') }}?text={{ urlencode($review->content) }}&source={{ urlencode($review->dataset->source ?? '') }}" class="inline-flex items-center gap-1.5 text-xs font-medium text-amber-600 dark:text-amber-400 hover:text-amber-800 dark:hover:text-amber-300 px-3 py-1.5 rounded-md hover:bg-amber-50 dark:hover:bg-amber-900/30 transition-colors" title="Predict this review">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        Predict
                    </a>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('analyst.reviews.edit', $review) }}" class="text-xs font-medium text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 px-2 py-1.5 transition-colors">Edit</a>
                        <form action="{{ route('analyst.reviews.destroy', $review) }}" method="POST" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this review?', confirm: () => $el.submit() })">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-xs font-medium text-red-500 hover:text-red-700 dark:hover:text-red-400 px-2 py-1.5 transition-colors">Delete</button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-12 text-center text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 rounded-xl border border-dashed border-gray-300 dark:border-gray-700">
                <svg class="w-12 h-12 mx-auto text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                <p class="text-lg font-medium">No reviews found</p>
                <p class="text-sm mt-1">Wait for sync jobs to complete or manually add one.</p>
            </div>
        @endforelse
    </div>

    @if($reviews->hasPages())
        <div class="mt-6 flex justify-center">
            {{ $reviews->links() }}
        </div>
    @endif
</div>
@endsection
