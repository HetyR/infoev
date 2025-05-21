<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class KeranjangController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null
            ], 401); // Unauthorized
        }

        $kendaraanDisukai = $user->lovedVehicles()->pluck('vehicle_id');

        $informasiKendaraan = [];

        foreach ($kendaraanDisukai as $vehicleId) {
            $vehicle = Vehicle::with(['brand', 'pictures'])->find($vehicleId);

            if ($vehicle) {
                $informasiKendaraan[] = [
                    'id' => $vehicle->id,
                    'nama' => $vehicle->name,
                    'merek' => $vehicle->brand->name,
                    'gambar' => $vehicle->pictures->isEmpty()
                        ? asset('img/placeholder-md.png')
                        : asset('storage/' . $vehicle->pictures->first()->path),
                    'slug' => $vehicle->slug,
                ];
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Daftar kendaraan disukai berhasil diambil.',
            'data' => $informasiKendaraan
        ], 200); // OK
    }

    public function remove($vehicleId)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null
            ], 401); // Unauthorized
        }

        // Cek apakah kendaraan disukai ada
        if (!$user->lovedVehicles()->where('vehicle_id', $vehicleId)->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan dalam daftar disukai.',
                'data' => null
            ], 404); // Not Found
        }

        $user->lovedVehicles()->detach($vehicleId);

        return response()->json([
            'success' => true,
            'message' => 'Kendaraan berhasil dihapus dari keranjang.',
            'data' => null
        ], 200); // OK
    }
}
