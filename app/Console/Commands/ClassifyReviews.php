<?php

namespace App\Console\Commands;

use App\Models\Review;
use App\Services\MlService;
use Illuminate\Console\Command;

class ClassifyReviews extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reviews:classify {--limit=100 : Maximum number of reviews to classify}';

    /**
     * The console command description.
     */
    protected $description = 'Send NLP-processed reviews to the ML service for classification (Topic, Intent, Bug)';

    /**
     * Execute the console command.
     */
    public function handle(MlService $mlService): int
    {
        $limit = (int) $this->option('limit');

        $reviews = Review::where('processed_status', 'processed')
            ->where('ml_status', 'pending')
            ->limit($limit)
            ->get();

        if ($reviews->isEmpty()) {
            $this->info('No eligible reviews found for classification. Nothing to process.');
            return self::SUCCESS;
        }

        $this->info("Classifying {$reviews->count()} reviews...");
        $bar = $this->output->createProgressBar($reviews->count());
        $bar->start();

        $classified = 0;
        $errors = 0;

        foreach ($reviews as $review) {
            try {
                // If there's no cleaned content, fallback to raw content just in case
                $textToClassify = $review->cleaned_content ?: $review->content;

                $result = $mlService->classify($textToClassify);

                $review->update([
                    'topic' => $result['topic'],
                    'intent' => $result['intent'],
                    'is_bug' => $result['is_bug'],
                    'ml_status' => 'classified',
                ]);

                $classified++;
            } catch (\Exception $e) {
                $review->update(['ml_status' => 'error']);
                $errors++;

                $this->newLine();
                $this->warn("  Error classifying review #{$review->id}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Classification complete:");
        $this->line("  ✓ Classified: {$classified}");

        if ($errors > 0) {
            $this->warn("  ✗ Errors:     {$errors}");
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
