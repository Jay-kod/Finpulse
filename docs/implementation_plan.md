# Master Implementation Plan — Component-Driven Development

Analysis of Customer Sentiment Towards Nigerian Fintech Apps
Version 1.0 · July 2026

## Project Summary

Build a production-ready, modular, enterprise-grade sentiment analysis platform using
Laravel (v13/latest), MySQL, Tailwind CSS v4, Alpine.js, Chart.js, and Python FastAPI.
The platform will analyze customer sentiment toward **OPay**, **PalmPay**, and **Kuda**
using NLP and Machine Learning.

Every component will be designed → planned → implemented → tested → documented → integrated sequentially.
No shortcuts, no placeholder code, no parallel component development.

## Environment

| Tool            | Required   | Current   | Status                    |
|-----------------|-----------|-----------|---------------------------|
| PHP             | 8.3+      | 8.3+      | ✅ Ready (Laravel 13)     |
| Composer        | 2.x       | 2.10.1    | ✅ Ready                  |
| Node.js         | 18+       | 24.18.0   | ✅ Ready                  |
| npm             | 9+        | 11.16.0   | ✅ Ready                  |
| MySQL/MariaDB   | 10.4+     | 10.4.32   | ✅ Ready                  |
| Python          | 3.10+     | TBD       | ❌ Needed for ML (C13+)   |
| Tailwind CSS    | v4        | v4        | ✅ Ready                  |

## Architecture Overview

```
sentiment-analysis/
├── app/
│   ├── Components/              # All business features (C03-C23)
│   └── Shared/
│       ├── Core/                # Traits, Contracts, Enums, Services, Helpers
│       └── UI/                  # Blade components (C02)
├── ml-service/                  # Python FastAPI microservice (C13-C14)
├── resources/views/             # Blade views
├── database/                    # Migrations, seeders, factories
├── routes/                      # Route definitions
├── config/                      # App configuration
├── tests/                       # Test suite
└── docs/                        # Documentation
```

## Standard Component Structure

Every business component under `app/Components/{Name}/` follows:

```
ComponentName/
├── Controllers/
├── Models/
├── Services/
├── Repositories/
├── Actions/
├── Requests/
├── Policies/
├── Events/
├── Listeners/
├── Notifications/
├── Mail/
├── Jobs/
├── Middleware/
├── Routes/
├── Views/
├── Resources/
├── DTO/
├── Enums/
├── Traits/
├── Tests/
├── Database/
├── Config/
└── README.md
```

## Development Workflow Per Component

| Step | Action |
|------|--------|
| 1    | **Announce** — State which component I'm building |
| 2    | **Implement** — Write all production-ready code |
| 3    | **Test** — Verify the component works without errors |
| 4    | **Document** — List all files created/modified, routes, APIs, DB changes |
| 5    | **Report** — Provide a completion summary |
| 6    | **Wait** — Stop and wait for approval before proceeding |

## Component Dependency Graph

```
01: Core Shell → 02: UI Library → 03: Auth → 04: RBAC → 05: User Mgmt
→ 06: Dashboard → 07: Admin → 08: Analyst → 09: Viewer
→ 10: Fintech Apps → 11: Dataset → 12: Reviews
→ 13: NLP → 14: ML Engine → 15: Sentiment
→ 16: Analytics → 17: Reports → 18: Exports
→ 19: Notifications → 20: Search → 21: Audit Log
→ 22: Settings → 23: REST API → 24: Testing → 25: Docs
```

## Verification Plan

### After Every Component

- `php artisan test` — Run full test suite
- `php artisan route:list` — Verify routes
- `npm run build` — Verify frontend compilation
- Browser testing of pages/forms
- Theme switching (dark/light)

### ML Components (13-14)

- `pytest` tests via Python FastAPI
- ML prediction accuracy validation

## Component Details

### Component 01 — Core Application Shell
Scaffold project, configure environment, build layouts, navigation, sidebar, theme switching,
global error handling, shared infrastructure (traits, helpers, enums, contracts, services).

### Component 02 — Shared UI Library
Blade components: buttons, cards, forms, tables, feedback, navigation, data display, states.

### Component 03 — Authentication
Laravel Breeze with custom UI. Registration, login, password reset, email verification,
session management, profile management.

### Component 04 — Authorization (RBAC)
Roles: administrator, analyst, research_viewer. Granular permissions, middleware, policies.

### Component 05 — User Management
User CRUD, role assignment, status management, activity tracking, avatar upload.

### Component 06 — Dashboard Engine
Widget registry, stat cards, chart widgets, quick actions, recent activity, role-based content.

### Component 07 — Admin Workspace
Admin dashboard, system overview, user stats, ML health, system logs, settings access.

### Component 08 — Analyst Workspace
Analyst dashboard, dataset upload wizard, run predictions, view charts, generate reports.

### Component 09 — Research Viewer Workspace
Read-only dashboard, charts, analytics, report viewing and download, limited export.

### Component 10 — Fintech Application Management
Fintech app model, seeder (OPay, PalmPay, Kuda), CRUD, comparison view, per-app stats.

### Component 11 — Dataset Engine
CSV/Excel import, upload wizard, column mapping, validation, preview, statistics, history.

### Component 12 — Review Management
Review model, data table, search, filters, bulk actions, detail view, pagination.

### Component 13 — NLP Preprocessing Pipeline
Text cleaning, normalization, tokenization, stopword removal, lemmatization, TF-IDF.
Python NLTK pipeline via FastAPI.

### Component 14 — Machine Learning Engine
FastAPI microservice, training/prediction endpoints, Naive Bayes, Logistic Regression,
SVM, Random Forest, model metrics, Laravel HTTP client integration.

### Component 15 — Sentiment Analysis Module
Single/bulk prediction, confidence scores, prediction history, result caching, distribution charts.

### Component 16 — Analytics Engine
KPI cards, sentiment distribution, monthly trends, app comparison, model performance charts.

### Component 17 — Reporting Engine
PDF (DomPDF), Excel (Maatwebsite), CSV reports, print-friendly views, scheduled generation.

### Component 18 — Export Engine
Unified export service: predictions, analytics, chart images, reviews. Download management.

### Component 19 — Notification Center
Database/toast/email notifications, real-time updates, unread counter, notification preferences.

### Component 20 — Global Search
Multi-model search (reviews, users, datasets, predictions), advanced filters, saved filters.

### Component 21 — Audit Log
Automatic logging via model events, track all significant actions, admin viewer, per-user timeline.

### Component 22 — Settings Engine
Application/appearance/email/API/ML/backup settings. Key-value model with types.

### Component 23 — REST API
Versioned (v1), Sanctum auth, rate limiting, standardized responses, documentation.

### Component 24 — Testing
Unit, feature, API, integration tests. Coverage report. ~150-200 tests.

### Component 25 — Documentation
Installation, architecture, developer guide, API docs, deployment, database schema, user manual.
