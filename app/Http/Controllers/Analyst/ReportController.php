<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\FintechApp;
use App\Models\Report;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportController extends Controller
{
    /**
     * Display a listing of the saved reports.
     */
    public function index(): View
    {
        $reports = Report::with('user')->latest()->get();

        return view('analyst.reports.index', compact('reports'));
    }

    /**
     * Show the form for creating a new report.
     */
    public function create(): View
    {
        $apps = FintechApp::orderBy('name')->get();

        return view('analyst.reports.create', compact('apps'));
    }

    /**
     * Store a newly created report configuration in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'app_id' => 'nullable|exists:fintech_apps,id',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        $parameters = array_filter([
            'app_id' => $validated['app_id'] ?? null,
            'start_date' => $validated['start_date'] ?? null,
            'end_date' => $validated['end_date'] ?? null,
        ]);

        Report::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'parameters' => empty($parameters) ? null : $parameters,
            'user_id' => auth()->id(),
        ]);

        return redirect()->route('analyst.reports.index')->with('success', 'Report configuration saved successfully.');
    }

    /**
     * Display the specified report (renders the scoped dashboard).
     */
    public function show(Report $report, AnalyticsService $analyticsService): View
    {
        $filters = $report->parameters ?? [];

        $overviewStats = $analyticsService->getOverviewStats($filters);
        $sentimentDistribution = $analyticsService->getSentimentDistribution($filters);
        $topicDistribution = $analyticsService->getTopicDistribution($filters);
        $intentDistribution = $analyticsService->getIntentDistribution($filters);
        $sentimentOverTime = $analyticsService->getSentimentOverTime(30, $filters);
        $recentAnomalies = $analyticsService->getRecentAnomalies($filters);

        // Fetch app name if filtered
        $appName = null;
        if (!empty($filters['app_id'])) {
            $appName = FintechApp::find($filters['app_id'])?->name;
        }

        return view('analyst.reports.show', compact(
            'report',
            'overviewStats',
            'sentimentDistribution',
            'topicDistribution',
            'intentDistribution',
            'sentimentOverTime',
            'recentAnomalies',
            'appName'
        ));
    }
}
