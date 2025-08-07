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

    /**
     * Added: Alex Kuvallini
     * Exporting results in CSV for API calls: /api/nearest-golfers/csv?lat=52.5200&lng=13.4050
     */
    public function nearestCsv(Request $request)
    {
        $golfers = $this->nearestQuery($request);

        $filename = 'nearest_golfers.csv';
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $columns = ['ID', 'Debitor Account', 'Name', 'Email', 'Date of birth', 'Latitude', 'Longitude', 'Distance (km)'];

        $callback = function() use ($golfers, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($golfers as $golfer) {
                fputcsv($file, [
                    $golfer->id,
                    $golfer->debitor_account,
                    $golfer->name,
                    $golfer->email,
                    $golfer->born_at,
                    $golfer->latitude,
                    $golfer->longitude,
                    round($golfer->distance, 2),
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}