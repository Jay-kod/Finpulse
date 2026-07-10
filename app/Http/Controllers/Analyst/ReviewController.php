<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\Review;
use Illuminate\Http\Request;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::with('dataset.fintechApp')->latest()->paginate(15);
        return view('analyst.reviews.index', compact('reviews'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $datasets = Dataset::with('fintechApp')->orderBy('created_at', 'desc')->get();
        return view('analyst.reviews.create', compact('datasets'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'dataset_id' => 'required|exists:datasets,id',
            'author_name' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'content' => 'required|string',
            'processed_status' => 'required|in:pending,processed,error',
            'published_at' => 'nullable|date',
        ]);

        Review::create($validated);

        return redirect()->route('analyst.reviews.index')
            ->with('success', 'Review added successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Review $review)
    {
        $datasets = Dataset::with('fintechApp')->orderBy('created_at', 'desc')->get();
        return view('analyst.reviews.edit', compact('review', 'datasets'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Review $review)
    {
        $validated = $request->validate([
            'dataset_id' => 'required|exists:datasets,id',
            'author_name' => 'nullable|string|max:255',
            'rating' => 'nullable|integer|min:1|max:5',
            'content' => 'required|string',
            'processed_status' => 'required|in:pending,processed,error',
            'published_at' => 'nullable|date',
        ]);

        $review->update($validated);

        return redirect()->route('analyst.reviews.index')
            ->with('success', 'Review updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Review $review)
    {
        $review->delete();

        return redirect()->route('analyst.reviews.index')
            ->with('success', 'Review deleted successfully.');
    }
}
