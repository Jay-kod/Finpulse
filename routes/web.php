<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ui-playground', function () {
    return view('ui-playground');
});

Route::get('/p/{slug}', [\App\Http\Controllers\PageController::class, 'show'])->name('pages.show');

// Smart redirect based on role (checks all guards)
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth:web,analyst,admin', 'verified'])
    ->name('dashboard');

// Shared routes (all authenticated users)
Route::middleware('auth:web,analyst,admin')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/api-tokens', [\App\Http\Controllers\ApiTokenController::class, 'store'])->name('api-tokens.store');
    Route::delete('/api-tokens/{token}', [\App\Http\Controllers\ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');

    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read')->middleware('throttle:60,1');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read')->middleware('throttle:10,1');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy')->middleware('throttle:60,1');

    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');
});

// --- VIEWER ROUTES ---
Route::middleware(['auth:web,analyst,admin'])->prefix('viewer')->name('viewer.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'viewerDashboard'])->name('dashboard');
    Route::get('/analytics', [\App\Http\Controllers\Analyst\AnalyticsController::class, 'index'])->name('analytics.index');
    Route::get('/reports', [\App\Http\Controllers\Analyst\ReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/{report}', [\App\Http\Controllers\Analyst\ReportController::class, 'show'])->name('reports.show');
    
    // Viewer report exports
    Route::get('/export/report/{report}', [\App\Http\Controllers\Analyst\ExportController::class, 'exportReport'])->name('export.report');
    Route::get('/export/report/{report}/pdf', [\App\Http\Controllers\Analyst\ExportController::class, 'exportPdfReport'])->name('export.report.pdf');

    Route::get('/apps', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'index'])->name('apps.index');
    Route::get('/apps/{app}', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'show'])->name('apps.show');
    Route::get('/apps/{app}/reviews', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'reviews'])->name('apps.reviews');
});

// --- ANALYST ROUTES ---
Route::middleware(['auth:analyst,admin'])->prefix('analyst')->name('analyst.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'analystDashboard'])->name('dashboard');
    
    Route::get('/analytics', [\App\Http\Controllers\Analyst\AnalyticsController::class, 'index'])->name('analytics.index');
    
    Route::resource('datasets', \App\Http\Controllers\Analyst\DatasetController::class)->except(['show']);
    Route::resource('reviews', \App\Http\Controllers\Analyst\ReviewController::class)->except(['show']);
    Route::resource('reports', \App\Http\Controllers\Analyst\ReportController::class)->only(['index', 'create', 'store', 'show']);

    Route::get('/export/all', [\App\Http\Controllers\Analyst\ExportController::class, 'exportAll'])->name('export.all');
    Route::get('/export/report/{report}', [\App\Http\Controllers\Analyst\ExportController::class, 'exportReport'])->name('export.report');
    Route::get('/export/report/{report}/pdf', [\App\Http\Controllers\Analyst\ExportController::class, 'exportPdfReport'])->name('export.report.pdf');

    Route::get('/preprocessing', [\App\Http\Controllers\Analyst\PreprocessingController::class, 'index'])->name('preprocessing.index');
    Route::post('/preprocessing/dispatch', [\App\Http\Controllers\Analyst\PreprocessingController::class, 'dispatch'])->name('preprocessing.dispatch');
    Route::post('/pipeline/dispatch-ml', [\App\Http\Controllers\Analyst\PreprocessingController::class, 'dispatchMl'])->name('preprocessing.dispatch-ml');
    Route::post('/pipeline/dispatch-sentiment', [\App\Http\Controllers\Analyst\PreprocessingController::class, 'dispatchSentiment'])->name('preprocessing.dispatch-sentiment');

    Route::get('/predictions', [\App\Http\Controllers\Analyst\PredictionController::class, 'index'])->name('predictions.index');
    Route::post('/predictions/analyze', [\App\Http\Controllers\Analyst\PredictionController::class, 'analyze'])->name('predictions.analyze');

    // App Directory (within analyst guard)
    Route::get('/apps', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'index'])->name('apps.index');
    Route::get('/apps/{app}', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'show'])->name('apps.show');
    Route::get('/apps/{app}/reviews', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'reviews'])->name('apps.reviews');
});

// --- ADMIN ROUTES ---
Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('dashboard');
    
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
    
    // Add the app lookup route BEFORE the resource route so it doesn't get caught by the {fintech_app} wildcard
    Route::post('fintech-apps/lookup', [\App\Http\Controllers\Admin\AppLookupController::class, 'lookup'])->name('fintech-apps.lookup');
    Route::resource('fintech-apps', \App\Http\Controllers\Admin\FintechAppController::class)->except(['show']);
    
    Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
    
    Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class)->only(['index', 'show', 'edit', 'update']);

    // App Directory (within admin guard)
    Route::get('/apps', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'index'])->name('apps.index');
    Route::get('/apps/{app}', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'show'])->name('apps.show');
    Route::get('/apps/{app}/reviews', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'reviews'])->name('apps.reviews');
});

require __DIR__.'/auth.php';