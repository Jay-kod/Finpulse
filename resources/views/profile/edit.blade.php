@extends('layouts.app')

@section('title', '| Profile')

@section('content')
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Profile</h1>
        <p class="text-gray-600 dark:text-gray-400 mt-1">Manage your account settings and preferences.</p>
    </div>

    <div class="space-y-6 max-w-4xl">
        <x-ui.card>
            @include('profile.partials.update-profile-information-form')
        </x-ui.card>

        <x-ui.card>
            @include('profile.partials.update-password-form')
        </x-ui.card>

        <x-ui.card>
            @include('profile.partials.delete-user-form')
        </x-ui.card>

        <x-ui.card>
            @include('profile.partials.api-tokens-form')
        </x-ui.card>
    </div>
@endsection
