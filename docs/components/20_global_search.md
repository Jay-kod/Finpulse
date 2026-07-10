# Component 20: Global Search

## Overview
The Global Search component provides a unified search experience across the entire platform. By leveraging the existing topbar search input, users can type a query and immediately see matched results from multiple database models organized into clear, role-aware sections.

## Architecture

### Controller (`App\Http\Controllers\SearchController`)
A dedicated controller handles global search:
- **`index(Request $request)`**: Takes a `q` parameter from the query string and performs `LIKE %...%` queries on multiple database tables simultaneously.
- **RBAC Integration**: The search results are conditionally queried based on the authenticated user's role:
  - **Admin / Super Admin**: Has access to search Users, Fintech Apps, Datasets, Reviews, and Reports.
  - **Analyst**: Can search Fintech Apps, Datasets, Reviews, and Reports.
  - **Viewer**: Restricted to searching published Reports only.

### UI Integration
#### Topbar Update (`layouts/partials/topbar.blade.php`)
- The previous static "Search placeholder" input was upgraded to a functional HTML `<form>` targeting the `/search` route via `GET`.
- It preserves the user's search query by setting `value="{{ request('q') }}"`.

#### Search Results View (`resources/views/search/index.blade.php`)
- A robust, dedicated search results page using standard Blade components (`<x-ui.card>`, `<x-ui.badge>`).
- If no query is provided, an "empty state" is shown prompting the user to search.
- If a query yields 0 results, a "No matches found" state is displayed.
- Otherwise, the results are grouped into distinct sections (Users, Fintech Apps, Datasets, Reviews, Reports) with a count next to each heading.
- Each result acts as a large clickable card navigating to that resource's edit or view page.

## Performance Considerations
- Database queries use `limit(10)` per model to prevent large queries and ensure the search page remains fast, even on large datasets.
- Simple `LIKE` queries were used for rapid implementation. For larger-scale deployments (millions of records), this architecture can be swapped out for Laravel Scout (e.g. Meilisearch or Algolia) without changing the frontend UI.

## Testing
`tests/Feature/GlobalSearchTest.php` provides test coverage for the component:
- **Authentication**: Confirms guests are redirected to login.
- **Empty States**: Tests both the empty query state and the no-results state.
- **Data Matching**: Ensures an Admin can search and match records across all 5 registered models.
- **RBAC Security**: 
  - Validates that an Analyst cannot see matched User records even when the search query matches perfectly.
  - Validates that a Viewer cannot see Fintech Apps or other administrative data, restricted solely to Reports.
