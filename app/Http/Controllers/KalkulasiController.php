<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Blog;
use App\Models\Option;
use App\Models\Type;
use App\Models\Vehicle;
use App\Models\Spec;
use App\Models\SpecVehicle;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Log;

class KalkulasiController extends Controller
{
     // Konstanta untuk biaya maintenance per km
    const BIAYA_MAINTENANCE_PER_KM = 100; // Rp
    
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

    return view('kalkulasi.index', [
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


 public function hitungBiaya(Request $request, $vehicleId)
    {
        // Validasi input
        $request->validate([
            'rata_rata_berkendara' => 'nullable|numeric|min:0',
            'harga_listrik' => 'nullable|numeric|min:0'
        ]);
        
        // Default values
        $rataRataBerkendara = $request->input('rata_rata_berkendara', 30); // km per hari
        $hargaListrik = $request->input('harga_listrik', 1445); // Rp per kWh
        
        // Cari kendaraan dari database
        $vehicle = Vehicle::find($vehicleId);
        
        if (!$vehicle || $vehicle->name == 'all') {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan'
            ], 404);
        }
        
        // Ambil data kapasitas baterai dan jarak tempuh dari database
        $kapasitasBaterai = $this->getVehicleSpec($vehicle, 'kapasitas');
        $jarakTempuh = $this->getVehicleSpec($vehicle, 'jarak tempuh');
        
        if (!$kapasitasBaterai || !$jarakTempuh) {
            return response()->json([
                'success' => false,
                'message' => 'Data spesifikasi kendaraan tidak lengkap'
            ], 404);
        }
        
        // Hitung konsumsi energi (kWh/km)
        $konsumsiEnergi = $kapasitasBaterai / $jarakTempuh;
        
        // Biaya per Kilometer = Konsumsi Energi * Harga Listrik per kWh
        $biayaPerKilometer = $konsumsiEnergi * $hargaListrik;
        
        // Biaya per 100 Kilometer = Biaya per Kilometer * 100
        $biayaPer100Kilometer = $biayaPerKilometer * 100;
        
        // Biaya Pengisian Penuh = Kapasitas Baterai * Harga Listrik per kWh
        $biayaPengisianPenuh = $kapasitasBaterai * $hargaListrik;
        
        // Biaya Harian = (Biaya per Kilometer + Biaya Maintenance per km) * Rata-rata Berkendara per Hari(KM)
        $biayaHarian = ($biayaPerKilometer + self::BIAYA_MAINTENANCE_PER_KM) * $rataRataBerkendara;
        
        // Biaya Bulanan(estimasi) = Biaya Harian * 30
        $biayaBulanan = $biayaHarian * 30;
        
        // Jarak Tempuh per Pengisian = Jarak Tempuh
        $jarakTempuhPerPengisian = $jarakTempuh;
        
        // Format data tambahan untuk response
        $kendaraanData = $this->getVehicleHighlightSpecs($vehicle);
        
        return response()->json([
            'success' => true,
            'data' => [
                'kendaraan' => [
                    'id' => $vehicle->id,
                    'nama' => $vehicle->name,
                    'kapasitas_baterai' => $kapasitasBaterai,
                    'jarak_tempuh' => $jarakTempuh,
                    'highlight_specs' => $kendaraanData
                ],
                'input' => [
                    'rata_rata_berkendara' => $rataRataBerkendara,
                    'harga_listrik' => $hargaListrik,
                    'biaya_maintenance' => self::BIAYA_MAINTENANCE_PER_KM
                ],
                'hasil' => [
                    'konsumsi_energi' => round($konsumsiEnergi, 4),
                    'biaya_per_kilometer' => round($biayaPerKilometer, 2),
                    'biaya_per_100_kilometer' => round($biayaPer100Kilometer, 2),
                    'biaya_pengisian_penuh' => round($biayaPengisianPenuh, 2),
                    'biaya_harian' => round($biayaHarian, 2),
                    'biaya_bulanan' => round($biayaBulanan, 2),
                    'jarak_tempuh_per_pengisian' => $jarakTempuhPerPengisian
                ]
            ]
        ]);
    }
    
    /**
     * Mengambil nilai spesifikasi kendaraan tertentu
     * 
     * @param Vehicle $vehicle
     * @param string $specName
     * @return float|null
     */
    private function getVehicleSpec(Vehicle $vehicle, $specName)
    {
        $spec = Spec::where('name', $specName)->first();
        
        if (!$spec) {
            return null;
        }
        
        $vehicleSpec = $vehicle->specs()->where('specs.id', $spec->id)->first();
        
        if (!$vehicleSpec) {
            return null;
        }
        
        return (float) $vehicleSpec->pivot->value;
    }
    
    /**
     * Mengambil spesifikasi highlight kendaraan
     * 
     * @param Vehicle $vehicle
     * @return array
     */
    private function getVehicleHighlightSpecs(Vehicle $vehicle)
    {
        $highlightSpecIds = Spec::where('name', 'kapasitas')
            ->orWhere('name', 'pengisian daya ac')
            ->orWhere('name', 'kecepatan maksimal')
            ->orWhere('name', 'jarak tempuh')
            ->get()
            ->pluck('id');
            
        $specs = $vehicle->specs()->wherePivotIn('spec_id', $highlightSpecIds)->get();
        $highlightSpecs = [];

        foreach ($specs as $spec) {
            $push = [];
            switch (strtolower($spec->name)) {
                case 'kapasitas':
                    $push['type'] = 'capacity';
                    $push['value'] = (float) $spec->pivot->value;
                    $push['unit'] = $spec->unit;
                    break;
                case 'pengisian daya ac':
                    $push['type'] = 'charge';
                    $push['value'] = (float) $spec->pivot->value;
                    $push['unit'] = $spec->unit;
                    $push['desc'] = $spec->pivot->value_desc;
                    break;
                case 'kecepatan maksimal':
                    $push['type'] = 'maxSpeed';
                    $push['value'] = (float) $spec->pivot->value;
                    $push['unit'] = $spec->unit;
                    break;
                case 'jarak tempuh':
                    $push['type'] = 'range';
                    $push['value'] = (float) $spec->pivot->value;
                    $push['unit'] = $spec->unit;
                    break;
                default:
                    break;
            }

            if (!empty($push)) {
                array_push($highlightSpecs, $push);
            }
        }
        
        return $highlightSpecs;
    }
    
    /**
     * Menampilkan form kalkulasi (opsional)
     * 
     * @param int $vehicleId
     * @return \Illuminate\View\View
     */
    public function showKalkulasi($vehicleId)
    {
        $vehicle = Vehicle::findOrFail($vehicleId);
        
        if ($vehicle->name == 'all') abort(404);
        
        // Dapatkan informasi spesifikasi kendaraan
        $kapasitasBaterai = $this->getVehicleSpec($vehicle, 'kapasitas');
        $jarakTempuh = $this->getVehicleSpec($vehicle, 'jarak tempuh');
        
        return view('kalkulasi.index', [
            'vehicle' => $vehicle,
            'kapasitasBaterai' => $kapasitasBaterai,
            'jarakTempuh' => $jarakTempuh,
            'biayaMaintenance' => self::BIAYA_MAINTENANCE_PER_KM
        ]);
    }
}