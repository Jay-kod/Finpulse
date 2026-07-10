<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('API Tokens') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Manage personal access tokens to interact with the platform\'s REST API.') }}
        </p>
    </header>

    @if (session('flash') && isset(session('flash')['plain_text_token']))
        <div class="mt-4 p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
            <span class="font-medium">Success!</span> {{ session('flash')['message'] }}
            <div class="mt-2 p-2 bg-gray-100 dark:bg-gray-900 rounded font-mono break-all">
                {{ session('flash')['plain_text_token'] }}
            </div>
        </div>
    @endif

    <form method="post" action="{{ route('api-tokens.store') }}" class="mt-6 space-y-6">
        @csrf

        <x-ui.form-group label="Token Name" for="token_name">
            <x-ui.input id="token_name" name="token_name" type="text" class="mt-1 block w-full sm:w-1/2" placeholder="e.g. Jenkins CI, Local Dev" required />
            <x-ui.error for="token_name" />
        </x-ui.form-group>

        <div class="flex items-center gap-4">
            <x-ui.button type="submit" variant="primary">{{ __('Create Token') }}</x-ui.button>
        </div>
    </form>

    @if(auth()->user()->tokens->count() > 0)
        <div class="mt-8 border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-md font-medium text-gray-900 dark:text-gray-100 mb-4">Active Tokens</h3>
            <div class="space-y-4">
                @foreach(auth()->user()->tokens as $token)
                    <div class="flex items-center justify-between p-4 bg-gray-50 dark:bg-gray-800 rounded-lg">
                        <div>
                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $token->name }}</div>
                            <div class="text-xs text-gray-500 mt-1">
                                Created: {{ $token->created_at->diffForHumans() }}
                                @if($token->last_used_at)
                                    &bull; Last used: {{ $token->last_used_at->diffForHumans() }}
                                @endif
                            </div>
                        </div>
                        <form method="post" action="{{ route('api-tokens.destroy', $token->id) }}" x-data @submit.prevent="$dispatch('open-confirm', { message: 'Are you sure you want to revoke this token?', confirm: () => $el.submit() })">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-sm text-red-600 hover:text-red-900 dark:text-red-500 dark:hover:text-red-400">
                                Revoke
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
    @endif
</section>
