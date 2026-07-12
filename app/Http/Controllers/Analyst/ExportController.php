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
    /**
     * Export the filtered reviews associated with a specific report as PDF.
     */
    public function exportPdfReport(Report $report)
    {
        $filters = $report->parameters ?? [];
        
        // Fetch top stats for the PDF header
        $query = $this->analyticsService->applyFilters(Review::query(), $filters);
        $totalReviews = $query->count();
        
        $clone = clone $query;
        $avgSentiment = round($clone->avg('sentiment_compound') ?? 0, 2);
        
        // Fetch a sample of reviews for the PDF (limit to 100 to avoid massive PDFs)
        $reviews = $query->with('dataset.fintechApp')->latest('published_at')->limit(100)->get();
        
        $safeTitle = preg_replace('/[^a-zA-Z0-9]+/', '_', strtolower($report->title));
        $filename = 'report_' . $safeTitle . '_' . now()->format('Ymd_His') . '.pdf';

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('analyst.analytics.pdf', compact('report', 'reviews', 'totalReviews', 'avgSentiment'));
        
        return $pdf->download($filename);
    }
}
