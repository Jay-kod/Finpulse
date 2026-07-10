<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/ui-playground', function () {
    return view('ui-playground');
});

// Smart redirect based on role
Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware('auth')->group(function () {
    // Shared routes (all authenticated users)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::post('/api-tokens', [\App\Http\Controllers\ApiTokenController::class, 'store'])->name('api-tokens.store');
    Route::delete('/api-tokens/{token}', [\App\Http\Controllers\ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');

    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{id}', [\App\Http\Controllers\NotificationController::class, 'destroy'])->name('notifications.destroy');

    Route::get('/search', [\App\Http\Controllers\SearchController::class, 'index'])->name('search');

    // --- VIEWER ROUTES ---
    Route::middleware(['role:Super Admin|Admin|Analyst|Viewer'])->prefix('viewer')->name('viewer.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'viewerDashboard'])->name('dashboard');
        Route::get('/reports', [\App\Http\Controllers\ReportsController::class, 'index'])->name('reports.index');
        Route::get('/reports/{report}', [\App\Http\Controllers\ReportsController::class, 'show'])->name('reports.show');
        
        Route::get('/apps', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'index'])->name('apps.index');
        Route::get('/apps/{app}', [\App\Http\Controllers\Viewer\AppDirectoryController::class, 'show'])->name('apps.show');
    });

    // --- ANALYST ROUTES ---
    Route::middleware(['role:Super Admin|Admin|Analyst'])->prefix('analyst')->name('analyst.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'analystDashboard'])->name('dashboard');
        
        Route::get('/analytics', [\App\Http\Controllers\Analyst\AnalyticsController::class, 'index'])->name('analytics.index');
        
        Route::resource('datasets', \App\Http\Controllers\Analyst\DatasetController::class)->except(['show']);
        Route::resource('reviews', \App\Http\Controllers\Analyst\ReviewController::class)->except(['show']);
        Route::resource('reports', \App\Http\Controllers\Analyst\ReportController::class)->only(['index', 'create', 'store', 'show']);

        Route::get('/export/all', [\App\Http\Controllers\Analyst\ExportController::class, 'exportAll'])->name('export.all');
        Route::get('/export/report/{report}', [\App\Http\Controllers\Analyst\ExportController::class, 'exportReport'])->name('export.report');

        Route::get('/preprocessing', [\App\Http\Controllers\Analyst\PreprocessingController::class, 'index'])->name('preprocessing.index');
        Route::post('/preprocessing/dispatch', [\App\Http\Controllers\Analyst\PreprocessingController::class, 'dispatch'])->name('preprocessing.dispatch');
        Route::post('/pipeline/dispatch-ml', [\App\Http\Controllers\Analyst\PreprocessingController::class, 'dispatchMl'])->name('preprocessing.dispatch-ml');
        Route::post('/pipeline/dispatch-sentiment', [\App\Http\Controllers\Analyst\PreprocessingController::class, 'dispatchSentiment'])->name('preprocessing.dispatch-sentiment');
    });

    // --- ADMIN ROUTES ---
    Route::middleware(['role:Super Admin|Admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'adminDashboard'])->name('dashboard');
        
        Route::resource('users', \App\Http\Controllers\Admin\UserController::class)->except(['show']);
        Route::resource('fintech-apps', \App\Http\Controllers\Admin\FintechAppController::class)->except(['show']);
        
        Route::get('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [\App\Http\Controllers\Admin\SettingsController::class, 'update'])->name('settings.update');
        
        Route::get('/audit-logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-logs.index');
    });
});

require __DIR__.'/auth.php';
