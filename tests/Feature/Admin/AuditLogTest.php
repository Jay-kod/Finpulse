<?php

namespace Tests\Feature\Admin;

use App\Models\AuditLog;
use App\Models\FintechApp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AuditLogTest extends TestCase
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

    public function test_creating_auditable_model_generates_audit_log(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $this->actingAs($admin);

        $app = FintechApp::create([
            'name' => 'Test App',
            'package_name' => 'com.test.app',
            'description' => 'Test Description'
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'created',
            'auditable_type' => FintechApp::class,
            'auditable_id' => $app->id,
            'user_id' => $admin->id
        ]);

        $log = AuditLog::where('auditable_type', FintechApp::class)->where('auditable_id', $app->id)->first();
        $this->assertNotNull($log->new_values);
        $this->assertEquals('Test App', $log->new_values['name']);
        $this->assertEmpty($log->old_values);
    }

    public function test_updating_auditable_model_generates_audit_log_with_dirty_values(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        $this->actingAs($admin);

        $app = FintechApp::factory()->create([
            'name' => 'Original Name',
            'description' => 'Original Description'
        ]);

        $app->update([
            'name' => 'New Name'
        ]);

        $this->assertDatabaseHas('audit_logs', [
            'event' => 'updated',
            'auditable_type' => FintechApp::class,
            'auditable_id' => $app->id
        ]);

        $log = AuditLog::where('event', 'updated')
            ->where('auditable_type', FintechApp::class)
            ->where('auditable_id', $app->id)
            ->first();

        // Check that only changed fields are recorded
        $this->assertArrayHasKey('name', $log->old_values);
        $this->assertEquals('Original Name', $log->old_values['name']);
        $this->assertArrayNotHasKey('description', $log->old_values); // Untouched

        $this->assertArrayHasKey('name', $log->new_values);
        $this->assertEquals('New Name', $log->new_values['name']);
    }

    public function test_admin_can_view_audit_logs(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');
        
        // Trigger a log
        FintechApp::factory()->create();

        $response = $this->actingAs($admin)->get(route('admin.audit-logs.index'));
        $response->assertStatus(200);
        $response->assertSee('System Audit Logs');
    }

    public function test_analyst_cannot_view_audit_logs(): void
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $response = $this->actingAs($analyst)->get(route('admin.audit-logs.index'));
        $response->assertStatus(403);
    }
}
