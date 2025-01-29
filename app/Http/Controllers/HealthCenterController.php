<?php

namespace App\Http\Controllers;

use App\Http\Requests\HealthCenterRequest;
use App\Models\HealthCenter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Throwable;

class HealthCenterController extends Controller
{
    public array $data = [];

    public function get()
    {
        return Inertia::render('HealthCenter/Admin/Index');
    }

    public function ajaxList(Request $request)
    {
        $query = HealthCenter::query();

        if($request->filled('search')){
            $search = $request->query('search');
            $query->where(function($query) use ($search){
               $query->where('health_centers.name', 'like', "%{$search}%")
                   ->orWhere("health_centers.phone", 'like', "%{$search}%");
            });
        }

        if($request->has(['field', 'direction'])) {
            $query->orderBy($request->query('field'), $request->query('direction'));
        }

        return $query->paginate($request->query('size', 10));
    }

    public function create()
    {
        return Inertia::render('HealthCenter/Admin/Create');
    }

    public function store(HealthCenterRequest $request)
    {
        DB::beginTransaction();
        try {
            HealthCenter::query()->create($request->validated());
            DB::commit();
            return redirect()->route('health-centers.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }

    public function edit($id)
    {
        $data['healthCenter'] = HealthCenter::query()->findOrFail($id);;
        return Inertia::render('HealthCenter/Admin/Edit', $data);
    }

    public function update(HealthCenterRequest $request, $id)
    {
        $healthCenter = HealthCenter::query()->findOrFail($id);
        DB::beginTransaction();
        try {
            $healthCenter->update($request->validated());
            DB::commit();
            return redirect()->route('health-centers.index');
        } catch (Throwable $throwable) {
            DB::rollBack();
            return redirect()->back()->with('error', $throwable->getMessage());
        }
    }

    public function delete($id)
    {
        $healthCenter = HealthCenter::query()->findOrFail($id);
        DB::beginTransaction();
        try {
            $healthCenter->delete();
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Health center deleted successfully.'
            ]);
        } catch (Throwable $throwable) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => app()->isProduction() ? 'Failed to delete health center.' : $throwable->getMessage()
            ], 500);
        }
    }


    public function index(Request $request)
    {
        $data['healthCenters'] = [];

        if($request->has('postcode') && $request->filled('postcode')) {
            $this->data['healthCenters'] = $this->getHealthCenters($request->input('postcode'));
            if(!$this->data['healthCenters']) {
                return redirect()->route('user.account')->with('error', 'Please check your address and try again.');
            }
            $this->data['postcode'] = $request->input('postcode');
        } else {
            return redirect()->route('user.account')->with('error', 'Please check your address and try again.');
        }
        return Inertia::render('HealthCenter/Index', $this->data);
    }

    public function getHealthCenters($postcode = null)
    {
        if(!empty($postcode)) {

            $apiKey = DB::table('settings')->first()->map_api_key; //google map api key
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

                $healthCenters = DB::table('health_centers')
                    ->select(
                        'id',
                        'name',
                        'latitude',
                        'longitude',
                        'line_1',
                        'postcode',
                        'county',
                        'city',
                        DB::raw("(
                            3959 * acos(
                                cos(radians($lat)) * cos(radians(latitude)) *
                                cos(radians(longitude) - radians($lng)) +
                                sin(radians($lat)) * sin(radians(latitude))
                            )
                        ) AS distance")
                    )
                    ->having('distance', '<=', 70)
                    ->where('active', '=', true)
                    ->orderBy('distance', 'asc')
                    ->get();


                // Places API to get health centers
                // $placesResponse = Http::get("https://maps.googleapis.com/maps/api/place/nearbysearch/json", [
                //     'location' => "{$lat},{$lng}",
                //     'radius' => 5000, // Radius in meters
                //     'type' => 'hospital', // Or 'health'
                //     'key' => $apiKey,
                // ]);
                // $response = $placesResponse->json();
                // $allHealtCenters = array_map(function ($item) use($lat, $lng) {
                //     $item['distance'] = round($this->calculateDistance($lat, $lng, $item['geometry']['location']['lat'],$item['geometry']['location']['lng']), 2);
                //     return $item;
                // },$response['results'] ?? []);
                // usort($allHealtCenters, function($a, $b) {
                //     return $a['distance'] <=> $b['distance'];
                // });
                $healthCenters->each(function($item) {
                    $item->distance = round($item->distance, 2);
                });
                return $healthCenters;
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
