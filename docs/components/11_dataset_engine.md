# Component 11: Dataset Engine

## Overview
The Dataset Engine provides the interface and underlying schema to manage batches of raw data (e.g., Google Play Store review scrapes). Each `Dataset` is assigned to a `FintechApp` via a foreign key relationship. This ensures traceability—allowing the platform to track exactly which reviews originated from which data ingestion event. 

The Dataset Engine is accessible to `Super Admin`, `Admin`, and `Analyst` roles.

## Architecture

### Model & Database
- **Migration**: `create_datasets_table` establishes the schema:
  - `fintech_app_id`: Foreign key linked to `fintech_apps` (`cascadeOnDelete`).
  - `name`: string (e.g., "OPay Q1 Reviews")
  - `source`: string (e.g., "Google Play", "App Store")
  - `status`: enum ('pending', 'processing', 'completed', 'failed')
  - `record_count`: integer
  - Soft deletes are enabled to prevent accidental loss of historical batch tracking.
- **Models**:
  - `App\Models\Dataset`: Casts `record_count` to integer and defines a `belongsTo` relationship to `FintechApp`.
  - `App\Models\FintechApp`: Updated to include a `hasMany` relationship for `datasets`.
- **Factory**: `DatasetFactory` allows for rapid generation of mock datasets assigned to randomized apps.

### Controller & Routing
- `App\Http\Controllers\Analyst\DatasetController` manages CRUD operations.
- Bound to `Route::resource('datasets')` inside the `['auth', 'role:Super Admin|Admin|Analyst']` middleware group.
- The `create` and `edit` methods fetch only `is_active = true` FintechApps to populate the assignment dropdown.
- Validation logic enforces proper enums for `status` and ensures the `fintech_app_id` exists in the database.

### Views
- **Index (`analyst.datasets.index`)**: Leverages `<x-ui.table>` to display all imported datasets. It eager-loads the related `fintechApp` to display the app name efficiently. It uses styled badges to visualize the `status` (e.g., green `success` for 'completed', yellow `warning` for 'processing').
- **Create & Edit (`analyst.datasets.create`, `edit`)**: Two-column responsive form layouts containing:
  - A `<select>` dropdown populated with active apps for the `fintech_app_id`.
  - Input fields for `name`, `source` (dropdown), `record_count` (number), and `status` (dropdown).

### Sidebar Integration
- The `Datasets` sidebar link in `sidebar-nav.blade.php` was updated to point to `/datasets`.

## Testing
- `tests/Feature/Analyst/DatasetTest.php` covers:
  - Analysts and Admins can view the index page.
  - The index page correctly displays dataset information and eager-loaded App names.
  - Analysts can successfully create a new Dataset, with the correct foreign key link.
  - Analysts can successfully update a Dataset.
  - Analysts can successfully soft-delete a Dataset.
  - Viewers get a 403 Forbidden response.
- Full suite: **74 tests, 191 assertions**, all passing.
