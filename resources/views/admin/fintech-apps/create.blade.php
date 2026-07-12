@extends('layouts.app')

@section('title', 'Add Fintech Application')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('admin.fintech-apps.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 dark:text-gray-400 hover:text-primary-600 dark:hover:text-primary-400 transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Applications
        </a>
    </div>

    <div class="mb-8 flex justify-between items-end">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Add Fintech Application</h1>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Register a new mobile application to track and analyze.</p>
        </div>
        <div id="lookupIndicator" class="hidden flex items-center text-sm text-primary-600 dark:text-primary-400">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Fetching App Details...
        </div>
    </div>

    <x-ui.card>
        <form action="{{ route('admin.fintech-apps.store') }}" method="POST">
            @csrf

            <x-ui.form-group label="Application Name" name="name" required class="mb-6">
                <x-ui.input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="e.g. OPay" autocomplete="off" />
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Type the app name and click out to auto-fill details from the app store.</p>
            </x-ui.form-group>

            <x-ui.form-group label="Logo URL" name="logo_url" class="mb-6">
                <div class="flex items-center gap-4">
                    <x-ui.input type="url" name="logo_url" id="logo_url" value="{{ old('logo_url') }}" placeholder="https://example.com/logo.png" class="flex-1" />
                    <img id="logoPreview" src="" class="w-10 h-10 rounded bg-gray-100 hidden object-cover" alt="Preview">
                </div>
            </x-ui.form-group>

            <x-ui.form-group label="Description" name="description" class="mb-6">
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">{{ old('description') }}</textarea>
            </x-ui.form-group>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <x-ui.form-group label="Platform" name="platform" required>
                    <select name="platform" id="platform" class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-900 shadow-sm focus:border-primary-500 focus:ring-primary-500 sm:text-sm dark:text-white">
                        <option value="both" {{ old('platform') == 'both' ? 'selected' : '' }}>Both</option>
                        <option value="android" {{ old('platform') == 'android' ? 'selected' : '' }}>Android (Play Store)</option>
                        <option value="ios" {{ old('platform') == 'ios' ? 'selected' : '' }}>iOS (App Store)</option>
                    </select>
                </x-ui.form-group>

                <x-ui.form-group label="Package Name (Legacy)" name="package_name" required>
                    <x-ui.input type="text" name="package_name" id="package_name" value="{{ old('package_name') }}" required placeholder="e.g. team.opay.pay" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">The unique identifier used internally.</p>
                </x-ui.form-group>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <x-ui.form-group label="Google Play Store ID" name="playstore_id">
                    <x-ui.input type="text" name="playstore_id" id="playstore_id" value="{{ old('playstore_id') }}" placeholder="e.g. com.opay.app" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required if Android is supported.</p>
                </x-ui.form-group>

                <x-ui.form-group label="Apple App Store ID" name="appstore_id">
                    <x-ui.input type="text" name="appstore_id" id="appstore_id" value="{{ old('appstore_id') }}" placeholder="e.g. 1461642822" />
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Required if iOS is supported.</p>
                </x-ui.form-group>
            </div>

            <div class="mb-8">
                <label class="flex items-center">
                    <input type="checkbox" name="is_active" value="1" class="rounded border-gray-300 text-primary-600 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-800" {{ old('is_active', true) ? 'checked' : '' }}>
                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Active</span>
                </label>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400 ml-6">If inactive, the pipeline will stop fetching new reviews for this app.</p>
            </div>

            <div class="flex items-center justify-end border-t border-gray-200 dark:border-gray-700 pt-6">
                <a href="{{ route('admin.fintech-apps.index') }}" class="text-sm font-medium text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-white mr-4">Cancel</a>
                <x-ui.button type="submit" variant="primary">Save Application</x-ui.button>
            </div>
        </form>
    </x-ui.card>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nameInput = document.getElementById('name');
        const packageNameInput = document.getElementById('package_name');
        const playstoreInput = document.getElementById('playstore_id');
        const appstoreInput = document.getElementById('appstore_id');
        const logoInput = document.getElementById('logo_url');
        const descriptionInput = document.getElementById('description');
        const logoPreview = document.getElementById('logoPreview');
        const indicator = document.getElementById('lookupIndicator');
        const platformSelect = document.getElementById('platform');
        
        let debounceTimer;

        // Display logo preview if URL is manually entered
        logoInput.addEventListener('input', function() {
            updateLogoPreview(this.value);
        });

        // Trigger lookup when user stops typing in the Name field
        nameInput.addEventListener('input', function() {
            clearTimeout(debounceTimer);
            const val = this.value.trim();
            if (val.length >= 3) {
                debounceTimer = setTimeout(() => performLookup(val), 800);
            }
        });

        // Toggle required fields based on platform selection
        platformSelect.addEventListener('change', function() {
            const val = this.value;
            if (val === 'android') {
                playstoreInput.setAttribute('required', 'required');
                appstoreInput.removeAttribute('required');
            } else if (val === 'ios') {
                appstoreInput.setAttribute('required', 'required');
                playstoreInput.removeAttribute('required');
            } else {
                playstoreInput.removeAttribute('required');
                appstoreInput.removeAttribute('required');
            }
        });

        function performLookup(query) {
            indicator.classList.remove('hidden');
            
            fetch("{{ route('admin.fintech-apps.lookup') }}", {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': "{{ csrf_token() }}",
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ name: query })
            })
            .then(res => {
                if (!res.ok) throw new Error('Network response was not ok. You might be logged out.');
                return res.json();
            })
            .then(response => {
                indicator.classList.add('hidden');
                
                if (response.success && response.data) {
                    const data = response.data;
                    
                    // Only autofill if the field is empty to not overwrite manual entries
                    if (!packageNameInput.value) packageNameInput.value = data.package_name;
                    if (!playstoreInput.value) playstoreInput.value = data.playstore_id;
                    if (!appstoreInput.value) appstoreInput.value = data.appstore_id;
                    
                    if (!descriptionInput.value) descriptionInput.value = data.description;
                    
                    if (!logoInput.value && data.logo_url) {
                        logoInput.value = data.logo_url;
                        updateLogoPreview(data.logo_url);
                    }
                } else if (!response.success) {
                    console.warn("Lookup didn't find a result: " + response.message);
                }
            })
            .catch(err => {
                console.error("Lookup failed:", err);
                indicator.classList.add('hidden');
                // Optional: alert('Failed to auto-fill. Please ensure you are logged in and connected to the internet.');
            });
        }

        function updateLogoPreview(url) {
            if (url) {
                logoPreview.src = url;
                logoPreview.classList.remove('hidden');
            } else {
                logoPreview.classList.add('hidden');
            }
        }
        
        // Trigger platform logic initially
        platformSelect.dispatchEvent(new Event('change'));
    });
</script>
@endpush
@endsection
