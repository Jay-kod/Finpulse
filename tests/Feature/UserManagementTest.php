<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected User $admin;
    protected User $viewer;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);

        $this->admin = User::where('email', 'admin@example.com')->first();
        $this->viewer = User::where('email', 'viewer@example.com')->first();
    }

    public function test_admin_can_view_user_list(): void
    {
        $response = $this->actingAs($this->admin)->get('/admin/users');

        $response->assertStatus(200);
        $response->assertSee('User Management', false);
    }

    public function test_viewer_cannot_access_user_management(): void
    {
        $response = $this->actingAs($this->viewer)->get('/admin/users');

        $response->assertStatus(403);
    }

    public function test_admin_can_create_a_user(): void
    {
        $response = $this->actingAs($this->admin)->post('/admin/users', [
            'name'                  => 'New User',
            'email'                 => 'newuser@example.com',
            'password'              => 'password',
            'password_confirmation' => 'password',
            'role'                  => 'Analyst',
        ]);

        $response->assertRedirect('/admin/users');

        $this->assertDatabaseHas('users', ['email' => 'newuser@example.com']);

        $newUser = User::where('email', 'newuser@example.com')->first();
        $this->assertTrue($newUser->hasRole('Analyst'));
    }

    public function test_admin_can_edit_a_user_role(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Viewer');

        $response = $this->actingAs($this->admin)->put("/admin/users/{$user->id}", [
            'name'  => $user->name,
            'email' => $user->email,
            'role'  => 'Analyst',
        ]);

        $response->assertRedirect('/admin/users');

        $user->refresh();
        $this->assertTrue($user->hasRole('Analyst'));
        $this->assertFalse($user->hasRole('Viewer'));
    }

    public function test_admin_can_delete_a_user(): void
    {
        $user = User::factory()->create();
        $user->assignRole('Viewer');

        $response = $this->actingAs($this->admin)->delete("/admin/users/{$user->id}");

        $response->assertRedirect('/admin/users');
        $this->assertDatabaseMissing('users', ['id' => $user->id]);
    }

    public function test_admin_cannot_delete_themselves(): void
    {
        $response = $this->actingAs($this->admin)->delete("/admin/users/{$this->admin->id}");

        $response->assertRedirect();
        $response->assertSessionHas('error');
        $this->assertDatabaseHas('users', ['id' => $this->admin->id]);
    }
}
