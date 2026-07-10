<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Http\Resources\Api\V1\DatasetResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $datasets = Dataset::query()
            ->when($request->filled('fintech_app_id'), function ($query) use ($request) {
                $query->where('fintech_app_id', $request->fintech_app_id);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->with('fintechApp')
            ->paginate($request->integer('per_page', 15));

        return DatasetResource::collection($datasets);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dataset $dataset): DatasetResource
    {
        $dataset->load('fintechApp');
        return new DatasetResource($dataset);
    }
}
