# Component 09: Research Viewer Workspace

## Overview
The Research Viewer Workspace provides a dedicated area for viewing finalized, curated sentiment analysis reports. It is the primary workspace designed for the `Viewer` role, though it is accessible to all authenticated users. It deliberately exposes no raw data and contains no editing capabilities.

## Architecture

### Controller
`app/Http/Controllers/ReportsController.php` manages the workspace:
- Uses a mock data store (`getMockReports`) to simulate a database containing published reports. 
- The `index` method returns a collection of these reports to the listing view.
- The `show` method looks up a specific report by ID and either renders it or throws a 404 Exception if it doesn't exist.

### Routing
In `routes/web.php`:
- The `/reports` and `/reports/{report}` routes are nested within the standard `auth` middleware group.
- This allows all logged-in roles (`Super Admin`, `Admin`, `Analyst`, `Viewer`) to access the reports without needing specific role permissions, maintaining the "read-only access for everyone" philosophy.

### Views
- **Listing (`resources/views/reports/index.blade.php`)**: A responsive card grid layout displaying published reports. Each card shows the targeted app (OPay, PalmPay, Kuda) as a styled badge, along with the title, date, excerpt, author, and a prominent "View Report" link.
- **Detail (`resources/views/reports/show.blade.php`)**: A single-report view designed for readability. It uses Tailwind Typography (`prose`) to elegantly render HTML content (simulating rich text output from a database). It includes metadata badges and a back-navigation link.
- **Sidebar Integration**: The `Reports` link in `sidebar-nav.blade.php` was updated from a `#` placeholder to point to `/reports`. 

## Testing
- `tests/Feature/ReportsTest.php` contains 7 tests verifying access across all roles and testing 404 behavior:
  - `test_viewer_can_access_reports` (200 OK)
  - `test_analyst_can_access_reports` (200 OK)
  - `test_admin_can_access_reports` (200 OK)
  - `test_guest_cannot_access_reports` (Redirect to Login)
  - `test_reports_index_displays_mock_reports` (Checks for specific mock titles)
  - `test_valid_report_show_page_renders` (200 OK and prose layout)
  - `test_invalid_report_returns_404` (404 status code)
- Full suite: **63 tests, 164 assertions**, all passing.
