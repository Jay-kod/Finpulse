<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class NlpService
{
    /**
     * The base URL for the FastAPI NLP microservice.
     */
    protected string $baseUrl;

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.nlp.url'), '/');
    }

    /**
     * Send raw review text to the NLP service for preprocessing.
     *
     * @param string $text The raw review content.
     * @return array{cleaned_text: string, language: string, word_count: int}
     *
     * @throws \Exception If the NLP service is unreachable or returns an error.
     */
    public function preprocess(string $text): array
    {
        try {
            $response = Http::timeout(15)
                ->retry(2, 500)
                ->post("{$this->baseUrl}/api/preprocess", [
                    'text' => $text,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'cleaned_text' => $data['cleaned_text'] ?? $text,
                    'language' => $data['language'] ?? 'unknown',
                    'word_count' => $data['word_count'] ?? str_word_count($text),
                ];
            }

            Log::warning('NLP Service returned non-success status', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception("NLP service returned status {$response->status()}");
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('NLP Service connection failed', [
                'url' => $this->baseUrl,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception("NLP service unreachable at {$this->baseUrl}: {$e->getMessage()}");
        }
    }

    /**
     * Check if the NLP service is reachable.
     */
    public function healthCheck(): bool
    {
        try {
            $response = Http::timeout(5)->get("{$this->baseUrl}/health");
            return $response->successful();
        } catch (\Exception $e) {
            return false;
        }
    }
}
