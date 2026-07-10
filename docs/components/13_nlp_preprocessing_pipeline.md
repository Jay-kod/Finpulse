# Component 13: NLP Preprocessing Pipeline

## Overview
The NLP Preprocessing Pipeline acts as the bridge between raw user reviews and the platform's Machine Learning Engine. It takes raw `Review` records that have a `pending` status, sends their text content to an external FastAPI NLP microservice for cleaning and normalization, and stores the processed results back in the database.

This component is purely the **Laravel-side orchestration** of this process. It gracefully handles communication with the external FastAPI service.

## Architecture

### Database Updates
- **Migration**: `add_nlp_fields_to_reviews_table` appends three new columns to the existing `reviews` table:
  - `cleaned_content` (text, nullable): The normalized text output from the NLP service.
  - `detected_language` (string, nullable): Language code (e.g., 'en', 'es') identified by the service.
  - `word_count` (integer, nullable): The length of the cleaned text in words.
- **Model (`App\Models\Review`)**: Updated to include these new fields in the `$fillable` array and added a cast for `word_count` to `integer`.

### Service Layer (`App\Services\NlpService`)
- Encapsulates all HTTP communication with the external NLP backend using Laravel's `Http` facade.
- Configured via `config('services.nlp.url')` (defaulting to `http://127.0.0.1:8000`), allowing the URL to be set via the `.env` variable `NLP_SERVICE_URL`.
- Implements the `preprocess()` method to POST review text and return an array containing `cleaned_text`, `language`, and `word_count`.
- Handles connection timeouts and failed HTTP statuses by throwing exceptions, ensuring failures are trackable.
- Includes a `healthCheck()` method to determine if the FastAPI backend is currently online.

### Artisan Command (`App\Console\Commands\PreprocessReviews`)
- Command signature: `php artisan reviews:preprocess {--limit=100}`
- Designed to be run manually or via Laravel's Task Scheduler (Cron).
- Retrieves `pending` reviews up to the specified limit.
- Iterates over each review, passes the text to the `NlpService`, and updates the database with the results.
- Transitions the review's `processed_status` to `processed` on success, or `error` if the `NlpService` throws an exception.
- Provides real-time console feedback using a progress bar and a final summary.

### Controller & View (`Analyst\PreprocessingController`)
- **Dashboard (`/preprocessing`)**:
  - Displays high-level statistics: Total Reviews, Pending, Processed, and Errors.
  - Shows a live health status indicator for the FastAPI NLP service.
  - Includes a "Run Preprocessing" form to manually dispatch the Artisan command with a customizable batch limit.
  - Lists the 10 most recently processed reviews in a data table to verify output quality.
- **Dispatch**: The controller action `dispatch()` triggers the command synchronously using `Artisan::call()` and flashes the CLI output to the user's session.

## Testing
- `tests/Feature/Analyst/PreprocessingTest.php` covers:
  - Analyst access to the dashboard.
  - Viewer restriction (403 Forbidden).
  - Validation of HTTP request formatting sent by the `NlpService` to the mock API endpoint.
  - Proper exception throwing when the NLP service is unreachable.
  - Successful execution of the Artisan command, confirming database records are updated correctly (`processed` status and populated NLP fields).
  - Graceful degradation of the Artisan command, marking records as `error` when the API fails.
  - Controller dispatch successfully triggering the preprocessing pipeline.
- Full suite: **86 tests, 224 assertions**, all passing.
