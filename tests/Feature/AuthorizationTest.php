<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Support\Facades\Hash;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(RolesAndPermissionsSeeder::class);
    }

    public function test_new_user_gets_viewer_role_on_registration(): void
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $this->assertAuthenticated();
        
        $user = User::where('email', 'test@example.com')->first();
        
        $this->assertTrue($user->hasRole('Viewer'));
        $this->assertFalse($user->hasRole('Admin'));
    }

    public function test_admin_can_have_multiple_permissions(): void
    {
        $admin = User::firstOrCreate([
            'email' => 'admin_test@example.com',
        ], [
            'name' => 'Admin Test',
            'password' => Hash::make('password'),
        ]);
        
        $admin->assignRole('Admin');
        
        $this->assertTrue($admin->hasPermissionTo('manage users'));
        $this->assertTrue($admin->hasPermissionTo('manage configuration'));
    }
}
