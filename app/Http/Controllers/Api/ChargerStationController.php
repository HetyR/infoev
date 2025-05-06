<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use App\Models\ChargerStation;
use App\Models\City; 
use App\Models\Province;
use Carbon\Carbon;

class ChargerStationController extends Controller
{
    public function search(Request $request)
    {
        $request->validate([
            'wilayah' => 'required|string|max:255',
        ]);

        $wilayah = $request->input('wilayah');
        // $cacheKey = 'charging_' . md5($wilayah);
        $apiKey = env('GOMAPS_API_KEY');

        // 1. Cek Redis cache
        // if (Cache::has($cacheKey)) {
        //     $places = Cache::get($cacheKey);
        //     return response()->json([
        //         'success' => true,
        //         'wilayah' => $wilayah,
        //         'cached' => true,
        //         'places' => $this->calculateOpenStatus($places),
        //     ], 200);
        // }

        // 2. Cek database
        $chargerStation = ChargerStation::where('wilayah', $wilayah)->first();

        if ($chargerStation) {
            $lastUpdated = Carbon::parse($chargerStation->updated_at);
            $shouldUpdate = $lastUpdated->diffInYears(now()) > 1;

            if (!$shouldUpdate) {
                $places = json_decode($chargerStation->places, true);
                // Cache::put($cacheKey, $places, now()->addHours(24));

                return response()->json([
                    'success' => true,
                    'wilayah' => $wilayah,
                    'cached' => true,
                    'places' => $this->calculateOpenStatus($places),
                ], 200);
            }
        }

        // 3. Jika tidak ada di cache dan DB atau data sudah lama → update data
        // return $this->updateSearchData($wilayah, $apiKey, $chargerStation, $cacheKey);
        return $this->updateSearchData($wilayah, $apiKey, $chargerStation);
    }

    // private function updateSearchData($wilayah, $apiKey, $chargerStation, $cacheKey)
    private function updateSearchData($wilayah, $apiKey, $chargerStation)
    {
        $geoResponse = Http::get('https://maps.gomaps.pro/maps/api/geocode/json', [
            'address' => $wilayah,
            'language' => 'id',
            'region' => 'id',
            'key' => $apiKey,
        ]);

        if ($geoResponse->failed() || empty($geoResponse['results'])) {
            return response()->json([
                'success' => false,
                'message' => 'Wilayah tidak ditemukan.'
            ], 404);
        }

        $location = $geoResponse['results'][0]['geometry']['location'];
        $latlng = $location['lat'] . ',' . $location['lng'];

        // Panggil Nearby Search API
        $response = Http::get("https://maps.gomaps.pro/maps/api/place/nearbysearch/json", [
            'location' => $latlng,
            'radius' => 10000,
            'type' => 'charging_station',
            'keyword' => 'charging station ev',
            'language' => 'id',
            'key' => $apiKey,
        ]);

        $nearbyResults = $response->json()['results'] ?? [];
        $filteredPlaces = [];

        foreach ($nearbyResults as $place) {
            $placeId = $place['place_id'] ?? null;

            if ($placeId) {
                $detailResponse = Http::get("https://maps.gomaps.pro/maps/api/place/details/json", [
                    'place_id' => $placeId,
                    'fields' => 'name,vicinity,rating,business_status,opening_hours,place_id,geometry',
                    'language' => 'id',
                    'key' => $apiKey,
                ]);

                $placeDetails = $detailResponse->json()['result'] ?? [];

                $filteredPlace = [
                    'place_id' => $place['place_id'] ?? null,
                    'geometry' => $place['geometry'] ?? null,
                    'name' => $place['name'] ?? null,
                    'vicinity' => $place['vicinity'] ?? null,
                    'rating' => $place['rating'] ?? null,
                    'business_status' => $place['business_status'] ?? null,
                ];

                if (isset($placeDetails['opening_hours']['weekday_text'])) {
                    $filteredPlace['opening_hours'] = [
                        'weekday_text' => $placeDetails['opening_hours']['weekday_text']
                    ];
                }

                $filteredPlaces[] = $filteredPlace;
            }
        }
        
        // dd(json_encode($filteredPlaces));

        // Simpan ke DB
        if ($chargerStation) {
            $chargerStation->update([
                'places' => json_encode($filteredPlaces),
                'updated_at' => now(),
            ]);
        } else {
            ChargerStation::create([
                'wilayah' => $wilayah,
                'places' => json_encode($filteredPlaces),
                'updated_at' => now(),
            ]);
        }

        // Simpan ke Redis
        // Cache::put($cacheKey, $filteredPlaces, now()->addHours(24));
        

        return response()->json([
            'success' => true,
            'wilayah' => $wilayah,
            'cached' => false,
            'places' => $this->calculateOpenStatus($filteredPlaces)
        ], 200);
    }

    private function calculateOpenStatus($places)
    {
        $daysInIndonesian = [
            0 => 'Minggu',
            1 => 'Senin',
            2 => 'Selasa',
            3 => 'Rabu',
            4 => 'Kamis',
            5 => 'Jumat',
            6 => 'Sabtu',
        ];

        $now = now();
        $currentDay = $daysInIndonesian[$now->dayOfWeek];
        $currentTime = $now->format('H.i');

        foreach ($places as &$place) {
            $isOpen = false;

            if (isset($place['opening_hours']['weekday_text'])) {
                foreach ($place['opening_hours']['weekday_text'] as $dayInfo) {
                    if (strpos($dayInfo, $currentDay) === 0) {
                        if (strpos($dayInfo, 'Buka 24 jam') !== false) {
                            $isOpen = true;
                            break;
                        }
                        if (strpos($dayInfo, 'Tutup') !== false) {
                            break;
                        }

                        preg_match_all('/\d{1,2}\.\d{2}/', $dayInfo, $timeMatches);

                        if (count($timeMatches[0]) >= 2) {
                            $openTime = $timeMatches[0][0];
                            $closeTime = $timeMatches[0][1];

                            $isOpen = ($closeTime < $openTime)
                                ? ($currentTime >= $openTime || $currentTime <= $closeTime)
                                : ($currentTime >= $openTime && $currentTime <= $closeTime);
                        }
                        break;
                    }
                }
            }

            if (isset($place['opening_hours'])) {
                $place['opening_hours']['open_now'] = $isOpen;
            }
        }

        return $places;
    }

    public function getCities()
    {
        // Ambil semua kota dan hanya mengambil id, name
        $cities = City::select('id', 'name')->get();

        return response()->json($cities);
    }
 
    public function searchCities(Request $request)
    {
        // Ambil query pencarian dari parameter 'query'
        $query = $request->input('cari');

        // Cari kota berdasarkan nama yang sesuai dengan query
        $cities = City::where('name', 'like', '%' . $query . '%')->select('id', 'name')->get();

        // Jika kota ditemukan, kirimkan data kota
        if ($cities->isNotEmpty()) {
            return response()->json($cities);
        }

        // Jika tidak ada kota yang ditemukan
        return response()->json(['message' => 'Kota tidak ditemukan'], 404);
    }
}
