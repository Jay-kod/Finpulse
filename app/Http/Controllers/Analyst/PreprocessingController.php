<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Services\NlpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class PreprocessingController extends Controller
{
    /**
     * Display the preprocessing dashboard.
     */
    public function index(NlpService $nlpService)
    {
        $stats = [
            'total' => Review::count(),
            'nlp_pending' => Review::where('processed_status', 'pending')->count(),
            'nlp_processed' => Review::where('processed_status', 'processed')->count(),
            'nlp_error' => Review::where('processed_status', 'error')->count(),
            'ml_pending' => Review::where('processed_status', 'processed')->where('ml_status', 'pending')->count(),
            'ml_classified' => Review::where('ml_status', 'classified')->count(),
            'ml_error' => Review::where('ml_status', 'error')->count(),
            'sentiment_pending' => Review::where('ml_status', 'classified')->where('sentiment_status', 'pending')->count(),
            'sentiment_analyzed' => Review::where('sentiment_status', 'analyzed')->count(),
            'sentiment_error' => Review::where('sentiment_status', 'error')->count(),
        ];

        $serviceOnline = $nlpService->healthCheck();

        $recentlyProcessed = Review::where(function ($query) {
                $query->where('processed_status', 'processed')
                      ->orWhere('ml_status', 'classified')
                      ->orWhere('sentiment_status', 'analyzed');
            })
            ->latest('updated_at')
            ->limit(10)
            ->get();

        return view('analyst.preprocessing.index', compact('stats', 'serviceOnline', 'recentlyProcessed'));
    }

    /**
     * Dispatch the preprocessing Artisan command.
     */
    public function dispatch(Request $request)
    {
        $limit = $request->input('limit', 50);

        Artisan::call('reviews:preprocess', ['--limit' => $limit]);

        $output = Artisan::output();

        return redirect()->route('analyst.preprocessing.index')
            ->with('success', "Preprocessing dispatched. {$output}");
    }

    /**
     * Dispatch the ML classification Artisan command.
     */
    public function dispatchMl(Request $request)
    {
        $limit = $request->input('limit', 50);

        Artisan::call('reviews:classify', ['--limit' => $limit]);

        $output = Artisan::output();

        return redirect()->route('analyst.preprocessing.index')
            ->with('success', "ML Classification dispatched. {$output}");
    }

    /**
     * Dispatch the Sentiment Analysis Artisan command.
     */
    public function dispatchSentiment(Request $request)
    {
        $limit = $request->input('limit', 50);

        Artisan::call('reviews:sentiment', ['--limit' => $limit]);

        $output = Artisan::output();

        return redirect()->route('analyst.preprocessing.index')
            ->with('success', "Sentiment Analysis dispatched. {$output}");
    }
}
