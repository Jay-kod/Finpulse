# Component 06: Dashboard Engine

## Overview
The Dashboard Engine serves as the central hub of the application post-login. It provides a high-level, visual summary of the platform's key performance indicators (KPIs) and recent activities. Because the underlying data models (datasets, NLP analysis) are not yet implemented, this component utilizes a production-ready mock data scaffold.

## Architecture

### Controller
`app/Http/Controllers/DashboardController.php` powers the dashboard:
- The `index` method defines structured arrays for `kpis` and `recentReviews`.
- These arrays simulate database queries, allowing the UI to be fully built and styled. In future components, these static arrays will be replaced with real Eloquent queries.

### Routing
- The `/dashboard` route in `routes/web.php` has been updated from a simple closure to point to `DashboardController@index`.
- It remains protected by the `auth` and `verified` middleware.

### Frontend Assets
- **Chart.js** (v4.5) is utilized for data visualization.
- `resources/js/dashboard.js` handles the initialization of the charts:
  1.  **Sentiment Trends**: A multi-line chart comparing sentiment over time for OPay, PalmPay, and Kuda.
  2.  **Overall Breakdown**: A doughnut chart displaying the proportion of Positive, Neutral, and Negative sentiments.
- The chart logic includes dynamic color swapping to fully support the application's Dark Mode (`.dark` class on the `<html>` element).
- `vite.config.js` was updated to include `resources/js/dashboard.js` as an entry point, which is then loaded via `@vite(['resources/js/dashboard.js'])` in the view.

### Views
`resources/views/dashboard.blade.php` was completely overhauled:
- **KPI Row**: Four cards displaying critical metrics (Total Reviews, Average Sentiment, Active Datasets, Anomalies Detected) with contextual trend indicators (e.g., up/down arrows colored green/red).
- **Charts Row**: A responsive grid containing the Line Chart and the Doughnut Chart.
- **Recent Activity**: A stylized data table using `<x-ui.table>` components to show a real-time feed of the latest analyzed reviews, complete with sentiment badges and calculated scores.

## Testing
- `tests/Feature/DashboardTest.php` ensures the route remains secure.
- Tests verify that unauthenticated users are redirected to login, while authenticated users can access the dashboard and see the expected UI components ("Dashboard Overview", "Sentiment Trends").
- Full suite: **47 tests, 129 assertions** — all passing.
