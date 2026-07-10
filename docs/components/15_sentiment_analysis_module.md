# Component 15: Sentiment Analysis Module

## Overview
The Sentiment Analysis Module is the third and final stage of the data processing pipeline. It processes reviews that have successfully completed Stage 2 (ML Classification), dispatching them to the external FastAPI microservice to extract granular sentiment scores (Positive, Negative, Neutral, and Compound).

This component provides the Laravel-side orchestration to fetch, store, and display this sentiment data.

## Architecture

### Database Updates
- **Migration**: `add_sentiment_fields_to_reviews_table` appends five new columns to the `reviews` table:
  - `sentiment_status` (enum: 'pending', 'analyzed', 'error'): Tracks the state of the review within the sentiment pipeline. Defaults to 'pending'.
  - `sentiment_positive` (decimal 5,4, nullable): Confidence score for positive sentiment (e.g., 0.8500).
  - `sentiment_negative` (decimal 5,4, nullable): Confidence score for negative sentiment (e.g., 0.1000).
  - `sentiment_neutral` (decimal 5,4, nullable): Confidence score for neutral sentiment (e.g., 0.0500).
  - `sentiment_compound` (decimal 5,4, nullable): Overall normalized score (typically -1.0 to 1.0).
- **Model (`App\Models\Review`)**: Added these fields to the `$fillable` array and cast the four score fields to `float`.

### Service Layer (`App\Services\SentimentService`)
- Manages HTTP POST requests to the FastAPI backend (`/api/sentiment`).
- Reads `config('services.nlp.url')` (reusing the common microservice URL).
- `analyze(string $cleanedText)` method transmits the text and returns an associative array of the four floating-point scores.
- Implements strict timeout (20s) and retry (2 attempts) logic to handle transient network issues.

### Artisan Command (`App\Console\Commands\AnalyzeSentiment`)
- Command signature: `php artisan reviews:sentiment {--limit=100}`
- Filters the `reviews` table for records where `ml_status = 'classified'` AND `sentiment_status = 'pending'`. This enforces strict linear pipeline execution.
- Iterates over eligible reviews, calls `SentimentService::analyze()`, and updates the database records with the decimal scores.
- Catches exceptions from the service layer gracefully, marking failed records as `error` so the batch process can continue uninterrupted.

### Controller & View (`Analyst\PreprocessingController`)
- **Dashboard (`/preprocessing`)**:
  - The "Data Pipeline Management" dashboard was expanded to include "Stage 3: Sentiment".
  - Added statistical cards tracking "Awaiting Sentiment" reviews, alongside processed counts and errors.
  - Added a third manual dispatch form for triggering the `reviews:sentiment` Artisan command.
  - The Activity Logs table now visualizes the sentiment results. It calculates a color-coded label (Positive/Green, Negative/Red, Neutral/Gray) based on the `sentiment_compound` score (thresholds at >= 0.05 and <= -0.05), and displays the raw decimal values.
- **Dispatch**: Added `dispatchSentiment()` method to trigger the command via the UI.

## Testing
- `tests/Feature/Analyst/SentimentAnalysisTest.php` covers:
  - Mocking the HTTP endpoint to verify `SentimentService` properly formats the JSON request and parses the decimal response.
  - Exception handling when the `/api/sentiment` endpoint returns a 500 status.
  - Validation that the Artisan command accurately respects pipeline ordering (ignoring reviews that haven't passed ML classification).
  - Verification of database persistence for the decimal scores.
  - Controller dispatch successfully triggering the sentiment pipeline.
- Full suite: **96 tests, 256 assertions**, all passing.
