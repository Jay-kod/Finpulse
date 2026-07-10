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

class ReportingEngineTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        if (!Role::where('name', 'Analyst')->exists()) {
            Role::create(['name' => 'Analyst']);
        }
    }

    public function test_analyst_can_view_reports_index()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        Report::factory()->create(['user_id' => $analyst->id, 'title' => 'Test Report A']);

        $response = $this->actingAs($analyst)->get(route('analyst.reports.index'));

        $response->assertStatus(200);
        $response->assertSee('Saved Reports');
        $response->assertSee('Test Report A');
    }

    public function test_analyst_can_create_report()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');
        $app = FintechApp::factory()->create();

        $response = $this->actingAs($analyst)->post(route('analyst.reports.store'), [
            'title' => 'My Custom Report',
            'description' => 'A report for testing',
            'app_id' => $app->id,
            'start_date' => '2026-01-01',
        ]);

        $response->assertRedirect(route('analyst.reports.index'));
        
        $this->assertDatabaseHas('reports', [
            'title' => 'My Custom Report',
            'user_id' => $analyst->id,
        ]);

        $report = Report::first();
        $this->assertEquals($app->id, $report->parameters['app_id']);
        $this->assertEquals('2026-01-01', $report->parameters['start_date']);
    }

    public function test_report_show_page_scopes_analytics_data()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $appA = FintechApp::factory()->create(['name' => 'App A']);
        $datasetA = Dataset::factory()->create(['fintech_app_id' => $appA->id]);
        
        $appB = FintechApp::factory()->create(['name' => 'App B']);
        $datasetB = Dataset::factory()->create(['fintech_app_id' => $appB->id]);

        // Review for App A
        Review::factory()->create([
            'dataset_id' => $datasetA->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => 0.90, // Positive
        ]);

        // Review for App B
        Review::factory()->create([
            'dataset_id' => $datasetB->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => -0.90, // Negative
        ]);

        // Create report scoped to App A
        $report = Report::factory()->create([
            'user_id' => $analyst->id,
            'title' => 'App A Report',
            'parameters' => ['app_id' => $appA->id],
        ]);

        $response = $this->actingAs($analyst)->get(route('analyst.reports.show', $report));

        $response->assertStatus(200);
        $response->assertSee('App A Report');
        
        // Assert the view receives stats specifically for App A
        // Total should be 1 (not 2)
        $overviewStats = $response->original->getData()['overviewStats'];
        $this->assertEquals(1, $overviewStats['total_reviews']);
        $this->assertEquals(0.90, $overviewStats['avg_sentiment']);
    }
}
