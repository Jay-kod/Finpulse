<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SentimentService
{
    /**
     * The base URL for the FastAPI Sentiment microservice.
     */
    protected string $baseUrl;

    public function __construct()
    {
        // Reusing the NLP URL as per our assumption, or fallback to it
        $this->baseUrl = rtrim(config('services.nlp.url', 'http://127.0.0.1:8000'), '/');
    }

    /**
     * Send cleaned review text to the Sentiment service for analysis.
     *
     * @param string $cleanedText The preprocessed review content.
     * @return array{positive: ?float, negative: ?float, neutral: ?float, compound: ?float}
     *
     * @throws \Exception If the sentiment service is unreachable or returns an error.
     */
    public function analyze(string $cleanedText): array
    {
        try {
            $response = Http::timeout(20)
                ->retry(2, 500)
                ->post("{$this->baseUrl}/api/sentiment", [
                    'cleaned_text' => $cleanedText,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'positive' => $data['positive'] ?? null,
                    'negative' => $data['negative'] ?? null,
                    'neutral' => $data['neutral'] ?? null,
                    'compound' => $data['compound'] ?? null,
                ];
            }

            Log::warning('Sentiment Service returned non-success status', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception("Sentiment service returned status {$response->status()}");
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Sentiment Service connection failed', [
                'url' => $this->baseUrl,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception("Sentiment service unreachable at {$this->baseUrl}: {$e->getMessage()}");
        }
    }
}
