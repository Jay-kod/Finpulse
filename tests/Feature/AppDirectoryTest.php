<?php

namespace Tests\Feature;

use App\Models\FintechApp;
use App\Models\Dataset;
use App\Models\Review;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class AppDirectoryTest extends TestCase
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

    public function test_viewer_can_access_app_directory()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        FintechApp::factory()->create(['name' => 'OPay', 'is_active' => true]);

        $response = $this->actingAs($viewer)->get(route('viewer.apps.index'));

        $response->assertStatus(200);
        $response->assertSee('OPay');
    }

    public function test_inactive_apps_are_hidden_from_directory()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        FintechApp::factory()->create(['name' => 'Active App', 'is_active' => true]);
        FintechApp::factory()->create(['name' => 'Hidden App', 'is_active' => false]);

        $response = $this->actingAs($viewer)->get(route('viewer.apps.index'));

        $response->assertStatus(200);
        $response->assertSee('Active App');
        $response->assertDontSee('Hidden App');
    }

    public function test_viewer_can_view_app_details()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $app = FintechApp::factory()->create([
            'name' => 'Kuda Bank',
            'platform' => 'both',
            'downloads' => 1500000,
            'average_rating' => 4.25,
        ]);

        $response = $this->actingAs($viewer)->get(route('viewer.apps.show', $app));

        $response->assertStatus(200);
        $response->assertSee('Kuda Bank');
        $response->assertSee('1,500,000');
        $response->assertSee('4.3'); // number_format(4.25, 1)
    }

    public function test_app_detail_shows_reviews()
    {
        $viewer = User::factory()->create();
        $viewer->assignRole('Viewer');

        $app = FintechApp::factory()->create(['name' => 'Moniepoint']);
        $dataset = Dataset::factory()->create(['fintech_app_id' => $app->id]);
        Review::factory()->create([
            'dataset_id' => $dataset->id,
            'author_name' => 'TestReviewer',
            'rating' => 5,
            'content' => 'Absolutely fantastic app!',
        ]);

        $response = $this->actingAs($viewer)->get(route('viewer.apps.show', $app));

        $response->assertStatus(200);
        $response->assertSee('TestReviewer');
        $response->assertSee('Absolutely fantastic app!');
    }

    public function test_analyst_and_admin_can_also_access_app_directory()
    {
        $admin = User::factory()->create();
        $admin->assignRole('Admin');

        $analyst = User::factory()->create();
        $analyst->assignRole('Analyst');

        FintechApp::factory()->create(['is_active' => true]);

        $this->actingAs($admin)->get(route('viewer.apps.index'))->assertStatus(200);
        $this->actingAs($analyst)->get(route('viewer.apps.index'))->assertStatus(200);
    }

    public function test_unauthenticated_user_cannot_access_app_directory()
    {
        $response = $this->get(route('viewer.apps.index'));

        $response->assertRedirect(route('login'));
    }
}
