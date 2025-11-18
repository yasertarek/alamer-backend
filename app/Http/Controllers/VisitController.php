<?php

// app/Http/Controllers/VisitController.php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VisitController extends Controller
{
    public function track(Request $request)
    {
        $ipAddress = $request->ip();
        $url = $request->input('url');

        // Define a unique key for the cache based on IP and URL (optional, can just be IP for a site-wide unique visit)
        $cacheKey = 'visit_' . md5($ipAddress . $url);
        $decayMinutes = 1440; // 24 hours

        // Check if a visit for this IP/URL combination has been recorded recently in the cache
        if (Cache::has($cacheKey)) {
            return response()->json(['message' => 'Visit already tracked recently'], 200);
        }

        // If not, record the visit
        Visit::create([
            'ip_address' => $ipAddress,
            'url' => $url,
        ]);

        // Store a marker in the cache to prevent duplicates for the decay time
        Cache::put($cacheKey, true, now()->addMinutes($decayMinutes));

        return response()->json(['message' => 'Visit tracked successfully']);
    }
}
