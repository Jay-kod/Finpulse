<section class="space-y-6">
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            Delete Account
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </p>
    </header>

    <div x-data>
        <x-ui.button variant="danger" x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')">
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
