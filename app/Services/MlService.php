<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MlService
{
    /**
     * The base URL for the FastAPI ML microservice.
     */
    protected string $baseUrl;

    public function __construct()
    {
        // Reusing the NLP URL as per our assumption, or fallback to it
        $this->baseUrl = rtrim(config('services.nlp.url', 'http://127.0.0.1:8000'), '/');
    }

    /**
     * Send cleaned review text to the ML service for classification.
     *
     * @param string $cleanedText The preprocessed review content.
     * @return array{topic: ?string, intent: ?string, is_bug: bool}
     *
     * @throws \Exception If the ML service is unreachable or returns an error.
     */
    public function classify(string $cleanedText): array
    {
        try {
            $response = Http::timeout(20)
                ->retry(2, 500)
                ->post("{$this->baseUrl}/api/classify", [
                    'cleaned_text' => $cleanedText,
                ]);

            if ($response->successful()) {
                $data = $response->json();

                return [
                    'topic' => $data['topic'] ?? null,
                    'intent' => $data['intent'] ?? null,
                    'is_bug' => $data['is_bug'] ?? false,
                ];
            }

            Log::warning('ML Service returned non-success status', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            throw new \Exception("ML service returned status {$response->status()}");
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('ML Service connection failed', [
                'url' => $this->baseUrl,
                'error' => $e->getMessage(),
            ]);

            throw new \Exception("ML service unreachable at {$this->baseUrl}: {$e->getMessage()}");
        }
    }
}
