<?php

namespace Tests\Feature;

use App\Models\User;
use App\Notifications\CriticalBugDetectedNotification;
use App\Notifications\PipelineCompletedNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class NotificationCenterTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        if (!Role::where('name', 'Viewer')->exists()) {
            Role::create(['name' => 'Viewer']);
        }

        $this->user = User::factory()->create();
        $this->user->assignRole('Viewer');
    }

    // ──────────────────────────────────────────────
    // Notification Classes
    // ──────────────────────────────────────────────

    public function test_pipeline_completed_notification_stores_correct_data(): void
    {
        $this->user->notify(new PipelineCompletedNotification(
            datasetId: 42,
            stage: 'NLP',
            recordsProcessed: 150,
        ));

        $this->assertCount(1, $this->user->notifications);

        $data = $this->user->notifications->first()->data;
        $this->assertEquals('pipeline_completed', $data['type']);
        $this->assertStringContainsString('NLP', $data['title']);
        $this->assertStringContainsString('150', $data['message']);
        $this->assertEquals(42, $data['dataset_id']);
        $this->assertEquals('green', $data['color']);
        $this->assertEquals('check-circle', $data['icon']);
    }

    public function test_critical_bug_notification_stores_correct_data(): void
    {
        $this->user->notify(new CriticalBugDetectedNotification(
            reviewId: 99,
            appName: 'OPay',
            sentimentScore: -0.87,
        ));

        $this->assertCount(1, $this->user->notifications);

        $data = $this->user->notifications->first()->data;
        $this->assertEquals('critical_bug', $data['type']);
        $this->assertStringContainsString('OPay', $data['title']);
        $this->assertStringContainsString('-0.87', $data['message']);
        $this->assertEquals(99, $data['review_id']);
        $this->assertEquals('red', $data['color']);
        $this->assertEquals('exclamation-triangle', $data['icon']);
    }

    // ──────────────────────────────────────────────
    // Inbox View
    // ──────────────────────────────────────────────

    public function test_guest_cannot_access_notifications(): void
    {
        $this->get(route('notifications.index'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_notification_inbox(): void
    {
        $this->user->notify(new PipelineCompletedNotification(1, 'NLP', 10));

        $response = $this->actingAs($this->user)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertViewIs('notifications.index');
        $response->assertViewHas('notifications');
        $response->assertSee('NLP Pipeline Completed');
    }

    public function test_empty_inbox_shows_all_caught_up(): void
    {
        $response = $this->actingAs($this->user)->get(route('notifications.index'));

        $response->assertStatus(200);
        $response->assertSee('All caught up!');
    }

    // ──────────────────────────────────────────────
    // Mark As Read
    // ──────────────────────────────────────────────

    public function test_user_can_mark_single_notification_as_read(): void
    {
        $this->user->notify(new PipelineCompletedNotification(1, 'NLP', 10));
        $notification = $this->user->notifications->first();

        $this->assertNull($notification->read_at);

        $response = $this->actingAs($this->user)
            ->patch(route('notifications.read', $notification->id));

        $response->assertRedirect();
        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_user_can_mark_all_notifications_as_read(): void
    {
        $this->user->notify(new PipelineCompletedNotification(1, 'NLP', 10));
        $this->user->notify(new CriticalBugDetectedNotification(2, 'Kuda', -0.9));

        $this->assertEquals(2, $this->user->unreadNotifications()->count());

        $response = $this->actingAs($this->user)
            ->post(route('notifications.mark-all-read'));

        $response->assertRedirect();
        $this->assertEquals(0, $this->user->fresh()->unreadNotifications()->count());
    }

    // ──────────────────────────────────────────────
    // Delete
    // ──────────────────────────────────────────────

    public function test_user_can_delete_notification(): void
    {
        $this->user->notify(new PipelineCompletedNotification(1, 'NLP', 10));
        $notification = $this->user->notifications->first();

        $response = $this->actingAs($this->user)
            ->delete(route('notifications.destroy', $notification->id));

        $response->assertRedirect();
        $this->assertCount(0, $this->user->fresh()->notifications);
    }

    public function test_user_cannot_delete_another_users_notification(): void
    {
        $otherUser = User::factory()->create();
        $otherUser->notify(new PipelineCompletedNotification(1, 'NLP', 10));
        $notification = $otherUser->notifications->first();

        $response = $this->actingAs($this->user)
            ->delete(route('notifications.destroy', $notification->id));

        // Should fail because findOrFail scopes to the authenticated user's notifications
        $response->assertStatus(404);
    }

    // ──────────────────────────────────────────────
    // JSON Response (for future AJAX/SPA support)
    // ──────────────────────────────────────────────

    public function test_mark_as_read_returns_json_when_requested(): void
    {
        $this->user->notify(new PipelineCompletedNotification(1, 'NLP', 10));
        $notification = $this->user->notifications->first();

        $response = $this->actingAs($this->user)
            ->patchJson(route('notifications.read', $notification->id));

        $response->assertStatus(200);
        $response->assertJson(['success' => true]);
    }

    // ──────────────────────────────────────────────
    // Topbar Badge
    // ──────────────────────────────────────────────

    public function test_topbar_shows_unread_badge_when_notifications_exist(): void
    {
        $this->user->notify(new PipelineCompletedNotification(1, 'NLP', 10));

        $response = $this->actingAs($this->user)->get(route('viewer.dashboard'));

        $response->assertStatus(200);
        $response->assertSee('notification-badge');
    }

    public function test_topbar_hides_badge_when_no_unread_notifications(): void
    {
        $response = $this->actingAs($this->user)->get(route('viewer.dashboard'));

        $response->assertStatus(200);
        $response->assertDontSee('notification-badge');
    }
}
