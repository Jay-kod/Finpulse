<?php

namespace App\Console\Commands;

use App\Models\Review;
use App\Services\NlpService;
use Illuminate\Console\Command;

class PreprocessReviews extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'reviews:preprocess {--limit=100 : Maximum number of reviews to process}';

    /**
     * The console command description.
     */
    protected $description = 'Send pending reviews to the NLP service for text preprocessing';

    /**
     * Execute the console command.
     */
    public function handle(NlpService $nlpService): int
    {
        $limit = (int) $this->option('limit');

        $reviews = Review::where('processed_status', 'pending')
            ->limit($limit)
            ->get();

        if ($reviews->isEmpty()) {
            $this->info('No pending reviews found. Nothing to process.');
            return self::SUCCESS;
        }

        $this->info("Processing {$reviews->count()} pending reviews...");
        $bar = $this->output->createProgressBar($reviews->count());
        $bar->start();

        $processed = 0;
        $errors = 0;

        foreach ($reviews as $review) {
            try {
                $result = $nlpService->preprocess($review->content);

                $review->update([
                    'cleaned_content' => $result['cleaned_text'],
                    'detected_language' => $result['language'],
                    'word_count' => $result['word_count'],
                    'processed_status' => 'processed',
                ]);

                $processed++;
            } catch (\Exception $e) {
                $review->update(['processed_status' => 'error']);
                $errors++;

                $this->newLine();
                $this->warn("  Error processing review #{$review->id}: {$e->getMessage()}");
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Processing complete:");
        $this->line("  ✓ Processed: {$processed}");

        if ($errors > 0) {
            $this->warn("  ✗ Errors:    {$errors}");
        }

        return $errors > 0 ? self::FAILURE : self::SUCCESS;
    }
}
