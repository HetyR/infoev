<?php

namespace App\Http\Controllers;

use App\Models\ChargerStation;
use Illuminate\Http\Request;
use App\Models\Brand;
use App\Models\Blog;
use App\Models\Option;
use App\Models\Type;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Http;

class ChargerController extends Controller
{
    public function index()
    {
    $types = Type::select('id', 'name', 'slug')->get();
    $vehicles = Vehicle::with('brand')->get();

    $logo = Option::where('type', 'logo')->with('thumbnail')->first();
    $bikeBrands = Brand::whereHas('vehicles.type', fn ($q) => $q->where('name', 'sepeda motor'))
        ->withCount('vehicles')->having('vehicles_count', '>', 0)
        ->orderByDesc('vehicles_count')->limit(14)->get();

    $carBrands = Brand::whereHas('vehicles.type', fn ($q) => $q->where('name', 'mobil'))
        ->withCount('vehicles')->having('vehicles_count', '>', 0)
        ->orderByDesc('vehicles_count')->limit(14)->get();

    $banner = Option::where('type', 'banner')->with('thumbnail')->first();
    $recentVehicles = Vehicle::with('brand')->latest()->limit(8)->get();
    $popularVehicles = Vehicle::with('brand')->withCount('views')->orderByDesc('views_count')->limit(10)->get();

    $featured = Blog::with('thumbnail')->latest()->where('published', true)->where('featured', true)->limit(3)->get();
    $stickies = Blog::with('thumbnail')->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')
        ->where('blogs.published', true)
        ->orderBy('sticky_articles.created_at', 'desc')->get();

    $newsLimit = 3 - $featured->count();
    if ($newsLimit > 0 && $newsLimit <= 3) {
        $extra = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get();
        $stickies = $stickies->concat($featured)->concat($extra);
    }

    return view('charger.index', [
        'types' => $types,
        'vehicles' => $vehicles,
        'logo' => $logo?->thumbnail,
        'banner' => $banner?->thumbnail,
        'bikeBrands' => $bikeBrands,
        'carBrands' => $carBrands,
        'recentVehicles' => $recentVehicles,
        'popularVehicles' => $popularVehicles,
        'stickies' => $stickies,
    ]);

    }
    
    public function search(Request $request)
    {
        $wilayah = $request->input('wilayah');
        $apiKey = env('GOMAPS_API_KEY'); 
    
        // Cek apakah hasil pencarian wilayah sudah ada di database
        $searchHistory = ChargerStation::where('wilayah', $wilayah)->first();
    
        // Jika data sudah ada, cek apakah perlu pembaruan (misalnya jika sudah lebih dari 24 jam)
        if ($searchHistory) {
            $lastUpdated = \Carbon\Carbon::parse($searchHistory->updated_at);
            $shouldUpdate = $lastUpdated->diffInYears(now()) > 1; // Jika lebih dari 24 jam
    
            if ($shouldUpdate) {
                // Panggil API lagi untuk memperbarui data
                return $this->updateSearchData($wilayah, $apiKey, $searchHistory);
            }
    
            // Jika data masih valid, ambil data dari database
            $places = json_decode($searchHistory->places, true);


            // Hitung status buka/tutup berdasarkan weekday_text
            $places = $this->calculateOpenStatus($places);

            // dd($places);
            return view('charger.index', compact('wilayah', 'places'));
        }
    
        // Jika data tidak ada, panggil API untuk pertama kali
        return $this->updateSearchData($wilayah, $apiKey, $searchHistory);
    }


    private function updateSearchData($wilayah, $apiKey, $searchHistory)
    {
        // Step 1: Geocode wilayah ke koordinat
        $geoResponse = Http::get('https://maps.gomaps.pro/maps/api/geocode/json', [
            'address' => $wilayah,
            'language' => 'id',
            'region' => 'id',
            'key' => $apiKey,
        ]);

        if ($geoResponse->failed() || empty($geoResponse['results'])) {
            return redirect()->back()->with('error', 'Wilayah tidak ditemukan.');
        }

        if ($geoResponse->successful() && isset($geoResponse['results'][0])) {
            $location = $geoResponse['results'][0]['geometry']['location'];
            $latlng = $location['lat'] . ',' . $location['lng'];

            // Panggil Nearby Search untuk EV Charging Station
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

            // Untuk setiap tempat di hasil nearby search, ambil detail dengan Place Details API
            foreach ($nearbyResults as $place) {
                $placeId = $place['place_id'] ?? null;
                
                if ($placeId) {
                    // Panggil Place Details API untuk mendapatkan informasi opening_hours yang lebih lengkap
                    $detailResponse = Http::get("https://maps.gomaps.pro/maps/api/place/details/json", [
                        'place_id' => $placeId,
                        'fields' => 'name,vicinity,rating,business_status,opening_hours,place_id',
                        'language' => 'id',
                        'key' => $apiKey,
                    ]);
                    
                    $placeDetails = $detailResponse->json()['result'] ?? [];
                    
                    // Buat array dengan fields yang diinginkan saja
                    $filteredPlace = [
                        'place_id' => $place['place_id'] ?? null,
                        'name' => $place['name'] ?? null,
                        'vicinity' => $place['vicinity'] ?? null,
                        'rating' => $place['rating'] ?? null,
                        'business_status' => $place['business_status'] ?? null
                    ];
                    
                    // Simpan weekday_text untuk perhitungan status buka/tutup nanti
                    if (isset($placeDetails['opening_hours']) && isset($placeDetails['opening_hours']['weekday_text'])) {
                        $filteredPlace['opening_hours'] = [
                            'weekday_text' => $placeDetails['opening_hours']['weekday_text']
                        ];
                    }
                    
                    $filteredPlaces[] = $filteredPlace;
                }
            }

            // Simpan atau perbarui hasil pencarian di database
            if ($searchHistory) {
                // Jika sudah ada data, perbarui dengan data baru
                $searchHistory->update([
                    'places' => json_encode($filteredPlaces),
                    'updated_at' => now(),
                ]);
            } else {
                // Jika data belum ada, buat data baru
                ChargerStation::create([
                    'wilayah' => $wilayah,
                    'places' => json_encode($filteredPlaces),
                    'updated_at' => now(),
                ]);
            }

            // Hitung status buka/tutup saat ini berdasarkan weekday_text
            $placesWithOpenStatus = $this->calculateOpenStatus($filteredPlaces);

            // dd($placesWithOpenStatus);

            return view('charger.index', [
                'wilayah' => $wilayah,
                 'places' => $placesWithOpenStatus,
            ]);     
        }

        return redirect()->route('charger.index')->with('error', 'Wilayah tidak ditemukan.');
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
        $currentDayOfWeek = $now->dayOfWeek; // 0 (Minggu) sampai 6 (Sabtu)
        $currentDay = $daysInIndonesian[$currentDayOfWeek];
        $currentTime = $now->format('H.i');
        
        foreach ($places as &$place) {
            $isOpen = false;
            
            if (isset($place['opening_hours']) && isset($place['opening_hours']['weekday_text'])) {
                // Cari informasi untuk hari ini
                foreach ($place['opening_hours']['weekday_text'] as $dayInfo) {
                    if (strpos($dayInfo, $currentDay) === 0) {
                        // Periksa untuk format "Buka 24 jam"
                        if (strpos($dayInfo, 'Buka 24 jam') !== false) {
                            $isOpen = true;
                            break;
                        }
                        
                        // Format: "Senin: 07.00–21.00" atau "Minggu: Tutup"
                        if (strpos($dayInfo, 'Tutup') !== false) {
                            $isOpen = false;
                            break;
                        }
                        
                        // Ekstrak waktu menggunakan cara yang lebih fleksibel
                        preg_match_all('/\d{1,2}\.\d{2}/', $dayInfo, $timeMatches);
                        
                        if (!empty($timeMatches[0]) && count($timeMatches[0]) >= 2) {
                            $openTime = $timeMatches[0][0];
                            $closeTime = $timeMatches[0][1];
                            
                            // Bandingkan dengan waktu saat ini
                            if ($closeTime < $openTime) {
                                // Untuk tempat yang buka hingga lewat tengah malam
                                $isOpen = ($currentTime >= $openTime || $currentTime <= $closeTime);
                            } else {
                                $isOpen = ($currentTime >= $openTime && $currentTime <= $closeTime);
                            }
                        }
                        
                        break;
                    }
                }
            }
            
            // Update nilai open_now di dalam struktur opening_hours
            if (isset($place['opening_hours'])) {
                $place['opening_hours']['open_now'] = $isOpen;
            }
        }
        
        return $places;
    }   
    
    // private function updateSearchData($wilayah, $apiKey, $searchHistory)
    // {
    //     // Step 1: Geocode wilayah ke koordinat
    //     $geoResponse = Http::get('https://maps.gomaps.pro/maps/api/geocode/json', [
    //         'address' => $wilayah,
    //         'language' => 'id',
    //         'region' => 'id',
    //         'key' => $apiKey,
    //     ]);
    
    //     if ($geoResponse->failed() || empty($geoResponse['results'])) {
    //         return redirect()->back()->with('error', 'Wilayah tidak ditemukan.');
    //     }
    
    //     if ($geoResponse->successful() && isset($geoResponse['results'][0])) {
    //         $location = $geoResponse['results'][0]['geometry']['location'];
    //         $latlng = $location['lat'] . ',' . $location['lng'];
    
    //         // Panggil Nearby Search untuk EV Charging Station
    //         $response = Http::get("https://maps.gomaps.pro/maps/api/place/nearbysearch/json", [
    //             'location' => $latlng,
    //             'radius' => 10000,
    //             'type' => 'charging_station',
    //             'keyword' => 'charging station ev',
    //             'language' => 'id',
    //             'key' => $apiKey,
    //         ]);
    
    //         $places = $response->json()['results'] ?? []; 
    
    //         // Simpan atau perbarui hasil pencarian di database
    //         if ($searchHistory) {
    //             // Jika sudah ada data, perbarui dengan data baru
    //             $searchHistory->update([
    //                 'places' => json_encode($places),
    //                 'updated_at' => now(),
    //             ]);
    //         } else {
    //             // Jika data belum ada, buat data baru
    //             SearchHistory::create([
    //                 'wilayah' => $wilayah,
    //                 'places' => json_encode($places),
    //                 'updated_at' => now(),
    //             ]);
    //         }
    
    //         return view('charger.index', compact('wilayah', 'places'));
    //     }
    
    //     return redirect()->route('charger.index')->with('error', 'Wilayah tidak ditemukan.');
    // }


    

    //new update
    // private function updateSearchData($wilayah, $apiKey, $searchHistory)
    // {
    //     // Step 1: Geocode wilayah ke koordinat
    //     $geoResponse = Http::get('https://maps.gomaps.pro/maps/api/geocode/json', [
    //         'address' => $wilayah,
    //         'language' => 'id',
    //         'region' => 'id',
    //         'key' => $apiKey,
    //     ]);

    //     if ($geoResponse->failed() || empty($geoResponse['results'])) {
    //         return redirect()->back()->with('error', 'Wilayah tidak ditemukan.');
    //     }

    //     if ($geoResponse->successful() && isset($geoResponse['results'][0])) {
    //         $location = $geoResponse['results'][0]['geometry']['location'];
    //         $latlng = $location['lat'] . ',' . $location['lng'];

    //         // Panggil Nearby Search untuk EV Charging Station
    //         $response = Http::get("https://maps.gomaps.pro/maps/api/place/nearbysearch/json", [
    //             'location' => $latlng,
    //             'radius' => 10000,
    //             'type' => 'charging_station',
    //             'keyword' => 'charging station ev',
    //             'language' => 'id',
    //             'key' => $apiKey,
    //         ]);

    //         $nearbyResults = $response->json()['results'] ?? [];
    //         $filteredPlaces = [];

    //         // Untuk setiap tempat di hasil nearby search, ambil detail dengan Place Details API
    //         foreach ($nearbyResults as $place) {
    //             $placeId = $place['place_id'] ?? null;
                
    //             if ($placeId) {
    //                 // Panggil Place Details API untuk mendapatkan informasi opening_hours yang lebih lengkap
    //                 $detailResponse = Http::get("https://maps.gomaps.pro/maps/api/place/details/json", [
    //                     'place_id' => $placeId,
    //                     'fields' => 'name,vicinity,rating,business_status,opening_hours',
    //                     'language' => 'id',
    //                     'key' => $apiKey,
    //                 ]);
                    
    //                 $placeDetails = $detailResponse->json()['result'] ?? [];
                    
    //                 // Buat array dengan fields yang diinginkan saja
    //                 $filteredPlace = [
    //                     'name' => $place['name'] ?? null,
    //                     'vicinity' => $place['vicinity'] ?? null,
    //                     'rating' => $place['rating'] ?? null,
    //                     'business_status' => $place['business_status'] ?? null
    //                 ];
                    
    //                 // Tambahkan data opening_hours dari Place Details API jika tersedia
    //                 if (isset($placeDetails['opening_hours'])) {
    //                     $filteredPlace['opening_hours'] = $placeDetails['opening_hours'];
    //                 }
                    
    //                 $filteredPlaces[] = $filteredPlace;
    //             }
    //         }

    //         // Simpan atau perbarui hasil pencarian di database
    //         if ($searchHistory) {
    //             // Jika sudah ada data, perbarui dengan data baru
    //             $searchHistory->update([
    //                 'places' => json_encode($filteredPlaces),
    //                 'updated_at' => now(),
    //             ]);
    //         } else {
    //             // Jika data belum ada, buat data baru
    //             SearchHistory::create([
    //                 'wilayah' => $wilayah,
    //                 'places' => json_encode($filteredPlaces),
    //                 'updated_at' => now(),
    //             ]);
    //         }
    //         return view('charger.index', [
    //             'wilayah' => $wilayah,
    //             'places' => $filteredPlaces,
    //         ]); 
    //     }

    //     return redirect()->route('charger.index')->with('error', 'Wilayah tidak ditemukan.');
    // }
    
    // private function calculateOpenStatus($places)
    // {
    //     $daysInIndonesian = [
    //         0 => 'Minggu',
    //         1 => 'Senin',
    //         2 => 'Selasa',
    //         3 => 'Rabu',
    //         4 => 'Kamis', 
    //         5 => 'Jumat',
    //         6 => 'Sabtu',
    //     ];
        
    //     $now = now();
    //     $currentDayOfWeek = $now->dayOfWeek; // 0 (Minggu) sampai 6 (Sabtu)
    //     $currentDay = $daysInIndonesian[$currentDayOfWeek];
    //     $currentTime = $now->format('H.i');
        
    //     foreach ($places as &$place) {
    //         $isOpen = false;
            
    //         if (isset($place['weekday_text'])) {
    //             // Cari informasi untuk hari ini
    //             foreach ($place['weekday_text'] as $dayInfo) {
    //                 if (strpos($dayInfo, $currentDay) === 0) {
    //                     // Format: "Senin: 07.00–21.00" atau "Minggu: Tutup"
    //                     if (strpos($dayInfo, 'Tutup') !== false) {
    //                         $isOpen = false;
    //                         break;
    //                     }
                        
    //                     // Ekstrak waktu menggunakan cara yang lebih fleksibel
    //                     // Ekstrak semua angka dengan format jam (seperti 07.00, 21.00, dsb)
    //                     preg_match_all('/\d{1,2}\.\d{2}/', $dayInfo, $timeMatches);
                        
    //                     if (!empty($timeMatches[0]) && count($timeMatches[0]) >= 2) {
    //                         $openTime = $timeMatches[0][0];
    //                         $closeTime = $timeMatches[0][1];
                            
    //                         // Bandingkan dengan waktu saat ini
    //                         if ($closeTime < $openTime) {
    //                             // Untuk tempat yang buka hingga lewat tengah malam
    //                             $isOpen = ($currentTime >= $openTime || $currentTime <= $closeTime);
    //                         } else {
    //                             $isOpen = ($currentTime >= $openTime && $currentTime <= $closeTime);
    //                         }
    //                     }
                        
    //                     break;
    //                 }
    //             }
    //         }
            
    //         $place['open_now'] = $isOpen;
    //     }
        
    //     return $places;
    // }


}