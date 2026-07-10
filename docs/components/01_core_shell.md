# Component 01: Core Application Shell & Design System

## Overview
This component establishes the foundational infrastructure for the Fintech Sentiment Analysis Platform. It provides the global configuration, unified layouts, shared interfaces, and common helpers required by all subsequent modules.

## Features Implemented
- **Tailwind CSS v4 & Alpine.js:** Configured for the dashboard layout.
- **Global Config:** `config/sentiment.php` manages shared constants (API URLs, roles, fallback values).
- **Core Repository Pattern:** `RepositoryInterface` and abstract `BaseRepository` to standardize database queries across all components.
- **Shared Traits:** 
  - `Auditable`: Tracks created_by/updated_by.
  - `HasUuid`: Automatically assigns UUIDs to models.
  - `HasSlug`: Manages URL-friendly slugs.
- **API Helpers:** `ResponseHelper` standardizes JSON outputs (`success()` and `error()`).
- **Global Exception Handler:** Intercepts common exceptions (Validation, ModelNotFound) in `bootstrap/app.php` and formats them consistently for API consumers.
- **Layouts & Navigation:** Responsive sidebar with mobile overlay, topbar, theme toggling, and generic breadcrumbs setup.

## Technical Notes
- **PHP Version:** Requires PHP 8.3+.
- **Database:** Uses MariaDB (via XAMPP).
- **Frontend Stack:** Vue is removed, replaced with Alpine.js for lightweight interactions within Blade views.
- **Testing:** Core components are unit-tested in `Tests\Unit\Shared\Core`.

## Next Steps
This shell will be utilized by **Component 02 (Shared UI Library & Form Components)**, which will build the reusable form inputs, datatables, and cards for the application.
