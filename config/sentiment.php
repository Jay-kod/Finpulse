<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application Settings
    |--------------------------------------------------------------------------
    |
    | Core configuration for the Sentiment Analysis platform.
    |
    */

    'name' => env('SENTIMENT_APP_NAME', 'Fintech Sentiment Analyzer'),

    'version' => '1.0.0',

    /*
    |--------------------------------------------------------------------------
    | Fintech Applications
    |--------------------------------------------------------------------------
    |
    | Default fintech apps tracked by the platform.
    |
    */

    'fintech_apps' => ['OPay', 'PalmPay', 'Kuda'],

    /*
    |--------------------------------------------------------------------------
    | Sentiment Labels
    |--------------------------------------------------------------------------
    */

    'sentiment_labels' => [
        'positive' => 'Positive',
        'negative' => 'Negative',
        'neutral'  => 'Neutral',
    ],

    /*
    |--------------------------------------------------------------------------
    | ML Service Configuration
    |--------------------------------------------------------------------------
    */

    'ml_service' => [
        'base_url'            => env('ML_SERVICE_URL', 'http://127.0.0.1:8000'),
        'timeout'             => env('ML_SERVICE_TIMEOUT', 30),
        'default_algorithm'   => env('ML_DEFAULT_ALGORITHM', 'logistic_regression'),
        'confidence_threshold' => env('ML_CONFIDENCE_THRESHOLD', 0.6),
    ],

    /*
    |--------------------------------------------------------------------------
    | Dataset Settings
    |--------------------------------------------------------------------------
    */

    'dataset' => [
        'max_upload_size_mb' => env('DATASET_MAX_UPLOAD_MB', 50),
        'allowed_formats'    => ['csv', 'xlsx', 'xls'],
        'chunk_size'         => env('DATASET_CHUNK_SIZE', 1000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Export Settings
    |--------------------------------------------------------------------------
    */

    'export' => [
        'formats'   => ['csv', 'xlsx', 'pdf'],
        'max_rows'  => env('EXPORT_MAX_ROWS', 50000),
    ],

    /*
    |--------------------------------------------------------------------------
    | Pagination
    |--------------------------------------------------------------------------
    */

    'pagination' => [
        'per_page' => env('PAGINATION_PER_PAGE', 25),
    ],

    /*
    |--------------------------------------------------------------------------
    | Roles
    |--------------------------------------------------------------------------
    */

    'roles' => [
        'administrator'    => 'Administrator',
        'analyst'          => 'Analyst',
        'research_viewer'  => 'Research Viewer',
    ],

];
