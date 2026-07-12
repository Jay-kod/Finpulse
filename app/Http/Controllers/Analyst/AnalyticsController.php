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
        $period = $request->query('period', '30');
        $validPeriods = ['7', '14', '30', '90'];
        if (!in_array($period, $validPeriods)) {
            $period = '30';
        }
        $days = (int) $period;

        $overviewStats = $analyticsService->getOverviewStats();
        $sentimentDistribution = $analyticsService->getSentimentDistribution();
        $topicDistribution = $analyticsService->getTopicDistribution();
        $intentDistribution = $analyticsService->getIntentDistribution();
        $sentimentOverTime = $analyticsService->getSentimentTrendsPerApp($days);
        $recentAnomalies = $analyticsService->getRecentAnomalies();

        return view('analyst.analytics.index', compact(
            'overviewStats',
            'sentimentDistribution',
            'topicDistribution',
            'intentDistribution',
            'sentimentOverTime',
            'recentAnomalies',
            'period'
        ));
    }
}
