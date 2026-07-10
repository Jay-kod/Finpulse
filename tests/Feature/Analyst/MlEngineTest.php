<?php

namespace Tests\Feature\Analyst;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\Review;
use App\Services\MlService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;
use App\Models\User;
use Spatie\Permission\Models\Role;

class MlEngineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (!Role::where('name', 'Analyst')->exists()) {
            Role::create(['name' => 'Analyst']);
        }
    }

    public function test_ml_service_classify_sends_correct_request()
    {
        Http::fake([
            '*/api/classify' => Http::response([
                'topic' => 'Performance',
                'intent' => 'Complaint',
                'is_bug' => true,
            ], 200),
        ]);

        $service = new MlService();
        $result = $service->classify('app crashes on launch');

        $this->assertEquals('Performance', $result['topic']);
        $this->assertEquals('Complaint', $result['intent']);
        $this->assertTrue($result['is_bug']);

        Http::assertSent(function ($request) {
            return str_contains($request->url(), '/api/classify')
                && $request['cleaned_text'] === 'app crashes on launch';
        });
    }

    public function test_ml_service_throws_on_connection_failure()
    {
        Http::fake([
            '*/api/classify' => Http::response(null, 500),
        ]);

        $service = new MlService();

        $this->expectException(\Exception::class);
        $service->classify('Test text');
    }

    public function test_artisan_command_classifies_eligible_reviews()
    {
        Http::fake([
            '*/api/classify' => Http::response([
                'topic' => 'UI',
                'intent' => 'Praise',
                'is_bug' => false,
            ], 200),
        ]);

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        $review = Review::factory()->create([
            'dataset_id' => $dataset->id,
            'content' => 'Love the new design',
            'cleaned_content' => 'love new design',
            'processed_status' => 'processed', // Eligible
            'ml_status' => 'pending',
        ]);

        $ineligibleReview = Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'pending', // Not yet preprocessed
            'ml_status' => 'pending',
        ]);

        $this->artisan('reviews:classify', ['--limit' => 5])
            ->assertExitCode(0);

        $review->refresh();
        $this->assertEquals('classified', $review->ml_status);
        $this->assertEquals('UI', $review->topic);
        $this->assertEquals('Praise', $review->intent);
        $this->assertFalse($review->is_bug);

        // Ensure ineligible review was not touched
        $ineligibleReview->refresh();
        $this->assertEquals('pending', $ineligibleReview->ml_status);
    }

    public function test_artisan_command_marks_error_on_ml_failure()
    {
        Http::fake([
            '*/api/classify' => Http::response(null, 500),
        ]);

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        $review = Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'pending',
        ]);

        $this->artisan('reviews:classify', ['--limit' => 5])
            ->assertExitCode(1);

        $review->refresh();
        $this->assertEquals('error', $review->ml_status);
    }
    
    public function test_dispatch_ml_triggers_classification()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        Http::fake([
            '*/health' => Http::response('OK', 200),
            '*/api/classify' => Http::response([
                'topic' => 'Test',
                'intent' => 'Test',
                'is_bug' => false,
            ], 200),
        ]);

        $response = $this->actingAs($analyst)->post(route('analyst.preprocessing.dispatch-ml'), ['limit' => 10]);

        $response->assertRedirect(route('analyst.preprocessing.index'));
        $response->assertSessionHas('success');
    }
}
