<?php

namespace App\Jobs;

use App\Models\FintechApp;
use App\Models\Dataset;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncAppReviewsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public FintechApp $app)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // 1. Simulate fetching app metadata (Downloads, average rating)
        $downloads = rand(10000, 5000000);
        $avgRating = mt_rand(250, 480) / 100; // Between 2.50 and 4.80

        $this->app->update([
            'downloads' => $downloads,
            'average_rating' => $avgRating,
        ]);

        // 2. Create a default dataset to hold the synced reviews if one doesn't exist
        $dataset = Dataset::firstOrCreate(
            ['fintech_app_id' => $this->app->id, 'name' => 'Auto-Synced Reviews (All Time)'],
            ['description' => 'Automatically synced reviews from Play Store and App Store']
        );

        // 3. Generate some fake reviews to simulate the sync
        $numReviews = rand(15, 40);
        
        $sentiments = [
            5 => ['Excellent app', 'Love it!', 'Very smooth and fast.', 'Best fintech app.', 'Highly recommended'],
            4 => ['Good app but a few bugs.', 'Nice interface', 'Works well most of the time.', 'Reliable'],
            3 => ['Okay app.', 'Average experience.', 'Could be better.', 'It gets the job done'],
            2 => ['Too many ads/glitches.', 'Customer service is bad.', 'App crashes sometimes.'],
            1 => ['Terrible app.', 'They stole my money.', 'Cannot login at all.', 'Worst app ever']
        ];

        $reviewsToInsert = [];
        for ($i = 0; $i < $numReviews; $i++) {
            // Skew rating towards average
            $rating = rand(1, 5);
            if (rand(1, 100) > 50) {
                $rating = round($avgRating);
            }
            if ($rating < 1) $rating = 1;
            if ($rating > 5) $rating = 5;

            $comments = $sentiments[$rating];
            $content = $comments[array_rand($comments)];

            $reviewsToInsert[] = [
                'dataset_id' => $dataset->id,
                'author_name' => 'User_' . rand(1000, 9999),
                'rating' => $rating,
                'content' => $content,
                'processed_status' => 'pending',
                'published_at' => now()->subDays(rand(1, 30)),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        Review::insert($reviewsToInsert);
    }
}
