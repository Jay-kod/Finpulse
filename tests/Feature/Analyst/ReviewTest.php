<?php

namespace Tests\Feature\Analyst;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReviewTest extends TestCase
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

    public function test_analyst_can_view_reviews_index()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create(['name' => 'Test App']);
        $dataset = Dataset::factory()->create(['name' => 'Test Dataset', 'fintech_app_id' => $app->id]);
        Review::factory()->create(['content' => 'This app is great!', 'dataset_id' => $dataset->id]);

        $response = $this->actingAs($analyst)->get(route('analyst.reviews.index'));

        $response->assertStatus(200);
        $response->assertSee('This app is great!');
        $response->assertSee('Test App');
    }

    public function test_viewer_cannot_access_reviews()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $response = $this->actingAs($viewer)->get(route('analyst.reviews.index'));

        $response->assertStatus(403);
    }

    public function test_analyst_can_create_review()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);

        $payload = [
            'dataset_id' => $dataset->id,
            'author_name' => 'Jane Doe',
            'rating' => 5,
            'content' => 'Excellent performance.',
            'processed_status' => 'pending',
            'published_at' => now()->format('Y-m-d'),
        ];

        $response = $this->actingAs($analyst)->post(route('analyst.reviews.store'), $payload);

        $response->assertRedirect(route('analyst.reviews.index'));
        $this->assertDatabaseHas('reviews', [
            'author_name' => 'Jane Doe',
            'content' => 'Excellent performance.',
            'dataset_id' => $dataset->id,
        ]);
    }

    public function test_analyst_can_update_review()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        $review = Review::factory()->create(['dataset_id' => $dataset->id, 'rating' => 3]);

        $payload = [
            'dataset_id' => $dataset->id,
            'author_name' => $review->author_name,
            'rating' => 4,
            'content' => 'Updated content',
            'processed_status' => 'processed',
        ];

        $response = $this->actingAs($analyst)->put(route('analyst.reviews.update', $review), $payload);

        $response->assertRedirect(route('analyst.reviews.index'));
        $this->assertDatabaseHas('reviews', [
            'id' => $review->id,
            'rating' => 4,
            'content' => 'Updated content',
            'processed_status' => 'processed',
        ]);
    }

    public function test_analyst_can_delete_review()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        $review = Review::factory()->create(['dataset_id' => $dataset->id]);

        $response = $this->actingAs($analyst)->delete(route('analyst.reviews.destroy', $review));

        $response->assertRedirect(route('analyst.reviews.index'));
        $this->assertSoftDeleted('reviews', [
            'id' => $review->id,
        ]);
    }
}
