<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GolferController extends Controller
{
    /**
     * Added: Alex Kuvallini
     * Query to database for nearest golfers based on latitude and longitude
     */
    public function nearestQuery(Request $request)
    {
        $lat = $request->input('lat');
        $lng = $request->input('lng');

        if (!$lat || !$lng) {
            return response()->json(['error' => 'Latitude and longitude are required.'], 400);
        }

        $golfers = DB::table('golfers')
            ->selectRaw('
                id, debitor_account, name, email, born_at, latitude, longitude,
                (6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )) AS distance', [$lat, $lng, $lat])
            ->orderBy('distance')
            ->limit(500)
            ->get();
        
        return $golfers;
    }

    /**
     * Added: Alex Kuvallini
     * Showing results in json format for API calls: /api/nearest-golfers?lat=52.5200&lng=13.4050
     */
    public function nearestGet(Request $request)
    {
        $golfers = $this->nearestQuery($request);

        return response()->json($golfers);
    }
}