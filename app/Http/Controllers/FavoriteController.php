<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class FavoriteController extends Controller
{
    public function addToFavorites($vehicleId)
    {
         // Mengambil data kendaraan yang disukai oleh pengguna saat ini
         $user = auth()->user();
         if ($user) {
             // Mengambil data kendaraan yang disukai oleh pengguna beserta vehicle_id
             $kendaraanDisukai = $user->favoriteVehicles()->get(['vehicle_id']);

             // Inisialisasi array untuk menampung informasi kendaraan
             $informasiKendaraan = [];
             // Loop melalui kendaraan yang disukai untuk mendapatkan informasi kendaraan
             foreach ($kendaraanDisukai as $lovedVehicle) {
                 $vehicle = Vehicle::findOrFail($lovedVehicle->vehicle_id);
                 $informasiKendaraan[] = [
                     'nama' => $vehicle->name,
                     'merek' => $vehicle->brand->name,
                     'gambar' => $vehicle->pictures->isEmpty() ? asset('img/placeholder-md.png') : asset('storage/' . $vehicle->pictures->first()->path),
                 ];
             }

             // Hapus atau komentar debug dd setelah verifikasi
             // dd($informasiKendaraan);

             return view('keranjang.index', compact('informasiKendaraan', 'user'));
         } else {
             // Handle case when user is not authenticated
             return redirect()->route('login');
         }
    }
}
