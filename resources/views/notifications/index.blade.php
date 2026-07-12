@extends('layouts.app')

@section('title', 'Notifications')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Notifications</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Stay updated with pipeline completions, alerts, and system events.
            </p>
        </div>

        @if(auth()->user()->unreadNotifications()->exists())
            <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                @csrf
                <x-ui.button type="submit" variant="secondary" class="whitespace-nowrap">
                    <svg class="w-4 h-4 mr-1.5 -ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Mark All as Read
                </x-ui.button>
            </form>
        @endif
    </div>

    @if(session('success'))
        <div class="mb-6">
            <x-ui.alert type="success">{{ session('success') }}</x-ui.alert>
        </div>
    @endif

    {{-- Notification List --}}
    <div class="space-y-3">
        @forelse($notifications as $notification)
            @php
                $data = $notification->data;
                $isUnread = is_null($notification->read_at);

                // Determine icon and color based on notification type
                $iconType = $data['type'] ?? 'info';
                $iconColor = match($data['color'] ?? 'blue') {
                    'green'  => 'text-green-500 bg-green-100 dark:bg-green-900/30',
                    'red'    => 'text-red-500 bg-red-100 dark:bg-red-900/30',
                    'yellow' => 'text-yellow-500 bg-yellow-100 dark:bg-yellow-900/30',
                    default  => 'text-blue-500 bg-blue-100 dark:bg-blue-900/30',
                };
                $iconPath = match($data['icon'] ?? 'bell') {
                    'check-circle'        => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z',
                    'exclamation-triangle' => 'M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4.5c-.77-.833-2.694-.833-3.464 0L3.34 16.5c-.77.833.192 2.5 1.732 2.5z',
                    default               => 'M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9',
                };
            @endphp

            <x-ui.card class="relative transition-all duration-200 hover:shadow-md {{ $isUnread ? 'border-l-4 border-l-primary-500 bg-primary-50/30 dark:bg-primary-900/10' : 'opacity-75 hover:opacity-100' }}">
                <div class="flex items-start gap-4">
                    {{-- Icon --}}
                    <div class="flex-shrink-0 w-10 h-10 rounded-full flex items-center justify-center {{ $iconColor }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}"></path>
                        </svg>
                    </div>

                    {{-- Content --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-sm font-semibold text-gray-900 dark:text-white truncate">
                                {{ $data['title'] ?? 'Notification' }}
                            </h3>
                            @if($isUnread)
                                <span class="flex-shrink-0 w-2 h-2 rounded-full bg-primary-500 animate-pulse"></span>
                            @endif
                        </div>
                        <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">
                            {{ $data['message'] ?? '' }}
                        </p>
                        <span class="inline-block mt-2 text-xs text-gray-400 dark:text-gray-500">
                            {{ $notification->created_at->diffForHumans() }}
                        </span>
                    </div>

                    {{-- Actions --}}
                    <div class="flex items-center gap-1 flex-shrink-0">
                        @if($isUnread)
                            <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="p-2 text-gray-400 hover:text-green-600 dark:hover:text-green-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Mark as read">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                </button>
                            </form>
                        @endif

                        <form action="{{ route('notifications.destroy', $notification->id) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="p-2 text-gray-400 hover:text-red-600 dark:hover:text-red-400 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors" title="Delete notification">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </x-ui.card>
        @empty
            <x-ui.card class="text-center py-12">
                <div class="flex flex-col items-center">
                    <div class="w-16 h-16 rounded-full bg-gray-100 dark:bg-gray-800 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-1">All caught up!</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400">You have no notifications right now.</p>
                </div>
            </x-ui.card>
        @endforelse
    </div>

    {{-- Pagination --}}
    @if($notifications->hasPages())
        <div class="mt-6">
            {{ $notifications->links() }}
        </div>
    @endif
</div>
@endsection
