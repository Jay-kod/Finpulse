<?php

namespace Tests\Feature\Analyst;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ExportEngineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (!Role::where('name', 'Analyst')->exists()) {
            Role::create(['name' => 'Analyst']);
        }
    }

    public function test_analyst_can_export_all_reviews()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        Review::factory()->count(3)->create([
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => 0.5,
        ]);

        $response = $this->actingAs($analyst)->get(route('analyst.export.all'));

        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/csv; charset=UTF-8');
        $response->assertHeader('Content-Disposition');
        
        $this->assertStringContainsString('all_reviews_export_', $response->headers->get('Content-Disposition'));
        
        // Assert streamed response starts with headers
        $streamedContent = $response->streamedContent();
        $this->assertStringContainsString('ID,App,"Review Text",Rating', $streamedContent);
    }

    public function test_analyst_can_export_specific_report()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $appA = FintechApp::factory()->create(['name' => 'App A']);
        $datasetA = Dataset::factory()->create(['fintech_app_id' => $appA->id]);

        $appB = FintechApp::factory()->create(['name' => 'App B']);
        $datasetB = Dataset::factory()->create(['fintech_app_id' => $appB->id]);

        $reviewA = Review::factory()->create([
            'dataset_id' => $datasetA->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'content' => 'Review for App A',
        ]);

        $reviewB = Review::factory()->create([
            'dataset_id' => $datasetB->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'content' => 'Review for App B',
        ]);

        $report = Report::factory()->create([
            'user_id' => $analyst->id,
            'title' => 'Test App A',
            'parameters' => ['app_id' => $appA->id],
        ]);

        $response = $this->actingAs($analyst)->get(route('analyst.export.report', $report));

        $response->assertStatus(200);
        $streamedContent = $response->streamedContent();

        $this->assertStringContainsString('ID,App,"Review Text",Rating', $streamedContent);
        $this->assertStringContainsString('Review for App A', $streamedContent);
        $this->assertStringNotContainsString('Review for App B', $streamedContent);
    }
}
