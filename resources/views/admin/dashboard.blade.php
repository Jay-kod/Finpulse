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
<div class="max-w-7xl mx-auto animate-fade-in relative">
    {{-- Background Decorative Effects --}}
    <div class="absolute -top-24 -left-24 w-96 h-96 bg-primary-500/10 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute top-1/3 -right-24 w-80 h-80 bg-purple-500/10 rounded-full blur-3xl pointer-events-none"></div>

    {{-- Header --}}
    <div class="mb-8 relative z-10 bg-white/60 dark:bg-gray-800/60 backdrop-blur-xl p-6 sm:p-8 rounded-3xl border border-gray-100/50 dark:border-gray-700/50 shadow-sm overflow-hidden group">
        <div class="absolute -right-10 -top-10 opacity-[0.03] pointer-events-none transition-transform group-hover:scale-110 duration-700">
            <svg class="w-64 h-64 text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
        </div>
        <div class="relative z-10">
            <h1 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-500 dark:from-white dark:to-gray-400 tracking-tight">Admin Control Center</h1>
            <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400">Welcome back, {{ Auth::user()->name }}! Here's an overview of the system status.</p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6 mb-8 relative z-10">
        {{-- Total Users --}}
        <div class="group relative overflow-hidden bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100/50 dark:border-gray-700/50 p-6 hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-blue-500" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Users</h3>
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-blue-400 to-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </div>
                </div>
                <div>
                    <div class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($totalUsers) }}</div>
                    <div class="mt-3 flex items-center text-[11px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-500/10 inline-flex px-2 py-1 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span>{{ number_format($newUsersThisMonth) }} new this month</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tracked Apps --}}
        <div class="group relative overflow-hidden bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100/50 dark:border-gray-700/50 p-6 hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M17 1H7c-1.1 0-2 .9-2 2v18c0 1.1.9 2 2 2h10c1.1 0 2-.9 2-2V3c0-1.1-.9-2-2-2zm0 18H7V5h10v14zM8 6h8v2H8V6zm0 4h8v2H8v-2zm0 4h8v2H8v-2z"/></svg>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Tracked Apps</h3>
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white shadow-lg shadow-emerald-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
                <div>
                    <div class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($totalApps) }}</div>
                    <div class="mt-3 flex items-center text-[11px] font-bold text-gray-500 dark:text-gray-400 bg-gray-500/10 inline-flex px-2 py-1 rounded-full">
                        <span class="text-emerald-600 dark:text-emerald-400 mr-1">{{ $activeApps }}</span> active processing
                    </div>
                </div>
            </div>
        </div>

        {{-- Processed Reviews --}}
        <div class="group relative overflow-hidden bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100/50 dark:border-gray-700/50 p-6 hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-purple-500" fill="currentColor" viewBox="0 0 24 24"><path d="M21 3H3c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h18c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm0 16H3V5h18v14zM5 15h14v2H5v-2zm0-4h14v2H5v-2zm0-4h14v2H5V7z"/></svg>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total Reviews</h3>
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-purple-500 to-indigo-600 flex items-center justify-center text-white shadow-lg shadow-purple-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
                    </div>
                </div>
                <div>
                    <div class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($totalReviews) }}</div>
                    <div class="mt-3 flex items-center text-[11px] font-bold text-gray-500 dark:text-gray-400 bg-gray-500/10 inline-flex px-2 py-1 rounded-full">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                        <span>System-wide total</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Audit Events --}}
        <div class="group relative overflow-hidden bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100/50 dark:border-gray-700/50 p-6 hover:-translate-y-1.5 hover:shadow-xl transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                <svg class="w-24 h-24 text-amber-500" fill="currentColor" viewBox="0 0 24 24"><path d="M11 15h2v2h-2zm0-8h2v6h-2zm.99-5C6.47 2 2 6.48 2 12s4.47 10 9.99 10C17.52 22 22 17.52 22 12S17.52 2 11.99 2zM12 20c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"/></svg>
            </div>
            <div class="relative z-10 flex flex-col h-full justify-between">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xs font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Audit Events</h3>
                    <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white shadow-lg shadow-amber-500/30">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                </div>
                <div>
                    <div class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($totalAuditEvents) }}</div>
                    <div class="mt-3 flex items-center text-[11px] font-bold text-gray-500 dark:text-gray-400 bg-gray-500/10 inline-flex px-2 py-1 rounded-full">
                        <span>Logs securely recorded</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8 relative z-10">
        {{-- Line Chart: Review Ingestion Growth --}}
        <div class="lg:col-span-2 bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100/50 dark:border-gray-700/50 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">Review Ingestion (Last 6 Months)</h3>
            <div class="h-80 w-full relative">
                <canvas id="adminGrowthChart"></canvas>
            </div>
        </div>

        {{-- Doughnut Chart: Role Distribution --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100/50 dark:border-gray-700/50 p-6 sm:p-8">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-6">User Roles Breakdown</h3>
            <div class="h-80 w-full relative flex items-center justify-center">
                <canvas id="adminRolesChart"></canvas>
                {{-- Centered Text inside Doughnut --}}
                <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none pb-4">
                    <span class="text-4xl font-black text-gray-900 dark:text-white tracking-tight">{{ number_format($totalUsers) }}</span>
                    <span class="text-[10px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-widest mt-1">Total Users</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Tables Row --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8 relative z-10">
        
        {{-- Recent Users Table --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100/50 dark:border-gray-700/50 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-gray-100/50 dark:border-gray-700/50 flex items-center justify-between bg-white/30 dark:bg-gray-800/30">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">Recent Users</h3>
                <a href="{{ route('admin.users.index') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors uppercase tracking-wide">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100/50 dark:border-gray-700/50 text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold bg-gray-50/30 dark:bg-gray-900/10">
                            <th class="py-3 px-6 sm:px-8">User Details</th>
                            <th class="py-3 px-6 sm:px-8">Role</th>
                            <th class="py-3 px-6 sm:px-8 text-right">Joined</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/50 dark:divide-gray-700/50">
                        @foreach($recentUsers as $user)
                        <tr class="hover:bg-white/40 dark:hover:bg-gray-800/40 transition-colors group">
                            <td class="py-4 px-6 sm:px-8 whitespace-nowrap">
                                <div class="flex items-center gap-4">
                                    <div class="h-10 w-10 rounded-full bg-gradient-to-br from-primary-400 to-indigo-500 flex items-center justify-center text-white font-black shadow-md shadow-primary-500/20 group-hover:scale-105 transition-transform">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $user->name }}</div>
                                        <div class="text-[11px] font-medium text-gray-500 dark:text-gray-400">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-4 px-6 sm:px-8 whitespace-nowrap">
                                <div class="flex flex-wrap gap-1.5">
                                    @foreach($user->roles as $role)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                            {{ match($role->name) {
                                                'Super Admin', 'Admin' => 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20',
                                                'Analyst' => 'bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 border border-indigo-500/20',
                                                default => 'bg-gray-500/10 text-gray-600 dark:text-gray-400 border border-gray-500/20'
                                            } }}">
                                            {{ $role->name }}
                                        </span>
                                    @endforeach
                                </div>
                            </td>
                            <td class="py-4 px-6 sm:px-8 whitespace-nowrap text-right text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        {{-- Recent Audit Events Table --}}
        <div class="bg-white/70 dark:bg-gray-800/70 backdrop-blur-xl rounded-3xl shadow-sm border border-gray-100/50 dark:border-gray-700/50 overflow-hidden">
            <div class="p-6 sm:p-8 border-b border-gray-100/50 dark:border-gray-700/50 flex items-center justify-between bg-white/30 dark:bg-gray-800/30">
                <h3 class="text-lg font-bold text-gray-900 dark:text-white">System Activity</h3>
                <a href="{{ route('admin.audit-logs.index') }}" class="text-xs font-bold text-primary-600 dark:text-primary-400 hover:text-primary-700 dark:hover:text-primary-300 transition-colors uppercase tracking-wide">View All</a>
            </div>
            
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100/50 dark:border-gray-700/50 text-[10px] uppercase tracking-widest text-gray-400 dark:text-gray-500 font-bold bg-gray-50/30 dark:bg-gray-900/10">
                            <th class="py-3 px-6 sm:px-8">User</th>
                            <th class="py-3 px-6 sm:px-8">Action Taken</th>
                            <th class="py-3 px-6 sm:px-8 text-right">Time</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100/50 dark:divide-gray-700/50">
                        @foreach($recentAuditEvents as $log)
                        <tr class="hover:bg-white/40 dark:hover:bg-gray-800/40 transition-colors group">
                            <td class="py-4 px-6 sm:px-8 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <div class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center text-gray-600 dark:text-gray-300 text-xs font-bold group-hover:scale-105 transition-transform">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                                    </div>
                                    <span class="text-sm font-bold text-gray-900 dark:text-white">{{ $log->user->name ?? 'System' }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 sm:px-8 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide
                                    {{ match($log->event) {
                                        'created' => 'bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-500/20',
                                        'updated' => 'bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-500/20',
                                        'deleted' => 'bg-red-500/10 text-red-600 dark:text-red-400 border border-red-500/20',
                                        default => 'bg-gray-500/10 text-gray-600 dark:text-gray-400 border border-gray-500/20'
                                    } }}">
                                    {{ ucfirst($log->event) }}
                                </span>
                                <span class="text-[11px] font-medium text-gray-500 dark:text-gray-400 ml-2">on {{ class_basename($log->auditable_type) }}</span>
                            </td>
                            <td class="py-4 px-6 sm:px-8 whitespace-nowrap text-right text-[11px] font-medium text-gray-500 dark:text-gray-400">
                                {{ $log->created_at->diffForHumans() }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
