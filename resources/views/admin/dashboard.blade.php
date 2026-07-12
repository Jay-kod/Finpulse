@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('scripts')
    {{-- We use a custom script for admin charts to handle the specific admin data format --}}
    @vite(['resources/js/admin-dashboard.js'])
    <script>
        window.adminDashboardData = {
            roleCounts: @json($roleCounts),
            reviewGrowth: @json($reviewGrowth)
        };
    </script>
@endpush

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Admin Control Center</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Welcome back, {{ Auth::user()->name }}! Here's an overview of the system status.</p>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Users --}}
        <x-ui.card class="flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Total Users</h3>
                <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-900/30 flex items-center justify-center text-blue-600 dark:text-blue-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</div>
                <div class="mt-2 flex items-center text-sm text-green-600 dark:text-green-400">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    <span>{{ number_format($newUsersThisMonth) }} new</span>
                    <span class="text-gray-500 dark:text-gray-400 ml-1">this month</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Tracked Apps --}}
        <x-ui.card class="flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Tracked Apps</h3>
                <div class="w-8 h-8 rounded-full bg-emerald-100 dark:bg-emerald-900/30 flex items-center justify-center text-emerald-600 dark:text-emerald-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalApps) }}</div>
                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <span class="text-emerald-600 dark:text-emerald-400 font-medium mr-1">{{ $activeApps }}</span> active
                </div>
            </div>
        </x-ui.card>

        {{-- Processed Reviews --}}
        <x-ui.card class="flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Processed Reviews</h3>
                <div class="w-8 h-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-600 dark:text-primary-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalReviews) }}</div>
                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <svg class="w-4 h-4 mr-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    <span>System-wide total</span>
                </div>
            </div>
        </x-ui.card>

        {{-- Audit Events --}}
        <x-ui.card class="flex flex-col justify-between">
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400">Audit Events</h3>
                <div class="w-8 h-8 rounded-full bg-amber-100 dark:bg-amber-900/30 flex items-center justify-center text-amber-600 dark:text-amber-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                </div>
            </div>
            <div>
                <div class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalAuditEvents) }}</div>
                <div class="mt-2 flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <span>Logs recorded</span>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        {{-- Line Chart: Review Ingestion Growth --}}
        <x-ui.card class="lg:col-span-2">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Review Ingestion (Last 6 Months)</h3>
            <div class="h-80 w-full relative">
                <canvas id="adminGrowthChart"></canvas>
            </div>
        </x-ui.card>

        {{-- Doughnut Chart: Role Distribution --}}
        <x-ui.card>
            <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">User Roles Breakdown</h3>
            <div class="h-80 w-full relative flex items-center justify-center">
                <canvas id="adminRolesChart"></canvas>
                {{-- Centered Text inside Doughnut --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-4">
                    <span class="text-3xl font-bold text-gray-900 dark:text-white">{{ number_format($totalUsers) }}</span>
                    <span class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Users</span>
                </div>
            </div>
        </x-ui.card>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        
        {{-- Recent Users Table --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Users</h3>
                <x-ui.button tag="a" href="{{ route('admin.users.index') }}" variant="ghost" size="sm">View All</x-ui.button>
            </div>
            
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <x-ui.table class="border-t border-gray-200 dark:border-gray-700">
                        <thead>
                            <x-ui.table.tr>
                                <x-ui.table.th>Name</x-ui.table.th>
                                <x-ui.table.th>Role</x-ui.table.th>
                                <x-ui.table.th class="text-right">Joined</x-ui.table.th>
                            </x-ui.table.tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentUsers as $user)
                            <x-ui.table.tr>
                                <x-ui.table.td>
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-primary-100 dark:bg-primary-900/30 flex items-center justify-center text-primary-700 dark:text-primary-400 font-bold text-xs uppercase mr-3">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $user->name }}</div>
                                            <div class="text-xs text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    @foreach($user->roles as $role)
                                        <x-ui.badge variant="{{ match($role->name) {
                                            'Super Admin', 'Admin' => 'danger',
                                            'Analyst' => 'primary',
                                            default => 'secondary'
                                        } }}">{{ $role->name }}</x-ui.badge>
                                    @endforeach
                                </x-ui.table.td>
                                <x-ui.table.td class="text-right text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $user->created_at->diffForHumans() }}
                                </x-ui.table.td>
                            </x-ui.table.tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                </div>
            </div>
        </x-ui.card>

        {{-- Recent Audit Events Table --}}
        <x-ui.card>
            <div class="flex items-center justify-between mb-4">
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">System Activity</h3>
                <x-ui.button tag="a" href="{{ route('admin.audit-logs.index') }}" variant="ghost" size="sm">View All</x-ui.button>
            </div>
            
            <div class="overflow-x-auto -mx-6">
                <div class="inline-block min-w-full align-middle">
                    <x-ui.table class="border-t border-gray-200 dark:border-gray-700">
                        <thead>
                            <x-ui.table.tr>
                                <x-ui.table.th>User</x-ui.table.th>
                                <x-ui.table.th>Action</x-ui.table.th>
                                <x-ui.table.th class="text-right">Time</x-ui.table.th>
                            </x-ui.table.tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @foreach($recentAuditEvents as $log)
                            <x-ui.table.tr>
                                <x-ui.table.td>
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->user->name ?? 'System' }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td>
                                    <x-ui.badge variant="{{ match($log->event) {
                                        'created' => 'success',
                                        'updated' => 'primary',
                                        'deleted' => 'danger',
                                        default => 'secondary'
                                    } }}">{{ ucfirst($log->event) }}</x-ui.badge>
                                    <span class="text-xs text-gray-500 dark:text-gray-400 ml-1">on {{ class_basename($log->auditable_type) }}</span>
                                </x-ui.table.td>
                                <x-ui.table.td class="text-right text-gray-500 dark:text-gray-400 text-sm">
                                    {{ $log->created_at->diffForHumans() }}
                                </x-ui.table.td>
                            </x-ui.table.tr>
                            @endforeach
                        </tbody>
                    </x-ui.table>
                </div>
            </div>
        </x-ui.card>

    </div>
</div>
@endsection
