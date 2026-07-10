<?php

namespace Tests\Feature;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GlobalSearchTest extends TestCase
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
        if (!Role::where('name', 'Viewer')->exists()) {
            Role::create(['name' => 'Viewer']);
        }
    }

    public function test_guest_cannot_access_search(): void
    {
        $this->get(route('search', ['q' => 'test']))
            ->assertRedirect(route('login'));
    }

    public function test_empty_search_query_shows_empty_state(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('search'));
        
        $response->assertStatus(200);
        $response->assertSee('Please enter a search term above.');
        $response->assertSee('Start searching');
    }

    public function test_search_with_no_results_shows_no_matches_state(): void
    {
        $user = User::factory()->create();
        
        $response = $this->actingAs($user)->get(route('search', ['q' => 'NonExistentXYZ123']));
        
        $response->assertStatus(200);
        $response->assertSee('No results found');
    }

    public function test_admin_can_search_all_models(): void
    {
        $admin = User::factory()->create(['name' => 'Admin User']);
        $admin->assignRole('Admin');

        // Create test data
        $targetUser = User::factory()->create(['name' => 'John Doe']);
        $app = FintechApp::factory()->create(['name' => 'Opay Mobile']);
        $dataset = Dataset::factory()->create(['name' => 'Opay Q1 Reviews']);
        $report = Report::factory()->create(['title' => 'Opay Sentiment Analysis']);
        
        // Review needs a dataset, so link it
        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'content' => 'Opay is a great app'
        ]);

        $response = $this->actingAs($admin)->get(route('search', ['q' => 'Opay']));
        
        $response->assertStatus(200);
        $response->assertSee('Opay Mobile');
        $response->assertSee('Opay Q1 Reviews');
        $response->assertSee('Opay Sentiment Analysis');
        $response->assertSee('Opay is a great app');

        // Test user search specifically
        $response2 = $this->actingAs($admin)->get(route('search', ['q' => 'John Doe']));
        $response2->assertStatus(200);
        $response2->assertSee('John Doe');
    }

    public function test_analyst_cannot_search_users(): void
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        User::factory()->create(['name' => 'Secret User']);
        $app = FintechApp::factory()->create(['name' => 'Secret App']);

        // Analyst searches for "Secret"
        $response = $this->actingAs($analyst)->get(route('search', ['q' => 'Secret']));
        
        $response->assertStatus(200);
        $response->assertSee('Secret App');
        $response->assertDontSee('Secret User'); // Role restricted
    }

    public function test_viewer_can_only_search_reports(): void
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $app = FintechApp::factory()->create(['name' => 'Kuda']);
        $report = Report::factory()->create(['title' => 'Kuda Report']);

        // Viewer searches for "Kuda"
        $response = $this->actingAs($viewer)->get(route('search', ['q' => 'Kuda']));
        
        $response->assertStatus(200);
        $response->assertSee('Kuda Report');
        $response->assertDontSee('Kuda (1)'); // Should not see Apps section
    }
}
