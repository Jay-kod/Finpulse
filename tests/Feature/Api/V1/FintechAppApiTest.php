<?php

namespace Tests\Feature\Api\V1;

use App\Models\FintechApp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FintechAppApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_user_cannot_access_api(): void
    {
        $response = $this->getJson('/api/v1/fintech-apps');
        $response->assertStatus(401);
    }

    public function test_authenticated_user_can_list_apps(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        FintechApp::factory()->count(3)->create();

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson('/api/v1/fintech-apps');

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'package_name',
                    'description',
                    'logo_url',
                    'is_active',
                    'created_at',
                    'updated_at',
                ]
            ],
            'links',
            'meta'
        ]);
        $this->assertCount(3, $response->json('data'));
    }

    public function test_authenticated_user_can_view_single_app(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('test-token')->plainTextToken;

        $app = FintechApp::factory()->create([
            'name' => 'Specific App',
        ]);

        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token,
        ])->getJson("/api/v1/fintech-apps/{$app->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('data.name', 'Specific App');
    }
}
