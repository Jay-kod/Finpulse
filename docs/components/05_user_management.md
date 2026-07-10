# Component 05: User Management

## Overview
Provides a comprehensive CRUD (Create, Read, Update, Delete) interface for administrators to manage users and assign roles within the sentiment analysis platform.

## Architecture

### Routes
- Resource routes registered under `/admin/users` in `routes/web.php`.
- Protected by the `auth` and `role:Super Admin|Admin` middleware, meaning only Super Admins and Admins can access these routes.

### Controller
`app/Http/Controllers/Admin/UserController.php` handles all user management logic:
- `index`: Displays a paginated list of users (15 per page). Supports searching by name or email, and filtering by role.
- `create` / `store`: Validates input and creates a new user, assigning the selected role.
- `edit` / `update`: Updates user details. The password field is optional; if left blank, the existing password is kept. Updates the user's role using `syncRoles`.
- `destroy`: Deletes a user. Implements safety guards to prevent users from deleting their own accounts or Super Admin accounts.

### Views
- `resources/views/admin/users/index.blade.php`: The main list view. Uses `<x-ui.table>` components to display user data, roles (as colored badges), and action buttons. Includes a search bar and role filter.
- `resources/views/admin/users/create.blade.php`: A form to add a new user. Uses `<x-ui.form-group>`, `<x-ui.input>`, and `<x-ui.select>`.
- `resources/views/admin/users/edit.blade.php`: A form to edit an existing user. Includes a prominent "Danger Zone" card for account deletion.

### Gate bypass
- Added `Gate::before` in `App\Providers\AppServiceProvider.php` to allow `Super Admin` to implicitly bypass all authorization checks, ensuring seamless access across the platform for super users.

### Navigation
- The sidebar link for "Users" (`resources/views/layouts/partials/sidebar-nav.blade.php`) now points to `/admin/users`.

## Testing
- `tests/Feature/UserManagementTest.php` contains comprehensive tests verifying:
  - Admins can view the user list.
  - Viewers (non-admins) receive a 403 Forbidden response.
  - Admins can successfully create users and assign roles.
  - Admins can update a user's role.
  - Admins can delete users.
  - Admins cannot delete their own accounts.
- Full suite: **45 tests, 123 assertions** — all passing.
