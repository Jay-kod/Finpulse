<section>
    <header class="mb-6">
        <h2 class="text-xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-white dark:to-gray-400 bg-clip-text text-transparent flex items-center">
            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path></svg>
            {{ __('API Tokens') }}
        </h2>

        <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 font-medium">
            {{ __('API tokens allow third-party services to authenticate with our application on your behalf.') }}
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

        <div class="flex items-center gap-4 pt-4 border-t border-gray-100 dark:border-gray-800">
            <x-ui.button type="submit" variant="primary" class="bg-blue-600 hover:bg-blue-700 focus:ring-blue-500 shadow-lg shadow-blue-500/30">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                {{ __('Create Token') }}
            </x-ui.button>
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
