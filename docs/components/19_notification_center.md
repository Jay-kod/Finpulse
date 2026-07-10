# Component 19: Notification Center

## Overview
The Notification Center provides a centralized system for alerting users about important events throughout the platform. It leverages Laravel's built-in Notification system with database storage, ensuring notifications persist across sessions and can be managed (read, deleted) by each user individually.

## Architecture

### Database Layer
Uses Laravel's standard `notifications` table (UUID primary key, polymorphic `notifiable` relationship, JSON `data` column, and `read_at` timestamp). The `User` model already includes the `Notifiable` trait, so no model changes were required.

### Notification Classes (`App\Notifications`)

#### `PipelineCompletedNotification`
Dispatched when a pipeline stage (NLP, ML, or Sentiment) finishes processing a batch. Stores the dataset ID, stage name, and number of records processed. Uses a green `check-circle` icon.

#### `CriticalBugDetectedNotification`
Dispatched when a review with extremely negative sentiment is detected (potential critical bug). Stores the review ID, app name, and sentiment score. Uses a red `exclamation-triangle` icon.

Both notifications deliver via the `database` channel and serialize a consistent payload structure:
```php
[
    'type'    => 'pipeline_completed' | 'critical_bug',
    'title'   => '...',
    'message' => '...',
    'icon'    => 'check-circle' | 'exclamation-triangle',
    'color'   => 'green' | 'red',
    // ...additional contextual IDs
]
```

### Controller (`App\Http\Controllers\NotificationController`)
A dedicated controller handles all notification management for the authenticated user:
- **`index()`**: Paginated list of all notifications (15 per page).
- **`markAsRead(string $id)`**: Marks a single notification as read. Returns JSON `{ success: true }` when the request accepts JSON (enabling future AJAX integration).
- **`markAllAsRead()`**: Marks every unread notification as read in a single operation.
- **`destroy(string $id)`**: Permanently deletes a notification. Uses `findOrFail` scoped to the authenticated user, preventing cross-user deletion.

### Routes (`routes/web.php`)
All notification routes are registered inside the `auth` middleware group, making them accessible to any authenticated user regardless of role:
```
GET    /notifications                  → notifications.index
PATCH  /notifications/{id}/read        → notifications.read
POST   /notifications/mark-all-read    → notifications.mark-all-read
DELETE /notifications/{id}             → notifications.destroy
```

### UI Integration

#### Topbar Bell Icon (`layouts/partials/topbar.blade.php`)
The static bell icon placeholder was replaced with a live notification bell:
- Links directly to the Notification Inbox (`notifications.index`).
- Displays a red animated badge (`animate-pulse`) with the unread count when > 0.
- Badge shows `99+` when the count exceeds 99.
- Badge is hidden when there are zero unread notifications.

#### Notification Inbox (`resources/views/notifications/index.blade.php`)
A full-page inbox view that renders each notification as a card with:
- **Color-coded icon** based on the notification's `color` and `icon` payload fields.
- **Unread indicator**: Unread notifications have a left accent border and a pulsing dot.
- **Read notifications**: Displayed at reduced opacity with hover-to-reveal.
- **Actions**: Mark as read (checkmark) and delete (trash) buttons per notification.
- **Bulk action**: "Mark All as Read" button in the header when unread notifications exist.
- **Empty state**: A friendly "All caught up!" message with an illustration when no notifications exist.
- **Pagination**: Standard Laravel pagination when the notification list exceeds 15 items.

## Performance Considerations
- Notification queries use Laravel's built-in `morphMany` relationship which is indexed via the `notifiable_type` and `notifiable_id` columns.
- The `unreadNotifications` count in the topbar executes on every authenticated page load. For high-traffic deployments, this could be cached in a session variable or served via an AJAX endpoint.

## Testing
`tests/Feature/NotificationCenterTest.php` provides comprehensive coverage:
- **Data integrity**: Verifies both notification classes serialize the correct payload structure.
- **Auth guard**: Confirms guests are redirected to login.
- **Inbox rendering**: Tests that notifications appear and the empty state shows correctly.
- **Mark as read**: Tests single and bulk read operations.
- **Deletion**: Tests successful deletion and cross-user ownership protection (404).
- **JSON API**: Verifies `markAsRead` returns JSON when requested via `Accept: application/json`.
- **Topbar badge**: Confirms the badge appears/disappears based on unread count.
