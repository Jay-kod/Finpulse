# Component 14: Machine Learning Engine

## Overview
The Machine Learning (ML) Engine represents Stage 2 of the data processing pipeline. It picks up reviews that have successfully completed Stage 1 (NLP Preprocessing) and dispatches them to an external FastAPI ML microservice. This service is responsible for determining the review's **Topic**, identifying the user's **Intent**, and flagging potential **Bugs**.

*(Note: Sentiment Analysis is handled separately in Component 15).*

This component implements the Laravel-side orchestration for this classification process.

## Architecture

### Database Updates
- **Migration**: `add_ml_fields_to_reviews_table` appends four new columns to the `reviews` table:
  - `ml_status` (enum: 'pending', 'classified', 'error'): Tracks the state of the review within the ML pipeline. Defaults to 'pending'.
  - `topic` (string, nullable): High-level categorization (e.g., 'Performance', 'Authentication', 'UI').
  - `intent` (string, nullable): Goal of the user (e.g., 'Feature Request', 'Complaint', 'Praise').
  - `is_bug` (boolean): Flag indicating if the review describes anomalous software behavior. Defaults to `false`.
- **Model (`App\Models\Review`)**: Added these fields to the `$fillable` array and cast `is_bug` to a boolean.

### Service Layer (`App\Services\MlService`)
- Manages HTTP communication with the FastAPI ML backend.
- Reads `config('services.nlp.url')` (reusing the NLP URL as they are hosted on the same microservice backend).
- `classify(string $cleanedText)` method sends a POST request with the preprocessed text and returns an array of `topic`, `intent`, and `is_bug`.
- Inherits robust timeout (20s) and retry (2 attempts) logic to handle intermittent network issues.

### Artisan Command (`App\Console\Commands\ClassifyReviews`)
- Command signature: `php artisan reviews:classify {--limit=100}`
- Filters the `reviews` table for records where `processed_status = 'processed'` AND `ml_status = 'pending'`. This ensures only cleaned reviews are classified.
- Iterates over eligible reviews, calls `MlService::classify()`, and updates the database records.
- Gracefully catches exceptions from the service layer, marking failed records as `error` and continuing batch execution.

### Controller & View (`Analyst\PreprocessingController`)
- **Dashboard (`/preprocessing`)**:
  - The previous "NLP Preprocessing" dashboard has been upgraded to a unified **"Data Pipeline Management"** dashboard.
  - Added new statistical cards to track reviews "Awaiting NLP Preprocessing" vs. "Awaiting ML Classification".
  - Split the UI into two distinct stages (Stage 1: NLP, Stage 2: ML), each with its own batch processing metrics and manual dispatch form.
  - The "Recently Processed" activity table now displays badges for both NLP and ML pipeline states, and renders extracted Topics, Intents, and Bug Flags.
- **Dispatch**: Added `dispatchMl()` method to trigger the `reviews:classify` Artisan command via the UI.

## Testing
- `tests/Feature/Analyst/MlEngineTest.php` covers:
  - API mock validation to ensure the `MlService` sends the correct JSON structure to `/api/classify`.
  - Exception handling when the ML endpoint is down (HTTP 500).
  - Validation that the Artisan command only selects reviews that have completed the NLP stage (`processed_status = 'processed'`).
  - Validation that the database is accurately updated with ML categorization data.
  - Controller dispatch successfully triggering the ML pipeline.
- Full suite: **91 tests, 240 assertions**, all passing.
