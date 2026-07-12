<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AppLookupController extends Controller
{
    /**
     * Look up app metadata from public APIs (iTunes Search API)
     * to help autofill the application registration form.
     */
    public function lookup(Request $request)
    {
        $request->validate([
            'name' => 'required|string|min:2',
        ]);

        $appName = $request->input('name');
        
        try {
            // We use iTunes API as a reliable source of truth for app metadata.
            // Often, Android package names match the iOS bundle ID.
            $response = Http::withoutVerifying()->timeout(5)->get('https://itunes.apple.com/search', [
                'term' => $appName,
                'entity' => 'software',
                'country' => 'ng',
                'limit' => 1
            ]);

            if ($response->successful() && $response->json('resultCount') > 0) {
                $appData = $response->json('results')[0];
                
                return response()->json([
                    'success' => true,
                    'data' => [
                        'name' => $appData['trackName'] ?? '',
                        'package_name' => $appData['bundleId'] ?? '',
                        'appstore_id' => (string)($appData['trackId'] ?? ''),
                        // Playstore uses the package name for its ID most of the time
                        'playstore_id' => $appData['bundleId'] ?? '', 
                        'logo_url' => $appData['artworkUrl512'] ?? $appData['artworkUrl100'] ?? '',
                        'description' => $appData['description'] ?? '',
                    ]
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'No matching app found on public stores.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error communicating with external API.'
            ], 500);
        }
    }
}
