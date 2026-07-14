<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class AnalyticsService
{
    private const CACHE_TTL = 1800; // 30 minutes

    /**
     * Generate a unique cache key based on the method prefix and applied filters.
     */
    private function getCacheKey(string $prefix, array $filters = []): string
    {
        ksort($filters);
        $hash = md5(json_encode($filters));
        return "analytics.{$prefix}.{$hash}";
    }
    /**
     * Apply common filters to the review query.
     */
    public function applyFilters($query, array $filters)
    {
        $query->where('sentiment_status', 'analyzed');

        // Resolve valid datasets directly to avoid heavy WHERE EXISTS subqueries on the massive reviews table
        $activeAppIds = empty($filters['app_id']) 
            ? \App\Models\FintechApp::where('is_active', true)->pluck('id')->toArray() 
            : [$filters['app_id']];
            
        $validDatasetIds = \App\Models\Dataset::whereIn('fintech_app_id', $activeAppIds)->pluck('id')->toArray();

        // If no valid datasets exist, ensure the query returns empty efficiently
        if (empty($validDatasetIds)) {
            $query->whereRaw('1 = 0');
        } else {
            $query->whereIn('dataset_id', $validDatasetIds);
        }

        if (!empty($filters['start_date'])) {
            $query->where('published_at', '>=', $filters['start_date']);
        }

        if (!empty($filters['end_date'])) {
            $query->where('published_at', '<=', $filters['end_date']);
        }

        return $query;
    }

    /**
     * Get high-level overview statistics.
     */
    public function getOverviewStats(array $filters = []): array
    {
        return Cache::remember($this->getCacheKey('overview', $filters), self::CACHE_TTL, function () use ($filters) {
            $baseQuery = $this->applyFilters(Review::query(), $filters);
            $totalReviews = (clone $baseQuery)->count();

            if ($totalReviews === 0) {
                return [
                    'total_reviews' => 0,
                    'avg_sentiment' => 0,
                    'bug_rate' => 0,
                    'top_topic' => 'N/A',
                ];
            }

            $avgSentiment = (clone $baseQuery)->avg('sentiment_compound') ?? 0;
            
            $bugCount = (clone $baseQuery)->where('is_bug', true)->count();
            $bugRate = ($bugCount / $totalReviews) * 100;

            $topTopicRecord = (clone $baseQuery)
                ->select('topic', DB::raw('count(*) as total'))
                ->whereNotNull('topic')
                ->groupBy('topic')
                ->orderByDesc('total')
                ->first();

            $topTopic = $topTopicRecord ? $topTopicRecord->topic : 'N/A';

            return [
                'total_reviews' => $totalReviews,
                'avg_sentiment' => round($avgSentiment, 2),
                'bug_rate' => round($bugRate, 1),
                'top_topic' => $topTopic,
            ];
        });
    }

    /**
     * Get the distribution of sentiment categories (Positive, Negative, Neutral).
     */
    public function getSentimentDistribution(array $filters = []): array
    {
        return Cache::remember($this->getCacheKey('sentiment_dist', $filters), self::CACHE_TTL, function () use ($filters) {
            $query = $this->applyFilters(Review::query(), $filters);

            $positive = (clone $query)->where('sentiment_compound', '>=', 0.05)->count();
            $negative = (clone $query)->where('sentiment_compound', '<=', -0.05)->count();
            $neutral = (clone $query)->whereBetween('sentiment_compound', [-0.0499, 0.0499])->count();

            return [
                'labels' => ['Positive', 'Neutral', 'Negative'],
                'data' => [$positive, $neutral, $negative],
            ];
        });
    }

    /**
     * Get the top 5 topics and their counts.
     */
    public function getTopicDistribution(array $filters = []): array
    {
        return Cache::remember($this->getCacheKey('topic_dist', $filters), self::CACHE_TTL, function () use ($filters) {
            $query = $this->applyFilters(Review::query(), $filters);

            $topics = $query->select('topic', DB::raw('count(*) as total'))
                ->whereNotNull('topic')
                ->groupBy('topic')
                ->orderByDesc('total')
                ->limit(5)
                ->get();

            return [
                'labels' => $topics->pluck('topic')->toArray(),
                'data' => $topics->pluck('total')->toArray(),
            ];
        });
    }

    /**
     * Get the distribution of user intents.
     */
    public function getIntentDistribution(array $filters = []): array
    {
        return Cache::remember($this->getCacheKey('intent_dist', $filters), self::CACHE_TTL, function () use ($filters) {
            $query = $this->applyFilters(Review::query(), $filters);

            $intents = $query->select('intent', DB::raw('count(*) as total'))
                ->whereNotNull('intent')
                ->groupBy('intent')
                ->orderByDesc('total')
                ->get();

            return [
                'labels' => $intents->pluck('intent')->toArray(),
                'data' => $intents->pluck('total')->toArray(),
            ];
        });
    }

    /**
     * Get average sentiment over time.
     */
    public function getSentimentOverTime(int $days = 30, array $filters = []): array
    {
        $cacheKey = $this->getCacheKey("sentiment_time_{$days}", $filters);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($days, $filters) {
            $query = $this->applyFilters(Review::query(), $filters);

            // If start_date isn't explicitly set in filters, fallback to $days calculation
            if (empty($filters['start_date'])) {
                $startDate = now()->subDays($days)->startOfDay();
                $query->where('published_at', '>=', $startDate);
            } else {
                $startDate = \Carbon\Carbon::parse($filters['start_date']);
                // Recalculate days based on start and end for the loop below
                $endDate = empty($filters['end_date']) ? now() : \Carbon\Carbon::parse($filters['end_date']);
                $days = (int) $startDate->diffInDays($endDate) + 1; // +1 to include both ends
            }

            $data = $query->select(
                    DB::raw('DATE(published_at) as date'),
                    DB::raw('AVG(sentiment_compound) as avg_sentiment')
                )
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            // Fill in missing dates with nulls to maintain a continuous timeline
            $timeline = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $dateObj = (empty($filters['end_date']) ? now() : \Carbon\Carbon::parse($filters['end_date']))->subDays($i);
                $dateStr = $dateObj->format('Y-m-d');
                $record = $data->firstWhere('date', $dateStr);
                $timeline[$dateStr] = $record ? round($record->avg_sentiment, 2) : null;
            }

            return [
                'labels' => array_keys($timeline),
                'data' => array_values($timeline),
            ];
        });
    }

    /**
     * Get average sentiment over time per active app.
     */
    public function getSentimentTrendsPerApp(int $days = 30, array $filters = []): array
    {
        $cacheKey = $this->getCacheKey("sentiment_time_per_app_{$days}", $filters);
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($days, $filters) {
            $baseQuery = $this->applyFilters(Review::query(), $filters);

            if (empty($filters['start_date'])) {
                $startDate = now()->subDays($days)->startOfDay();
                $baseQuery->where('published_at', '>=', $startDate);
            } else {
                $startDate = \Carbon\Carbon::parse($filters['start_date']);
                $endDate = empty($filters['end_date']) ? now() : \Carbon\Carbon::parse($filters['end_date']);
                $days = (int) $startDate->diffInDays($endDate) + 1;
            }

            // Get all active apps
            $apps = \App\Models\FintechApp::where('is_active', true)->get();
            $datasets = [];

            // Pre-calculate the timeline labels
            $labels = [];
            for ($i = $days - 1; $i >= 0; $i--) {
                $dateObj = (empty($filters['end_date']) ? now() : \Carbon\Carbon::parse($filters['end_date']))->subDays($i);
                $labels[] = $dateObj->format('Y-m-d');
            }

            // Execute a single grouped query to prevent N+1 issues
            $data = (clone $baseQuery)
                ->join('datasets', 'reviews.dataset_id', '=', 'datasets.id')
                ->select(
                    'datasets.fintech_app_id',
                    DB::raw('DATE(published_at) as date'),
                    DB::raw('AVG(sentiment_compound) as avg_sentiment')
                )
                ->groupBy('datasets.fintech_app_id', 'date')
                ->orderBy('date')
                ->get();

            foreach ($apps as $app) {
                $appData = $data->where('fintech_app_id', $app->id);

                $timeline = [];
                foreach ($labels as $dateStr) {
                    $record = $appData->firstWhere('date', $dateStr);
                    $timeline[] = $record ? round($record->avg_sentiment, 2) : null;
                }

                $datasets[] = [
                    'label' => $app->name,
                    'data' => $timeline,
                ];
            }

            return [
                'labels' => $labels,
                'datasets' => $datasets,
            ];
        });
    }

    /**
     * Get the 10 most recent reviews flagged as bugs across all active apps.
     */
    public function getRecentAnomalies(array $filters = [])
    {
        $query = clone $this->applyFilters(Review::query(), $filters);

        return $query->with('dataset.fintechApp')
            ->where('is_bug', true)
            ->latest('published_at')
            ->limit(10)
            ->get();
    }
}
