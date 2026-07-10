<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\View\View;

class SettingsController extends Controller
{
    /**
     * Display the settings configuration panel.
     */
    public function index(): View
    {
        $settings = Setting::getAllAsKeyValue();

        return view('admin.settings.index', compact('settings'));
    }

    /**
     * Update the platform settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'platform_name' => ['required', 'string', 'max:255'],
            'support_email' => ['required', 'email', 'max:255'],
            'default_language' => ['required', 'string', 'in:en,fr,ha,yo,ig'],
            'sentiment_sensitivity' => ['required', 'numeric', 'min:0', 'max:1'],
            'huggingface_api_key' => ['nullable', 'string'],
            'openai_api_key' => ['nullable', 'string'],
        ]);

        // Define the type map for each setting key
        $typeMap = [
            'platform_name' => 'string',
            'support_email' => 'string',
            'default_language' => 'string',
            'sentiment_sensitivity' => 'float',
            'huggingface_api_key' => 'encrypted',
            'openai_api_key' => 'encrypted',
        ];

        foreach ($validated as $key => $value) {
            $type = $typeMap[$key] ?? 'string';
            Setting::set($key, $value, $type);
        }

        return redirect()->route('admin.settings.index')
            ->with('success', 'Platform configuration updated successfully.');
    }
}
