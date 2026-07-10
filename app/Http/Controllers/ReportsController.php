<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ReportsController extends Controller
{
    /**
     * Mock reports data store.
     * In production, this will be replaced by Eloquent queries.
     */
    private function getMockReports(): array
    {
        return [
            1 => [
                'id' => 1,
                'title' => 'OPay Q2 2026 Sentiment Analysis Report',
                'app' => 'OPay',
                'excerpt' => 'Overall sentiment for OPay improved by 12.5% in Q2, driven by faster transaction processing and improved UX. Customer service complaints remain the primary negative driver.',
                'date' => 'Jul 01, 2026',
                'status' => 'Published',
                'author' => 'Suliat Analytics Team',
                'content' => '<h2>Executive Summary</h2><p>OPay demonstrated significant improvement in user sentiment during Q2 2026. The overall positive sentiment score rose from 62% to 74%, marking the strongest quarterly growth since tracking began.</p><h2>Key Findings</h2><ul><li><strong>Transfer Speed</strong>: Positive mentions of transfer speed increased by 35%, correlating with the backend infrastructure upgrade deployed in April.</li><li><strong>Customer Service</strong>: Despite improvements, customer service remains the most negative category at 48% negative sentiment. Response times averaging 72 hours continue to drive frustration.</li><li><strong>Cashback Promotions</strong>: The May cashback campaign generated a 22% spike in positive reviews, particularly among users aged 18-25.</li></ul><h2>Recommendations</h2><ol><li>Invest in customer service response time reduction (target: under 24 hours).</li><li>Continue cashback-style promotions quarterly to maintain engagement.</li><li>Monitor card delivery complaints — early Q3 data shows emerging negative trend.</li></ol>',
            ],
            2 => [
                'id' => 2,
                'title' => 'PalmPay Customer Service Deep Dive',
                'app' => 'PalmPay',
                'excerpt' => 'A focused analysis of customer service sentiment for PalmPay reveals systemic issues in email response handling and dispute resolution workflows.',
                'date' => 'Jun 15, 2026',
                'status' => 'Published',
                'author' => 'Suliat Analytics Team',
                'content' => '<h2>Executive Summary</h2><p>PalmPay\'s customer service sentiment sits at 28% positive, significantly below the industry average of 45%. This deep dive identifies root causes and recommends targeted interventions.</p><h2>Key Findings</h2><ul><li><strong>Email Response</strong>: 67% of negative reviews mentioning customer service cite "no response" or "delayed response" to emails. Average first-response time is estimated at 4.2 days.</li><li><strong>Dispute Resolution</strong>: Failed transactions with no refund are the single highest-volume complaint category, representing 31% of all negative reviews.</li><li><strong>In-App Chat</strong>: Users who interacted via in-app chat reported 2.4x higher satisfaction than email users, suggesting the channel is effective but underutilized.</li></ul><h2>Recommendations</h2><ol><li>Prioritize in-app chat adoption by making it the default support channel.</li><li>Implement automated refund processing for failed transactions under ₦5,000.</li><li>Deploy email autoresponders with estimated resolution timelines.</li></ol>',
            ],
            3 => [
                'id' => 3,
                'title' => 'Kuda vs OPay: Comparative Sentiment Analysis',
                'app' => 'Multiple',
                'excerpt' => 'A head-to-head comparison of user sentiment between Kuda and OPay across five key feature categories reveals distinct competitive advantages for each platform.',
                'date' => 'May 28, 2026',
                'status' => 'Published',
                'author' => 'Suliat Analytics Team',
                'content' => '<h2>Executive Summary</h2><p>This comparative analysis examines sentiment across Login/Onboarding, Transfers, Savings, Card Services, and Customer Support for both Kuda and OPay. Each platform holds distinct advantages.</p><h2>Key Findings</h2><ul><li><strong>Login/Onboarding</strong>: Kuda leads with 89% positive vs OPay\'s 78%. Kuda\'s BVN-based instant signup is frequently praised.</li><li><strong>Transfers</strong>: OPay leads with 82% positive vs Kuda\'s 71%. OPay\'s lower transfer fees are the primary driver.</li><li><strong>Savings</strong>: Kuda dominates at 91% positive, with users highlighting competitive interest rates and the "Spend+Save" feature.</li><li><strong>Card Services</strong>: Kuda leads at 76% positive. OPay\'s virtual card feature is well-received (80% positive) but physical card delivery complaints drag overall score to 58%.</li><li><strong>Customer Support</strong>: Both score poorly — Kuda at 42% and OPay at 38% positive.</li></ul><h2>Recommendations</h2><ol><li>Both platforms should prioritize customer support improvements as the shared weakness.</li><li>OPay should consider competitive savings features to counter Kuda\'s advantage.</li><li>Kuda should evaluate transfer fee reductions to compete with OPay\'s pricing.</li></ol>',
            ],
        ];
    }

    /**
     * Display the reports listing page.
     */
    public function index(Request $request): View
    {
        $reports = collect($this->getMockReports())->values();

        return view('reports.index', compact('reports'));
    }

    /**
     * Display a single report.
     */
    public function show(int $report): View
    {
        $reports = $this->getMockReports();

        if (!isset($reports[$report])) {
            abort(404, 'Report not found.');
        }

        $reportData = $reports[$report];

        return view('reports.show', ['report' => $reportData]);
    }
}
