# Component 18: Export Engine

## Overview
The Export Engine provides raw data extraction capabilities. It allows Analysts to download the fully processed `Review` data as a CSV file. Crucially, the Export Engine uses PHP's output streaming, ensuring that downloading tens of thousands of reviews does not exhaust the server's memory.

## Architecture

### Service Layer (`App\Services\ExportService`)
The `ExportService` is designed to be highly generic and robust:
- **`exportReviewsToCsv(Builder $query, string $filename)`**: Accepts an active query builder and a filename. 
- It wraps the execution in a `StreamedResponse`, opening `php://output`.
- It uses Laravel's `chunk(500)` feature to load records from the database in small batches.
- It automatically handles the eager loading of `$review->dataset->fintechApp` to prevent N+1 database queries during the loop.
- It iterates through the chunks, calling `fputcsv()` to write data directly to the stream.

### Controller (`App\Http\Controllers\Analyst\ExportController`)
The controller acts as the bridge between user intents and the Export Service:
- **`exportAll()`**: Retrieves all reviews where `sentiment_status = 'analyzed'` and triggers the download.
- **`exportReport(Report $report)`**: Injects the `AnalyticsService` to apply the exact same filtering parameters defined in the specific `Report` model. It then passes that scoped query to the Export Service.

### UI Integration
- Added an "Export All CSV" button to the main Analytics Dashboard (`resources/views/analyst/analytics/index.blade.php`).
- Added an "Export CSV" button to the Saved Report View (`resources/views/analyst/reports/show.blade.php`), positioned next to the Print PDF button.

## Performance Considerations
Because sentiment analysis and classification datasets grow rapidly, standard implementations (`Review::all()`) would lead to fatal Out of Memory (OOM) errors. By combining `chunk()` with `StreamedResponse`, memory utilization remains flat regardless of whether the system is exporting 100 or 1,000,000 records.

## Testing
- `tests/Feature/Analyst/ExportEngineTest.php` ensures that:
  - Both routes respond with HTTP 200 and the correct `text/csv` headers.
  - The content streamed contains the expected headers and data formatting.
  - The scoping logic in `exportReport` successfully filters out reviews that do not belong to the selected application.
