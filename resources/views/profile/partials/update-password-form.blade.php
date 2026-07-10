<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Update Password
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Ensure your account is using a long, random password to stay secure.
        </p>
    </header>

    <form method="POST" action="{{ route('password.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

        <div class="max-w-xl">
            <x-ui.form-group label="Current Password" for="update_password_current_password">
                <x-ui.input 
                    id="update_password_current_password" 
                    name="current_password" 
                    type="password" 
                    autocomplete="current-password"
                    :error="$errors->updatePassword->has('current_password')" 
                />
                <x-ui.error :messages="$errors->updatePassword->get('current_password')" class="mt-2" />
            </x-ui.form-group>

            <x-ui.form-group label="New Password" for="update_password_password">
                <x-ui.input 
                    id="update_password_password" 
                    name="password" 
                    type="password" 
                    autocomplete="new-password"
                    :error="$errors->updatePassword->has('password')" 
                />
                <x-ui.error :messages="$errors->updatePassword->get('password')" class="mt-2" />
            </x-ui.form-group>

            <x-ui.form-group label="Confirm Password" for="update_password_password_confirmation">
                <x-ui.input 
                    id="update_password_password_confirmation" 
                    name="password_confirmation" 
                    type="password" 
                    autocomplete="new-password"
                    :error="$errors->updatePassword->has('password_confirmation')" 
                />
                <x-ui.error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2" />
            </x-ui.form-group>
        </div>

        <div class="flex items-center gap-4">
            <x-ui.button type="submit" variant="primary">Save</x-ui.button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >Saved.</p>
            @endif
        </div>
    </form>
</section>
