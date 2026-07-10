<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_dashboard(): void
    {
        $response = $this->get('/dashboard');

        $response->assertRedirect('/login');
    }

    public function test_authenticated_user_can_access_dashboard(): void
    {
        $user = User::factory()->create();
        if (!\Spatie\Permission\Models\Role::where('name', 'Viewer')->exists()) {
            \Spatie\Permission\Models\Role::create(['name' => 'Viewer']);
        }
        $user->assignRole('Viewer');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect();
        $redirectResponse = $this->followRedirects($response);
        $redirectResponse->assertStatus(200);
        $redirectResponse->assertSee('Dashboard Overview', false);
        $redirectResponse->assertSee('Sentiment Trends', false);
    }
}
