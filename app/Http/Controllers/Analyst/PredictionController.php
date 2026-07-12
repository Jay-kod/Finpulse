<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Services\NlpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PredictionController extends Controller
{
    /**
     * Show the predictions playground page.
     */
    public function index(NlpService $nlpService)
    {
        $serviceOnline = $nlpService->healthCheck();

        return view('analyst.predictions.index', [
            'serviceOnline' => $serviceOnline,
        ]);
    }

    /**
     * Run the full analysis pipeline on user-submitted text.
     */
    public function analyze(Request $request)
    {
        $request->validate([
            'text' => 'required|string|min:5|max:5000',
        ]);

        $baseUrl = rtrim(config('services.nlp.url', 'http://127.0.0.1:8001'), '/');

        try {
            $response = Http::timeout(30)
                ->retry(2, 500)
                ->post("{$baseUrl}/api/analyze-full", [
                    'text' => $request->input('text'),
                ]);

            if ($response->successful()) {
                return response()->json([
                    'success' => true,
                    'data' => $response->json(),
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'The ML service returned an error (status ' . $response->status() . ').',
            ], 422);
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('Prediction service connection failed', [
                'url' => $baseUrl,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Cannot reach the ML microservice. Please ensure it is running.',
            ], 503);
        } catch (\Exception $e) {
            Log::error('Prediction analysis failed', [
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during analysis.',
            ], 500);
        }
    }
}
