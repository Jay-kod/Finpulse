<?php

namespace Tests\Feature\Analyst;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\Review;
use App\Services\SentimentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class SentimentAnalysisTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (!Role::where('name', 'Analyst')->exists()) {
            Role::create(['name' => 'Analyst']);
        }
    }

    public function test_sentiment_service_analyze_sends_correct_request()
    {
        Http::fake([
            '*/api/sentiment' => Http::response([
                'positive' => 0.8500,
                'negative' => 0.0500,
                'neutral' => 0.1000,
                'compound' => 0.8000,
            ], 200),
        ]);

        $service = new SentimentService();
        $result = $service->analyze('great app');

        $this->assertEquals(0.8500, $result['positive']);
        $this->assertEquals(0.0500, $result['negative']);
        $this->assertEquals(0.1000, $result['neutral']);
        $this->assertEquals(0.8000, $result['compound']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/sentiment')
                && $request['cleaned_text'] === 'great app';
        });
    }

    public function test_sentiment_service_throws_on_connection_failure()
    {
        Http::fake([
            '*/api/sentiment' => Http::response(null, 500),
        ]);

        $service = new SentimentService();

        $this->expectException(\Exception::class);
        $service->analyze('Test text');
    }

    public function test_artisan_command_analyzes_eligible_reviews()
    {
        Http::fake([
            '*/api/sentiment' => Http::response([
                'positive' => 0.9000,
                'negative' => 0.0000,
                'neutral' => 0.1000,
                'compound' => 0.9500,
            ], 200),
        ]);

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        
        $review = Review::factory()->create([
            'dataset_id' => $dataset->id,
            'content' => 'I love this!',
            'cleaned_content' => 'love this',
            'processed_status' => 'processed',
            'ml_status' => 'classified', // Eligible for Sentiment
            'sentiment_status' => 'pending',
        ]);

        $ineligibleReview = Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'pending', // Not yet classified
            'sentiment_status' => 'pending',
        ]);

        $this->artisan('reviews:sentiment', ['--limit' => 5])
            ->assertExitCode(0);

        $review->refresh();
        $this->assertEquals('analyzed', $review->sentiment_status);
        $this->assertEquals(0.9000, $review->sentiment_positive);
        $this->assertEquals(0.9500, $review->sentiment_compound);

        // Ensure ineligible review was not touched
        $ineligibleReview->refresh();
        $this->assertEquals('pending', $ineligibleReview->sentiment_status);
    }

    public function test_artisan_command_marks_error_on_sentiment_failure()
    {
        Http::fake([
            '*/api/sentiment' => Http::response(null, 500),
        ]);

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        
        $review = Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'pending',
        ]);

        $this->artisan('reviews:sentiment', ['--limit' => 5])
            ->assertExitCode(1);

        $review->refresh();
        $this->assertEquals('error', $review->sentiment_status);
    }
    
    public function test_dispatch_sentiment_triggers_analysis()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        Http::fake([
            '*/health' => Http::response('OK', 200),
            '*/api/sentiment' => Http::response([
                'positive' => 0.5,
                'negative' => 0.5,
                'neutral' => 0.0,
                'compound' => 0.0,
            ], 200),
        ]);

        $response = $this->actingAs($analyst)->post(route('analyst.preprocessing.dispatch-sentiment'), ['limit' => 10]);

        $response->assertRedirect(route('analyst.preprocessing.index'));
        $response->assertSessionHas('success');
    }
}
