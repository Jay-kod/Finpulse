@extends('layouts.app')

@section('title', 'Saved Reports')

@section('content')
<div class="max-w-7xl mx-auto animate-fade-in">
    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4 bg-white/50 dark:bg-gray-800/50 backdrop-blur-xl p-6 rounded-3xl border border-gray-100 dark:border-gray-700 shadow-sm">
        <div>
            <h1 class="text-3xl font-black bg-clip-text text-transparent bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-300 tracking-tight">Saved Reports</h1>
            <p class="mt-2 text-sm font-medium text-gray-500 dark:text-gray-400 tracking-wide">Manage and view custom analytics configurations.</p>
        </div>
        <div class="flex items-center gap-3">
            @if(auth()->user()->hasRole(['Analyst', 'Admin', 'Super Admin']))
            <x-ui.button variant="primary" href="{{ route('analyst.reports.create') }}" class="rounded-2xl shadow-md hover:shadow-lg transition-all duration-300 hover:-translate-y-0.5">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Create Report
            </x-ui.button>
            @endif
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6">
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        </div>
    @endif

    @if($reports->isEmpty())
        <!-- Premium Empty State -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 p-12 text-center relative overflow-hidden group">
            <div class="absolute inset-0 bg-gradient-to-br from-primary-50/50 to-transparent dark:from-primary-900/10 dark:to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
            
            <div class="relative z-10 flex flex-col items-center justify-center">
                <div class="w-24 h-24 mb-6 rounded-full bg-primary-50 dark:bg-primary-900/30 flex items-center justify-center border border-primary-100 dark:border-primary-800/50">
                    <svg class="w-12 h-12 text-primary-500 dark:text-primary-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-2">No Reports Generated Yet</h3>
                <p class="text-gray-500 dark:text-gray-400 max-w-md mx-auto mb-8 leading-relaxed">You haven't saved any custom analytics reports. Create a new report to save your favorite filters and insights for quick access later.</p>
                
                @if(auth()->user()->hasRole(['Analyst', 'Admin', 'Super Admin']))
                <x-ui.button variant="primary" href="{{ route('analyst.reports.create') }}" class="rounded-xl shadow-md hover:shadow-lg transition-all duration-300">
                    Create Your First Report
                </x-ui.button>
                @endif
            </div>
        </div>
    @else
        <!-- Premium Table State -->
        <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50/50 dark:bg-gray-800/50">
                        <tr>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Report Title</th>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Filters Configured</th>
                            <th scope="col" class="px-6 py-4 text-left text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Author</th>
                            <th scope="col" class="px-6 py-4 text-right text-[11px] font-bold text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($reports as $report)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition-colors duration-200 group">
                                <td class="px-6 py-5 whitespace-nowrap">
                                    @php
                                        $showRoute = request()->routeIs('viewer.*') 
                                            ? route('viewer.reports.show', $report) 
                                            : route('analyst.reports.show', $report);
                                    @endphp
                                    <div class="flex flex-col">
                                        <a href="{{ $showRoute }}" class="text-sm font-bold text-gray-900 dark:text-white group-hover:text-primary-600 dark:group-hover:text-primary-400 transition-colors">
                                            {{ $report->title }}
                                        </a>
                                        <span class="text-xs text-gray-500 dark:text-gray-400 mt-1 truncate max-w-xs">{{ $report->description ?? 'No description provided' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5">
                                    <div class="flex flex-wrap gap-2">
                                        @if(empty($report->parameters))
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-gray-100 text-gray-600 dark:bg-gray-700 dark:text-gray-300 uppercase tracking-wide">Global (No Filters)</span>
                                        @else
                                            @if(!empty($report->parameters['app_id']))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-primary-50 text-primary-700 dark:bg-primary-900/30 dark:text-primary-300 border border-primary-100 dark:border-primary-800 uppercase tracking-wide">
                                                    App: {{ $report->parameters['app_id'] }}
                                                </span>
                                            @endif
                                            @if(!empty($report->parameters['start_date']))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-accent-50 text-accent-700 dark:bg-accent-900/30 dark:text-accent-300 border border-accent-100 dark:border-accent-800 uppercase tracking-wide">
                                                    From: {{ $report->parameters['start_date'] }}
                                                </span>
                                            @endif
                                            @if(!empty($report->parameters['end_date']))
                                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-accent-50 text-accent-700 dark:bg-accent-900/30 dark:text-accent-300 border border-accent-100 dark:border-accent-800 uppercase tracking-wide">
                                                    To: {{ $report->parameters['end_date'] }}
                                                </span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-primary-400 to-accent-500 flex items-center justify-center text-white text-xs font-bold mr-3 shadow-sm">
                                            {{ substr($report->user->name ?? 'U', 0, 1) }}
                                        </div>
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">{{ $report->user->name ?? 'Unknown' }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-5 whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400 font-medium">
                                    {{ $report->created_at->format('M d, Y') }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
