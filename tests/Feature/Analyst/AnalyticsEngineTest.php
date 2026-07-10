<?php

namespace Tests\Feature\Analyst;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\Review;
use App\Models\User;
use App\Services\AnalyticsService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AnalyticsEngineTest extends TestCase
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

    private function createAnalyzedReview(array $overrides = []): Review
    {
        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);

        return Review::factory()->create(array_merge([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'published_at' => now(),
        ], $overrides));
    }

    public function test_analyst_can_view_analytics_dashboard()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $response = $this->actingAs($analyst)->get(route('analyst.analytics.index'));

        $response->assertStatus(200);
        $response->assertSee('Analytics Hub');
    }

    public function test_viewer_cannot_access_analytics_dashboard()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $response = $this->actingAs($viewer)->get(route('analyst.analytics.index'));

        $response->assertStatus(403);
    }

    public function test_overview_stats_returns_correct_aggregates()
    {
        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);

        // Create 3 analyzed reviews
        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => 0.80,
            'topic' => 'Performance',
            'is_bug' => false,
        ]);
        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => -0.50,
            'topic' => 'Performance',
            'is_bug' => true,
        ]);
        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => 0.10,
            'topic' => 'UI',
            'is_bug' => false,
        ]);

        $service = new AnalyticsService();
        $stats = $service->getOverviewStats();

        $this->assertEquals(3, $stats['total_reviews']);
        // avg = (0.80 + -0.50 + 0.10) / 3 = 0.1333...
        $this->assertEquals(0.13, $stats['avg_sentiment']);
        // 1 bug out of 3 = 33.3%
        $this->assertEquals(33.3, $stats['bug_rate']);
        // Performance appears twice
        $this->assertEquals('Performance', $stats['top_topic']);
    }

    public function test_overview_stats_handles_empty_database()
    {
        $service = new AnalyticsService();
        $stats = $service->getOverviewStats();

        $this->assertEquals(0, $stats['total_reviews']);
        $this->assertEquals(0, $stats['avg_sentiment']);
        $this->assertEquals(0, $stats['bug_rate']);
        $this->assertEquals('N/A', $stats['top_topic']);
    }

    public function test_sentiment_distribution_returns_correct_counts()
    {
        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);

        // 2 positive (compound >= 0.05)
        Review::factory()->count(2)->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => 0.50,
        ]);

        // 1 negative (compound <= -0.05)
        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => -0.30,
        ]);

        // 1 neutral (-0.05 < compound < 0.05)
        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'sentiment_compound' => 0.00,
        ]);

        $service = new AnalyticsService();
        $dist = $service->getSentimentDistribution();

        $this->assertEquals(['Positive', 'Neutral', 'Negative'], $dist['labels']);
        $this->assertEquals([2, 1, 1], $dist['data']);
    }

    public function test_topic_distribution_returns_top_5()
    {
        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);

        $topics = ['Performance', 'Performance', 'Performance', 'UI', 'UI', 'Auth'];

        foreach ($topics as $topic) {
            Review::factory()->create([
                'dataset_id' => $dataset->id,
                'processed_status' => 'processed',
                'ml_status' => 'classified',
                'sentiment_status' => 'analyzed',
                'topic' => $topic,
            ]);
        }

        $service = new AnalyticsService();
        $dist = $service->getTopicDistribution();

        $this->assertEquals('Performance', $dist['labels'][0]);
        $this->assertEquals(3, $dist['data'][0]);
        $this->assertCount(3, $dist['labels']); // Only 3 unique topics
    }

    public function test_recent_anomalies_returns_only_bugs()
    {
        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);

        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'is_bug' => true,
            'published_at' => now(),
        ]);

        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'processed_status' => 'processed',
            'ml_status' => 'classified',
            'sentiment_status' => 'analyzed',
            'is_bug' => false,
            'published_at' => now(),
        ]);

        $service = new AnalyticsService();
        $anomalies = $service->getRecentAnomalies();

        $this->assertCount(1, $anomalies);
        $this->assertTrue($anomalies->first()->is_bug);
    }

    public function test_dashboard_receives_all_data_payloads()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $response = $this->actingAs($analyst)->get(route('analyst.analytics.index'));

        $response->assertStatus(200);
        $response->assertViewHas('overviewStats');
        $response->assertViewHas('sentimentDistribution');
        $response->assertViewHas('topicDistribution');
        $response->assertViewHas('intentDistribution');
        $response->assertViewHas('sentimentOverTime');
        $response->assertViewHas('recentAnomalies');
    }
}
