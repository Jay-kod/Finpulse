# Component 17: Reporting Engine

## Overview
The Reporting Engine allows Analysts to save specific analytic configurations and filters as "Reports". Instead of reapplying the same filters every time an Analyst wants to view metrics for a specific Fintech App or date range, they can create a named report (e.g., "Kuda Q2 Bugs") and access that specific dashboard view with one click.

## Architecture

### 1. Database & Models
- **`reports` table**: Stores `id`, `title`, `description`, `parameters` (JSON column), and `user_id`.
- **`App\Models\Report`**: The Eloquent model. The `parameters` attribute is automatically cast to an `array`.

### 2. Service Layer Modification
- The `AnalyticsService` was upgraded to accept a `$filters` array on all of its methods.
- **`applyFilters($query, array $filters)`**: A protected method that intercepts base queries and applies `app_id`, `start_date`, and `end_date` scoping if they are present in the filters array.
- This ensures that all charts (Topic Distribution, Sentiment Trend, etc.) respect the report's configuration.

### 3. Controller & Views
- **`App\Http\Controllers\Analyst\ReportController`**: Handles standard CRUD operations.
  - `index()`: Lists all saved reports.
  - `create()` / `store()`: Provides a form to configure a new report. Empty filters are removed to ensure a clean JSON payload.
  - `show(Report $report, AnalyticsService $service)`: Passes the report's stored parameters into the `AnalyticsService` and renders a specialized analytics dashboard scoped to that data.
- **Views**:
  - `reports/index.blade.php`: Table view of saved configurations.
  - `reports/create.blade.php`: Form utilizing `x-ui` components to select an App and Date Range.
  - `reports/show.blade.php`: Reuses the structure of the main Analytics Dashboard but displays the Report Title, Description, and active filter badges. It includes a "Print PDF" button which triggers the browser's native print functionality (ideal for generating static PDFs).

## Testing
- `Tests\Feature\Analyst\ReportingEngineTest` verifies:
  - Analyst access to the `/reports` route.
  - The successful creation and storage of a Report.
  - **Query Scoping**: Ensures that when a report is configured for "App A", reviews belonging to "App B" are strictly excluded from the calculations returned by the `AnalyticsService`.

## Integration
This component directly bridges the gap between raw interactive analytics (Component 16) and shareable static data. While the "Export Engine" (Component 18) handles raw CSV/PDF extraction, the Reporting Engine handles the preservation of valuable dashboard perspectives.
