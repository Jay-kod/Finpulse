@extends('layouts.app')

@section('title', 'Platform Settings')

@section('content')
<div class="max-w-4xl mx-auto">
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Platform Settings</h1>
        <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Manage global configurations, NLP parameters, and external API integrations.</p>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <x-ui.alert type="success" class="mb-6">{{ session('success') }}</x-ui.alert>
    @endif
    @if(session('error'))
        <x-ui.alert type="danger" class="mb-6">{{ session('error') }}</x-ui.alert>
    @endif

    <form method="POST" action="{{ route('admin.settings.update') }}">
        @csrf

        <div class="space-y-8">
            {{-- Section 1: General Settings --}}
            <x-ui.card>
                <div class="mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-primary-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        General Configuration
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Basic platform identifiers and support contact info.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.form-group label="Platform Name" for="platform_name">
                        <x-ui.input id="platform_name" name="platform_name" type="text" :value="old('platform_name', $settings['platform_name'] ?? '')" required />
                        <x-ui.error for="platform_name" />
                    </x-ui.form-group>

                    <x-ui.form-group label="Support Email" for="support_email">
                        <x-ui.input id="support_email" name="support_email" type="email" :value="old('support_email', $settings['support_email'] ?? '')" required />
                        <x-ui.error for="support_email" />
                    </x-ui.form-group>
                </div>
            </x-ui.card>

            {{-- Section 2: NLP & Analysis --}}
            <x-ui.card>
                <div class="mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path></svg>
                        NLP & Analysis Preferences
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Defaults for sentiment analysis and text processing.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <x-ui.form-group label="Default Analysis Language" for="default_language">
                        <x-ui.select id="default_language" name="default_language" required>
                            <option value="en" {{ old('default_language', $settings['default_language'] ?? 'en') == 'en' ? 'selected' : '' }}>English</option>
                            <option value="fr" {{ old('default_language', $settings['default_language'] ?? '') == 'fr' ? 'selected' : '' }}>French</option>
                            <option value="ha" {{ old('default_language', $settings['default_language'] ?? '') == 'ha' ? 'selected' : '' }}>Hausa</option>
                            <option value="yo" {{ old('default_language', $settings['default_language'] ?? '') == 'yo' ? 'selected' : '' }}>Yoruba</option>
                            <option value="ig" {{ old('default_language', $settings['default_language'] ?? '') == 'ig' ? 'selected' : '' }}>Igbo</option>
                        </x-ui.select>
                        <x-ui.error for="default_language" />
                    </x-ui.form-group>

                    <x-ui.form-group label="Sentiment Sensitivity Threshold" for="sentiment_sensitivity" help="Value between 0 (strict) and 1 (lenient).">
                        <x-ui.input id="sentiment_sensitivity" name="sentiment_sensitivity" type="number" step="0.1" min="0" max="1" :value="old('sentiment_sensitivity', $settings['sentiment_sensitivity'] ?? 0.5)" required />
                        <x-ui.error for="sentiment_sensitivity" />
                    </x-ui.form-group>
                </div>
            </x-ui.card>

            {{-- Section 3: API Integration --}}
            <x-ui.card>
                <div class="mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path></svg>
                        API Integrations
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage external service credentials for advanced NLP processing.</p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                    <x-ui.form-group label="HuggingFace API Key" for="huggingface_api_key">
                        <x-ui.input id="huggingface_api_key" name="huggingface_api_key" type="password" :value="old('huggingface_api_key', $settings['huggingface_api_key'] ?? '')" placeholder="hf_xxxxxxxxxxxxxxxxxxxx" />
                        <x-ui.error for="huggingface_api_key" />
                    </x-ui.form-group>

                    <x-ui.form-group label="OpenAI API Key" for="openai_api_key">
                        <x-ui.input id="openai_api_key" name="openai_api_key" type="password" :value="old('openai_api_key', $settings['openai_api_key'] ?? '')" placeholder="sk-xxxxxxxxxxxxxxxxxxxx" />
                        <x-ui.error for="openai_api_key" />
                    </x-ui.form-group>
                </div>
            </x-ui.card>

            {{-- Section 4: Theme & Branding --}}
            <x-ui.card>
                <div class="mb-4 pb-4 border-b border-gray-100 dark:border-gray-700">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white flex items-center">
                        <svg class="w-5 h-5 mr-2 text-pink-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                        Theme & Branding
                    </h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Configure the global color palette for the platform. These colors dynamically update all charts and UI elements.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <x-ui.form-group label="Primary Color (Trust)" for="theme_primary">
                        <div class="flex items-center space-x-2">
                            <input type="color" id="theme_primary_picker" class="h-10 w-10 p-1 border border-gray-200 rounded cursor-pointer" value="{{ old('theme_primary', $settings['theme_primary'] ?? '#1E3A8A') }}" oninput="document.getElementById('theme_primary').value = this.value">
                            <x-ui.input id="theme_primary" name="theme_primary" type="text" :value="old('theme_primary', $settings['theme_primary'] ?? '#1E3A8A')" placeholder="#1E3A8A" required class="font-mono uppercase uppercase" oninput="document.getElementById('theme_primary_picker').value = this.value" />
                        </div>
                        <x-ui.error for="theme_primary" />
                    </x-ui.form-group>

                    <x-ui.form-group label="Accent Color" for="theme_accent">
                        <div class="flex items-center space-x-2">
                            <input type="color" id="theme_accent_picker" class="h-10 w-10 p-1 border border-gray-200 rounded cursor-pointer" value="{{ old('theme_accent', $settings['theme_accent'] ?? '#6366F1') }}" oninput="document.getElementById('theme_accent').value = this.value">
                            <x-ui.input id="theme_accent" name="theme_accent" type="text" :value="old('theme_accent', $settings['theme_accent'] ?? '#6366F1')" placeholder="#6366F1" required class="font-mono uppercase" oninput="document.getElementById('theme_accent_picker').value = this.value" />
                        </div>
                        <x-ui.error for="theme_accent" />
                    </x-ui.form-group>

                    <x-ui.form-group label="Background Color" for="theme_bg">
                        <div class="flex items-center space-x-2">
                            <input type="color" id="theme_bg_picker" class="h-10 w-10 p-1 border border-gray-200 rounded cursor-pointer" value="{{ old('theme_bg', $settings['theme_bg'] ?? '#F8FAFC') }}" oninput="document.getElementById('theme_bg').value = this.value">
                            <x-ui.input id="theme_bg" name="theme_bg" type="text" :value="old('theme_bg', $settings['theme_bg'] ?? '#F8FAFC')" placeholder="#F8FAFC" required class="font-mono uppercase" oninput="document.getElementById('theme_bg_picker').value = this.value" />
                        </div>
                        <x-ui.error for="theme_bg" />
                    </x-ui.form-group>

                    <x-ui.form-group label="Positive Sentiment" for="theme_positive">
                        <div class="flex items-center space-x-2">
                            <input type="color" id="theme_positive_picker" class="h-10 w-10 p-1 border border-gray-200 rounded cursor-pointer" value="{{ old('theme_positive', $settings['theme_positive'] ?? '#16A34A') }}" oninput="document.getElementById('theme_positive').value = this.value">
                            <x-ui.input id="theme_positive" name="theme_positive" type="text" :value="old('theme_positive', $settings['theme_positive'] ?? '#16A34A')" placeholder="#16A34A" required class="font-mono uppercase" oninput="document.getElementById('theme_positive_picker').value = this.value" />
                        </div>
                        <x-ui.error for="theme_positive" />
                    </x-ui.form-group>

                    <x-ui.form-group label="Negative Sentiment" for="theme_negative">
                        <div class="flex items-center space-x-2">
                            <input type="color" id="theme_negative_picker" class="h-10 w-10 p-1 border border-gray-200 rounded cursor-pointer" value="{{ old('theme_negative', $settings['theme_negative'] ?? '#DC2626') }}" oninput="document.getElementById('theme_negative').value = this.value">
                            <x-ui.input id="theme_negative" name="theme_negative" type="text" :value="old('theme_negative', $settings['theme_negative'] ?? '#DC2626')" placeholder="#DC2626" required class="font-mono uppercase" oninput="document.getElementById('theme_negative_picker').value = this.value" />
                        </div>
                        <x-ui.error for="theme_negative" />
                    </x-ui.form-group>

                    <x-ui.form-group label="Neutral Sentiment" for="theme_neutral">
                        <div class="flex items-center space-x-2">
                            <input type="color" id="theme_neutral_picker" class="h-10 w-10 p-1 border border-gray-200 rounded cursor-pointer" value="{{ old('theme_neutral', $settings['theme_neutral'] ?? '#CA8A04') }}" oninput="document.getElementById('theme_neutral').value = this.value">
                            <x-ui.input id="theme_neutral" name="theme_neutral" type="text" :value="old('theme_neutral', $settings['theme_neutral'] ?? '#CA8A04')" placeholder="#CA8A04" required class="font-mono uppercase" oninput="document.getElementById('theme_neutral_picker').value = this.value" />
                        </div>
                        <x-ui.error for="theme_neutral" />
                    </x-ui.form-group>
                </div>
            </x-ui.card>
        </div>

        <div class="mt-8 flex justify-end">
            <x-ui.button type="submit" variant="primary">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                Save Configuration
            </x-ui.button>
        </div>
    </form>
</div>
@endsection
