<?php

namespace App\Services;

use App\Models\Review;
use Illuminate\Database\Eloquent\Builder;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ExportService
{
    /**
     * Export a review query to a streamed CSV response.
     */
    public function exportReviewsToCsv(Builder $query, string $filename): StreamedResponse
    {
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // Add CSV headers
            fputcsv($handle, [
                'ID',
                'App',
                'Review Text',
                'Rating',
                'Language',
                'Processed Status',
                'Intent',
                'Topic',
                'Sentiment Score',
                'Sentiment Label',
                'Is Bug',
                'Published At',
            ]);

            // Eager load dataset.fintechApp to avoid N+1 issues
            $query->with('dataset.fintechApp')
                ->chunk(500, function ($reviews) use ($handle) {
                    foreach ($reviews as $review) {
                        fputcsv($handle, [
                            $review->id,
                            $review->dataset->fintechApp->name ?? 'Unknown',
                            $review->content, // Raw review text
                            $review->rating,
                            $review->language,
                            $review->processed_status,
                            $review->intent,
                            $review->topic,
                            $review->sentiment_compound,
                            $review->sentiment_label,
                            $review->is_bug ? 'Yes' : 'No',
                            $review->published_at ? $review->published_at->format('Y-m-d H:i:s') : '',
                        ]);
                    }
                });

            fclose($handle);
        }, 200, $headers);
    }
}
