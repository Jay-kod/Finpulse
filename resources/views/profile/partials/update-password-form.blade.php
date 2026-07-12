<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent flex items-center">
            <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
            Update Password
        </h2>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
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

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
            <x-ui.button type="submit" variant="primary" class="bg-emerald-600 hover:bg-emerald-700 focus:ring-emerald-500 shadow-lg shadow-emerald-500/30">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                Change Password
            </x-ui.button>

            @if (session('status') === 'password-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-x-2"
                    x-transition:enter-end="opacity-100 translate-x-0"
                    x-transition:leave="transition ease-in duration-300"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-emerald-600 dark:text-emerald-400 flex items-center bg-emerald-50 dark:bg-emerald-500/10 px-3 py-1.5 rounded-full"
                >
                    <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Password updated successfully.
                </p>
            @endif
        </div>
    </form>
</section>
