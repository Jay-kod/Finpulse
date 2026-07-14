<?php

namespace App\Http\Controllers\Viewer;

use App\Http\Controllers\Controller;
use App\Models\FintechApp;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AppDirectoryController extends Controller
{
    /**
     * Determine the correct route name prefix based on the current URL.
     */
    private function getRoutePrefix(): string
    {
        $path = request()->path();
        if (str_starts_with($path, 'admin/')) {
            return 'admin';
        } elseif (str_starts_with($path, 'analyst/')) {
            return 'analyst';
        }
        return 'viewer';
    }

    /**
     * Display a listing of all tracked Fintech Apps.
     */
    public function index(): View
    {
        $apps = FintechApp::where('is_active', true)
            ->withCount('reviews')
            ->orderByDesc('downloads')
            ->get();

        $routePrefix = $this->getRoutePrefix();

        return view('viewer.apps.index', compact('apps', 'routePrefix'));
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
            ->with('dataset')
            ->latest('published_at')
            ->take(10)
            ->get();

        $routePrefix = $this->getRoutePrefix();

        return view('viewer.apps.show', compact(
            'app', 
            'totalReviews', 
            'goodReviews', 
            'badReviews', 
            'neutralReviews', 
            'recentReviews',
            'routePrefix'
        ));
    }

    /**
     * Display a paginated, filterable listing of reviews for a Fintech App.
     */
    public function reviews(Request $request, FintechApp $app): View
    {
        $query = $app->reviews()->with('dataset.fintechApp');

        // Filter by star rating
        if ($request->filled('rating')) {
            $query->where('rating', $request->input('rating'));
        }

        // Filter by sentiment (based on compound score)
        if ($request->filled('sentiment')) {
            match ($request->input('sentiment')) {
                'positive' => $query->where('sentiment_compound', '>', 0.05),
                'negative' => $query->where('sentiment_compound', '<', -0.05),
                'neutral'  => $query->whereBetween('sentiment_compound', [-0.05, 0.05]),
                default    => null,
            };
        }

        // Filter by topic
        if ($request->filled('topic')) {
            $query->where('topic', $request->input('topic'));
        }

        // Filter by source (dataset source)
        if ($request->filled('source')) {
            $query->whereHas('dataset', function ($q) use ($request) {
                $q->where('source', $request->input('source'));
            });
        }

        // Filter by bug reports only
        if ($request->boolean('bugs_only')) {
            $query->where('is_bug', true);
        }

        // Search by content
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('content', 'like', "%{$search}%")
                  ->orWhere('author_name', 'like', "%{$search}%");
            });
        }

        // Sorting
        $sortField = $request->input('sort', 'published_at');
        $sortDir = $request->input('dir', 'desc');
        $allowedSorts = ['published_at', 'rating', 'sentiment_compound', 'word_count'];
        if (!in_array($sortField, $allowedSorts)) {
            $sortField = 'published_at';
        }
        $sortDir = $sortDir === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortField, $sortDir);

        $reviews = $query->paginate(20)->withQueryString();

        // Get available topics for filter dropdown
        $topics = $app->reviews()
            ->whereNotNull('topic')
            ->distinct()
            ->pluck('topic')
            ->sort()
            ->values();

        // Get available sources for filter dropdown
        $sources = $app->datasets()
            ->distinct()
            ->pluck('source')
            ->sort()
            ->values();

        // Stats for the header
        $totalReviews = $app->reviews()->count();
        $avgRating = $app->reviews()->avg('rating');
        $bugCount = $app->reviews()->where('is_bug', true)->count();

        $routePrefix = $this->getRoutePrefix();

        return view('viewer.apps.reviews', compact(
            'app',
            'reviews',
            'topics',
            'sources',
            'totalReviews',
            'avgRating',
            'bugCount',
            'routePrefix',
        ));
    }
}
