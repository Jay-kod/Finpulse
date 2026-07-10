<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Http\Resources\Api\V1\ReportResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $reports = Report::query()
            ->when($request->filled('type'), function ($query) use ($request) {
                $query->where('type', $request->type);
            })
            ->latest()
            ->paginate($request->integer('per_page', 15));

        return ReportResource::collection($reports);
    }

    /**
     * Display the specified resource.
     */
    public function show(Report $report): ReportResource
    {
        return new ReportResource($report);
    }
}
