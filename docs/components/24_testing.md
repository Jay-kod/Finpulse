# Component 24: Testing

## Overview
The Fintech Sentiment Analyzer platform adopts a rigorous Test-Driven Development (TDD) and continuous testing strategy. Instead of writing tests as an afterthought, comprehensive test suites were built concurrently with every component.

## Current Test Suite Status
The application currently boasts a **100% pass rate** across its test suite.
- **Tests Passed:** 131
- **Total Assertions:** 406

## Testing Architecture

### Framework
- **PHPUnit / Pest**: The primary testing frameworks utilized natively by Laravel.
- **In-Memory SQLite**: The test environment is configured (`phpunit.xml`) to use an in-memory SQLite database, guaranteeing fast, isolated database transactions without polluting the development MySQL database.

### Test Categories

#### 1. Feature Tests (`tests/Feature/`)
Feature tests evaluate larger portions of the code, such as HTTP requests, controller logic, and complex database interactions.
- **Authentication & Authorization**: Validates login, registration, and strict Spatie Role-Based Access Control (RBAC) across Analyst and Admin routes.
- **API Tests**: Validates the `api/v1` endpoints and Sanctum token generation/revocation (`FintechAppApiTest.php`, `ApiTokenManagementTest.php`).
- **Core Workflows**: Tests the end-to-end flow of uploading datasets, processing reviews, and generating reports.

#### 2. Unit Tests (`tests/Unit/`)
Unit tests are used for small, isolated pieces of logic.
- **Settings Engine**: Validates the encryption/decryption, type-casting, and caching layers of the `Setting` model.
- **Audit Logging**: Validates that Eloquent model lifecycle hooks correctly trigger the `Auditable` trait without HTTP overhead.

### Best Practices Enforced
- **RefreshDatabase**: Every database-dependent test uses the `RefreshDatabase` trait to ensure a pristine state.
- **Factories & Seeders**: `UserFactory`, `FintechAppFactory`, `DatasetFactory`, and others are heavily utilized to scaffold complex relational states quickly.
- **Role Scaffolding**: Test setup blocks explicitly seed necessary roles (`Admin`, `Analyst`) to accurately simulate authorized behavior.

## Running Tests
To run the full suite:
```bash
php artisan test
```

To run a specific test filter:
```bash
php artisan test --filter=SettingsTest
```
