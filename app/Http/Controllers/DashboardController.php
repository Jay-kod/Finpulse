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
        return view('admin.dashboard', $this->getMockData());
    }

    public function analystDashboard(Request $request): View
    {
        return view('analyst.dashboard', $this->getMockData());
    }

    public function viewerDashboard(Request $request): View
    {
        return view('viewer.dashboard', $this->getMockData());
    }

    private function getMockData(): array
    {
        return [
            'kpis' => [
                'total_reviews' => [
                    'value' => '124,592',
                    'trend' => '+12.5%',
                    'trend_up' => true,
                ],
                'average_sentiment' => [
                    'value' => '68%',
                    'trend' => '+2.1%',
                    'trend_up' => true,
                ],
                'active_datasets' => [
                    'value' => '3',
                    'trend' => 'No change',
                    'trend_up' => null,
                ],
                'anomalies_detected' => [
                    'value' => '14',
                    'trend' => '-5',
                    'trend_up' => true,
                ],
            ],
            'recentReviews' => [
                [
                    'app' => 'OPay',
                    'text' => 'The app is very fast and reliable for transfers.',
                    'sentiment' => 'Positive',
                    'score' => 0.92,
                    'date' => now()->subMinutes(12)->diffForHumans(),
                ],
                [
                    'app' => 'PalmPay',
                    'text' => 'Customer service is not responding to my missing funds.',
                    'sentiment' => 'Negative',
                    'score' => -0.85,
                    'date' => now()->subMinutes(45)->diffForHumans(),
                ],
                [
                    'app' => 'Kuda',
                    'text' => 'It works fine but sometimes the network is down.',
                    'sentiment' => 'Neutral',
                    'score' => 0.10,
                    'date' => now()->subHours(2)->diffForHumans(),
                ],
                [
                    'app' => 'OPay',
                    'text' => 'I love the cashback features!',
                    'sentiment' => 'Positive',
                    'score' => 0.88,
                    'date' => now()->subHours(3)->diffForHumans(),
                ],
                [
                    'app' => 'Kuda',
                    'text' => 'Card delivery took way too long.',
                    'sentiment' => 'Negative',
                    'score' => -0.65,
                    'date' => now()->subHours(5)->diffForHumans(),
                ],
            ]
        ];
    }
}
