<?php

namespace App\Http\Controllers\Analyst;

use App\Http\Controllers\Controller;
use App\Models\Dataset;
use App\Models\FintechApp;
use Illuminate\Http\Request;

class DatasetController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $datasets = Dataset::with('fintechApp')->latest()->get();
        return view('analyst.datasets.index', compact('datasets'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $apps = FintechApp::where('is_active', true)->get();
        return view('analyst.datasets.create', compact('apps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'fintech_app_id' => 'required|exists:fintech_apps,id',
            'name' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'status' => 'required|in:pending,processing,completed,failed',
            'record_count' => 'required|integer|min:0',
        ]);

        Dataset::create($validated);

        return redirect()->route('analyst.datasets.index')
            ->with('success', 'Dataset registered successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dataset $dataset)
    {
        $apps = FintechApp::where('is_active', true)->get();
        return view('analyst.datasets.edit', compact('dataset', 'apps'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dataset $dataset)
    {
        $validated = $request->validate([
            'fintech_app_id' => 'required|exists:fintech_apps,id',
            'name' => 'required|string|max:255',
            'source' => 'required|string|max:255',
            'status' => 'required|in:pending,processing,completed,failed',
            'record_count' => 'required|integer|min:0',
        ]);

        $dataset->update($validated);

        return redirect()->route('analyst.datasets.index')
            ->with('success', 'Dataset updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dataset $dataset)
    {
        $dataset->delete();

        return redirect()->route('analyst.datasets.index')
            ->with('success', 'Dataset deleted successfully.');
    }
}
