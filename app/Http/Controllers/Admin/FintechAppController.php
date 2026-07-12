<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use App\Models\FintechApp;
use App\Models\User;
use App\Jobs\SyncAppReviewsJob;
use App\Notifications\NewFintechAppAdded;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

class FintechAppController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $apps = FintechApp::latest()->get();
        return view('admin.fintech-apps.index', compact('apps'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.fintech-apps.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'package_name' => ['required', 'string', 'max:255', Rule::unique('fintech_apps', 'package_name')->whereNull('deleted_at')],
            'playstore_id' => 'nullable|string|max:255',
            'appstore_id' => 'nullable|string|max:255',
            'platform' => 'required|in:android,ios,both',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        // Check if a soft-deleted app with this package name exists
        $app = FintechApp::withTrashed()->where('package_name', $validated['package_name'])->first();

        if ($app) {
            if ($app->trashed()) {
                $app->restore();
            }
            $app->update($validated);
        } else {
            $app = FintechApp::create($validated);
        }

        // Dispatch background job to sync initial reviews
        SyncAppReviewsJob::dispatch($app);

        // Notify all users in the system (chunked to avoid memory exhaustion)
        User::chunk(100, function ($users) use ($app) {
            Notification::send($users, new NewFintechAppAdded($app));
        });

        return redirect()->route('admin.fintech-apps.index')
            ->with('success', 'Fintech Application created successfully. Initial sync has started.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(FintechApp $fintechApp)
    {
        return view('admin.fintech-apps.edit', compact('fintechApp'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, FintechApp $fintechApp)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'package_name' => ['required', 'string', 'max:255', Rule::unique('fintech_apps', 'package_name')->ignore($fintechApp->id)->whereNull('deleted_at')],
            'playstore_id' => 'nullable|string|max:255',
            'appstore_id' => 'nullable|string|max:255',
            'platform' => 'required|in:android,ios,both',
            'description' => 'nullable|string',
            'logo_url' => 'nullable|url|max:255',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $fintechApp->update($validated);

        return redirect()->route('admin.fintech-apps.index')
            ->with('success', 'Fintech Application updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FintechApp $fintechApp)
    {
        $fintechApp->delete();

        return redirect()->route('admin.fintech-apps.index')
            ->with('success', 'Fintech Application deleted successfully.');
    }
}
