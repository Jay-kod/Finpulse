@extends('layouts.app')

@section('title', 'User Management')

@section('content')
<div class="max-w-7xl mx-auto">
    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">User Management</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage platform users, roles, and permissions.</p>
        </div>
        <div class="mt-4 sm:mt-0">
            <x-ui.button tag="a" href="{{ route('admin.users.create') }}" variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Add User
            </x-ui.button>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <x-ui.alert type="success" class="mb-4">{{ session('success') }}</x-ui.alert>
    @endif
    @if(session('error'))
        <x-ui.alert type="danger" class="mb-4">{{ session('error') }}</x-ui.alert>
    @endif

    {{-- Filters --}}
    <x-ui.card class="mb-6">
        <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <x-ui.input type="text" name="search" placeholder="Search by name or email..." value="{{ request('search') }}" />
            </div>
            <div class="w-full sm:w-48">
                <x-ui.select name="role">
                    <option value="">All Roles</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ request('role') === $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </x-ui.select>
            </div>
            <div class="flex gap-2">
                <x-ui.button type="submit" variant="primary" size="sm">Filter</x-ui.button>
                <x-ui.button tag="a" href="{{ route('admin.users.index') }}" variant="ghost" size="sm">Reset</x-ui.button>
            </div>
        </form>
    </x-ui.card>

    {{-- Users Table --}}
    <x-ui.card>
        <div class="overflow-x-auto">
            <x-ui.table>
                <thead>
                    <x-ui.table.tr>
                        <x-ui.table.th>Name</x-ui.table.th>
                        <x-ui.table.th>Email</x-ui.table.th>
                        <x-ui.table.th>Role</x-ui.table.th>
                        <x-ui.table.th>Registered</x-ui.table.th>
                        <x-ui.table.th class="text-right">Actions</x-ui.table.th>
                    </x-ui.table.tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                        <x-ui.table.tr>
                            <x-ui.table.td>
                                <div class="flex items-center">
                                    <div class="w-8 h-8 rounded-full bg-primary-500 flex items-center justify-center text-white font-bold text-sm uppercase mr-3 shrink-0">
                                        {{ substr($user->name, 0, 1) }}
                                    </div>
                                    <span class="font-medium text-gray-900 dark:text-white">{{ $user->name }}</span>
                                </div>
                            </x-ui.table.td>
                            <x-ui.table.td>{{ $user->email }}</x-ui.table.td>
                            <x-ui.table.td>
                                @php $role = $user->roles->first(); @endphp
                                @if($role)
                                    <x-ui.badge variant="{{ match($role->name) {
                                        'Super Admin' => 'danger',
                                        'Admin' => 'warning',
                                        'Analyst' => 'info',
                                        'Viewer' => 'secondary',
                                        default => 'secondary'
                                    } }}">{{ $role->name }}</x-ui.badge>
                                @else
                                    <x-ui.badge variant="secondary">No Role</x-ui.badge>
                                @endif
                            </x-ui.table.td>
                            <x-ui.table.td>{{ $user->created_at->format('M d, Y') }}</x-ui.table.td>
                            <x-ui.table.td class="text-right">
                                <div class="flex items-center justify-end space-x-2">
                                    <x-ui.button tag="a" href="{{ route('admin.users.edit', $user) }}" variant="ghost" size="sm">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                    </x-ui.button>
                                    @if($user->id !== auth()->id())
                                    <form method="POST" action="{{ route('admin.users.destroy', $user) }}" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to delete this user?', confirm: () => $el.submit() })">
                                        @csrf
                                        @method('DELETE')
                                        <x-ui.button type="submit" variant="danger" size="sm">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </x-ui.button>
                                    </form>
                                    @endif
                                </div>
                            </x-ui.table.td>
                        </x-ui.table.tr>
                    @empty
                        <x-ui.table.tr>
                            <x-ui.table.td colspan="5" class="text-center py-8">
                                <div class="text-gray-400">
                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                    <p class="text-sm">No users found matching your criteria.</p>
                                </div>
                            </x-ui.table.td>
                        </x-ui.table.tr>
                    @endforelse
                </tbody>
            </x-ui.table>
        </div>

        {{-- Pagination --}}
        @if($users->hasPages())
            <div class="mt-4 border-t border-gray-100 dark:border-gray-700 pt-4">
                {{ $users->links() }}
            </div>
        @endif
    </x-ui.card>
</div>
@endsection
