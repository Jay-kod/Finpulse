@extends('layouts.app')

@section('title', 'Datasets')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="datasetFilter()">
    {{-- Header --}}
    <div class="mb-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Dataset Engine</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage review datasets and monitor their ML processing status.</p>
        </div>
        <div class="flex items-center gap-3">
            <x-ui.button variant="primary" href="{{ route('analyst.datasets.create') }}" class="shadow-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Import Dataset
            </x-ui.button>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6">
            <div class="rounded-xl bg-positive-50 dark:bg-positive-900/30 p-4 border border-positive-200 dark:border-positive-800/50">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-positive-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-positive-800 dark:text-positive-200">{{ session('success') }}</p>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Stats Row --}}
    @php
        $totalDatasets = $datasets->count();
        $totalRecords = $datasets->sum('record_count');
        $processing = $datasets->where('status', 'processing')->count();
        $completed = $datasets->where('status', 'completed')->count();
    @endphp
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
        {{-- Card 1 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Datasets</h3>
                <div class="p-1.5 bg-blue-50 dark:bg-blue-900/30 text-blue-600 dark:text-blue-400 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"></path></svg>
                </div>
            </div>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ number_format($totalDatasets) }}</span>
            </div>
        </div>

        {{-- Card 2 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Total Records</h3>
                <div class="p-1.5 bg-purple-50 dark:bg-purple-900/30 text-purple-600 dark:text-purple-400 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
            </div>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ number_format($totalRecords) }}</span>
            </div>
        </div>

        {{-- Card 3 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Processing</h3>
                <div class="p-1.5 bg-yellow-50 dark:bg-yellow-900/30 text-yellow-600 dark:text-yellow-400 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                </div>
            </div>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ number_format($processing) }}</span>
            </div>
        </div>

        {{-- Card 4 --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl p-5 border border-gray-200 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow duration-200">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wide">Completed</h3>
                <div class="p-1.5 bg-positive-50 dark:bg-positive-900/30 text-positive-600 dark:text-positive-400 rounded-md">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                </div>
            </div>
            <div class="flex items-end space-x-2">
                <span class="text-3xl font-bold text-gray-900 dark:text-white tracking-tight">{{ number_format($completed) }}</span>
            </div>
        </div>
    </div>

    {{-- Table & Toolbar --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm overflow-hidden">
        {{-- Toolbar --}}
        <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-gray-50/50 dark:bg-gray-900/20">
            <div class="relative w-full sm:max-w-xs">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-4 w-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input x-model="search" type="text" placeholder="Search datasets..." class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg leading-5 bg-white dark:bg-gray-700 text-gray-900 dark:text-white placeholder-gray-500 focus:outline-none focus:ring-1 focus:ring-primary-500 focus:border-primary-500 sm:text-sm transition-colors">
            </div>
            <div class="flex items-center">
                <select x-model="statusFilter" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 focus:outline-none focus:ring-primary-500 focus:border-primary-500 sm:text-sm rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white transition-colors cursor-pointer">
                    <option value="">All Statuses</option>
                    <option value="completed">Completed</option>
                    <option value="processing">Processing</option>
                    <option value="pending">Pending</option>
                    <option value="failed">Failed</option>
                </select>
            </div>
        </div>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-900/50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name & App</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Source</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Records</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($datasets as $dataset)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors dataset-row" 
                            data-name="{{ strtolower($dataset->name) }}"
                            data-app="{{ strtolower($dataset->fintechApp->name ?? '') }}"
                            data-status="{{ strtolower($dataset->status) }}"
                            x-show="showRow('{{ strtolower($dataset->name) }}', '{{ strtolower($dataset->fintechApp->name ?? '') }}', '{{ strtolower($dataset->status) }}')">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10 flex items-center justify-center rounded-lg bg-gradient-to-br from-gray-100 to-gray-200 dark:from-gray-700 dark:to-gray-600 text-gray-600 dark:text-gray-300 font-bold border border-gray-200 dark:border-gray-600">
                                        {{ substr($dataset->name, 0, 1) }}
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $dataset->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 mt-0.5">{{ $dataset->fintechApp->name ?? 'No App Linked' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    @if(strtolower($dataset->source) == 'play store' || strtolower($dataset->source) == 'google play')
                                        <svg class="w-4 h-4 mr-1.5 text-green-500" fill="currentColor" viewBox="0 0 24 24"><path d="M3.609 1.814L13.792 12 3.61 22.186a1.984 1.984 0 0 1-.587-1.42V3.235c0-.528.213-1.036.586-1.421z"/><path d="M14.5 12.708l5.88-3.393a1.987 1.987 0 0 0 0-3.45L14.5 2.471l-.708.708L21.085 10.5 13.792 12l.708.708z"/><path d="M13.792 12l-10.183 10.183 10.183-5.88 2.5-2.5-2.5-1.803z"/><path d="M13.792 12l2.5-1.803 2.5-2.5-10.183-5.88L13.792 12z"/></svg>
                                    @elseif(strtolower($dataset->source) == 'app store' || strtolower($dataset->source) == 'apple')
                                        <svg class="w-4 h-4 mr-1.5 text-gray-800 dark:text-gray-200" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.477 2 2 6.477 2 12s4.477 10 10 10 10-4.477 10-10S17.523 2 12 2zm3.328 14.86c-.958-.168-1.58-.72-2.197-1.343-.464-.467-.88-.93-1.564-1.025-.662-.093-1.25.267-1.84.625-.797.483-1.603.97-2.618.59-.854-.317-1.436-1.028-1.92-1.63-.78-.97-1.332-2.176-1.554-3.447-.282-1.607.037-3.155.88-4.453.76-1.166 1.838-1.85 3.097-2.115 1.097-.23 2.155.053 3.078.435.63.262 1.18.492 1.64.442.484-.052 1.05-.34 1.705-.66.86-.418 1.916-.93 3.03-.78 1.344.18 2.457.855 3.197 1.92-.09.057-1.81 1.066-1.745 3.15.068 2.228 1.917 2.946 2.006 2.98-.016.046-.314.99-.95 1.91-.564.81-1.18 1.62-2.046 1.772zm-2.046-10.15c-.477.58-1.144.97-1.825.908-.08-1.106.398-2.146.992-2.822.54-.614 1.355-1.042 2.052-1.046.068 1.094-.654 2.274-1.22 2.96z"/></svg>
                                    @else
                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4"></path></svg>
                                    @endif
                                    {{ $dataset->source }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                    {{ number_format($dataset->record_count) }} rows
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = match($dataset->status) {
                                        'completed' => 'bg-positive-50 text-positive-700 dark:bg-positive-900/30 dark:text-positive-400 border-positive-200 dark:border-positive-800',
                                        'processing' => 'bg-yellow-50 text-yellow-700 dark:bg-yellow-900/30 dark:text-yellow-400 border-yellow-200 dark:border-yellow-800',
                                        'failed' => 'bg-negative-50 text-negative-700 dark:bg-negative-900/30 dark:text-negative-400 border-negative-200 dark:border-negative-800',
                                        default => 'bg-gray-50 text-gray-700 dark:bg-gray-900/30 dark:text-gray-400 border-gray-200 dark:border-gray-700'
                                    };
                                    $dotClass = match($dataset->status) {
                                        'completed' => 'bg-positive-500',
                                        'processing' => 'bg-yellow-500 animate-pulse',
                                        'failed' => 'bg-negative-500',
                                        default => 'bg-gray-500'
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border {{ $statusClasses }}">
                                    <span class="w-1.5 h-1.5 rounded-full mr-1.5 {{ $dotClass }}"></span>
                                    {{ ucfirst($dataset->status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <a href="{{ route('analyst.datasets.edit', $dataset) }}" class="text-gray-500 hover:text-primary-600 dark:text-gray-400 dark:hover:text-primary-400 transition-colors" title="Edit">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </a>
                                    <form action="{{ route('analyst.datasets.destroy', $dataset) }}" method="POST" class="inline-block" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this dataset? This cannot be undone.', confirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-gray-500 hover:text-red-600 dark:text-gray-400 dark:hover:text-red-400 transition-colors" title="Delete">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center">
                                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 dark:bg-gray-800 mb-4 border border-gray-100 dark:border-gray-700">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                </div>
                                <h3 class="text-base font-bold text-gray-900 dark:text-white">No datasets found</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto">Get started by importing your first review dataset from the Play Store or App Store.</p>
                                <div class="mt-6">
                                    <x-ui.button variant="primary" href="{{ route('analyst.datasets.create') }}">
                                        Import Dataset
                                    </x-ui.button>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                    
                    {{-- Alpine No Results State --}}
                    @if($datasets->count() > 0)
                        <tr x-show="!hasResults" style="display: none;">
                            <td colspan="5" class="px-6 py-12 text-center">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-gray-50 dark:bg-gray-800 mb-3 border border-gray-100 dark:border-gray-700">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                                </div>
                                <h3 class="text-sm font-bold text-gray-900 dark:text-white">No matching datasets</h3>
                                <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Try adjusting your search query or status filter.</p>
                                <button type="button" @click="search = ''; statusFilter = ''; checkResults()" class="mt-4 text-sm font-medium text-primary-600 dark:text-primary-400 hover:text-primary-800 dark:hover:text-primary-300">
                                    Clear filters
                                </button>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('datasetFilter', () => ({
            search: '',
            statusFilter: '',
            hasResults: true,
            
            init() {
                this.$watch('search', () => this.checkResults());
                this.$watch('statusFilter', () => this.checkResults());
                // Short delay to ensure DOM is ready
                setTimeout(() => this.checkResults(), 100);
            },
            
            showRow(name, app, status) {
                const searchMatch = name.includes(this.search.toLowerCase()) || app.includes(this.search.toLowerCase());
                const statusMatch = this.statusFilter === '' || status === this.statusFilter;
                return searchMatch && statusMatch;
            },
            
            checkResults() {
                // NextTick ensures DOM updates are complete before checking visibility
                this.$nextTick(() => {
                    const rows = document.querySelectorAll('.dataset-row');
                    if (rows.length === 0) return;
                    
                    let visible = false;
                    rows.forEach(row => {
                        if (row.style.display !== 'none') {
                            visible = true;
                        }
                    });
                    this.hasResults = visible;
                });
            }
        }));
    });
</script>
@endpush
@endsection

