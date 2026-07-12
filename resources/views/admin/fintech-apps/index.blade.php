@extends('layouts.app')

@section('title', 'Fintech Applications')

@section('content')
<div class="max-w-7xl mx-auto">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Fintech Applications</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage the core applications being analyzed on the platform.</p>
        </div>
        <div class="flex items-center space-x-3">
            <x-ui.button variant="secondary" href="{{ route('analyst.datasets.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"></path></svg>
                Upload Dataset
            </x-ui.button>
            <x-ui.button variant="primary" href="{{ route('admin.fintech-apps.create') }}">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Add Application
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
                            <x-ui.table.th>Name</x-ui.table.th>
                            <x-ui.table.th>Package Name</x-ui.table.th>
                            <x-ui.table.th>Status</x-ui.table.th>
                            <x-ui.table.th class="text-right">Actions</x-ui.table.th>
                        </x-ui.table.tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($apps as $app)
                            <x-ui.table.tr>
                                <x-ui.table.td>
                                    <div class="flex items-center">
                                        @if($app->logo_url)
                                            <img src="{{ $app->logo_url }}" alt="{{ $app->name }} logo" class="w-8 h-8 rounded bg-gray-100 mr-3">
                                        @else
                                            <div class="w-8 h-8 rounded bg-primary-100 text-primary-600 dark:bg-primary-900/50 dark:text-primary-400 flex items-center justify-center font-bold mr-3">
                                                {{ substr($app->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <span class="font-medium text-gray-900 dark:text-white">{{ $app->name }}</span>
                                    </div>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <span class="text-sm text-gray-500 dark:text-gray-400">{{ $app->package_name }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    @if($app->is_active)
                                        <x-ui.badge variant="success">Active</x-ui.badge>
                                    @else
                                        <x-ui.badge variant="secondary">Inactive</x-ui.badge>
                                    @endif
                                </x-ui.table.td>
                                <x-ui.table.td class="text-right whitespace-nowrap text-sm font-medium space-x-3">
                                    <a href="{{ route('admin.fintech-apps.edit', $app) }}" class="text-primary-600 dark:text-primary-400 hover:text-primary-900 dark:hover:text-primary-300">Edit</a>
                                    <form action="{{ route('admin.fintech-apps.destroy', $app) }}" method="POST" class="inline-block" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this application?', confirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                    </form>
                                </x-ui.table.td>
                            </x-ui.table.tr>
                        @empty
                            <x-ui.table.tr>
                                <x-ui.table.td colspan="4" class="text-center py-8 text-gray-500 dark:text-gray-400">
                                    No applications configured yet.
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
