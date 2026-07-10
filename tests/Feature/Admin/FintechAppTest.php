<?php

namespace Tests\Feature\Admin;

use App\Models\FintechApp;
use App\Models\User;
use App\Jobs\SyncAppReviewsJob;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class FintechAppTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Role::create(['name' => 'Super Admin']);
        Role::create(['name' => 'Admin']);
        Role::create(['name' => 'Analyst']);
        Role::create(['name' => 'Viewer']);
    }

    public function test_admin_can_view_fintech_apps_index()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        FintechApp::factory()->create(['name' => 'Test App X']);

        $response = $this->actingAs($admin)->get(route('admin.fintech-apps.index'));

        $response->assertStatus(200);
        $response->assertSee('Test App X');
    }

    public function test_analyst_cannot_access_fintech_apps()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $response = $this->actingAs($analyst)->get(route('admin.fintech-apps.index'));

        $response->assertStatus(403);
    }

    public function test_admin_can_create_fintech_app()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $payload = [
            'name' => 'OPay',
            'package_name' => 'team.opay.pay',
            'platform' => 'android',
            'description' => 'A mobile money platform.',
            'logo_url' => 'https://example.com/logo.png',
            'is_active' => '1',
        ];

        $response = $this->actingAs($admin)->post(route('admin.fintech-apps.store'), $payload);

        $response->assertRedirect(route('admin.fintech-apps.index'));
        $this->assertDatabaseHas('fintech_apps', [
            'name' => 'OPay',
            'package_name' => 'team.opay.pay',
        ]);
    }

    public function test_package_name_must_be_unique_on_create()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        FintechApp::factory()->create(['package_name' => 'team.opay.pay']);

        $payload = [
            'name' => 'Another App',
            'package_name' => 'team.opay.pay',
            'platform' => 'android',
        ];

        $response = $this->actingAs($admin)->post(route('admin.fintech-apps.store'), $payload);

        $response->assertSessionHasErrors('package_name');
    }

    public function test_admin_can_update_fintech_app()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $app = FintechApp::factory()->create([
            'name' => 'Old Name',
            'package_name' => 'com.old.app',
        ]);

        $payload = [
            'name' => 'New Name',
            'package_name' => 'com.new.app',
            'platform' => 'ios',
        ];

        $response = $this->actingAs($admin)->put(route('admin.fintech-apps.update', $app), $payload);

        $response->assertRedirect(route('admin.fintech-apps.index'));
        $this->assertDatabaseHas('fintech_apps', [
            'id' => $app->id,
            'name' => 'New Name',
            'package_name' => 'com.new.app',
            'is_active' => false, // Missing 'is_active' in payload means false
        ]);
    }

    public function test_admin_can_delete_fintech_app()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $app = FintechApp::factory()->create();

        $response = $this->actingAs($admin)->delete(route('admin.fintech-apps.destroy', $app));

        $response->assertRedirect(route('admin.fintech-apps.index'));
        $this->assertSoftDeleted('fintech_apps', [
            'id' => $app->id,
        ]);
    }

    public function test_creating_app_dispatches_sync_job_and_notifications()
    {
        Queue::fake();
        Notification::fake();

        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        // Create a second user to verify broadcast
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $payload = [
            'name' => 'PalmPay',
            'package_name' => 'com.palmpay.app',
            'platform' => 'both',
            'playstore_id' => 'com.palmpay.app',
            'appstore_id' => '1234567890',
        ];

        $response = $this->actingAs($admin)->post(route('admin.fintech-apps.store'), $payload);

        $response->assertRedirect(route('admin.fintech-apps.index'));

        $this->assertDatabaseHas('fintech_apps', [
            'name' => 'PalmPay',
            'platform' => 'both',
            'playstore_id' => 'com.palmpay.app',
        ]);

        Queue::assertPushed(SyncAppReviewsJob::class);
        Notification::assertSentTo([$admin, $viewer], \App\Notifications\NewFintechAppAdded::class);
    }
}
