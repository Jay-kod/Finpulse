<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::middleware('auth:sanctum')->prefix('v1')->name('api.v1.')->group(function () {
    Route::apiResource('fintech-apps', \App\Http\Controllers\Api\V1\FintechAppController::class)->only(['index', 'show']);
    Route::apiResource('datasets', \App\Http\Controllers\Api\V1\DatasetController::class)->only(['index', 'show']);
    Route::apiResource('reviews', \App\Http\Controllers\Api\V1\ReviewController::class)->only(['index', 'show']);
    Route::apiResource('reports', \App\Http\Controllers\Api\V1\ReportController::class)->only(['index', 'show']);
});
