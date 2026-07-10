@extends('layouts.app')

@section('title', 'Edit User — ' . $user->name)

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Users
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Edit User</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Update the details for <strong>{{ $user->name }}</strong>.</p>
    </div>

    {{-- Edit Form --}}
    <x-ui.card class="mb-6">
        <form method="POST" action="{{ route('admin.users.update', $user) }}">
            @csrf
            @method('PUT')

            <x-ui.form-group label="Full Name" for="name">
                <x-ui.input id="name" name="name" type="text" :value="old('name', $user->name)" required placeholder="Enter full name" />
                <x-ui.error for="name" />
            </x-ui.form-group>

            <x-ui.form-group label="Email Address" for="email">
                <x-ui.input id="email" name="email" type="email" :value="old('email', $user->email)" required placeholder="user@example.com" />
                <x-ui.error for="email" />
            </x-ui.form-group>

            <x-ui.form-group label="New Password" for="password" help="Leave blank to keep the current password.">
                <x-ui.input id="password" name="password" type="password" placeholder="Enter new password (optional)" />
                <x-ui.error for="password" />
            </x-ui.form-group>

            <x-ui.form-group label="Confirm New Password" for="password_confirmation">
                <x-ui.input id="password_confirmation" name="password_confirmation" type="password" placeholder="Re-enter new password" />
            </x-ui.form-group>

            <x-ui.form-group label="Role" for="role">
                <x-ui.select id="role" name="role" required>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role', $userRole) === $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.error for="role" />
            </x-ui.form-group>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                <x-ui.button tag="a" href="{{ route('admin.users.index') }}" variant="ghost">Cancel</x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Save Changes
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>

    {{-- Danger Zone --}}
    @if($user->id !== auth()->id())
    <x-ui.card class="border-red-200 dark:border-red-800">
        <h3 class="text-lg font-semibold text-red-600 dark:text-red-400 mb-2">Danger Zone</h3>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
            Permanently delete this user account. This action cannot be undone.
        </p>
        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you absolutely sure you want to delete {{ $user->name }}? This cannot be undone.', confirm: () => $el.submit() })">
            @csrf
            @method('DELETE')
            <x-ui.button type="submit" variant="danger">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                Delete User
            </x-ui.button>
        </form>
    </x-ui.card>
    @endif
</div>
@endsection
