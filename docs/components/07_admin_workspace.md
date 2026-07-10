# Component 07: Admin Workspace

## Overview
The Admin Workspace (Settings Panel) provides a centralized UI for users with the `Admin` or `Super Admin` role to configure platform-wide parameters. While real APIs and NLP engines are not yet integrated, this component establishes the robust configuration layout and endpoint scaffolding required for later components.

## Architecture

### Controller
`app/Http/Controllers/Admin/SettingsController.php` manages the Settings Workspace:
- The `index` method returns the `admin.settings.index` view, populated with a static array representing default settings (Platform Name, Default Language, API Keys, etc.).
- The `update` method handles POST requests, validates the incoming data ensuring types and boundaries (e.g., `sentiment_sensitivity` must be between 0 and 1), and then simulates saving the data by redirecting back with a success flash message.

### Routing
In `routes/web.php`:
- The `/admin/settings` routes (both GET and POST) are nested within the `admin` route group.
- This inherently protects them with the `auth` and `role:Super Admin|Admin` middleware, meaning users like `Viewer` or `Analyst` cannot even access the UI, much less submit configuration changes.

### Views
`resources/views/admin/settings/index.blade.php` organizes configurations into distinct, logical sections using custom UI components:
- **General Configuration**: Platform branding and support details.
- **NLP & Analysis Preferences**: Dropdowns and sliders (simulated via number inputs with steps) for defaults.
- **API Integrations**: Password inputs for external API keys (e.g., HuggingFace, OpenAI).

The `resources/views/layouts/partials/sidebar-nav.blade.php` was updated to accurately link to `/admin/settings` and display the active state when on any child route of `admin.settings.*`.

## Testing
- `tests/Feature/Admin/SettingsTest.php` asserts the RBAC logic and controller functionality.
- Key tests:
  - `test_admin_can_access_settings_page`: Verifies Admins get a 200 OK and see the Settings UI.
  - `test_viewer_cannot_access_settings_page`: Verifies Viewers get a 403 Forbidden.
  - `test_admin_can_update_settings`: Verifies form submissions with valid data redirect back with a success flash message.
- The full test suite now stands at **50 tests, 136 assertions**, all passing.
