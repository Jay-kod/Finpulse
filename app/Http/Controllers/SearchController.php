<?php

namespace App\Http\Controllers;

use App\Models\Dataset;
use App\Models\FintechApp;
use App\Models\Report;
use App\Models\Review;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    /**
     * Handle the global search.
     */
    public function index(Request $request): View
    {
        $query = $request->input('q', '');
        $user = $request->user();
        
        $results = [
            'users' => collect(),
            'apps' => collect(),
            'datasets' => collect(),
            'reviews' => collect(),
            'reports' => collect(),
        ];
        
        $totalResults = 0;

        if (empty(trim($query))) {
            return view('search.index', compact('query', 'results', 'totalResults'));
        }

        // Roles check
        $isAdmin = $user->hasRole(['Super Admin', 'Admin']);
        $isAnalyst = $user->hasRole(['Analyst']);
        
        // Viewer can see reports, Analyst can see most, Admin can see all.
        $canSeeReports = true; // All authenticated users (including Viewers) can see Reports
        $canSeeAnalystData = $isAdmin || $isAnalyst;
        $canSeeAdminData = $isAdmin;

        // Search Admin Data (Users)
        if ($canSeeAdminData) {
            $results['users'] = User::where('name', 'like', "%{$query}%")
                ->orWhere('email', 'like', "%{$query}%")
                ->limit(10)
                ->get();
            $totalResults += $results['users']->count();
        }

        // Search Analyst Data (Apps, Datasets, Reviews)
        if ($canSeeAnalystData) {
            $results['apps'] = FintechApp::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(10)
                ->get();
            $totalResults += $results['apps']->count();
                
            $results['datasets'] = Dataset::where('name', 'like', "%{$query}%")
                ->orWhere('description', 'like', "%{$query}%")
                ->limit(10)
                ->get();
            $totalResults += $results['datasets']->count();
                
            $results['reviews'] = Review::where('content', 'like', "%{$query}%")
                ->orWhere('cleaned_content', 'like', "%{$query}%")
                ->orWhere('topic', 'like', "%{$query}%")
                ->orWhere('intent', 'like', "%{$query}%")
                ->limit(10)
                ->get();
            $totalResults += $results['reviews']->count();
        }

        // Search Reports (Everyone)
        if ($canSeeReports) {
            $results['reports'] = Report::where('title', 'like', "%{$query}%")
                ->orWhere('excerpt', 'like', "%{$query}%")
                ->limit(10)
                ->get();
            $totalResults += $results['reports']->count();
        }

        return view('search.index', compact('query', 'results', 'totalResults'));
    }
}
