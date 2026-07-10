<?php

namespace App\Console\Commands;

use App\Models\Review;
use App\Services\SentimentService;
use Illuminate\Console\Command;

class AnalyzeSentiment extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reviews:sentiment {--limit=100 : Maximum number of reviews to analyze}';

    /**
     * The console command description.
     */
    protected $description = 'Send ML-classified reviews to the Sentiment service for analysis';

    /**
     * Execute the console command.
     */
    public function handle(SentimentService $sentimentService): int
    {
        $limit = (int) $this->option('limit');

        $reviews = Review::where('ml_status', 'classified')
            ->where('sentiment_status', 'pending')
            ->limit($limit)
            ->get();

        if ($reviews->isEmpty()) {
            $this->info('No eligible reviews found for sentiment analysis. Nothing to process.');
            return self::SUCCESS;
        }

        $this->info("Analyzing sentiment for {$reviews->count()} reviews...");
        $bar = $this->output->createProgressBar($reviews->count());
        $bar->start();

        $analyzed = 0;
        $errors = 0;

        foreach ($reviews as $review) {
            try {
                // If there's no cleaned content, fallback to raw content just in case
                $textToAnalyze = $review->cleaned_content ?: $review->content;

                $result = $sentimentService->analyze($textToAnalyze);

                $review->update([
                    'sentiment_positive' => $result['positive'],
                    'sentiment_negative' => $result['negative'],
                    'sentiment_neutral' => $result['neutral'],
                    'sentiment_compound' => $result['compound'],
                    'sentiment_status' => 'analyzed',
                ]);

                $analyzed++;
            } catch (\Exception $e) {
                $review->update(['sentiment_status' => 'error']);
                $errors++;

                $this->newLine();
                $this->warn("  Error analyzing review #{$review->id}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Sentiment Analysis complete:");
        $this->line("  ✓ Analyzed: {$analyzed}");

        if ($errors > 0) {
            $this->warn("  ✗ Errors:   {$errors}");
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
