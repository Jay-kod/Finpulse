<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsController extends Controller
{
    /**
     * Display the analytics hub view.
     */
    public function index(Request $request, AnalyticsService $analyticsService): View
    {
        $overviewStats = $analyticsService->getOverviewStats();
        $sentimentDistribution = $analyticsService->getSentimentDistribution();
        $topicDistribution = $analyticsService->getTopicDistribution();
        $intentDistribution = $analyticsService->getIntentDistribution();
        $sentimentOverTime = $analyticsService->getSentimentOverTime(30);
        $recentAnomalies = $analyticsService->getRecentAnomalies();

        return view('analyst.analytics.index', compact(
            'overviewStats',
            'sentimentDistribution',
            'topicDistribution',
            'intentDistribution',
            'sentimentOverTime',
            'recentAnomalies'
        ));
    }
}
