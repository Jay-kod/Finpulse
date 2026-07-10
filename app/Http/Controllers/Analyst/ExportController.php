<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Review;
use App\Services\AnalyticsService;
use App\Services\ExportService;

class ExportController extends Controller
{
    protected ExportService $exportService;
    protected AnalyticsService $analyticsService;

    public function __construct(ExportService $exportService, AnalyticsService $analyticsService)
    {
        $this->exportService = $exportService;
        $this->analyticsService = $analyticsService;
    }

    /**
     * Export all fully processed reviews to CSV.
     */
    public function exportAll()
    {
        $query = Review::where('sentiment_status', 'analyzed');
        $filename = 'all_reviews_export_' . now()->format('Ymd_His') . '.csv';

        return $this->exportService->exportReviewsToCsv($query, $filename);
    }

    /**
     * Export the filtered reviews associated with a specific report.
     */
    public function exportReport(Report $report)
    {
        $filters = $report->parameters ?? [];
        
        // Use the analytics service to apply the exact same scoping rules as the dashboard
        $query = $this->analyticsService->applyFilters(Review::query(), $filters);
        
        $safeTitle = preg_replace('/[^a-zA-Z0-9]+/', '_', strtolower($report->title));
        $filename = 'report_' . $safeTitle . '_' . now()->format('Ymd_His') . '.csv';

        return $this->exportService->exportReviewsToCsv($query, $filename);
    }
}
