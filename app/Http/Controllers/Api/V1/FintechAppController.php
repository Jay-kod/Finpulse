<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\FintechApp;
use App\Http\Resources\Api\V1\FintechAppResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class FintechAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $apps = FintechApp::query()
            ->when($request->boolean('active_only'), function ($query) {
                $query->where('is_active', true);
            })
            ->paginate($request->integer('per_page', 15));

        return FintechAppResource::collection($apps);
    }

    /**
     * Display the specified resource.
     */
    public function show(FintechApp $fintechApp): FintechAppResource
    {
        return new FintechAppResource($fintechApp);
    }
}
