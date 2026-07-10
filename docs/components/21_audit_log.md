# Component 21: Audit Log

## Overview
The Audit Log component provides robust system-wide tracking of data modifications. It tracks every time a model is created, updated, or deleted, automatically logging exactly who made the change and what values were altered. This is essential for accountability, compliance, and debugging within the application.

## Architecture

### Model & Schema (`App\Models\AuditLog`)
- **Table**: `audit_logs`
- **Fields**:
  - `id`: UUID primary key.
  - `user_id`: The ID of the authenticated user who performed the action (nullable for system actions).
  - `event`: Type of action (`created`, `updated`, `deleted`).
  - `auditable_type` & `auditable_id`: Polymorphic relation strings linking back to the original model.
  - `old_values`: JSON payload containing the attributes before the change (if applicable).
  - `new_values`: JSON payload containing the attributes after the change (if applicable).
  - `ip_address`: The IP address of the request.
  - `user_agent`: The browser/client making the request.

### Logging Mechanism (`App\Shared\Core\Traits\Auditable.php`)
- The `Auditable` trait was expanded to hook into Eloquent's `created`, `updated`, and `deleted` events.
- On `updated`, it intelligently calculates only the "dirty" (changed) values and their originals via `$model->getChanges()` and `$model->getOriginal()`.
- The trait securely verifies the existence of `created_by` and `updated_by` columns via Schema checks before attempting to set them, ensuring backward and forward compatibility with models that don't track blame directly on the row but still want an audit log record.

### UI & Access Control (`App\Http\Controllers\Admin\AuditLogController`)
- **Access**: Restricted to `Super Admin` and `Admin` roles.
- **View**: A paginated data table (`resources/views/admin/audit-logs/index.blade.php`).
- **Features**: 
  - Dynamic filtering by Event Type.
  - Search by Model Type or ID.
  - Alpine.js powered inline "Payload Viewers" that seamlessly toggle JSON blocks showing exactly what data was changed, beautifully formatted.

## Implemented Models
The `Auditable` trait has been applied to the primary resources:
- `User`
- `FintechApp`
- `Dataset`
- `Review`
- `Report`

## Testing
`tests/Feature/Admin/AuditLogTest.php` ensures that the `Auditable` trait hooks correctly fire during Eloquent saves and that unauthorized roles cannot peek at the logs.
