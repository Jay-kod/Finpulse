# Component 04: Authorization (RBAC)

## Overview
This component implements Role-Based Access Control (RBAC) using the `spatie/laravel-permission` package (v8.3). It governs who can access which features across the platform.

## Roles & Permissions

| Role | Permissions | Description |
|------|-------------|-------------|
| **Super Admin** | *Bypasses all checks* | Full platform access |
| **Admin** | `manage users`, `manage configuration`, `run analysis`, `view reports` | Manages users, config, and all analysis |
| **Analyst** | `run analysis`, `view reports` | Runs sentiment analysis and views reports |
| **Viewer** | `view reports` | Read-only access to dashboards and reports |

## Architecture

### Package
- `spatie/laravel-permission ^8.3` installed via Composer.
- Config published to `config/permission.php`.
- Migration creates `roles`, `permissions`, `model_has_roles`, `model_has_permissions`, and `role_has_permissions` tables.

### Model Integration
- `app/Models/User.php` now uses the `HasRoles` trait from Spatie.

### Middleware
- Three middleware aliases registered in `bootstrap/app.php`:
  - `role` → `RoleMiddleware`
  - `permission` → `PermissionMiddleware`
  - `role_or_permission` → `RoleOrPermissionMiddleware`
- Usage: `Route::middleware('role:Admin')->group(...)` or `Route::middleware('permission:manage users')->group(...)`.

### Registration Flow
- New users registered via `/register` are automatically assigned the **Viewer** role in `RegisteredUserController@store`.

### UI Integration
- **Topbar**: Displays the user's role as a small label beneath their name (e.g., "SUPER ADMIN", "VIEWER").
- **Sidebar**: Navigation items are wrapped with `@hasanyrole` directives. Admin-only links (Users, Settings, Fintech Apps) are hidden from Analysts and Viewers.

### Seeder
- `database/seeders/RolesAndPermissionsSeeder.php` creates all roles, permissions, and three test accounts:
  - `admin@example.com` / `password` → Super Admin
  - `analyst@example.com` / `password` → Analyst
  - `viewer@example.com` / `password` → Viewer

## Testing
- `tests/Feature/AuthorizationTest.php` — 2 tests verifying:
  1. New registered users receive the Viewer role.
  2. Admin role correctly grants multiple permissions.
- Full suite: **39 tests, 106 assertions** — all passing.
