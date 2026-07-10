# Component 23: REST API

## Overview
The REST API component provides programmatic access to the Fintech Sentiment Analyzer platform. External systems, data pipelines, and third-party dashboards can securely interact with the platform's core datasets via this API.

## Architecture

### Authentication (Laravel Sanctum)
- The API is secured using **Laravel Sanctum**.
- Requests must include an `Authorization: Bearer {token}` header.
- Users can generate and revoke Personal Access Tokens directly from their `Profile` settings in the web UI.

### Endpoints
All endpoints are namespaced under `/api/v1/` and return `application/json`.

#### Fintech Apps
- `GET /api/v1/fintech-apps`: List all tracked applications. Supports pagination and `?active_only=1` filtering.
- `GET /api/v1/fintech-apps/{id}`: Retrieve a specific application's details.

#### Datasets
- `GET /api/v1/datasets`: List datasets. Supports filtering by `?fintech_app_id={id}` and `?status={status}`.
- `GET /api/v1/datasets/{id}`: Retrieve a specific dataset.

#### Reviews
- `GET /api/v1/reviews`: List reviews. Supports filtering by `?dataset_id={id}`, `?sentiment_label={label}`, and `?language={code}`.
- `GET /api/v1/reviews/{id}`: Retrieve a specific review.

#### Reports
- `GET /api/v1/reports`: List generated reports. Supports filtering by `?type={type}`.
- `GET /api/v1/reports/{id}`: Retrieve a specific report.

### Data Transformation (API Resources)
All endpoints utilize Eloquent API Resources located in `App\Http\Resources\Api\V1`.
- **Purpose**: These resources dictate exactly what fields are exposed to the API, ensuring internal metadata (like soft deletes) or sensitive keys are not accidentally leaked.
- **Relationships**: Relationships (like `dataset.fintechApp`) are conditionally loaded (`$this->whenLoaded`) to prevent N+1 query problems while maintaining rich payload structures.

## Testing
Comprehensive testing is located in:
- `tests/Feature/Api/V1/FintechAppApiTest.php` (Validates Sanctum middleware enforcement and JSON payload structure).
- `tests/Feature/ApiTokenManagementTest.php` (Validates the token creation and revocation UI).
