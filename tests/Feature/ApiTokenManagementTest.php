<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTokenManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_create_api_token(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/api-tokens', [
            'token_name' => 'My New Token',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('flash.plain_text_token');
        
        $this->assertCount(1, $user->tokens);
        $this->assertEquals('My New Token', $user->tokens->first()->name);
    }

    public function test_user_can_revoke_api_token(): void
    {
        $user = User::factory()->create();
        $token = $user->createToken('To Be Revoked');

        $this->assertCount(1, $user->tokens);

        $response = $this->actingAs($user)->delete("/api-tokens/{$token->accessToken->id}");

        $response->assertRedirect();
        $this->assertCount(0, $user->fresh()->tokens);
    }
}
