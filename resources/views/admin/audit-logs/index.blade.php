@extends('layouts.app')

@section('title', 'Audit Logs')

@section('content')
<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Page header -->
    <div class="sm:flex sm:justify-between sm:items-center mb-8">
        <div class="mb-4 sm:mb-0">
            <h1 class="text-2xl md:text-3xl text-gray-800 dark:text-gray-100 font-bold">System Audit Logs</h1>
        </div>
    </div>

    <!-- Filters -->
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.audit-logs.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-1/3">
                <x-ui.input type="text" name="search" value="{{ request('search') }}" placeholder="Search by model or ID..." />
            </div>
            <div class="w-full sm:w-1/4">
                <select name="event" class="form-select w-full rounded-md shadow-sm border-gray-300 focus:border-primary-300 focus:ring focus:ring-primary-200 focus:ring-opacity-50 dark:bg-gray-800 dark:border-gray-700 dark:text-white" onchange="this.form.submit()">
                    <option value="">All Events</option>
                    <option value="created" {{ request('event') === 'created' ? 'selected' : '' }}>Created</option>
                    <option value="updated" {{ request('event') === 'updated' ? 'selected' : '' }}>Updated</option>
                    <option value="deleted" {{ request('event') === 'deleted' ? 'selected' : '' }}>Deleted</option>
                </select>
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="primary">Filter</x-ui.button>
                @if(request()->hasAny(['search', 'event']))
                    <x-ui.button type="button" variant="secondary" tag="a" href="{{ route('admin.audit-logs.index') }}">Clear</x-ui.button>
                @endif
            </div>
        </form>
    </x-ui.card>

    <!-- Table -->
    <x-ui.card>
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3">Timestamp</th>
                        <th scope="col" class="px-6 py-3">User</th>
                        <th scope="col" class="px-6 py-3">Action</th>
                        <th scope="col" class="px-6 py-3">Resource</th>
                        <th scope="col" class="px-6 py-3 text-right">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                        <tr class="bg-white dark:bg-gray-800 border-b dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700" x-data="{ expanded: false }">
                            <td class="px-6 py-4 whitespace-nowrap">
                                {{ $log->created_at->format('M d, Y H:i:s') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-bold text-xs">
                                        {{ $log->user ? substr($log->user->name, 0, 1) : 'S' }}
                                    </div>
                                    <div class="ml-3">
                                        <div class="text-sm font-medium text-gray-900 dark:text-white">
                                            {{ $log->user ? $log->user->name : 'System' }}
                                        </div>
                                        <div class="text-xs text-gray-500">{{ $log->ip_address }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $badgeVariant = match($log->event) {
                                        'created' => 'success',
                                        'updated' => 'warning',
                                        'deleted' => 'danger',
                                        default => 'secondary'
                                    };
                                @endphp
                                <x-ui.badge variant="{{ $badgeVariant }}">{{ ucfirst($log->event) }}</x-ui.badge>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 dark:text-white font-medium">
                                    {{ class_basename($log->auditable_type) }}
                                </div>
                                <div class="text-xs text-gray-500 truncate max-w-[200px]" title="{{ $log->auditable_id }}">
                                    ID: {{ $log->auditable_id }}
                                </div>
                            </td>
                            <td class="px-6 py-4 text-right">
                                @if(!empty($log->old_values) || !empty($log->new_values))
                                    <button @click="expanded = !expanded" class="text-primary-600 hover:text-primary-900 dark:text-primary-400 dark:hover:text-primary-300">
                                        <span x-show="!expanded">View Payload</span>
                                        <span x-show="expanded">Hide Payload</span>
                                    </button>
                                @else
                                    <span class="text-gray-400">N/A</span>
                                @endif
                            </td>
                        </tr>
                        @if(!empty($log->old_values) || !empty($log->new_values))
                            <tr x-show="expanded" x-cloak class="bg-gray-50 dark:bg-gray-900 border-b dark:border-gray-700">
                                <td colspan="5" class="px-6 py-4">
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        @if(!empty($log->old_values))
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Old Values</h4>
                                                <pre class="text-xs text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-900/20 p-3 rounded-md overflow-x-auto">{{ json_encode($log->old_values, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif
                                        @if(!empty($log->new_values))
                                            <div>
                                                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">New Values</h4>
                                                <pre class="text-xs text-green-600 dark:text-green-400 bg-green-50 dark:bg-green-900/20 p-3 rounded-md overflow-x-auto">{{ json_encode($log->new_values, JSON_PRETTY_PRINT) }}</pre>
                                            </div>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @endif
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                <p>No audit logs found matching your criteria.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($logs->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $logs->links() }}
            </div>
        @endif
    </x-ui.card>
</div>
@endsection
