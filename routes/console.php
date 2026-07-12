<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/*
|--------------------------------------------------------------------------
| Automated Pipeline Schedule
|--------------------------------------------------------------------------
|
| Sync reviews from app stores and run the AI pipeline daily at midnight.
| To activate: run `php artisan schedule:work` (dev) or add a system
| cron entry: * * * * * cd /path-to-project && php artisan schedule:run >> /dev/null 2>&1
|
*/

// Step 1: Sync fresh reviews from app stores every day at midnight
Schedule::call(function () {
    $apps = \App\Models\FintechApp::where('is_active', true)->get();
    foreach ($apps as $app) {
        \App\Jobs\SyncAppReviewsJob::dispatch($app);
    }
})->name('sync-app-reviews')
    ->dailyAt('00:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/schedule.log'));

// Step 2: Preprocess newly fetched reviews at 00:30
Schedule::command('reviews:preprocess --limit=500')->dailyAt('00:30')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/schedule.log'));

// Step 3: Classify preprocessed reviews at 01:00
Schedule::command('reviews:classify --limit=500')->dailyAt('01:00')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/schedule.log'));

// Step 4: Sentiment analysis on classified reviews at 01:30
Schedule::command('reviews:sentiment --limit=500')->dailyAt('01:30')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/schedule.log'));
