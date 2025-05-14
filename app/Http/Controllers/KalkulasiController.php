<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

use App\Models\Blog;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Spec;
use App\Models\SpecCategory;
use App\Models\VehicleView;
use App\Models\SpecVehicle;
use Illuminate\Support\Facades\Log; // Impor kelas Log

use Auth;
use App\Models\LovedVehicle;
use Illuminate\Database\Eloquent\Builder;

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
class KalkulasiController extends Controller
{
    // Menampilkan form kalkulasi
 
public function index()
{
    $vehicles = Vehicle::with('brand')->get();

    $logo = Option::where('type', 'logo')->with('thumbnail')->first();
    $bikeBrands = Brand::whereHas('vehicles.type', fn ($q) => $q->where('name', 'sepeda motor'))->withCount('vehicles')->having('vehicles_count', '>', 0)->orderByDesc('vehicles_count')->limit(14)->get();
    $carBrands = Brand::whereHas('vehicles.type', fn ($q) => $q->where('name', 'mobil'))->withCount('vehicles')->having('vehicles_count', '>', 0)->orderByDesc('vehicles_count')->limit(14)->get();
    $banner = Option::where('type', 'banner')->with('thumbnail')->first();
    $recentVehicles = Vehicle::with('brand')->latest()->limit(8)->get();
    $popularVehicles = Vehicle::with('brand')->withCount('views')->orderByDesc('views_count')->limit(10)->get();

    $featured = Blog::with('thumbnail')->latest()->where('published', true)->where('featured', true)->limit(3)->get();
    $stickies = Blog::with('thumbnail')->join('sticky_articles', 'blogs.id', '=', 'sticky_articles.blog_id')->where('blogs.published', true)->orderBy('sticky_articles.created_at', 'desc')->get();

    $newsLimit = 3 - $featured->count();
    if ($newsLimit > 0 && $newsLimit <= 3) {
        $extra = Blog::with('thumbnail')->latest()->where('published', true)->limit($newsLimit)->get();
        $stickies = $stickies->concat($featured)->concat($extra);
    }

    return view('vehicle.kalkulasi.index', [
        'vehicles' => $vehicles,
        'logo' => $logo,
        'bikeBrands' => $bikeBrands,
        'carBrands' => $carBrands,
        'recentVehicles' => $recentVehicles,
        'popularVehicles' => $popularVehicles,
        'stickies' => $stickies,
        'banner' => $banner?->thumbnail,
    ]);
}

   public function Hitung(Request $request)
{
    // Validasi inputan
    $request->validate([
        'vehicle_id' => 'required|exists:vehicles,id',  // Pastikan kendaraan ada
        'harga_listrik' => 'required|numeric|min:0',    // Harga listrik valid
        'jarak' => 'nullable|numeric|min:1',            // Jarak harus valid jika diisi
    ]);

    // Ambil data kendaraan berdasarkan ID
    $vehicle = Vehicle::findOrFail($request->vehicle_id);

    // Ambil semua spesifikasi terkait kendaraan
    $specs = $vehicle->specs()->get()->keyBy('name');

    // Mengambil data spesifikasi 'Kapasitas' dan 'Jarak Tempuh'
    $kapasitas = (float) optional($specs->get('Kapasitas'))->pivot->value;
    $jarakTempuh = (float) optional($specs->get('Jarak Tempuh'))->pivot->value;

    // Cek apakah data kapasitas dan jarak tempuh ada
    if ($kapasitas == 0 || $jarakTempuh == 0) {
        return back()->with('error', 'Data spesifikasi tidak lengkap.');
    }

    // Ambil harga listrik per kWh
    $hargaPerKwh = $request->harga_listrik;

    // Perhitungan biaya per kilometer dan biaya per 100 km
    $kwhPerKm = $kapasitas / $jarakTempuh;
    $biayaPerKm = $kwhPerKm * $hargaPerKwh;
    $biayaPer100Km = $biayaPerKm * 100;

    // Perhitungan biaya bulanan jika jarak bulanan diisi
    $jarakBulanan = $request->jarak;
    $biayaBulanan = $jarakBulanan ? $biayaPerKm * $jarakBulanan : null;

    // Mengirimkan hasil kalkulasi ke view
    return view('vehicle.kalkulasi.index', [
        'vehicle' => $vehicle,
        'result' => [
            'kwh_per_km' => round($kwhPerKm, 3),
            'biaya_per_km' => round($biayaPerKm),
            'biaya_per_100_km' => round($biayaPer100Km),
            'biaya_bulanan' => $biayaBulanan ? round($biayaBulanan) : null,
            'harga_kwh' => $hargaPerKwh,
            'jarak_bulanan' => $jarakBulanan,
        ],
    ]);
}



}
