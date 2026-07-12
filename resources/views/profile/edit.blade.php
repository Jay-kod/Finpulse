@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    <div class="mb-8 flex items-center space-x-3">
        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-indigo-600 rounded-xl flex items-center justify-center shadow-lg shadow-primary-500/20">
            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
        </div>
        <div>
            <h1 class="text-3xl font-black bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent">Profile Settings</h1>
            <p class="text-gray-500 dark:text-gray-400 mt-1 font-medium">Manage your account settings and preferences.</p>
        </div>
    </div>

    <div class="space-y-8 max-w-4xl">
        <x-ui.card glow="true" class="border-t-4 border-t-primary-500 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-primary-500/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
            <div class="relative">
                @include('profile.partials.update-profile-information-form')
            </div>
        </x-ui.card>

        <x-ui.card glow="true" class="border-t-4 border-t-emerald-500 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
            <div class="relative">
                @include('profile.partials.update-password-form')
            </div>
        </x-ui.card>

        <x-ui.card glow="true" class="border-t-4 border-t-blue-500 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-blue-500/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
            <div class="relative">
                @include('profile.partials.api-tokens-form')
            </div>
        </x-ui.card>

        <x-ui.card glow="true" class="border-t-4 border-t-red-500 relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/5 rounded-full blur-3xl -mr-32 -mt-32 pointer-events-none"></div>
            <div class="relative">
                @include('profile.partials.delete-user-form')
            </div>
        </x-ui.card>
    </div>
@endsection
