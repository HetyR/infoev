<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Vehicle;

class FavoriteController extends Controller
{
    public function index(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
                'data' => null,
            ], 401);
        }

        // Ambil jumlah per halaman dari query string atau default 10
        $perPage = $request->query('page', 10);

        // Ambil data kendaraan favorit dengan pagination
        $vehicles = $user
            ->lovedVehicles()
            ->with(['brand', 'pictures'])
            ->orderBy('loved_vehicles.created_at', 'desc')
            ->paginate($perPage);

        // Tambahkan thumbnail_url
        $vehicles->getCollection()->transform(function ($vehicle) {
            $vehicle->thumbnail_url = $vehicle->pictures->where('thumbnail', 1)->first()
                ? asset('storage/' . $vehicle->pictures->where('thumbnail', 1)->first()->path)
                : asset('img/placeholder-md.png');
            return $vehicle;
        });

        return response()->json([
            'success' => true,
            'message' => 'Daftar kendaraan disukai berhasil diambil.',
            'data' => $vehicles
        ], 200);
    }


    public function store(Request $request)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Unauthorized.',
                    'data' => null,
                ],
                401,
            ); // Unauthorized
        }

        $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
        ]);

        $vehicleId = $request->vehicle_id;

        // Cek apakah kendaraan sudah disukai
        if ($user->lovedVehicles()->where('vehicle_id', $vehicleId)->exists()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Kendaraan sudah ada dalam daftar disukai.',
                    'data' => null,
                ],
                409,
            ); // Conflict
        }

        // Tambahkan kendaraan ke daftar disukai
        $user->lovedVehicles()->attach($vehicleId);

        return response()->json(
            [
                'success' => true,
                'message' => 'Kendaraan berhasil ditambahkan ke daftar disukai.',
                'data' => null,
            ],
            201,
        ); // Created
    }

    public function remove($vehicleId)
    {
        $user = auth('sanctum')->user();

        if (!$user) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Unauthorized.',
                    'data' => null,
                ],
                401,
            ); // Unauthorized
        }

        // Cek apakah kendaraan disukai ada
        if (!$user->lovedVehicles()->where('vehicle_id', $vehicleId)->exists()) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Kendaraan tidak ditemukan dalam daftar disukai.',
                    'data' => null,
                ],
                404,
            ); // Not Found
        }

        $user->lovedVehicles()->detach($vehicleId);

        return response()->json(
            [
                'success' => true,
                'message' => 'Kendaraan berhasil dihapus dari keranjang.',
                'data' => null,
            ],
            200,
        ); // OK
    }
}
