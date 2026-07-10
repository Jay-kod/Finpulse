<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingsSeeder extends Seeder
{
    /**
     * Seed the application's default settings.
     */
    public function run(): void
    {
        $defaults = [
            ['key' => 'platform_name', 'value' => 'Fintech Sentiment Analyzer', 'type' => 'string'],
            ['key' => 'support_email', 'value' => 'support@example.com', 'type' => 'string'],
            ['key' => 'default_language', 'value' => 'en', 'type' => 'string'],
            ['key' => 'sentiment_sensitivity', 'value' => '0.5', 'type' => 'float'],
            ['key' => 'huggingface_api_key', 'value' => '', 'type' => 'encrypted'],
            ['key' => 'openai_api_key', 'value' => '', 'type' => 'encrypted'],
        ];

        foreach ($defaults as $setting) {
            Setting::updateOrCreate(
                ['key' => $setting['key']],
                ['value' => $setting['value'], 'type' => $setting['type']]
            );
        }
    }
}
