@extends('layouts.app')

@section('title', 'Create User')

@section('content')
<div class="max-w-2xl mx-auto">
    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center text-sm text-primary-600 hover:text-primary-700 dark:text-primary-400 mb-2 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Users
        </a>
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Create New User</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Add a new user to the platform and assign a role.</p>
    </div>

    <x-ui.card>
        <form method="POST" action="{{ route('admin.users.store') }}">
            @csrf

            <x-ui.form-group label="Full Name" for="name">
                <x-ui.input id="name" name="name" type="text" :value="old('name')" required autofocus placeholder="Enter full name" />
                <x-ui.error for="name" />
            </x-ui.form-group>

            <x-ui.form-group label="Email Address" for="email">
                <x-ui.input id="email" name="email" type="email" :value="old('email')" required placeholder="user@example.com" />
                <x-ui.error for="email" />
            </x-ui.form-group>

            <x-ui.form-group label="Password" for="password">
                <x-ui.input id="password" name="password" type="password" required placeholder="Minimum 8 characters" />
                <x-ui.error for="password" />
            </x-ui.form-group>

            <x-ui.form-group label="Confirm Password" for="password_confirmation">
                <x-ui.input id="password_confirmation" name="password_confirmation" type="password" required placeholder="Re-enter password" />
            </x-ui.form-group>

            <x-ui.form-group label="Role" for="role">
                <x-ui.select id="role" name="role" required>
                    <option value="">Select a role...</option>
                    @foreach($roles as $role)
                        <option value="{{ $role }}" {{ old('role') === $role ? 'selected' : '' }}>{{ $role }}</option>
                    @endforeach
                </x-ui.select>
                <x-ui.error for="role" />
            </x-ui.form-group>

            <div class="flex items-center justify-end space-x-3 pt-4 border-t border-gray-100 dark:border-gray-700 mt-6">
                <x-ui.button tag="a" href="{{ route('admin.users.index') }}" variant="ghost">Cancel</x-ui.button>
                <x-ui.button type="submit" variant="primary">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Create User
                </x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>
@endsection
