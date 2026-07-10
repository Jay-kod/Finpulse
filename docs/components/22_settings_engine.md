# Component 22: Settings Engine

## Overview
The Settings Engine provides a centralized, database-backed system for managing application configurations. It replaces the previously mocked settings data with real persistent storage, complete with type-casting, encryption for sensitive values, and an intelligent caching layer for performance.

## Architecture

### Model (`App\Models\Setting`)
- **Table**: `settings` (columns: `id`, `key`, `value`, `type`, timestamps)
- **Type System**: Supports `string`, `integer`, `float`, `boolean`, and `encrypted` types. Values are automatically cast when retrieved.
- **Encryption**: Settings with `type = 'encrypted'` are automatically encrypted via `Crypt::encryptString()` when saved and decrypted when read. API keys and secrets are stored safely.
- **Caching**: All settings are loaded and cached via `Cache::rememberForever('app_settings')`. The cache is automatically flushed whenever a setting is updated via `Setting::set()`.

### Global Helper (`app/Shared/Core/Helpers/SettingsHelper.php`)
- Provides a `setting($key, $default)` function available globally across the application (registered in `composer.json` autoload).
- Usage: `setting('platform_name')`, `setting('sentiment_sensitivity', 0.5)`.

### Seeder (`Database\Seeders\SettingsSeeder`)
Seeds default values:
| Key | Default Value | Type |
|-----|---------------|------|
| `platform_name` | Fintech Sentiment Analyzer | string |
| `support_email` | support@example.com | string |
| `default_language` | en | string |
| `sentiment_sensitivity` | 0.5 | float |
| `huggingface_api_key` | (empty) | encrypted |
| `openai_api_key` | (empty) | encrypted |

### Controller (`App\Http\Controllers\Admin\SettingsController`)
- **`index()`**: Loads all settings from the database (via cache) and passes them to the existing settings view.
- **`update()`**: Validates input, iterates over each field, calls `Setting::set()` with the appropriate type, and automatically flushes the cache.

### UI
The existing `resources/views/admin/settings/index.blade.php` was updated to use null-safe array access (`$settings['key'] ?? ''`) for graceful handling when keys are missing from the database.

## Testing
`tests/Feature/Admin/SettingsTest.php` provides 11 tests covering:
- Default seeder populates all expected keys
- `Setting::get()` returns correct values and defaults
- Type casting (integer, float, boolean)
- Encryption and decryption of sensitive values
- Cache priming and automatic invalidation
- Global `setting()` helper function
- Admin can view and update settings
- Analyst role is denied access (403)
