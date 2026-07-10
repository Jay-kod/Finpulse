<?php

namespace Tests\Feature\Analyst;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DatasetTest extends TestCase
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

    public function test_analyst_can_view_datasets_index()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create(['name' => 'Test App']);
        Dataset::factory()->create(['name' => 'Test Dataset', 'fintech_app_id' => $app->id]);

        $response = $this->actingAs($analyst)->get(route('analyst.datasets.index'));

        $response->assertStatus(200);
        $response->assertSee('Test Dataset');
        $response->assertSee('Test App');
    }

    public function test_viewer_cannot_access_datasets()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $response = $this->actingAs($viewer)->get(route('analyst.datasets.index'));

        $response->assertStatus(403);
    }

    public function test_analyst_can_create_dataset()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create();

        $payload = [
            'fintech_app_id' => $app->id,
            'name' => 'New Q3 Dataset',
            'source' => 'Google Play',
            'status' => 'pending',
            'record_count' => '5000',
        ];

        $response = $this->actingAs($analyst)->post(route('analyst.datasets.store'), $payload);

        $response->assertRedirect(route('analyst.datasets.index'));
        $this->assertDatabaseHas('datasets', [
            'name' => 'New Q3 Dataset',
            'fintech_app_id' => $app->id,
        ]);
    }

    public function test_analyst_can_update_dataset()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id, 'status' => 'pending']);

        $payload = [
            'fintech_app_id' => $app->id,
            'name' => 'Updated Dataset',
            'source' => 'App Store',
            'status' => 'completed',
            'record_count' => '1000',
        ];

        $response = $this->actingAs($analyst)->put(route('analyst.datasets.update', $dataset), $payload);

        $response->assertRedirect(route('analyst.datasets.index'));
        $this->assertDatabaseHas('datasets', [
            'id' => $dataset->id,
            'name' => 'Updated Dataset',
            'status' => 'completed',
        ]);
    }

    public function test_analyst_can_delete_dataset()
    {
        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        $app = FintechApp::factory()->create();
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);

        $response = $this->actingAs($analyst)->delete(route('analyst.datasets.destroy', $dataset));

        $response->assertRedirect(route('analyst.datasets.index'));
        $this->assertSoftDeleted('datasets', [
            'id' => $dataset->id,
        ]);
    }
}
