<?php

namespace App\Http\Controllers\Viewer;

use App\Http\Controllers\Controller;
use App\Models\FintechApp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppDirectoryController extends Controller
{
    /**
     * Display a listing of all tracked Fintech Apps.
     */
    public function index(): View
    {
        $apps = FintechApp::where('is_active', true)
            ->withCount('reviews')
            ->orderByDesc('downloads')
            ->get();

        return view('viewer.apps.index', compact('apps'));
    }

    /**
     * Display detailed stats for a specific Fintech App.
     */
    public function show(FintechApp $app): View
    {
        // Calculate some basic review stats
        // In the future, this can be heavily optimized or cached
        $totalReviews = $app->reviews()->count();
        $goodReviews = $app->reviews()->where('rating', '>=', 4)->count();
        $badReviews = $app->reviews()->where('rating', '<=', 2)->count();
        $neutralReviews = $totalReviews - $goodReviews - $badReviews;

        $recentReviews = $app->reviews()
            ->latest('published_at')
            ->take(10)
            ->get();

        return view('viewer.apps.show', compact(
            'app', 
            'totalReviews', 
            'goodReviews', 
            'badReviews', 
            'neutralReviews', 
            'recentReviews'
        ));
    }
}
