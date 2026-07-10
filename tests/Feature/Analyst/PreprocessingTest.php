<?php

namespace Tests\Feature\Analyst;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\Review;
use App\Models\User;
use App\Services\NlpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class PreprocessingTest extends TestCase
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

    public function test_analyst_can_view_preprocessing_dashboard()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        Review::factory()->count(3)->create(['dataset_id' => $dataset->id, 'processed_status' => 'pending']);
        Review::factory()->count(2)->create(['dataset_id' => $dataset->id, 'processed_status' => 'processed']);

        // Fake the health check so it doesn't make a real HTTP call
        Http::fake([
            '*/health' => Http::response('OK', 200),
        ]);

        $response = $this->actingAs($analyst)->get(route('analyst.preprocessing.index'));

        $response->assertStatus(200);
        $response->assertSee('Data Pipeline Management');
        $response->assertSee('3'); // pending count
        $response->assertSee('2'); // processed count
    }

    public function test_viewer_cannot_access_preprocessing_dashboard()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $response = $this->actingAs($viewer)->get(route('analyst.preprocessing.index'));

        $response->assertStatus(403);
    }

    public function test_nlp_service_preprocess_sends_correct_request()
    {
        Http::fake([
            '*/api/preprocess' => Http::response([
                'cleaned_text' => 'this app is great',
                'language' => 'en',
                'word_count' => 4,
            ], 200),
        ]);

        $service = new NlpService();
        $result = $service->preprocess('This app is GREAT!!!');

        $this->assertEquals('this app is great', $result['cleaned_text']);
        $this->assertEquals('en', $result['language']);
        $this->assertEquals(4, $result['word_count']);

        Http::assertSent(function ($request) {
            return $request->url() === 'http://127.0.0.1:8000/api/preprocess'
                && $request['text'] === 'This app is GREAT!!!';
        });
    }

    public function test_nlp_service_throws_on_connection_failure()
    {
        Http::fake([
            '*/api/preprocess' => Http::response(null, 500),
        ]);

        $service = new NlpService();

        $this->expectException(\Exception::class);
        $service->preprocess('Test text');
    }

    public function test_artisan_command_processes_pending_reviews()
    {
        Http::fake([
            '*/api/preprocess' => Http::response([
                'cleaned_text' => 'cleaned text',
                'language' => 'en',
                'word_count' => 2,
            ], 200),
        ]);

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        $review = Review::factory()->create([
            'dataset_id' => $dataset->id,
            'content' => 'Raw text here!',
            'processed_status' => 'pending',
        ]);

        $this->artisan('reviews:preprocess', ['--limit' => 5])
            ->assertExitCode(0);

        $review->refresh();
        $this->assertEquals('processed', $review->processed_status);
        $this->assertEquals('cleaned text', $review->cleaned_content);
        $this->assertEquals('en', $review->detected_language);
        $this->assertEquals(2, $review->word_count);
    }

    public function test_artisan_command_marks_error_on_failure()
    {
        Http::fake([
            '*/api/preprocess' => Http::response(null, 500),
        ]);

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        $review = Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'pending',
        ]);

        $this->artisan('reviews:preprocess', ['--limit' => 5])
            ->assertExitCode(1);

        $review->refresh();
        $this->assertEquals('error', $review->processed_status);
    }

    public function test_dispatch_triggers_preprocessing()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        Http::fake([
            '*/api/preprocess' => Http::response([
                'cleaned_text' => 'cleaned',
                'language' => 'en',
                'word_count' => 1,
            ], 200),
        ]);

        $response = $this->actingAs($analyst)->post(route('analyst.preprocessing.dispatch'), ['limit' => 10]);

        $response->assertRedirect(route('analyst.preprocessing.index'));
        $response->assertSessionHas('success');
    }
}
