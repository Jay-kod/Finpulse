<section class="space-y-6">
    <header class="mb-6">
        <h2 class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent flex items-center">
            <svg class="w-5 h-5 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Delete Account
        </h2>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <div x-data class="pt-4 border-t border-gray-100 dark:border-gray-800">
        <x-ui.button variant="danger" class="shadow-lg shadow-red-500/30" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            Delete Account
        </x-ui.button>
    </div>

    <x-ui.modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}">
            @csrf
            @method('delete')

            <x-slot name="title">Are you sure you want to delete your account?</x-slot>

            <p class="text-sm text-gray-500 dark:text-gray-400 mb-6">
                Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.
            </p>

            <x-ui.form-group for="password">
                <x-ui.input
                    id="password"
                    name="password"
                    type="password"
                    class="w-full sm:w-3/4"
                    placeholder="Password"
                    :error="$errors->userDeletion->has('password')"
                />
                <x-ui.error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </x-ui.form-group>

            <x-slot name="footer">
                <x-ui.button variant="ghost" x-on:click="$dispatch('close-modal', 'confirm-user-deletion')">
                    Cancel
                </x-ui.button>
                <x-ui.button type="submit" variant="danger">
                    Delete Account
                </x-ui.button>
            </x-slot>
        </form>
    </x-ui.modal>
</section>
