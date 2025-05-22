<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChargingStationController extends Controller
{
    // Menampilkan stasiun pengisian EV berdasarkan lokasi
    // public function index(Request $request)
    // {
    //     // Mengambil parameter lokasi dari input user, jika tidak ada, gunakan Jakarta sebagai default
    //     $latitude = $request->get('latitude', -6.1751);  // Jakarta
    //     $longitude = $request->get('longitude', 106.8650);  // Jakarta

    //     // API key untuk Open Charge Map
    //     $apiKey = env('OPEN_CHARGE_MAP_API_KEY');  // Pastikan menambahkan API key di .env file

    //     try {
    //         // Mengirim request ke Open Charge Map API menggunakan HTTP Facades
    //         $response = Http::get('https://api.openchargemap.io/v3/poi', [
    //             'output' => 'json',
    //             'latitude' => $latitude,
    //             'longitude' => $longitude,
    //             'maxresults' => 10,  // Menampilkan maksimal 10 stasiun terdekat
    //             'key' => $apiKey,
    //         ]);

    //         // Decode response JSON
    //         $data = $response->json();

    //         // Mengirim data ke view
    //         return view('charging_stations.index', compact('data'));
    //     } catch (\Exception $e) {
    //         // Menangani error jika API tidak dapat diakses
    //         return back()->with('error', 'Tidak dapat mengambil data stasiun pengisian EV.');
    //     }
    // }

    public function index(Request $request)
    {
        $locationName = $request->get('wilayah', 'Jakarta'); // Default ke Jakarta jika tidak diisi

        // API keys
        $apiKey = env('GOMAPS_API_KEY'); // dari .env
        $openChargeMapApiKey = env('OPEN_CHARGE_MAP_API_KEY');

        try {
            // Step 1: Dapatkan koordinat dari lokasi menggunakan Google Maps Geocoding API
            $geoResponse = Http::get('https://maps.gomaps.pro/maps/api/geocode/json', [
                'address' => $locationName,
                'key' => $apiKey,
            ]);

            $geoData = $geoResponse->json();

            if (empty($geoData['results'])) {
                return back()->with('error', 'Lokasi tidak ditemukan.');
            }

            $location = $geoData['results'][0]['geometry']['location'];
            $latitude = $location['lat'];
            $longitude = $location['lng'];

            // Step 2: Ambil data stasiun pengisian dari Open Charge Map API
            $chargingResponse = Http::get('https://api.openchargemap.io/v3/poi', [
                'output' => 'json',
                'latitude' => $latitude,
                'longitude' => $longitude,
                'maxresults' => 10,
                'key' => $openChargeMapApiKey,
            ]);

            $data = $chargingResponse->json();

            // dd($data);

            return view('charging_stations.index', compact('data', 'locationName'));
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengambil data: ' . $e->getMessage());
        }
    }

}