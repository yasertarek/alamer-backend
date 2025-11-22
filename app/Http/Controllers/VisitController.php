<?php

// app/Http/Controllers/VisitController.php

namespace App\Http\Controllers;

use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class VisitController extends Controller
{
    public function index(Request $request)
    {
        // Validate incoming query params
        $validated = $request->validate([
            'start' => 'nullable|date',
            'end'   => 'nullable|date|after_or_equal:start',
            'per_page' => 'nullable|integer|min=1|max:100'
        ]);

        $start = $validated['start'] ?? null;
        $end   = $validated['end']   ?? null;
        $perPage = $validated['per_page'] ?? 15;

        $visits = Visit::query()
            ->when($start && $end, function ($query) use ($start, $end) {
                $query->whereBetween('created_at', [$start, $end]);
            })
            ->when($start && !$end, function ($query) use ($start) {
                $query->whereDate('created_at', '>=', $start);
            })
            ->when(!$start && $end, function ($query) use ($end) {
                $query->whereDate('created_at', '<=', $end);
            })
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString(); // keep filters in pagination links

        return response()->json([
            'success' => true,
            'data' => $visits
        ]);
    }

    public function track(Request $request)
    {
        $ipAddress = $request->ip();
        $url = $request->input('url');

        // GeoIP lookup
        // $location = geoip($ipAddress);
        // $country = $location->country ?? 'Unknown';

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
            // 'country' => $country
        ]);

        // Store a marker in the cache to prevent duplicates for the decay time
        Cache::put($cacheKey, true, now()->addMinutes($decayMinutes));

        return response()->json(['message' => 'Visit tracked successfully']);
    }
}
