<?php

namespace Tests\Feature\Admin;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SettingsTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Role::where('name', 'Admin')->exists()) {
            Role::create(['name' => 'Admin']);
        }
        if (!Role::where('name', 'Analyst')->exists()) {
            Role::create(['name' => 'Analyst']);
        }
    }

    public function test_settings_seeder_creates_default_entries(): void
    {
        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $this->assertDatabaseHas('settings', ['key' => 'platform_name']);
        $this->assertDatabaseHas('settings', ['key' => 'support_email']);
        $this->assertDatabaseHas('settings', ['key' => 'default_language']);
        $this->assertDatabaseHas('settings', ['key' => 'sentiment_sensitivity']);
        $this->assertDatabaseHas('settings', ['key' => 'huggingface_api_key']);
        $this->assertDatabaseHas('settings', ['key' => 'openai_api_key']);
    }

    public function test_setting_model_get_returns_correct_value(): void
    {
        Setting::create(['key' => 'test_key', 'value' => 'hello', 'type' => 'string']);

        $this->assertEquals('hello', Setting::get('test_key'));
    }

    public function test_setting_model_get_returns_default_for_missing_key(): void
    {
        $this->assertEquals('fallback', Setting::get('nonexistent_key', 'fallback'));
    }

    public function test_setting_model_casts_types_correctly(): void
    {
        Setting::create(['key' => 'int_val', 'value' => '42', 'type' => 'integer']);
        Setting::create(['key' => 'float_val', 'value' => '0.75', 'type' => 'float']);
        Setting::create(['key' => 'bool_val', 'value' => '1', 'type' => 'boolean']);

        $this->assertSame(42, Setting::get('int_val'));
        $this->assertSame(0.75, Setting::get('float_val'));
        $this->assertSame(true, Setting::get('bool_val'));
    }

    public function test_setting_model_encrypts_and_decrypts_values(): void
    {
        Setting::set('secret', 'my-api-key-123', 'encrypted');

        // The raw DB value should NOT be the plaintext
        $raw = Setting::where('key', 'secret')->first();
        $this->assertNotEquals('my-api-key-123', $raw->value);

        // But retrieving via the model should decrypt it
        Setting::flushCache();
        $this->assertEquals('my-api-key-123', Setting::get('secret'));
    }

    public function test_setting_set_clears_cache(): void
    {
        Setting::create(['key' => 'cached_key', 'value' => 'old', 'type' => 'string']);

        // Prime the cache
        Setting::getAllCached();
        $this->assertTrue(Cache::has('app_settings'));

        // Setting a value should clear the cache
        Setting::set('cached_key', 'new', 'string');
        $this->assertFalse(Cache::has('app_settings'));

        // And the new value should be retrievable
        $this->assertEquals('new', Setting::get('cached_key'));
    }

    public function test_global_helper_function_works(): void
    {
        Setting::create(['key' => 'helper_test', 'value' => 'works', 'type' => 'string']);
        Setting::flushCache();

        $this->assertEquals('works', setting('helper_test'));
        $this->assertEquals('default_val', setting('missing_key', 'default_val'));
    }

    public function test_admin_can_view_settings_page(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $response = $this->actingAs($admin)->get(route('admin.settings.index'));
        $response->assertStatus(200);
        $response->assertSee('Platform Settings');
    }

    public function test_admin_can_update_settings(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $this->seed(\Database\Seeders\SettingsSeeder::class);

        $response = $this->actingAs($admin)->post(route('admin.settings.update'), [
            'platform_name' => 'Updated Platform',
            'support_email' => 'new@example.com',
            'default_language' => 'ha',
            'sentiment_sensitivity' => 0.8,
            'huggingface_api_key' => 'hf_test123',
            'openai_api_key' => 'sk_test456',
        ]);

        $response->assertRedirect(route('admin.settings.index'));
        $response->assertSessionHas('success');

        Setting::flushCache();
        $this->assertEquals('Updated Platform', Setting::get('platform_name'));
        $this->assertEquals('ha', Setting::get('default_language'));
        $this->assertSame(0.8, Setting::get('sentiment_sensitivity'));
    }

    public function test_analyst_cannot_view_settings(): void
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $response = $this->actingAs($analyst)->get(route('admin.settings.index'));
        $response->assertStatus(403);
    }

    public function test_analyst_cannot_update_settings(): void
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $response = $this->actingAs($analyst)->post(route('admin.settings.update'), [
            'platform_name' => 'Hacked',
            'support_email' => 'hacker@example.com',
            'default_language' => 'en',
            'sentiment_sensitivity' => 0.5,
        ]);

        $response->assertStatus(403);
    }
}
