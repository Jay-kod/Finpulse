# Component 10: Fintech Application Management

## Overview
This component implements the foundational `FintechApp` database model and its accompanying CRUD interface. This model serves as the core entity within the platform (representing apps like OPay, PalmPay, and Kuda), to which all future datasets, scraped reviews, and analytics reports will be linked.

## Architecture

### Model & Database
- **Migration**: `create_fintech_apps_table` defines the schema:
  - `name`: string (e.g., 'OPay')
  - `package_name`: string, unique (e.g., 'team.opay.pay')
  - `description`: text, nullable
  - `logo_url`: string, nullable
  - `is_active`: boolean (defaults to true; used later to toggle active review scraping)
  - Incorporates `$table->softDeletes()` to ensure related analytics data is never orphaned if an app is "deleted".
- **Model**: `App\Models\FintechApp` uses `SoftDeletes` and defines fillable properties. Also casts `is_active` to a boolean.
- **Factory**: `FintechAppFactory` generates realistic dummy data for testing using Faker.

### Controller & Routing
- `App\Http\Controllers\Admin\FintechAppController` manages the resource.
- Placed behind the `['auth', 'role:Super Admin|Admin']` middleware group via `Route::resource('fintech-apps')` in `routes/web.php`.
- The controller handles validation natively, enforcing uniqueness on `package_name` (ignoring the current record during updates).

### Views
- **Index (`admin.fintech-apps.index`)**: A cleanly designed data table (`<x-ui.table>`) listing apps with their logos (or a stylized initial), active status badges, and action buttons for editing/deleting. Includes empty state handling.
- **Create (`admin.fintech-apps.create`)**: Form using customized `<x-ui.*>` form components. Includes contextual helper text (e.g., explaining `package_name`).
- **Edit (`admin.fintech-apps.edit`)**: Similar form structure, populated with the existing `FintechApp` instance's data.

### Sidebar Integration
- Updated `sidebar-nav.blade.php` to point the 'Fintech Apps' menu item to `/admin/fintech-apps`.

## Testing
- `tests/Feature/Admin/FintechAppTest.php` covers:
  - Admin access to the index page.
  - Analyst/Viewer rejection (403).
  - Successful creation of an app.
  - Validation enforcement on unique `package_name`.
  - Successful updates (including toggling `is_active`).
  - Successful soft-deletion of an app.
- Full suite: **69 tests, 178 assertions**, all passing.
