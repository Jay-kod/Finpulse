<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Smart redirect based on user role.
     */
    public function index(Request $request): RedirectResponse
    {
        $user = $request->user();

        if ($user->hasRole('Super Admin') || $user->hasRole('Admin')) {
            return redirect()->route('admin.dashboard');
        }

        if ($user->hasRole('Analyst')) {
            return redirect()->route('analyst.dashboard');
        }

        return redirect()->route('viewer.dashboard');
    }

    public function adminDashboard(Request $request): View
    {
        $data = \Illuminate\Support\Facades\Cache::remember('admin.dashboard.data', now()->addMinutes(15), function () {
            $totalUsers = \App\Models\User::count();
            $newUsersThisMonth = \App\Models\User::where('created_at', '>=', now()->startOfMonth())->count();
            $totalApps = \App\Models\FintechApp::count();
            $activeApps = \App\Models\FintechApp::where('is_active', true)->count();
            $totalReviews = \App\Models\Review::count();
            $totalAuditEvents = \App\Models\AuditLog::count();
            $recentAuditEvents = \App\Models\AuditLog::with('user')->latest()->limit(5)->get();
            $recentUsers = \App\Models\User::latest()->limit(5)->get();

            // Role distribution for doughnut chart
            $roleCounts = [];
            $roles = \Spatie\Permission\Models\Role::withCount('users')->get();
            foreach ($roles as $role) {
                $roleCounts[$role->name] = $role->users_count;
            }

            // Review growth over last 6 months for line chart
            $reviewGrowth = [];
            for ($i = 5; $i >= 0; $i--) {
                $date = now()->subMonths($i);
                $reviewGrowth[] = [
                    'month' => $date->format('M'),
                    'count' => \App\Models\Review::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)
                        ->count(),
                ];
            }

            return compact(
                'totalUsers', 'newUsersThisMonth', 'totalApps', 'activeApps',
                'totalReviews', 'totalAuditEvents', 'recentAuditEvents',
                'recentUsers', 'roleCounts', 'reviewGrowth'
            );
        });

        return view('admin.dashboard', $data);
    }

    public function analystDashboard(Request $request, \App\Services\AnalyticsService $analytics): View
    {
        return view('analyst.dashboard', $this->getDashboardData($analytics));
    }

    public function viewerDashboard(Request $request, \App\Services\AnalyticsService $analytics): View
    {
        return view('viewer.dashboard', $this->getDashboardData($analytics));
    }

    private function getDashboardData(\App\Services\AnalyticsService $analytics): array
    {
        $overview = $analytics->getOverviewStats();
        
        $activeDatasets = \App\Models\Dataset::where('status', 'completed')->count();

        // Get recent reviews spread across ALL apps (not just one)
        $apps = \App\Models\FintechApp::where('is_active', true)->get();
        $recentReviewsData = collect();

        foreach ($apps as $app) {
            $appReviews = \App\Models\Review::with('dataset.fintechApp')
                ->whereHas('dataset', function ($q) use ($app) {
                    $q->where('fintech_app_id', $app->id);
                })
                ->where('sentiment_status', 'analyzed')
                ->latest('published_at')
                ->limit(2)
                ->get();

            $recentReviewsData = $recentReviewsData->merge($appReviews);
        }

        $recentReviewsData = $recentReviewsData
            ->sortByDesc('published_at')
            ->take(10)
            ->map(function ($review) {
                return [
                    'app' => $review->dataset->fintechApp->name ?? 'Unknown',
                    'text' => $review->content,
                    'source' => $review->source ?? 'google_play',
                    'rating' => $review->rating ?? null,
                    'sentiment' => match (true) {
                        $review->sentiment_compound >= 0.05 => 'Positive',
                        $review->sentiment_compound <= -0.05 => 'Negative',
                        default => 'Neutral',
                    },
                    'score' => $review->sentiment_compound,
                    'date' => $review->published_at ? $review->published_at->diffForHumans() : $review->created_at->diffForHumans(),
                ];
            })
            ->values()
            ->toArray();

        return [
            'kpis' => [
                'total_reviews' => [
                    'value' => number_format($overview['total_reviews']),
                    'trend' => 'Real-time',
                    'trend_up' => true,
                ],
                'average_sentiment' => [
                    'value' => $overview['avg_sentiment'] > 0 ? '+' . number_format($overview['avg_sentiment'], 2) : number_format($overview['avg_sentiment'], 2),
                    'trend' => 'Overall Score',
                    'trend_up' => $overview['avg_sentiment'] >= 0,
                ],
                'active_datasets' => [
                    'value' => number_format($activeDatasets),
                    'trend' => 'Processed sources',
                    'trend_up' => null,
                ],
                'anomalies_detected' => [
                    'value' => number_format($overview['bug_rate'], 1) . '%',
                    'trend' => 'Bug report rate',
                    'trend_up' => false,
                ],
            ],
            'recentReviews' => $recentReviewsData,
            'sentimentDistribution' => $analytics->getSentimentDistribution(),
            'sentimentTrends' => $analytics->getSentimentTrendsPerApp(14),
        ];
    }
}
