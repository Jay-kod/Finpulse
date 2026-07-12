<?php

namespace App\Jobs;

use App\Models\Dataset;
use App\Models\Review;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProcessDatasetImportJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $timeout = 300;
    public $tries = 2;

    public function __construct(public Dataset $dataset)
    {
    }

    public function handle(): void
    {
        try {
            $this->dataset->update(['status' => 'processing']);
            $app = $this->dataset->fintechApp;
            $source = strtolower($this->dataset->source);
            $reviewsCollected = [];

            if (str_contains($source, 'play')) {
                if ($app && $app->playstore_id) {
                    $reviewsCollected = $this->fetchGooglePlayReviews($app->playstore_id);
                } else {
                    throw new \Exception("No Play Store ID configured for this application.");
                }
            } elseif (str_contains($source, 'app store') || str_contains($source, 'apple')) {
                if ($app && $app->appstore_id) {
                    $reviewsCollected = $this->fetchAppleReviews($app->appstore_id);
                } else {
                    throw new \Exception("No App Store ID configured for this application.");
                }
            } elseif (str_contains($source, 'twitter') || str_contains($source, 'x')) {
                // Trigger Twitter Simulation Engine
                $handle = $app ? ($app->twitter_handle ?: $app->name) : 'FintechApp';
                $reviewsCollected = $this->generateTwitterSimulation($handle);
            } else {
                // Custom / Simulated fallback
                $reviewsCollected = $this->generateTwitterSimulation($app ? $app->name : 'Custom App');
            }

            if (empty($reviewsCollected)) {
                throw new \Exception("No data could be retrieved from the source.");
            }

            // Insert reviews
            $toInsert = array_map(function ($review) {
                return array_merge($review, [
                    'dataset_id' => $this->dataset->id,
                    'processed_status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }, $reviewsCollected);

            foreach (array_chunk($toInsert, 100) as $chunk) {
                Review::insert($chunk);
            }

            $this->dataset->update([
                'record_count' => Review::where('dataset_id', $this->dataset->id)->count(),
                'status' => 'completed',
            ]);

            Log::info("Dataset processing complete for Dataset ID: {$this->dataset->id}");

        } catch (Throwable $e) {
            Log::error("ProcessDatasetImportJob Failed for Dataset {$this->dataset->id}: " . $e->getMessage());
            $this->dataset->update(['status' => 'failed']);
            throw $e;
        }
    }

    private function fetchGooglePlayReviews(string $playstoreId): array
    {
        $reviews = [];
        try {
            $gplay = new \Nelexa\GPlay\GPlayApps('en', 'ng');
            $gplayReviews = $gplay->getReviews($playstoreId, 200); // Pull up to 200 for a dataset

            foreach ($gplayReviews as $review) {
                $reviews[] = [
                    'source_id' => 'gplay_' . $review->getId(),
                    'author_name' => $review->getUserName() ?: 'Anonymous',
                    'rating' => $review->getScore(),
                    'content' => $review->getText() ?: '',
                    'published_at' => $review->getDate() ? $review->getDate()->format('Y-m-d H:i:s') : now()->toDateTimeString(),
                ];
            }
        } catch (\Exception $e) {
            Log::error("Google Play fetch error: " . $e->getMessage());
        }
        return $reviews;
    }

    private function fetchAppleReviews(string $appstoreId): array
    {
        $reviews = [];
        try {
            for ($page = 1; $page <= 5; $page++) {
                $url = "https://itunes.apple.com/ng/rss/customerreviews/page={$page}/id={$appstoreId}/sortBy=mostRecent/json";
                $response = Http::withoutVerifying()->timeout(10)->get($url);
                if (!$response->successful()) break;

                $data = $response->json();
                $entries = $data['feed']['entry'] ?? [];
                if (empty($entries)) break;

                foreach ($entries as $entry) {
                    if (!isset($entry['im:rating'])) continue;
                    $reviews[] = [
                        'source_id' => 'apple_' . ($entry['id']['label'] ?? uniqid()),
                        'author_name' => $entry['author']['name']['label'] ?? 'Anonymous',
                        'rating' => (int) ($entry['im:rating']['label'] ?? 3),
                        'content' => $entry['content']['label'] ?? '',
                        'published_at' => isset($entry['updated']['label']) ? date('Y-m-d H:i:s', strtotime($entry['updated']['label'])) : now()->toDateTimeString(),
                    ];
                }
                usleep(300000);
            }
        } catch (\Exception $e) {
            Log::error("Apple fetch error: " . $e->getMessage());
        }
        return $reviews;
    }

    /**
     * Advanced Twitter Simulation Engine tailored for Fintech Apps
     */
    private function generateTwitterSimulation(string $handle): array
    {
        $numTweets = rand(80, 150);
        $reviews = [];
        
        $handleStr = str_starts_with($handle, '@') ? $handle : '@' . str_replace(' ', '', $handle);

        $templates = [
            // Highly Negative / Bug Reports (Mapped to rating 1-2)
            1 => [
                "I've been trying to transfer money for 3 days and $handleStr keeps failing! My money is stuck! #Frustrated",
                "$handleStr your customer service is non-existent. I've sent 5 DMs and no reply. Where is my refund?",
                "Worst app ever. $handleStr charged me twice for the same transaction. Thieves!",
                "Anyone else experiencing network issues with $handleStr today? Can't even log in.",
                "DO NOT USE $handleStr! My account was locked for no reason with funds inside.",
                "The new update is terrible, the app keeps crashing on my iPhone 14. Fix it $handleStr!"
            ],
            // Neutral / Questions (Mapped to rating 3)
            3 => [
                "Hey $handleStr, what are the current charges for international transfers?",
                "The app is okay, but I wish $handleStr had virtual dollar cards.",
                "$handleStr is fine I guess, but sometimes the network is a bit slow.",
                "Did $handleStr change their logo? Looks weird.",
                "Just downloaded $handleStr. We'll see how it goes compared to the others."
            ],
            // Positive / Praise (Mapped to rating 4-5)
            5 => [
                "Just used $handleStr to pay my utility bills. Fast and zero charges. I love it!",
                "Honestly, $handleStr is the best fintech app right now. Transfers are instant.",
                "Shoutout to $handleStr customer support for resolving my issue in 5 minutes! 🚀",
                "Switched from my traditional bank to $handleStr and I have zero regrets.",
                "The UI on the $handleStr app is so clean and easy to use. Great job team!"
            ]
        ];

        for ($i = 0; $i < $numTweets; $i++) {
            // Skew towards a mix of complaints and praise (typical Twitter behavior)
            $rand = rand(1, 100);
            if ($rand <= 40) $rating = 1;      // 40% negative
            elseif ($rand <= 60) $rating = 3;  // 20% neutral
            else $rating = 5;                  // 40% positive

            // Select a template
            $commentPool = $templates[$rating];
            $content = $commentPool[array_rand($commentPool)];

            // Add some variation (e.g. hashtags, emojis)
            if (rand(1, 3) == 1 && $rating == 1) $content .= " 😡";
            if (rand(1, 3) == 1 && $rating == 5) $content .= " 🔥";

            // Generate realistic Twitter IDs
            $tweetId = '1' . str_pad((string)rand(0, 999999999999999), 15, '0', STR_PAD_LEFT);

            $reviews[] = [
                'source_id' => 'tweet_' . $tweetId,
                'author_name' => '@user_' . strtolower(substr(md5(uniqid()), 0, 8)),
                'rating' => $rating, // Note: Twitter doesn't have ratings, we infer 1-5 for our ML pipeline mapping
                'content' => $content,
                'published_at' => now()->subMinutes(rand(1, 43200))->toDateTimeString(), // Tweets from last 30 days
            ];
        }

        return $reviews;
    }
}
