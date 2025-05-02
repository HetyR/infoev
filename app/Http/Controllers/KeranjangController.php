<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Models\Brand;
use App\Models\Option;
use App\Models\LovedVehicle;
use Illuminate\Database\Eloquent\Builder;

class KeranjangController extends Controller
{
    //
    public function index()
    {
        $user = auth()->user();

        if ($user) {
            $kendaraanDisukai = $user->lovedVehicles()->pluck('vehicle_id');

            $informasiKendaraan = [];

            foreach ($kendaraanDisukai as $vehicleId) {
                $vehicle = Vehicle::with(['brand', 'pictures'])->findOrFail($vehicleId);

                $informasiKendaraan[] = [
                    'id' => $vehicle->id, // Store vehicle ID for deletion
                    'nama' => $vehicle->name,
                    'merek' => $vehicle->brand->name,
                    'gambar' => $vehicle->pictures->isEmpty() ? asset('img/placeholder-md.png') : asset('storage/' . $vehicle->pictures->first()->path),
                    'slug' => $vehicle->slug, // Assuming you have a slug field
                ];
            }

            $bikeBrands = Brand::limit(14)
                ->whereHas('vehicles', function (Builder $query) {
                    $query->where('type_id', 'sepeda motor');
                })
                ->withCount('vehicles')
                ->having('vehicles_count', '>', 0)
                ->orderByDesc('vehicles_count')
                ->get();

            $carBrands = Brand::limit(14)
                ->whereHas('vehicles', function (Builder $query) {
                    $query->where('type_id', 'mobil');
                })
                ->withCount('vehicles')
                ->having('vehicles_count', '>', 0)
                ->orderByDesc('vehicles_count')
                ->get();

            $title = ''; // Set your title here
            $brand = Brand::findOrFail(1); // Example of fetching a brand, adjust as needed

            $stickies = []; // Placeholder for stickies, adjust as needed

            $recentVehicles = Vehicle::with('brand')
                ->latest()
                ->limit(8)
                ->get();

            $popularVehicles = Vehicle::with('brand')
                ->whereHas('views', function (Builder $query) {
                    $query->where('created_at', '>', now()->subMonths(3));
                })
                ->withCount('views')
                ->orderByDesc('views_count')
                ->limit(10)
                ->get();

            $logo = Option::where('type', 'logo')->with('thumbnail')->first();

            return view('keranjang.index', [
                'informasiKendaraan' => $informasiKendaraan,
                'user' => $user,
                'bikeBrands' => $bikeBrands,
                'carBrands' => $carBrands,
                'title' => $title,
                'banner' => $brand->thumbnail,
                'stickies' => $stickies,
                'recentVehicles' => $recentVehicles,
                'popularVehicles' => $popularVehicles,
                'logo' => $logo,
            ]);
        } else {
            return redirect()->route('login');
        }
    }
    public function remove($vehicleId)
    {
        $user = auth()->user();

        if ($user) {
            $user->lovedVehicles()->detach($vehicleId);

            return redirect()->route('keranjang.index')->with('success', 'Kendaraan berhasil dihapus dari keranjang.');
        } else {
            return redirect()->route('login');
        }
    }

    
}
