# Component 08: Analyst Workspace

## Overview
The Analyst Workspace provides a dedicated Analytics Hub for users with the `Analyst`, `Admin`, or `Super Admin` role. Unlike the general Dashboard (Component 06), which provides high-level KPIs for all users, this workspace offers granular sentiment breakdowns by app feature, raw review records with NLP confidence scores and extracted keywords, and advanced filtering scaffolds.

## Architecture

### Controller
`app/Http/Controllers/Analyst/AnalyticsController.php` powers the Analytics Hub:
- The `index` method prepares two structured mock datasets:
  1. `featureSentiment`: A breakdown of sentiment percentages by app feature (Login, Transfer, Customer Service, Card Request).
  2. `detailedReviews`: Individual review records with raw text, extracted keywords, sentiment labels, confidence scores, and timestamps.

### Routing
In `routes/web.php`:
- A new route group prefixed with `analytics` is protected by `['auth', 'role:Super Admin|Admin|Analyst']` middleware.
- `GET /analytics` maps to `AnalyticsController@index`.
- Viewers are explicitly excluded from this route group and will receive a 403 Forbidden.

### Frontend Assets
- `resources/js/analytics.js` initializes a **Stacked Bar Chart** using Chart.js. The chart data is passed from Blade via a `data-chart-data` attribute on the canvas element, keeping the JS logic decoupled from any server-side rendering.
- The chart displays the percentage distribution of Positive, Neutral, and Negative sentiment across different app features (Login, Transfer, Customer Service, Card Request).
- `vite.config.js` was updated to include `resources/js/analytics.js` as a separate entry point.

### Views
`resources/views/analyst/analytics/index.blade.php` contains:
- **Filter Bar**: Scaffold dropdowns for App selection and Date Range filtering, plus a "Filter Data" button.
- **Feature Sentiment Chart**: A full-width stacked bar chart canvas.
- **Processed Review Records Table**: A detailed data table using `<x-ui.table>` components showing review IDs, raw text, extracted keyword tags, sentiment badges with confidence scores, and timestamps. Includes a pagination scaffold.

### Sidebar Integration
- Updated `sidebar-nav.blade.php` to change the Analytics link URL from `#` to `/analytics`.
- Restricted the Analytics link visibility to `Analyst`, `Admin`, and `Super Admin` roles (removed `Viewer`).

## Testing
- `tests/Feature/Analyst/AnalyticsTest.php` contains 6 tests:
  - `test_analyst_can_access_analytics_page`: Verifies Analysts get 200 OK and see the correct view.
  - `test_admin_can_access_analytics_page`: Verifies Admins also have access.
  - `test_viewer_cannot_access_analytics_page`: Verifies Viewers are blocked with 403.
  - `test_guest_cannot_access_analytics_page`: Verifies unauthenticated users are redirected to login.
  - `test_analytics_page_contains_chart_canvas`: Verifies the Chart.js canvas is rendered.
  - `test_analytics_page_contains_review_table`: Verifies the mock review data appears in the table.
- Full suite: **56 tests, 149 assertions**, all passing.
