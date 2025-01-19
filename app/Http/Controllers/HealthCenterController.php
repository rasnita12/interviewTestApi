<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Throwable;

class HealthCenterController extends Controller
{
    public array $data = [];

    public function index(Request $request)
    {
        $data['healthCenters'] = [];

        if($request->has('postcode') && $request->filled('postcode')) {
            $this->data['healthCenters'] = $this->getHealthCenters($request->input('postcode'));
            if(!$this->data['healthCenters']) {
                return redirect()->route('user.account')->with('error', 'Please check your postcode and try again.');
            }
            $this->data['postcode'] = $request->input('postcode');
        } else {
            return redirect()->route('user.account')->with('error', 'Please check your postcode and try again.');
        }
        return Inertia::render('HealthCenter/Index', $this->data);
    }

    public function getHealthCenters($postcode = null)
    {
        if(!empty($postcode)) {
            $apiKey = 'AIzaSyCi327LFl7byyspi_v7snyRjEVQPptUBPc'; //google map api key
            try{
                // Geocoding to get coordinates
                $geocodeResponse = Http::get("https://maps.googleapis.com/maps/api/geocode/json", [
                    'address' => $postcode,
                    'key' => $apiKey,
                ]);

                $location = $geocodeResponse->json()['results'][0]['geometry']['location'];
                $lat = $location['lat'];
                $lng = $location['lng'];

                $this->data['center'] = ['lat' => $lat, 'lng' => $lng];

                // Places API to get health centers
                $placesResponse = Http::get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
                    'location' => "{$lat},{$lng}",
                    'radius' => 5000, // Radius in meters
                    'type' => 'hospital', // Or 'health'
                    'key' => $apiKey,
                ]);
                $response = $placesResponse->json();
                $allHealtCenters = array_map(function ($item) use($lat, $lng) {
                    $item['distance'] = round($this->calculateDistance($lat, $lng, $item['geometry']['location']['lat'],$item['geometry']['location']['lng']), 2);
                    return $item;
                },$response['results'] ?? []);
                usort($allHealtCenters, function($a, $b) {
                    return $a['distance'] <=> $b['distance'];
                });
                return $allHealtCenters;
            }catch(Throwable $e)
            {
                return false;
            }
        } else {
            return [];
        }
    }

    private function calculateDistance($lat1, $lng1, $lat2, $lng2) {
        $earthRadiusKm = 6371; // Radius of Earth in kilometers
        $kmToMile = 0.621371; // Conversion factor from kilometers to miles

        $dLat = deg2rad($lat2 - $lat1);
        $dLng = deg2rad($lng2 - $lng1);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
             sin($dLng / 2) * sin($dLng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distanceKm = $earthRadiusKm * $c; // Distance in kilometers

        // Convert to miles
        return $distanceKm * $kmToMile;
    }
}
