<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Http\Resources\Api\V1\ReviewResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $reviews = Review::query()
            ->when($request->filled('dataset_id'), function ($query) use ($request) {
                $query->where('dataset_id', $request->dataset_id);
            })
            ->when($request->filled('sentiment_label'), function ($query) use ($request) {
                $query->where('sentiment_label', $request->sentiment_label);
            })
            ->when($request->filled('language'), function ($query) use ($request) {
                $query->where('language', $request->language);
            })
            ->with('dataset')
            ->paginate($request->integer('per_page', 25));

        return ReviewResource::collection($reviews);
    }

    /**
     * Display the specified resource.
     */
    public function show(Review $review): ReviewResource
    {
        $review->load('dataset');
        return new ReviewResource($review);
    }
}
