<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Blog;
use App\Models\Option;
use App\Models\Type;
use App\Models\Vehicle;
use App\Models\Spec;
use Illuminate\Http\Request;

class KalkulasiController extends Controller
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
        $request->validate([
            'rata_rata_berkendara' => 'nullable|numeric|min:0',
            'harga_listrik' => 'nullable|numeric|min:0'
        ]);

        $rataRataBerkendara = $request->input('rata_rata_berkendara', 30);
        $hargaListrik = $request->input('harga_listrik', 1445);

        $vehicle = Vehicle::with('type')->find($vehicleId);

        if (!$vehicle || $vehicle->name == 'all') {
            return response()->json([
                'success' => false,
                'message' => 'Kendaraan tidak ditemukan'
            ], 404);
        }

        $kapasitasBaterai = $this->getVehicleSpec($vehicle, 'kapasitas');
        $jarakTempuh = $this->getVehicleSpec($vehicle, 'jarak tempuh');

        if (!$kapasitasBaterai || !$jarakTempuh) {
            return response()->json([
                'success' => false,
                'message' => 'Data spesifikasi kendaraan tidak lengkap'
            ], 404);
        }

        $biayaMaintenancePerKm = $this->getBiayaMaintenancePerKm($vehicle);
        $konsumsiEnergi = $kapasitasBaterai / $jarakTempuh;
        $biayaPerKilometer = $konsumsiEnergi * $hargaListrik;
        $biayaPer100Kilometer = $biayaPerKilometer * 100;
        $biayaPengisianPenuh = $kapasitasBaterai * $hargaListrik;
        $biayaHarian = ($biayaPerKilometer + $biayaMaintenancePerKm) * $rataRataBerkendara;
        $biayaBulanan = $biayaHarian * 30;

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
                    'biaya_maintenance' => $biayaMaintenancePerKm
                ],
                'hasil' => [
                    'konsumsi_energi' => round($konsumsiEnergi, 4),
                    'biaya_per_kilometer' => round($biayaPerKilometer, 2),
                    'biaya_per_100_kilometer' => round($biayaPer100Kilometer, 2),
                    'biaya_pengisian_penuh' => round($biayaPengisianPenuh, 2),
                    'biaya_harian' => round($biayaHarian, 2),
                    'biaya_bulanan' => round($biayaBulanan, 2),
                    'jarak_tempuh_per_pengisian' => $jarakTempuh
                ]
            ]
        ]);
    }

    private function getBiayaMaintenancePerKm(Vehicle $vehicle)
    {
        $typeName = strtolower($vehicle->type->name);
        return match ($typeName) {
            'mobil' => 108,
            'sepeda motor' => 42,
            default => 100 // fallback default
        };
    }

    private function getVehicleSpec(Vehicle $vehicle, $specName)
    {
        $spec = Spec::where('name', $specName)->first();
        if (!$spec) return null;
        $vehicleSpec = $vehicle->specs()->where('specs.id', $spec->id)->first();
        return $vehicleSpec ? (float) $vehicleSpec->pivot->value : null;
    }

    private function getVehicleHighlightSpecs(Vehicle $vehicle)
    {
        $highlightSpecIds = Spec::whereIn('name', [
            'kapasitas', 'pengisian daya ac', 'kecepatan maksimal', 'jarak tempuh'
        ])->pluck('id');

        $specs = $vehicle->specs()->wherePivotIn('spec_id', $highlightSpecIds)->get();
        $highlightSpecs = [];

        foreach ($specs as $spec) {
            $push = [];
            switch (strtolower($spec->name)) {
                case 'kapasitas':
                    $push = ['type' => 'capacity', 'value' => (float)$spec->pivot->value, 'unit' => $spec->unit];
                    break;
                case 'pengisian daya ac':
                    $push = [
                        'type' => 'charge',
                        'value' => (float)$spec->pivot->value,
                        'unit' => $spec->unit,
                        'desc' => $spec->pivot->value_desc
                    ];
                    break;
                case 'kecepatan maksimal':
                    $push = ['type' => 'maxSpeed', 'value' => (float)$spec->pivot->value, 'unit' => $spec->unit];
                    break;
                case 'jarak tempuh':
                    $push = ['type' => 'range', 'value' => (float)$spec->pivot->value, 'unit' => $spec->unit];
                    break;
            }

            if (!empty($push)) $highlightSpecs[] = $push;
        }

        return $highlightSpecs;
    }

    public function showKalkulasi($vehicleId)
    {
        $vehicle = Vehicle::with('type')->findOrFail($vehicleId);
        if ($vehicle->name == 'all') abort(404);

        $kapasitasBaterai = $this->getVehicleSpec($vehicle, 'kapasitas');
        $jarakTempuh = $this->getVehicleSpec($vehicle, 'jarak tempuh');
        $biayaMaintenance = $this->getBiayaMaintenancePerKm($vehicle);

        return view('kalkulasi.index', [
            'vehicle' => $vehicle,
            'kapasitasBaterai' => $kapasitasBaterai,
            'jarakTempuh' => $jarakTempuh,
            'biayaMaintenance' => $biayaMaintenance
        ]);
    }
}
