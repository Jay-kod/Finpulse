# Component 12: Review Management

## Overview
The Review Management component introduces the `Review` model, representing a single, atomic piece of user feedback (e.g., an individual review from the Google Play Store). 

Each `Review` belongs to a `Dataset`, completing the core data hierarchy of the platform: 
`FintechApp` (OPay) -> `Dataset` (OPay Q1 Reviews) -> `Review` (User: "App keeps crashing").

This CRUD interface allows Analysts to manually view, insert, or modify individual records, though in production, most reviews will be inserted automatically via the data scraping pipeline.

## Architecture

### Model & Database
- **Migration**: `create_reviews_table` establishes the schema:
  - `dataset_id`: Foreign key linked to `datasets` (`cascadeOnDelete`).
  - `source_id`: string (nullable, stores original store ID).
  - `author_name`: string (nullable).
  - `rating`: integer (nullable, usually 1-5).
  - `content`: text (the core review body).
  - `processed_status`: enum ('pending', 'processed', 'error').
  - `published_at`: timestamp (nullable, original posting date).
  - Soft deletes are enabled.
- **Models**:
  - `App\Models\Review`: Casts `rating` to integer and `published_at` to datetime. Defines a `belongsTo` relationship to `Dataset`.
  - `App\Models\Dataset`: Updated to include a `hasMany` relationship for `reviews`.
- **Factory**: `ReviewFactory` allows for rapid generation of mock reviews assigned to factory-generated datasets.

### Controller & Routing
- `App\Http\Controllers\Analyst\ReviewController` manages CRUD operations.
- Bound to `Route::resource('reviews')` inside the `['auth', 'role:Super Admin|Admin|Analyst']` middleware group.
- The `index` method uses `.paginate(15)` because review counts will scale massively.
- The `create` and `edit` methods fetch datasets, eager-loading their parent `fintechApp`, to construct contextually rich dropdowns (e.g., "OPay - Q1 Reviews").

### Views
- **Index (`analyst.reviews.index`)**: 
  - Leverages `<x-ui.table>` to display reviews.
  - Eager-loads `dataset.fintechApp` to avoid N+1 queries.
  - Uses CSS truncation (`line-clamp-2` and `truncate max-w-[150px]`) to ensure the table remains readable despite potentially massive review text lengths.
  - Displays dynamic badges for the `processed_status`.
- **Create & Edit (`analyst.reviews.create`, `edit`)**: Two-column responsive form layouts containing all fields, utilizing the customized `<x-ui.*>` form inputs.

### Sidebar Integration
- The `Reviews` sidebar link in `sidebar-nav.blade.php` was updated to point to `/reviews`.

## Testing
- `tests/Feature/Analyst/ReviewTest.php` covers:
  - Analysts and Admins can view the index page.
  - Viewers get a 403 Forbidden response.
  - Analysts can successfully create a new Review linked to a Dataset.
  - Analysts can successfully update a Review.
  - Analysts can successfully soft-delete a Review.
- Full suite: **79 tests, 204 assertions**, all passing.
